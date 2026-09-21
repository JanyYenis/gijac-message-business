<?php

use App\Http\Controllers\CalendarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CalendarioController::class, 'index'])
    ->name('index');

Route::get('/listado', [CalendarioController::class, 'listado'])
    ->name('listado');

Route::get('/listado-hoy', [CalendarioController::class, 'listadoHoy'])
    ->name('listado-hoy');

Route::get('/listado-proxima', [CalendarioController::class, 'listadoProxima'])
    ->name('listado-proxima');
