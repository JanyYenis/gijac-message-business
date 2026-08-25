<?php

use App\Http\Controllers\Apis\Mobile\AuthController;
use App\Http\Controllers\Apis\Mobile\ChatController;
use App\Http\Controllers\ChatbotController;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Solo para desarrollo - ELIMINAR en producción
if (config('app.env') === 'local') {
    Route::post('/auth/mock-login', function (Request $request) {
        $user = Usuario::where('email', 'jany.escobar@gijac.co')->first();

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->nombre_completo,
                'email' => $user->email,
                'uuid' => $user->uuid,
            ]
        ]);
    });
}

Route::prefix('mobile')->group(function () {

    // Rutas protegidas por token móvil
    Route::middleware('mobile.auth')->group(function () {

        Route::post('/broadcasting/auth', function (Request $request) {
            return Broadcast::auth($request);
        });

        // Perfil del usuario
        Route::get('/profile', [AuthController::class, 'getProfile']);

        // Cerrar sesión
        Route::post('/logout', [AuthController::class, 'logout']);

        // Rutas de Chats
        Route::get('/chats', [ChatController::class, 'index']);
        Route::get('/chats/{numero}', [ChatController::class, 'showMessages']);
        Route::post('/chats/{numero}/messages', [ChatController::class, 'sendMessage']);
        Route::post('/chats/{numero}/read', [ChatController::class, 'markAsRead']);
    });
});

include 'apis/general/principal.php';
