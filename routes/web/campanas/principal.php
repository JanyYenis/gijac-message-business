<?php

use App\Http\Controllers\CampanaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CampanaController::class, 'index'])->name('index');
Route::get('/listado', [CampanaController::class, 'listado'])->name('listado');
Route::post('/crear', [CampanaController::class, 'store'])->name('store');
Route::get('/editar/{campana}', [CampanaController::class, 'edit'])->name('edit');
Route::put('/actualizar/{campana}', [CampanaController::class, 'update'])->name('update');
Route::delete('/eliminar/{campana}', [CampanaController::class, 'delete'])->name('delete');

Route::get('/{campana}/ver', [CampanaController::class, 'show'])->name('show');
Route::post('/{campana}/filtro', [CampanaController::class, 'filtroShow'])->name('filtro-show');
Route::get('/{campana}/excel', [CampanaController::class, 'exportar'])->name('exportar');

Route::get('/{campana}/envios', [CampanaController::class, 'envios'])->name('envios');
Route::get('/{campana}/listado-detalle', [CampanaController::class, 'listadoDetalle'])->name('listado-detalle');
Route::get('/ver-respuesta-formulario/{wamid}', [CampanaController::class, 'verFormulario'])->name('ver-formulario');
Route::get('/ver-errores/{wamid}', [CampanaController::class, 'verErrores'])->name('ver-errores');
Route::get('/ver-links/{id}', [CampanaController::class, 'verLinks'])->name('ver-links');

Route::get('/cargar-contactos', [CampanaController::class, 'cargarContactos'])->name('cargarContactos');

Route::get('/listado-tarjeta', [CampanaController::class, 'listadoTarjeta'])->name('listado-tarjeta');

Route::get('/reenviar/{campana}', [CampanaController::class, 'reenviar'])->name('reenviar');

Route::post('/predecir', [CampanaController::class, 'predecir'])->name('predecir');
