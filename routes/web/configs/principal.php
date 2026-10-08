<?php

use App\Http\Controllers\Admin\WhatsAppOnboardingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WhatsAppOnboardingController::class, 'connect'])->name('index');
