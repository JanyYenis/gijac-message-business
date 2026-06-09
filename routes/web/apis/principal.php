<?php

use App\Http\Controllers\ApiKeyController;
use Illuminate\Support\Facades\Route;

Route::get('/listado', [ApiKeyController::class, 'listado'])->name('listado');
Route::get('/listado-log', [ApiKeyController::class, 'listadoLog'])->name('listado-log');
Route::post('/crear', [ApiKeyController::class, 'store'])->name('store');
Route::put('/actualizar/{key}', [ApiKeyController::class, 'update'])->name('update');
Route::delete('/eliminar/{key}', [ApiKeyController::class, 'delete'])->name('delete');