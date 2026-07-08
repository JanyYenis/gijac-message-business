<?php

namespace App\Services\N8n;

use App\Models\AutomatizacionN8n;
use App\Models\AutomatizacionN8nEjecucion;
use App\Models\AutomatizacionN8nEvento;
use App\Models\Contacto;
use Illuminate\Support\Facades\Http;

class N8nService
{
    public function ejecutar(
        AutomatizacionN8n $automatizacion,
        array $payload,
        Contacto $contacto
    ): ?array {

        $maxIntentos = ($automatizacion->cantidad_reintentos ?? 0) + 1;
        $inicio = microtime(true);
        $ejecucion = AutomatizacionN8nEjecucion::create([
            'cod_automatizacion' => $automatizacion->id,
            'cod_contacto' => $contacto?->id,
            'evento' => AutomatizacionN8nEvento::NUEVO_MENSAJE,
            'estado' => AutomatizacionN8nEjecucion::PROCESANDO,
            'payload_enviado' => $payload,
            'fecha_ejecucion' => now(),
        ]);

        for ($intento = 1; $intento <= $maxIntentos; $intento++) {
            try {
                $request = Http::withoutVerifying()
                    ->timeout($automatizacion->timeout_segundos ?? 30)
                    ->retry(
                        $automatizacion->cantidad_reintentos ?? 0,
                        ($automatizacion->tiempo_entre_intentos ?? 1) * 1000
                    );

                if ($automatizacion->token_seguridad) {
                    $request = $request->withHeaders([
                        'Authorization' => 'Bearer '.$automatizacion->token_seguridad
                    ]);
                }

                $response = match ($automatizacion->metodoHttp?->nombre ?? 'POST') {
                    'GET' => $request->get($automatizacion->url_webhook, $payload),
                    'PUT' => $request->put($automatizacion->url_webhook, $payload),
                    'PATCH' => $request->patch($automatizacion->url_webhook, $payload),
                    default => $request->post($automatizacion->url_webhook, $payload),
                };

                $duracion = round((microtime(true) - $inicio) * 1000);

                if (!$response->successful()) {
                    $ejecucion->update([
                        'estado' => AutomatizacionN8nEjecucion::ERROR,
                        'codigo_respuesta' => $response->status(),
                        'duracion_ms' => $duracion,
                        'respuesta' => $response->body(),
                    ]);

                    return null;
                }

                $ejecucion->update([
                    'estado' => AutomatizacionN8nEjecucion::EXITOSO,
                    'codigo_respuesta' => $response->status(),
                    'duracion_ms' => $duracion,
                    'respuesta' => $response->json() ?? $response->body(),
                ]);

                return $response->json() ?? [
                    'output' => $response->body()
                ];
            } catch (\Throwable $e) {

                $duracion = round(
                    (microtime(true) - $inicio) * 1000
                );

                $ejecucion->update([
                    'estado' => AutomatizacionN8nEjecucion::ERROR,
                    'codigo_respuesta' => 0,
                    'duracion_ms' => $duracion,
                    'respuesta' => [
                        'error' => $e->getMessage(),
                    ],
                ]);

                throw $e;
            }

            if ($intento < $maxIntentos) {

                $ejecucion->update([
                    'estado' => AutomatizacionN8nEjecucion::REINTENTANDO,
                ]);

                sleep(
                    $automatizacion->tiempo_entre_intentos ?? 1
                );
            }
        }

        return null;
    }
}
