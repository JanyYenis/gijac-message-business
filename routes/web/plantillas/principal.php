<?php

use App\Http\Controllers\PlantillaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PlantillaController::class, 'index'])->name('index');
Route::get('/listado', [PlantillaController::class, 'listado'])->name('listado');
Route::get('/{plantilla}/ver', [PlantillaController::class, 'show'])->name('show');
Route::post('/crear', [PlantillaController::class, 'store'])->name('store');
Route::get('/buscar', [PlantillaController::class, 'buscar'])->name('buscar');
