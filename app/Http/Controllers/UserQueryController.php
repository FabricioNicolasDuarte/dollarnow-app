<?php

namespace App\Http\Controllers;

use App\Models\UserQuery;
use App\Models\DollarRate;
use App\Services\DollarRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User; // <-- Añadimos esta línea para importar el modelo User

class UserQueryController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $user */ // <-- AÑADIMOS ESTA PISTA DE TIPO
        $user = Auth::user();
        $userQueries = $user->queries()->latest()->get();

        $chartLabels = [];
        $chartData = [];

        foreach ($userQueries as $query) {
            $latestAverage = $this->getLatestAverageForQuery($query);
            if ($latestAverage) {
                $chartLabels[] = $query->name;
                $chartData[] = $latestAverage->average;
            }
        }

        return view('history.index', [
            'userQueries' => $userQueries,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'value_type' => 'required|string|in:buy_price,sell_price',
            'period' => 'required|string|in:6_months,12_months,all_time',
        ]);

        $name = sprintf(
            "%s - %s (%s)",
            ucfirst($validated['type']),
            $validated['value_type'] == 'buy_price' ? 'Compra' : 'Venta',
            str_replace('_', ' ', $validated['period'])
        );

        /** @var \App\Models\User $user */ // <-- AÑADIMOS ESTA PISTA DE TIPO
        $user = Auth::user();
        $user->queries()->create([
            'name' => $name,
            'type' => $validated['type'],
            'value_type' => $validated['value_type'],
            'period' => $validated['period'],
        ]);

        return redirect()->route('history.index')->with('status', '¡Consulta guardada en tu historial!');
    }

    public function destroy(UserQuery $query)
    {
        if ($query->user_id !== Auth::id()) {
            abort(403);
        }
        $query->delete();
        return redirect()->route('history.index')->with('status', 'Consulta eliminada.');
    }

    private function getLatestAverageForQuery(UserQuery $userQuery)
    {
        $startDate = match ($userQuery->period) {
            '6_months' => Carbon::now()->subMonths(6)->startOfMonth(),
            '12_months' => Carbon::now()->subMonths(12)->startOfMonth(),
            default => null,
        };

        $dbDriver = DB::connection()->getDriverName();
        $yearExpression = $dbDriver === 'sqlite' ? "strftime('%Y', rate_date)" : "YEAR(rate_date)";
        $monthExpression = $dbDriver === 'sqlite' ? "strftime('%m', rate_date)" : "MONTH(rate_date)";

        $query = DollarRate::select(
            DB::raw("AVG({$userQuery->value_type}) as average")
        )
        ->where('type', $userQuery->type);

        if ($startDate) {
            $query->where('rate_date', '>=', $startDate);
        }

        return $query->groupBy(DB::raw($yearExpression), DB::raw($monthExpression))
            ->orderBy(DB::raw($yearExpression), 'desc')
            ->orderBy(DB::raw($monthExpression), 'desc')
            ->first();
    }
}
