<?php

use App\Http\Controllers\FacturaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FacturaController::class, 'index'])->name('index');
Route::get('/pagar/{plan}', [FacturaController::class, 'pagar'])->name('pago');
Route::get('/listado', [FacturaController::class, 'listado'])->name('listado');
Route::get('/ver/{factura}', [FacturaController::class, 'ver'])->name('ver');
