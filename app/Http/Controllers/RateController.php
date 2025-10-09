<?php

namespace App\Http\Controllers;

use App\Services\DollarRateService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RateController extends Controller
{
    public function index(): View
    {
        // Usamos la misma NUEVA llave de caché 'dollar_rates_v2'
        $rates = Cache::remember('dollar_rates_v2', 3600, function () {
            $dollarService = new DollarRateService();
            return $dollarService->fetchAndStoreRates();
        });

        return view('rates.index', [
            'rates' => $rates
        ]);
    }
}
