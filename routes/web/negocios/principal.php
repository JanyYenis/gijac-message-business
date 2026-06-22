<?php

use App\Http\Controllers\EmpresaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EmpresaController::class, 'index'])
    ->name('index');

Route::post('/guardar', [EmpresaController::class, 'store'])
    ->name('store');
