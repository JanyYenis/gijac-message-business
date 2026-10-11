<?php

namespace App\Services\Meta\Flows;

use Illuminate\Support\Facades\Http;

class CreateService
{
    /**
     * Consulta la cantidad de solicitudes recibidas por el endpoint de un Flow.
     *
     * Obtiene la métrica ENDPOINT_REQUEST_COUNT con granularidad diaria
     * para el intervalo de fechas especificado.
     *
     * Se pueden solicitar campos específicos mediante el parámetro
     * de consulta "fields".
     *
     * Endpoint:
     * GET /{FLOW-ID}?fields=metric.name(ENDPOINT_REQUEST_COUNT).granularity(DAY).since(YYYY-MM-DD).until(YYYY-MM-DD)
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - flowId: ID del WhatsApp Flow.
     * - since: Fecha inicial del periodo (YYYY-MM-DD).
     * - until: Fecha final del periodo (YYYY-MM-DD).
     *
     * Respuesta exitosa:
     * Devuelve los datos de la métrica solicitada.
     */
    public function getEndpointRequestCountMetric(
        string $accessToken,
        string $flowId,
        string $since = '2024-01-28',
        string $until = '2024-01-30'
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $fields = sprintf(
            'metric.name(ENDPOINT_REQUEST_COUNT).granularity(DAY).since(%s).until(%s)',
            $since,
            $until
        );

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get(
                "https://graph.facebook.com/{$version}/{$flowId}",
                [
                    'fields' => $fields,
                ]
            );

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al consultar la métrica de solicitudes del endpoint del Flow.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Obtener la métrica de errores en las solicitudes al endpoint de un Flow de WhatsApp.
     *
     * Consulta la cantidad de errores generados en las solicitudes al endpoint
     * del Flow durante un rango de fechas, con granularidad diaria.
     *
     * Endpoint:
     * GET /{flow-id}?fields=metric.name(ENDPOINT_REQUEST_ERROR).granularity(DAY).since({since}).until({until})
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $flowId ID del Flow de WhatsApp.
     * @param string $since Fecha inicial del periodo (Y-m-d).
     * @param string $until Fecha final del periodo (Y-m-d).
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function getEndpointRequestErrorMetric(
        string $accessToken,
        string $flowId,
        string $since = '2024-01-28',
        string $until = '2024-01-30'
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $fields = "metric.name(ENDPOINT_REQUEST_ERROR).granularity(DAY).since({$since}).until({$until})";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get("https://graph.facebook.com/{$version}/{$flowId}", [
                'fields' => $fields,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al consultar la métrica de errores del endpoint del Flow.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Obtener la métrica de latencia de las solicitudes al endpoint de un Flow de WhatsApp.
     *
     * Consulta la latencia de las solicitudes al endpoint del Flow,
     * agrupada por día y expresada en segundos según la métrica de Meta.
     *
     * Endpoint:
     * GET /{flow-id}?fields=metric.name(ENDPOINT_REQUEST_LATENCY_SECONDS_CEIL).granularity(DAY).since({since}).until({until})
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $flowId ID del Flow de WhatsApp.
     * @param string $since Fecha inicial del periodo (Y-m-d).
     * @param string $until Fecha final del periodo (Y-m-d).
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function getEndpointRequestLatenciesMetric(
        string $accessToken,
        string $flowId,
        string $since = '2024-01-28',
        string $until = '2024-01-30'
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $fields = "metric.name(ENDPOINT_REQUEST_LATENCY_SECONDS_CEIL).granularity(DAY).since({$since}).until({$until})";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get("https://graph.facebook.com/{$version}/{$flowId}", [
                'fields' => $fields,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al consultar la métrica de latencia de las solicitudes al endpoint del Flow.',
            'error' => $response->json(),
        ], $response->status());
    }
}
