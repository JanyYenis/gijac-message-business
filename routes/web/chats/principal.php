<?php

use App\Http\Controllers\MensajeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MensajeController::class, 'index'])->name('index');
Route::get('/{contacto}/chat', [MensajeController::class, 'chat'])->name('contacto');
Route::get('/{contacto}/actualizar-estado', [MensajeController::class, 'actualizarMensaje'])->name('estado-mensaje');
Route::post('/enviar-mensaje', [MensajeController::class, 'store'])->name('store');
Route::get('/{de}/macar-mensale-leido/{para}', [MensajeController::class, 'macarMensaleLeido'])->name('macar-mensale-leido');
Route::get('/actualizar/contactos', [MensajeController::class, 'actualizarContactos'])->name('actualizarContactos');
Route::get('/info/{contacto}', [MensajeController::class, 'infoContacto'])->name('infoContacto');
