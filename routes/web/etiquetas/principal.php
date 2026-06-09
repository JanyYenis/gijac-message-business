<?php

use App\Http\Controllers\EtiquetaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EtiquetaController::class, 'index'])->name('index');
Route::get('/listado', [EtiquetaController::class, 'listado'])->name('listado');
Route::post('/guardar', [EtiquetaController::class, 'store'])->name('store');
Route::get('{etiqueta}/editar', [EtiquetaController::class, 'edit'])->name('edit');
Route::put('{etiqueta}/actualizar', [EtiquetaController::class, 'update'])->name('update');
Route::delete('{etiqueta}/eliminar', [EtiquetaController::class, 'delete'])->name('delete');
Route::get('/buscar', [EtiquetaController::class, 'buscar'])->name('buscar');
