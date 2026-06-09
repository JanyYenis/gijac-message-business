<?php

use App\Http\Controllers\Apis\ChatController;
use App\Http\Controllers\Apis\ContactController;
use App\Http\Controllers\Apis\FormOptionsController;
use App\Http\Controllers\Apis\MessageController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Chat
    Route::get('/contactos', [ChatController::class, 'index']);

    // Messages
    Route::get('/conversations/{contactId}/messages', [MessageController::class, 'index']);
    Route::post('/conversations/{contactId}/messages', [MessageController::class, 'store']);
    Route::put('/messages/mark-read', [MessageController::class, 'markAsRead']);
    Route::get('/conversations/{contactId}/status', [MessageController::class, 'contactStatus']);

    // Contacts
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::get('/contacts/{id}', [ContactController::class, 'show']);

    Route::get('/form-options', [FormOptionsController::class, 'index']);
});
