<?php

namespace App\Http\Middleware;

use App\Models\Dispositivo;
use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MobileAuth
{
    /**
     * Validar el token que viene desde la app móvil (escaneo QR)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token no proporcionado',
            ], 401);
        }

        // Buscar la autenticación por token
        $auth = Dispositivo::where('token', $token)
            // ->where('estado', 1) // Activa
            ->first();

        if (!$auth) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido o expirado',
            ], 401);
        }

        // Buscar el usuario
        $user = Usuario::find($auth->usuario_id);

        if (!$user || $user->estado != Usuario::ACTIVO) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado o inactivo',
            ], 401);
        }

        // Asignar el usuario al request para que el controller lo use
        $request->setUserResolver(fn() => $user);

        return $next($request);
    }
}
