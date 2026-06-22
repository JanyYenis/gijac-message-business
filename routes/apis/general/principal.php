<?php

use App\Http\Controllers\Apis\GeneralController;
use Illuminate\Support\Facades\Route;

Route::middleware('validate_api_key')->get('/campanas', [GeneralController::class, 'campanas']);
Route::middleware('validate_api_key')->get('/campana', [GeneralController::class, 'campana']);
Route::middleware('validate_api_key')->get('/detalle-campanas', [GeneralController::class, 'detalleCampanas']);
Route::middleware('validate_api_key')->post('/enviar-mensaje', [GeneralController::class, 'enviarMensaje']);
Route::middleware('validate_api_key')->post('/plantillas/enviar-individual', [GeneralController::class, 'enviarPlantillaIndividual']);
