<?php

use App\Http\Controllers\UserQueryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RateController;
use App\Http\Controllers\AnalysisController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/rates', [RateController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('rates.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis.index');
    Route::get('/history', [UserQueryController::class, 'index'])->name('history.index');
    Route::post('/history', [UserQueryController::class, 'store'])->name('history.store');
    Route::delete('/history/{userQuery}', [UserQueryController::class, 'destroy'])->name('history.destroy');
});

require __DIR__.'/auth.php';
