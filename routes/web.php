<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\UserQueryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// La ruta 'dashboard' la dejamos en inglés por convención
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // La ruta 'profile' la dejamos en inglés por convención
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- RUTAS TRADUCIDAS ---
    Route::get('/cotizaciones', [RateController::class, 'index'])->name('sites.index');
    Route::get('/analisis', [AnalysisController::class, 'index'])->name('analysis.index');
    Route::get('/historial', [UserQueryController::class, 'index'])->name('history.index');
    Route::post('/historial', [UserQueryController::class, 'store'])->name('history.store');
    Route::delete('/historial/{query}', [UserQueryController::class, 'destroy'])->name('history.destroy');
});

require __DIR__.'/auth.php';
