<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('catalogo.index');
})->name('index');
