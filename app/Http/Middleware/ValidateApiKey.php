<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use App\Models\ApiKeyLog;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) {
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');
        $apiKey = $request->header('X-API-KEY');

        $location = null;
        // Obtener ubicación desde la API externa
        $geoData = Http::get("http://ip-api.com/json/{$ip}")->json();
        if (count($geoData)) {
            $location = (array_key_exists('country', $geoData) ? $geoData['country'] : '') . ', ' . (array_key_exists('regionName', $geoData) ? $geoData['regionName'] : '') . ', ' . (array_key_exists('city', $geoData) ? $geoData['city'] : '');
        }

        // Verificar si la IP está bloqueada
        $ultimoIntento = ApiKeyLog::where('ip_address', $ip)
            ->whereNotNull('bloqueado_hasta')
            ->latest()
            ->first();

        if ($ultimoIntento && Carbon::now()->lt(Carbon::parse($ultimoIntento->bloqueado_hasta))) {
            return response()->json([
                'error' => 'Esta IP ha sido bloqueada temporalmente. Intenta más tarde.'
            ], 429);
        }

        // Verificar advertencias en la última hora
        $horaAtras = Carbon::now()->subHour();
        $advertencias = ApiKeyLog::where('ip_address', $ip)
            ->where('estado', ApiKeyLog::ALVERTENCIA)
            ->where('fecha', '>=', $horaAtras)
            ->count();

        if ($advertencias >= 3) {
            // Registrar el bloqueo de la IP (1 hora)
            ApiKeyLog::create([
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'fecha' => now(),
                'estado' => ApiKeyLog::ERROR,
                'location' => $location,
                'comentario' => 'Demasiados intentos fallidos. La IP ha sido bloqueada por 1 hora.',
                'bloqueado_hasta' => Carbon::now()->addHour()
            ]);

            return response()->json([
                'error' => 'Demasiados intentos fallidos. La IP ha sido bloqueada por 1 hora.'
            ], 429);
        }

        // Si falta la API Key
        if (!$apiKey) {
            ApiKeyLog::create([
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'fecha' => now(),
                'estado' => ApiKeyLog::ALVERTENCIA,
                'comentario' => 'El API Key es requerido',
                'location' => $location
            ]);

            return response()->json(['error' => 'El API Key es requerido'], 401);
        }

        // Validar la API Key
        $key = ApiKey::where('key', $apiKey)->where('estado', ApiKey::ACTIVO)->first();

        if (!$key) {
            ApiKeyLog::create([
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'fecha' => now(),
                'estado' => ApiKeyLog::ALVERTENCIA,
                'comentario' => 'El API Key es inválido',
                'location' => $location
            ]);

            return response()->json(['error' => 'El API Key es inválido'], 403);
        }

        // Registrar uso exitoso
        ApiKeyLog::create([
            'api_key_id' => $key->id,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'fecha' => now(),
            'estado' => ApiKeyLog::OK,
            'location' => $location
        ]);

        // 🔹 Agregar el ID del usuario a la request
        $request->merge(['user_id' => $key->id_usuario]);

        return $next($request);
    }
}
