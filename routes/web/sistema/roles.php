<?php

use App\Http\Controllers\Sistema\RolController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RolController::class, 'index'])->name('index');
Route::get('/listado', [RolController::class, 'listado'])->name('listado');
Route::get('/crear', [RolController::class, 'create'])->name('create');
Route::post('/guardar', [RolController::class, 'store'])->name('store');
Route::post('/buscarRol', [RolController::class, 'buscarRol'])->name('buscarRol');
Route::post('/buscarPermisos', [RolController::class, 'buscarPermisos'])->name('buscarPermisos');
Route::post('{usuario}/asignarRol', [RolController::class, 'asignarRol'])->name('asignarRol');
Route::post('{usuario}/asignarPermiso', [RolController::class, 'asignarPermiso'])->name('asignarPermiso');
Route::get('{rol}/editar', [RolController::class, 'edit'])->name('edit');
Route::put('{rol}/actualizar', [RolController::class, 'update'])->name('update');
Route::put('{rol}/eliminar', [RolController::class, 'destroy'])->name('delete');