<?php

use App\Http\Controllers\Chatbots\ChatbotN8nController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'n8n', 'as' => 'n8n.'], function() {
    Route::get('/', [ChatbotN8nController::class, 'index'])->name('index');
    Route::get('/listado', [ChatbotN8nController::class, 'listado'])->name('listado');
    Route::post('/guardar', [ChatbotN8nController::class, 'store'])->name('store');
    Route::get('/editar/{chatbot}', [ChatbotN8nController::class, 'edit'])->name('edit');
    Route::put('/actualizar/{chatbot}', [ChatbotN8nController::class, 'update'])->name('update');
});
