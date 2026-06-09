<?php

use App\Http\Controllers\CiudadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CiudadController::class, 'index'])->name('index');
Route::get('{pais}/buscar', [CiudadController::class, 'buscar'])->name('buscar');
Route::get('{departamento}/buscar-ciudad', [CiudadController::class, 'buscarCiudad'])->name('buscarCiudad');
Route::put('{ciudad}/actualizar', [CiudadController::class, 'update'])->name('update');