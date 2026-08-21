<?php

use App\Http\Controllers\Chatbots\ChatbotAsistenteController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'asistente', 'as' => 'asistente.'], function() {
    Route::get('/', [ChatbotAsistenteController::class, 'index'])->name('index');
    Route::get('/modelos', [ChatbotAsistenteController::class, 'modelos'])->name('modelos');
    Route::post('/guardar', [ChatbotAsistenteController::class, 'guardar'])->name('guardar');
    Route::post('/simular', [ChatbotAsistenteController::class, 'simular'])->name('simular');
});
