<?php

use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChatbotController::class, 'index'])->name('index');
Route::get('/listado', [ChatbotController::class, 'listado'])->name('listado');
Route::get('/crear', [ChatbotController::class, 'create'])->name('create');
Route::post('/guardar', [ChatbotController::class, 'store'])->name('store');
Route::get('/editar/{chatbot}', [ChatbotController::class, 'edit'])->name('edit');
Route::put('/actualizar/{chatbot}', [ChatbotController::class, 'update'])->name('update');
Route::get('/consultar-nodes/{chatbot}', [ChatbotController::class, 'consultarNodes'])->name('consultar-nodes');
