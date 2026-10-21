<?php

namespace App\Jobs;

use App\Models\ApiWebhook;
use App\Services\Api\ApiWebhookService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Envía un evento a la URL de webhook del cliente, firmado con HMAC-SHA256:
 *   X-GMB-Signature: sha256=HMAC(secreto, "{X-GMB-Timestamp}.{cuerpo}")
 */
class EnviarWebhookApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $timeout = 20;

    public string $deliveryId;

    public function __construct(public string $webhookId, public string $evento, public array $payload)
    {
        $this->deliveryId = (string) Str::uuid();
    }

    public function backoff(): array
    {
        return [30, 120, 600, 1800];
    }

    public function handle(): void
    {
        $wh = ApiWebhook::find($this->webhookId);
        if (!$wh || $wh->estado != ApiWebhook::ACTIVO) {
            return;
        }

        [$ok, $http, $error, $reintentar] = self::enviar($wh, $this->evento, $this->payload, $this->deliveryId);

        $wh->forceFill([
            'ultimo_envio_at' => now(),
            'ultimo_http' => $http,
            'ultimo_error' => $ok ? null : $error,
        ]);
        if ($ok) {
            $wh->fallos_consecutivos = 0;
        }
        $wh->save();

        if (!$ok) {
            if (!$reintentar) {
                $this->desactivar($wh);
                return;
            }
            throw new \RuntimeException($error ?? 'Error al enviar el webhook');
        }
    }

    public function failed(\Throwable $e): void
    {
        $wh = ApiWebhook::find($this->webhookId);
        if (!$wh) {
            return;
        }

        $wh->increment('fallos_consecutivos');
        if ($wh->fallos_consecutivos >= ApiWebhook::MAX_FALLOS) {
            $this->desactivar($wh);
        }
    }

    private function desactivar(ApiWebhook $wh): void
    {
        $wh->update(['estado' => ApiWebhook::INACTIVO]);
        ApiWebhookService::olvidar($wh->cod_empresa);
    }

    /** Hace el POST. Devuelve [ok, http, error, reintentar]. También lo usa el botón "Probar". */
    public static function enviar(ApiWebhook $wh, string $evento, array $payload, ?string $deliveryId = null): array
    {
        [$errUrl, $ip, $host, $puerto] = ApiWebhookService::validarUrl($wh->url);
        if ($errUrl) {
            return [false, null, $errUrl, false]; // URL insegura: no se reintenta
        }

        $deliveryId ??= (string) Str::uuid();
        $cuerpo = json_encode([
            'id' => $deliveryId,
            'evento' => $evento,
            'creado' => now()->toIso8601String(),
            'data' => $payload,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $ts = (string) time();
        $firma = 'sha256=' . hash_hmac('sha256', "{$ts}.{$cuerpo}", $wh->secreto);

        try {
            $resp = Http::timeout(10)
                ->withOptions([
                    'allow_redirects' => false,
                    // Se conecta a la IP ya validada (evita que el DNS cambie entre la validación y el envío)
                    'curl' => [CURLOPT_RESOLVE => ["{$host}:{$puerto}:{$ip}"]],
                ])
                ->withHeaders([
                    'User-Agent' => 'GIJAC-Webhook/1.0',
                    'X-GMB-Event' => $evento,
                    'X-GMB-Delivery' => $deliveryId,
                    'X-GMB-Timestamp' => $ts,
                    'X-GMB-Signature' => $firma,
                ])
                ->withBody($cuerpo, 'application/json')
                ->post($wh->url);
        } catch (\Throwable $e) {
            return [false, null, mb_substr('No se pudo conectar: ' . $e->getMessage(), 0, 250), true];
        }

        if ($resp->successful()) {
            return [true, $resp->status(), null, false];
        }

        return [false, $resp->status(), 'La URL respondió HTTP ' . $resp->status(), true];
    }
}
