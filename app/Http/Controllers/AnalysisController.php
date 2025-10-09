<?php

namespace App\Http\Controllers;

use App\Models\DollarRate;
use App\Services\DollarRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class AnalysisController extends Controller
{
    public function index(Request $request): View
    {
        // Se asegura de que la BD esté poblada usando la caché
        Cache::remember('dollar_rates_v2', 3600, function () {
            $dollarService = new DollarRateService();
            return $dollarService->fetchAndStoreRates();
        });

        $dollarTypes = DollarRate::select('type')->distinct()->pluck('type');

        $monthlyAverages = null;
        $chartLabels = [];
        $chartData = [];

        // Verifica si el formulario fue enviado con todos los datos necesarios
        if ($request->filled(['type', 'value_type', 'period'])) {
            $validated = $request->validate([
                'type' => 'required|string',
                'value_type' => 'required|string|in:buy_price,sell_price',
                'period' => 'required|string|in:6_months,12_months,all_time',
            ]);

            // Determina la fecha de inicio para la consulta según el período seleccionado
            $startDate = match ($validated['period']) {
                '6_months' => Carbon::now()->subMonths(6)->startOfMonth(),
                '12_months' => Carbon::now()->subMonths(12)->startOfMonth(),
                default => null,
            };

            $dbDriver = DB::connection()->getDriverName();
            $yearExpression = $dbDriver === 'sqlite' ? "strftime('%Y', rate_date)" : "YEAR(rate_date)";
            $monthExpression = $dbDriver === 'sqlite' ? "strftime('%m', rate_date)" : "MONTH(rate_date)";

            $query = DollarRate::select(
                DB::raw("$yearExpression as year"),
                DB::raw("$monthExpression as month"),
                'type',
                DB::raw("AVG({$validated['value_type']}) as average")
            )
            ->where('type', $validated['type']);

            if ($startDate) {
                $query->where('rate_date', '>=', $startDate);
            }

            // Ordenamos por fecha ascendente para que el gráfico tenga sentido cronológico
            $monthlyAverages = $query->groupBy('year', 'month', 'type')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();

            // Preparamos los datos para el gráfico
            if ($monthlyAverages->isNotEmpty()) {
                foreach ($monthlyAverages as $average) {
                    $chartLabels[] = Carbon::create()->month($average->month)->year($average->year)->locale('es')->translatedFormat('F Y');
                    $chartData[] = $average->average;
                }
            }
        }

        return view('analysis.index', [
            'dollarTypes' => $dollarTypes,
            // Revertimos la colección para la tabla, para mostrar lo más reciente primero
            'monthlyAverages' => $monthlyAverages ? $monthlyAverages->reverse() : null,
            'oldInput' => $request->all(),
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}

