<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/{app_id}', [WebhookController::class, 'webhook']);
Route::post('/{app_id}', [WebhookController::class, 'acctionWebhook']);