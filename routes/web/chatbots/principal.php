<?php

use App\Http\Controllers\Chatbots\ChatbotController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChatbotController::class, 'index'])->name('index');

Route::group(['prefix' => 'configuracion', 'as' => 'configuracion.'], function() {
    Route::get('/', [ChatbotController::class, 'configuracion'])->name('index');
    Route::post('guardar', [ChatbotController::class, 'guardar'])->name('guardar');
});

include 'chatbot-nodos.php';
include 'asistente-ia.php';
include 'n8n.php';
