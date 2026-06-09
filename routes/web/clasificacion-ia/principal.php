<?php

use App\Http\Controllers\ClasificacionIaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ClasificacionIaController::class, 'index'])->name('index');
Route::post('/guardar', [ClasificacionIaController::class, 'store'])->name('store');
Route::put('/actualizar/{clasificacion}', [ClasificacionIaController::class, 'update'])->name('update');
