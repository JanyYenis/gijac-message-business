<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class AnalyticsService
{
    /**
     * Obtener las analíticas de una cuenta de WhatsApp Business.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $wabaId ID de la cuenta de WhatsApp Business.
     * @param int $start Timestamp Unix inicial.
     * @param int $end Timestamp Unix final.
     * @param string $granularity Granularidad de las métricas: DAY, MONTH o HALF_HOUR, según lo admitido por la API.
     * @param array $phoneNumbers Números de teléfono para filtrar. Array vacío para consultar todos.
     * @param array $countryCodes Códigos de países para filtrar, por ejemplo ['US', 'BR'].
     * @return mixed
     */
    public function getAnalytics(
        string $accessToken,
        string $wabaId,
        int $start,
        int $end,
        string $granularity = 'DAY',
        array $phoneNumbers = [],
        array $countryCodes = []
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $fields = sprintf(
            'analytics.start(%d).end(%d).granularity(%s).phone_numbers(%s).country_codes(%s)',
            $start,
            $end,
            $granularity,
            json_encode(array_values($phoneNumbers)),
            json_encode(array_values($countryCodes))
        );

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get(
                "https://graph.facebook.com/{$version}/{$wabaId}",
                [
                    'fields' => $fields,
                ]
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible obtener las analíticas de WhatsApp Business.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Obtener las analíticas de conversaciones de una cuenta de WhatsApp Business.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $wabaId ID de la cuenta de WhatsApp Business.
     * @param int $start Timestamp Unix inicial.
     * @param int $end Timestamp Unix final.
     * @param string $granularity Granularidad de las métricas, por ejemplo MONTHLY.
     * @param array $conversationDirections Direcciones de conversación para filtrar.
     * @param array $dimensions Dimensiones por las que se agruparán las métricas.
     * @return mixed
     */
    public function getConversationAnalytics(
        string $accessToken,
        string $wabaId,
        int $start,
        int $end,
        string $granularity = 'MONTHLY',
        array $conversationDirections = ['business_initiated'],
        array $dimensions = ['conversation_type', 'conversation_direction']
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $fields = sprintf(
            'conversation_analytics.start(%d).end(%d).granularity(%s).conversation_directions(%s).dimensions(%s)',
            $start,
            $end,
            $granularity,
            json_encode(array_values($conversationDirections)),
            json_encode(array_values($dimensions))
        );

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get(
                "https://graph.facebook.com/{$version}/{$wabaId}",
                [
                    'fields' => $fields,
                ]
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible obtener las analíticas de conversaciones de WhatsApp Business.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }
}
