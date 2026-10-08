<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebhookController::class, 'webhook']);
Route::post('/', [WebhookController::class, 'acctionWebhook']);
