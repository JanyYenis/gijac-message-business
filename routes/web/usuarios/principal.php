<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UsuarioController::class, 'index'])
    ->name('index');
Route::get('/listado', [UsuarioController::class, 'listado'])
    ->name('listado');
Route::post('/guardar', [UsuarioController::class, 'store'])
    ->name('store');
Route::get('{usuario}/editar', [UsuarioController::class, 'edit'])
    ->name('edit');
Route::put('{usuario}/actualizar', [UsuarioController::class, 'update'])
    ->name('update');
Route::delete('{usuario}/eliminar', [UsuarioController::class, 'delete'])
    ->name('delete');
Route::post('buscar', [UsuarioController::class, 'buscar'])
    ->name('buscar');