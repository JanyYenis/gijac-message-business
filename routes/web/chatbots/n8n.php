<?php

use App\Http\Controllers\AutomatizacionN8nController;
use App\Http\Controllers\AutomatizacionN8nEjecucionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'n8n', 'as' => 'n8n.'], function() {
    Route::get('/', [AutomatizacionN8nController::class, 'index'])->name('index');
    Route::post('/guardar', [AutomatizacionN8nController::class, 'store'])->name('store');
    Route::post('/probar', [AutomatizacionN8nController::class, 'probar'])->name('probar');
    Route::post('/enviar-prueba', [AutomatizacionN8nController::class, 'enviarPrueba'])->name('enviar-prueba');
    Route::group(['prefix' => 'ejecuciones', 'as' => 'ejecuciones.'], function() {
        Route::get('/listado/{automatizacion}', [AutomatizacionN8nEjecucionController::class, 'listado'])->name('listado');
    });
});
