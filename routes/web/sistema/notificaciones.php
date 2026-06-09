<?php

use App\Http\Controllers\Sistema\NotificacionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [NotificacionController::class, 'index'])->name('index');
Route::get('/marcarNotificaciones', [NotificacionController::class, 'marcarNotificaciones'])->name('marcarNotificaciones');
Route::get('{notificacion}/marcarNotificacion', [NotificacionController::class, 'marcarNotificacion'])->name('marcarNotificacion');