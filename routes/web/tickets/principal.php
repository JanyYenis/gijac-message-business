<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TicketController::class, 'index'])->name('index');
Route::get('/listado', [TicketController::class, 'listado'])->name('listado');
Route::post('/crear', [TicketController::class, 'store'])->name('store');
Route::get('/editar/{ticket}', [TicketController::class, 'edit'])->name('edit');
Route::put('/actualizar/{ticket}', [TicketController::class, 'update'])->name('update');
