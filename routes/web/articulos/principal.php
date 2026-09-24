<?php

use Illuminate\Support\Facades\Route;

Route::get('/api-whatsapp-business-importancia-empresas', function() {
    return view('articulos.api-whatsapp');
});
