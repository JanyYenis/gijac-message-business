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
});
