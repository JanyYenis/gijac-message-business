<?php

use App\Http\Controllers\Chatbots\ChatbotController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChatbotController::class, 'index'])->name('index');

include 'chatbot-nodos.php';
include 'asistente-ia.php';
include 'n8n.php';
