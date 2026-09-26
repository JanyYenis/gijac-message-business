<?php

use Illuminate\Support\Facades\Route;

Route::get('/api-whatsapp-business-importancia-empresas', function() {
    return view('articulos.api-whatsapp');
})->name('articulos.api-whatsapp');

Route::get('/enviar-mensajes-masivos-whatsapp-sin-bloqueo', function() {
    return view('articulos.mensajes-masivos');
})->name('articulos.mensajes-masivos');
