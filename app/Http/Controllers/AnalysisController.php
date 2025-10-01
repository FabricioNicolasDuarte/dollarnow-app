<?php

namespace App\Http\Controllers;

use App\Models\DollarRate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    /**
     * Muestra el formulario de análisis y los resultados si los hay.
     */
    public function index(Request $request): View
    {
        // Obtener los tipos de dólar únicos para el formulario
        $dollarTypes = DollarRate::select('type')->distinct()->pluck('type');

        $monthlyAverages = null;

        // Si el formulario fue enviado, validar y hacer el cálculo
        if ($request->filled(['type', 'value_type'])) {
            $request->validate([
                'type' => 'required|string',
                'value_type' => 'required|string|in:buy_price,sell_price',
            ]);

            $validated = $request->only(['type', 'value_type']);

            $monthlyAverages = DollarRate::select(
                DB::raw('YEAR(rate_date) as year'),
                DB::raw('MONTH(rate_date) as month'),
                'type',
                DB::raw('AVG('.$validated['value_type'].') as average')
            )
            ->where('type', $validated['type'])
            ->groupBy('year', 'month', 'type')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        }

        return view('analysis.index', [
            'dollarTypes' => $dollarTypes,
            'monthlyAverages' => $monthlyAverages,
            'oldInput' => $request->all(), // Para recordar la selección del usuario
        ]);
    }
}