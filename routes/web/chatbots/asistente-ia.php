<?php

use App\Http\Controllers\Chatbots\ChatbotAsistenteController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'asistente', 'as' => 'asistente.'], function() {
    Route::get('/', [ChatbotAsistenteController::class, 'index'])->name('index');
    Route::get('/listado', [ChatbotAsistenteController::class, 'listado'])->name('listado');
    Route::post('/guardar', [ChatbotAsistenteController::class, 'store'])->name('store');
    Route::get('/editar/{chatbot}', [ChatbotAsistenteController::class, 'edit'])->name('edit');
    Route::put('/actualizar/{chatbot}', [ChatbotAsistenteController::class, 'update'])->name('update');
});
