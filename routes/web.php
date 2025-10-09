<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\UserQueryController; // <-- Añadir este 'use'
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas existentes
    Route::get('/sites', [RateController::class, 'index'])->name('sites.index');
    Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis.index');

    // --- AÑADIR ESTAS NUEVAS RUTAS PARA EL HISTORIAL ---
    Route::get('/history', [UserQueryController::class, 'index'])->name('history.index');
    Route::post('/history', [UserQueryController::class, 'store'])->name('history.store');
    Route::delete('/history/{query}', [UserQueryController::class, 'destroy'])->name('history.destroy');
    // --- FIN DE LAS NUEVAS RUTAS ---
});

require __DIR__.'/auth.php';
