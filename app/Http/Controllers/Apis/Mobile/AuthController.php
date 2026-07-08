<?php

namespace App\Http\Controllers\Apis\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserProfileResource;
use App\Models\Usuario;
use App\Models\Dispositivo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Obtener el perfil del usuario autenticado
     */
    public function getProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        // Cargar relaciones necesarias
        $user->load([
            'infoGenero',
            'ciudad.pais',
            'empresa',
            'plan',
            'roles',
        ]);

        return response()->json([
            'success' => true,
            'data'    => new UserProfileResource($user),
        ]);
    }

    /**
     * Cerrar sesión (invalidar token del dispositivo)
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $token = $request->bearerToken();

        if ($token) {
            // Eliminar la autenticación específica de este dispositivo
            Dispositivo::where('usuario_id', $user->id)
                ->where('token', $token)
                ->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente',
        ]);
    }
}
