<?php

use App\Http\Controllers\CatalogoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogoController::class, 'index'])
    ->name('index');

Route::get('/listado', [CatalogoController::class, 'listado'])
    ->name('listado');

Route::get('producto/{id}', [CatalogoController::class, 'obtener'])
    ->name('obtener');

Route::post('guardar', [CatalogoController::class, 'store'])
    ->name('store');

Route::delete('eliminar/{id}', [CatalogoController::class, 'eliminar'])
    ->name('eliminar');

