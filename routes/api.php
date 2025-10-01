<?php

use App\Http\Controllers\Api\DollarRateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Vinculamos las rutas a los métodos del controlador
Route::get('/rates/monthly-average', [DollarRateController::class, 'monthlyAverage']);
Route::get('/rates/history/{year}/{month}', [DollarRateController::class, 'history']);

