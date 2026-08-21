<?php

use App\Http\Controllers\Chatbots\ChatbotNodoController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'nodos', 'as' => 'nodos.'], function() {
    Route::get('/', [ChatbotNodoController::class, 'index'])->name('index');
    Route::get('/listado', [ChatbotNodoController::class, 'listado'])->name('listado');
    Route::post('/guardar', [ChatbotNodoController::class, 'store'])->name('store');
    Route::get('/editar/{chatbot}', [ChatbotNodoController::class, 'edit'])->name('edit');
    Route::put('/actualizar/{chatbot}', [ChatbotNodoController::class, 'update'])->name('update');
    Route::get('/consultar-nodes', [ChatbotNodoController::class, 'getFlowData'])->name('consultar-nodes');
    Route::get('/versiones', [ChatbotNodoController::class, 'listadoVersiones'])->name('listado-versiones');
    Route::get('/chatbots/nodos/versiones/{version}/ver', [ChatbotNodoController::class, 'verVersion'])
        ->name('versiones.ver');
    Route::post('/chatbots/nodos/versiones/{version}/deshacer', [ChatbotNodoController::class, 'deshacerVersion'])
        ->name('versiones.deshacer');
});
