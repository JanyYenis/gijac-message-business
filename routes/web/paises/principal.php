<?php

use App\Http\Controllers\PaisController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PaisController::class, 'index'])->name('index');
Route::put('{pais}/actualizar', [PaisController::class, 'update'])->name('update');