<?php

use App\Http\Controllers\Apis\ContactoApiController;
use App\Http\Controllers\Apis\GeneralController;
use App\Http\Controllers\Apis\MensajeApiController;
use App\Http\Controllers\ApiWebhookController;
use Illuminate\Support\Facades\Route;

// Rutas existentes (cada una exige el permiso correspondiente en la API Key; las llaves anteriores tienen acceso completo)
Route::middleware('validate_api_key:campaigns.read')->get('/campanas', [GeneralController::class, 'campanas']);
Route::middleware('validate_api_key:campaigns.read')->get('/campana', [GeneralController::class, 'campana']);
Route::middleware('validate_api_key:campaigns.read')->get('/detalle-campanas', [GeneralController::class, 'detalleCampanas']);
// /enviar-mensaje era un stub vacío: ahora envía texto igual que /v1/mensajes/texto
Route::middleware('validate_api_key:messages.send')->post('/enviar-mensaje', [MensajeApiController::class, 'texto']);
Route::middleware('validate_api_key:messages.send')->post('/plantillas/enviar-individual', [GeneralController::class, 'enviarPlantillaIndividual']);

// API v1
Route::prefix('v1')->group(function () {
    // Mensajes
    Route::middleware('validate_api_key:messages.send')->post('/mensajes/texto', [MensajeApiController::class, 'texto']);
    Route::middleware('validate_api_key:messages.send')->get('/mensajes/{wamid}', [MensajeApiController::class, 'estado']);

    // Contactos
    Route::middleware('validate_api_key:contacts.read')->get('/contactos', [ContactoApiController::class, 'index']);
    Route::middleware('validate_api_key:contacts.read')->get('/contactos/{telefono}', [ContactoApiController::class, 'show'])->where('telefono', '\+?[0-9]+');
    Route::middleware('validate_api_key:contacts.write')->post('/contactos', [ContactoApiController::class, 'store']);
    Route::middleware('validate_api_key:contacts.write')->put('/contactos/{telefono}', [ContactoApiController::class, 'update'])->where('telefono', '\+?[0-9]+');

    // Etiquetas
    Route::middleware('validate_api_key:tags.read')->get('/etiquetas', [ContactoApiController::class, 'etiquetas']);
    Route::middleware('validate_api_key:tags.write')->post('/contactos/{telefono}/etiquetas', [ContactoApiController::class, 'asignarEtiqueta'])->where('telefono', '\+?[0-9]+');
    Route::middleware('validate_api_key:tags.write')->delete('/contactos/{telefono}/etiquetas/{etiqueta}', [ContactoApiController::class, 'quitarEtiqueta'])->where('telefono', '\+?[0-9]+');
});

Route::prefix('api-webhook')->name('api-webhook.')->group(function () {
    Route::get('/', [ApiWebhookController::class, 'show'])->name('show');
    Route::post('/', [ApiWebhookController::class, 'guardar'])->name('guardar');
    Route::post('/secreto', [ApiWebhookController::class, 'secreto'])->name('secreto');
    Route::post('/probar', [ApiWebhookController::class, 'probar'])->name('probar');
});
