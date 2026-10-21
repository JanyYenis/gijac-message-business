<?php

namespace App\Services\Api;

use App\Jobs\EnviarWebhookApi;
use App\Models\ApiWebhook;
use App\Models\Empresa;
use App\Models\Plan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ApiWebhookService
{
    /** Avisa a la URL del cliente (si tiene webhook activo suscrito a ese evento y su plan incluye API). Nunca lanza errores. */
    public static function emitir($empresaId, string $evento, array $payload): void
    {
        if (!$empresaId) {
            return;
        }

        try {
            $info = Cache::remember(self::clave($empresaId), now()->addMinutes(5), function () use ($empresaId) {
                $wh = ApiWebhook::where('cod_empresa', $empresaId)->where('estado', ApiWebhook::ACTIVO)->first();
                if (!$wh) {
                    return false;
                }

                $empresa = Empresa::with('facturaVigente')->find($empresaId);
                $plan = Plan::find($empresa?->facturaVigente?->cod_plan);
                if (!$plan?->tieneServicio('api')) {
                    return false;
                }

                return ['id' => $wh->id, 'eventos' => $wh->eventos ?? []];
            });

            if ($info && in_array($evento, $info['eventos'], true)) {
                EnviarWebhookApi::dispatch($info['id'], $evento, $payload);
            }
        } catch (\Throwable $e) {
            Log::warning('No se pudo encolar el webhook de la API', ['empresa' => $empresaId, 'error' => $e->getMessage()]);
        }
    }

    public static function olvidar($empresaId): void
    {
        Cache::forget(self::clave($empresaId));
    }

    private static function clave($empresaId): string
    {
        return "api:webhook:{$empresaId}";
    }

    /**
     * Comprueba que la URL sea segura (evita que el servidor llame a redes internas).
     * Devuelve [error|null, ip|null, host, puerto].
     */
    public static function validarUrl(string $url): array
    {
        $p = parse_url($url);
        $esLocal = app()->environment('local');

        if (!$p || empty($p['host']) || empty($p['scheme'])) {
            return ['La URL no es válida.', null, null, null];
        }
        if (isset($p['user']) || isset($p['pass'])) {
            return ['La URL no puede incluir usuario ni contraseña.', null, null, null];
        }
        if (!in_array($p['scheme'], $esLocal ? ['https', 'http'] : ['https'], true)) {
            return ['La URL debe empezar por https://', null, null, null];
        }

        $host = strtolower($p['host']);
        $puerto = $p['port'] ?? ($p['scheme'] === 'https' ? 443 : 80);
        $ips = filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? [$host] : (gethostbynamel($host) ?: []);

        if (!$ips) {
            return ['No se pudo resolver el dominio de la URL (se requiere una dirección IPv4 pública).', null, null, null];
        }

        foreach ($ips as $ip) {
            if (!$esLocal && !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return ['La URL apunta a una dirección privada o reservada.', null, null, null];
            }
        }

        return [null, $ips[0], $host, $puerto];
    }
}
