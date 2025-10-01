<?php

namespace App\Http\Controllers;

use App\Models\DollarRate; // Importamos el modelo
use Illuminate\Http\Request;
use Illuminate\View\View;

class RateController extends Controller
{
    /**
     * Muestra la vista principal de cotizaciones.
     */
    public function index(): View
    {
        // 1. Obtenemos TODOS los registros de la tabla, ordenados por la fecha más reciente.
        $rates = DollarRate::latest('rate_date')->get();

        // 2. Pasamos la variable $rates a la vista.
        return view('rates.index', [
            'rates' => $rates
        ]);
    }
}
