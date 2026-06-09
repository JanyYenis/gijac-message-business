<?php

use App\Http\Controllers\ActividadesController;
use Illuminate\Support\Facades\Route;

// De aqui es de donde se trae la data de las actividades que se muestran en la tabla
Route::get('/listadoAct', [ActividadesController::class, 'listado'])->name('listadoActividades');


// ------------------ Rutas para editar Actividades ----------
// Esta ruta es para traer una actividad en especifico no todas las actividades ademas esta apunta puntualmente para traer los datos y luego editar la actividad
Route::get('/act/{id}', [ActividadesController::class, 'edit'])->name('traerAct');
// Esta ruta es para guardar los datos de la edicion de la Actividad esta solo es para guardar los cambios realizados a la actividad
Route::post('/editback/{id}', [ActividadesController::class, 'editBack'])->name('editGuardar');


// Crear actividades
Route::post('/crearAct', [ActividadesController::class, 'crear'])->name('crearActividades');

// Ruta para desactivar actividades
Route::post('/inactivarAct/{id}', [ActividadesController::class, 'inactivar'])->name('inactivarAct');

