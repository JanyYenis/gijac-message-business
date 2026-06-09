<?php

use App\Http\Controllers\ContactoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ContactoController::class, 'index'])->name('index');
Route::get('/listado', [ContactoController::class, 'listado'])->name('listado');
Route::post('/guardar', [ContactoController::class, 'store'])->name('store');
Route::post('/cargar-contactos', [ContactoController::class, 'cargarContactos'])->name('cargarContactos');
Route::get('/{contacto}/editar', [ContactoController::class, 'edit'])->name('edit');
Route::put('/{contacto}/actualizar', [ContactoController::class, 'update'])->name('update');
Route::get('/{contacto}/ver', [ContactoController::class, 'show'])->name('show');
Route::get('/{contacto}/reporte', [ContactoController::class, 'showInfo'])->name('showInfo');
Route::delete('/{contacto}/eliminar', [ContactoController::class, 'delete'])->name('delete');
Route::get('/buscar', [ContactoController::class, 'buscar'])->name('buscar');
Route::get('/buscarPrediccion', [ContactoController::class, 'buscarPrediccion'])->name('buscarPrediccion');
