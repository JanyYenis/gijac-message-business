<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use App\Models\ApiKeyLog;
use App\Models\Empresa;
use App\Models\Plan;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    /** Uso: validate_api_key  o  validate_api_key:messages.send (permiso que debe tener la llave). */
    public function handle(Request $request, Closure $next, string ...$scopes) {
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');
        $apiKey = $request->header('X-API-KEY');

        $location = $this->ubicacion($ip);

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
        $key = ApiKey::where('key_hash', ApiKey::hashear($apiKey))->where('estado', ApiKey::ACTIVO)->first();

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

        $usuario = $key?->user ?? null;
        $tiene = false;
        $empresa = null;
        if ($usuario?->empresa) {
            $empresa = Empresa::with('facturaVigente')->find($usuario?->empresa?->id);
            if ($empresa?->facturaVigente) {
                $factura = $empresa?->facturaVigente ?? null;
                $plan = Plan::find($factura->cod_plan);
                $tiene = (bool) $plan?->tieneServicio('api');
            }
        } else {
            return response()->json(['error' => 'No cuenta asociado a ningun negocio.'], 403);
        }

        if (!$tiene) {
             ApiKeyLog::create([
                'api_key_id' => $key->id,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'fecha' => now(),
                'estado' => ApiKeyLog::ALVERTENCIA,
                'location' => $location
            ]);
            return response()->json(['error' => 'Por favor revise que tenga un plan activo.'], 403);
        }

        // La llave debe tener el permiso que pide la ruta
        foreach ($scopes as $scope) {
            if (!$key->tieneScope($scope)) {
                ApiKeyLog::create([
                    'api_key_id' => $key->id,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'fecha' => now(),
                    'estado' => ApiKeyLog::ERROR,
                    'comentario' => "Permiso insuficiente: {$scope}",
                    'location' => $location
                ]);
                return response()->json(['error' => "Esta API Key no tiene el permiso requerido ({$scope})."], 403);
            }
        }

        // Límite de solicitudes por llave (60 por minuto)
        $limite = 'api-key:' . $key->id;
        if (RateLimiter::tooManyAttempts($limite, 60)) {
            return response()->json(['error' => 'Demasiadas solicitudes. Intenta de nuevo en unos segundos.'], 429)
                ->header('Retry-After', RateLimiter::availableIn($limite));
        }
        RateLimiter::hit($limite, 60);

        // Registrar uso exitoso
        ApiKeyLog::create([
            'api_key_id' => $key->id,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'fecha' => now(),
            'estado' => ApiKeyLog::OK,
            'location' => $location
        ]);

        $key->forceFill(['ultimo_uso' => now()])->saveQuietly();

        // 🔹 Agregar el ID del usuario a la request
        $request->merge(['user_id' => $key->uuid]);
        $request->attributes->set('api_key', $key);
        $request->attributes->set('api_usuario', $usuario);
        $request->attributes->set('api_empresa', $empresa ?? $usuario->empresa);
        $request->attributes->set('api_empresa_id', ($empresa ?? $usuario->empresa)?->id);

        return $next($request);
    }

    /** País/región/ciudad de la IP. Se guarda en caché 24 h y nunca rompe la petición si el servicio falla. */
    private function ubicacion(string $ip): ?string
    {
        if (in_array($ip, ['127.0.0.1', '::1'])) {
            return null;
        }

        return Cache::remember("api:geo:{$ip}", now()->addDay(), function () use ($ip) {
            try {
                $geo = Http::timeout(2)->get("http://ip-api.com/json/{$ip}")->json();
            } catch (\Throwable $e) {
                return null;
            }

            if (!is_array($geo) || empty($geo['country'])) {
                return null;
            }

            return ($geo['country'] ?? '') . ', ' . ($geo['regionName'] ?? '') . ', ' . ($geo['city'] ?? '');
        });
    }
}
