<?php

use App\Http\Controllers\ComentarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ComentarioController::class, 'index'])->name('index');
Route::get('/listado', [ComentarioController::class, 'listado'])->name('listado');
Route::post('/guardar', [ComentarioController::class, 'store'])->name('store');
Route::get('{comentario}/editar', [ComentarioController::class, 'edit'])->name('edit');
Route::put('{comentario}/actualizar', [ComentarioController::class, 'update'])->name('update');
Route::delete('{comentario}/eliminar', [ComentarioController::class, 'delete'])->name('delete');
