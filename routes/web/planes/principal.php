<?php

use App\Http\Controllers\PlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PlanController::class, 'index'])->name('index');
Route::get('/listado', [PlanController::class, 'listado'])->name('listado');
Route::post('/guardar', [PlanController::class, 'store'])->name('store');
Route::get('{plan}/ver', [PlanController::class, 'show'])->name('show');
Route::get('{plan}/editar', [PlanController::class, 'edit'])->name('edit');
Route::put('{plan}/actualizar', [PlanController::class, 'update'])->name('update');
Route::delete('{plan}/eliminar', [PlanController::class, 'delete'])->name('delete');
