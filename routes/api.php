<?php

use App\Http\Controllers\ChatbotController;
use App\Models\Usuario;
use Illuminate\Http\Request;
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

include 'apis/movil/principal.php';
include 'apis/general/principal.php';
