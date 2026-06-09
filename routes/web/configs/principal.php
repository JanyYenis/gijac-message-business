<?php

use App\Http\Controllers\ConfigController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ConfigController::class, 'index'])->name('index');
Route::get('/listado', [ConfigController::class, 'listado'])->name('listado');
Route::post('/guardar', [ConfigController::class, 'store'])->name('store');
Route::get('{config}/editar', [ConfigController::class, 'edit'])->name('edit');
Route::put('{config}/actualizar', [ConfigController::class, 'update'])->name('update');
Route::delete('{config}/eliminar', [ConfigController::class, 'delete'])->name('delete');