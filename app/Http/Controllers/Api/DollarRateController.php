<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DollarRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DollarRateController extends Controller
{
    /**
     * Calcula el promedio mensual para un tipo de dólar y valor específico.
     */
    public function monthlyAverage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'value_type' => 'required|string|in:buy_price,sell_price',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        // Lógica para que la consulta funcione en MySQL y SQLite
        $dbDriver = DB::connection()->getDriverName();
        $yearExpression = $dbDriver === 'sqlite' ? "strftime('%Y', rate_date)" : "YEAR(rate_date)";
        $monthExpression = $dbDriver === 'sqlite' ? "strftime('%m', rate_date)" : "MONTH(rate_date)";

        $monthlyAverages = DollarRate::select(
            DB::raw("$yearExpression as year"),
            DB::raw("$monthExpression as month"),
            'type',
            DB::raw("AVG({$validated['value_type']}) as average")
        )
        ->where('type', $validated['type'])
        ->groupBy('year', 'month', 'type')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        return response()->json($monthlyAverages);
    }

    /**
     * Devuelve el historial de cotizaciones para un año y mes dados.
     */
    public function history($year, $month)
    {
        $rates = DollarRate::whereYear('rate_date', $year)
                           ->whereMonth('rate_date', $month)
                           ->orderBy('rate_date', 'asc')
                           ->get();

        return response()->json($rates);
    }
}
