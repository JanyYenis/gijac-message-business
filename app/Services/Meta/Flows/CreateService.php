<?php

namespace App\Services\Meta\Flows;

use Illuminate\Support\Facades\Http;

class CreateService
{
    /**
     * Crea un Flow de WhatsApp Business.
     *
     * Endpoint:
     * POST /{WABA-ID}/flows
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - wabaId: ID de la cuenta de WhatsApp Business.
     * - name: Nombre del Flow.
     * - categories: Categorías del Flow (por defecto OTHER).
     *
     * Respuesta exitosa:
     * {
     *     "id": "FLOW_ID"
     * }
     */
    public function createFlow(
        string $accessToken,
        string $wabaId,
        string $name,
        array $categories = ['OTHER']
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->asMultipart()
            ->post("https://graph.facebook.com/{$version}/{$wabaId}/flows", [
                [
                    'name' => 'name',
                    'contents' => $name,
                ],
                [
                    'name' => 'categories',
                    'contents' => json_encode($categories),
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear el Flow de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Migra Flows de una cuenta de WhatsApp Business a otra.
     *
     * Endpoint:
     * POST /{WABA-ID}/migrate_flows
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - wabaId: ID de la cuenta de WhatsApp Business de destino.
     * - sourceWabaId: ID de la cuenta de WhatsApp Business de origen.
     * - sourceFlowNames: Nombres de los Flows que se van a migrar.
     *
     * Respuesta exitosa:
     * Devuelve la respuesta de Meta con el resultado de la migración.
     */
    public function migrateFlows(
        string $accessToken,
        string $wabaId,
        string $sourceWabaId,
        array $sourceFlowNames
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->asMultipart()
            ->post("https://graph.facebook.com/{$version}/{$wabaId}/migrate_flows", [
                [
                    'name' => 'source_waba_id',
                    'contents' => $sourceWabaId,
                ],
                [
                    'name' => 'source_flow_names',
                    'contents' => json_encode($sourceFlowNames),
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al migrar los Flows de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Consulta los detalles de un Flow de WhatsApp Business.
     *
     * Endpoint:
     * GET /{FLOW-ID}?fields=id,name,categories,preview,status,
     * validation_errors,json_version,data_api_version,data_channel_uri,
     * health_status,whatsapp_business_account,application
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - flowId: ID del Flow que se desea consultar.
     *
     * Respuesta exitosa:
     * Devuelve los detalles del Flow solicitado.
     */
    public function getFlowDetails(string $accessToken, string $flowId)
    {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $fields = implode(',', [
            'id',
            'name',
            'categories',
            'preview',
            'status',
            'validation_errors',
            'json_version',
            'data_api_version',
            'data_channel_uri',
            'health_status',
            'whatsapp_business_account',
            'application',
        ]);

        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$version}/{$flowId}", [
                'fields' => $fields,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al consultar los detalles del Flow de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Obtiene la vista previa de un Flow de WhatsApp Business.
     *
     * Endpoint:
     * GET /{FLOW-ID}?fields=preview.invalidate(false)
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - flowId: ID del Flow que se desea consultar.
     *
     * Nota:
     * invalidate(false) indica que no se debe invalidar la vista previa
     * al realizar esta consulta.
     */
    public function getFlowPreview(string $accessToken, string $flowId)
    {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$version}/{$flowId}", [
                'fields' => 'preview.invalidate(false)',
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al obtener la vista previa del Flow de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Obtiene el listado de Flows de una cuenta de WhatsApp Business.
     *
     * Endpoint:
     * GET /{WABA-ID}/flows
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - wabaId: ID de la cuenta de WhatsApp Business.
     *
     * Respuesta exitosa:
     * Devuelve el listado de Flows asociados a la cuenta.
     */
    public function getFlows(string $accessToken, string $wabaId)
    {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$version}/{$wabaId}/flows");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al consultar los Flows de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }
}
