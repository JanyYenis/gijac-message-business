<?php

namespace App\Services\Meta\Flows;

use Illuminate\Support\Facades\Http;

class UpdateService
{

    /**
     * Sube un archivo JSON con el contenido de un Flow de WhatsApp Business.
     *
     * El archivo debe adjuntarse como parte de una solicitud multipart/form-data.
     *
     * La respuesta incluirá los posibles errores de validación encontrados
     * en el archivo JSON.
     *
     * Documentación oficial del formato JSON de WhatsApp Flows:
     * https://developers.facebook.com/docs/whatsapp/flows/reference/flowjson
     *
     * Endpoint:
     * POST /{FLOW-ID}/assets
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - flowId: ID del Flow al que se asociará el archivo.
     * - filePath: Ruta local del archivo JSON.
     * - fileName: Nombre del archivo (por defecto, flow.json).
     *
     * Tipo de recurso:
     * FLOW_JSON
     */
    public function uploadFlowJson(
        string $accessToken,
        string $flowId,
        string $filePath,
        string $fileName = 'flow.json'
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        if (!file_exists($filePath) || !is_readable($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'El archivo JSON no existe o no se puede leer.',
            ], 400);
        }

        $response = Http::withToken($accessToken)
            ->attach(
                'file',
                file_get_contents($filePath),
                $fileName,
                ['Content-Type' => 'application/json']
            )
            ->post("https://graph.facebook.com/{$version}/{$flowId}/assets", [
                'name' => $fileName,
                'asset_type' => 'FLOW_JSON',
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al subir el archivo JSON del Flow de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Publica un Flow de WhatsApp Business.
     *
     * Actualiza el estado del Flow a "PUBLISHED".
     *
     * Esta acción es irreversible. Una vez publicado, el Flow y sus recursos
     * asociados se vuelven inmutables y no podrán modificarse.
     *
     * Para actualizar un Flow publicado, debes crear uno nuevo e indicar
     * el ID del Flow anterior mediante el parámetro clone_flow_id.
     *
     * Endpoint:
     * POST /{FLOW-ID}/publish
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - flowId: ID del Flow que se desea publicar.
     *
     * Respuesta exitosa:
     * Devuelve la respuesta de Meta con el resultado de la publicación.
     */
    public function publishFlow(string $accessToken, string $flowId)
    {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$flowId}/publish");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al publicar el Flow de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Actualiza la configuración de un Flow de WhatsApp Business.
     *
     * Permite modificar el nombre, las categorías y la URI del endpoint
     * asociado al Flow.
     *
     * Endpoint:
     * POST /{FLOW-ID}
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - flowId: ID del Flow que se desea actualizar.
     * - name: Nuevo nombre del Flow.
     * - categories: Array con las categorías del Flow.
     * - endpointUri: URI del endpoint que procesará las solicitudes de datos
     *   del Flow. Este parámetro es opcional.
     *
     * Los parámetros enviados dependen de los campos que se deseen actualizar.
     *
     * Respuesta exitosa:
     * Devuelve la respuesta de Meta con el resultado de la actualización.
     */
    public function updateFlow(
        string $accessToken,
        string $flowId,
        string $name,
        array $categories = ['OTHER'],
        ?string $endpointUri = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $data = [
            [
                'name' => 'name',
                'contents' => $name,
            ],
            [
                'name' => 'categories',
                'contents' => json_encode($categories),
            ],
        ];

        if ($endpointUri !== null) {
            $data[] = [
                'name' => 'endpoint_uri',
                'contents' => $endpointUri,
            ];
        }

        $response = Http::withToken($accessToken)
            ->asMultipart()
            ->post("https://graph.facebook.com/{$version}/{$flowId}", $data);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el Flow de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Obtiene todos los recursos asociados a un Flow de WhatsApp Business.
     *
     * Devuelve todos los recursos adjuntos al Flow. Actualmente, solo se admite
     * el recurso de tipo FLOW_JSON, que contiene la definición del Flow en formato JSON.
     *
     * Endpoint:
     * GET /{FLOW-ID}/assets
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - flowId: ID del Flow cuyos recursos se desean consultar.
     *
     * Respuesta exitosa:
     * Devuelve el listado de recursos asociados al Flow.
     */
    public function getFlowAssets(string $accessToken, string $flowId)
    {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$version}/{$flowId}/assets");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al consultar los recursos del Flow de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Marca un Flow de WhatsApp Business como obsoleto (DEPRECATED).
     *
     * Actualiza el estado del Flow a "DEPRECATED".
     *
     * Esta acción es irreversible. Solo se puede marcar como obsoleto
     * un Flow que se encuentre publicado (PUBLISHED).
     *
     * Una vez marcado como obsoleto, se impide que el Flow se envíe
     * o se abra para su utilización.
     *
     * Endpoint:
     * POST /{FLOW-ID}/deprecate
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - flowId: ID del Flow que se desea marcar como obsoleto.
     *
     * Respuesta exitosa:
     * Devuelve la respuesta de Meta con el resultado de la operación.
     */
    public function deprecateFlow(string $accessToken, string $flowId)
    {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$flowId}/deprecate");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al marcar el Flow de WhatsApp Business como obsoleto.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Elimina por completo un Flow de WhatsApp Business.
     *
     * Esta acción es irreversible. Solo se pueden eliminar Flows que
     * se encuentren en estado "DRAFT" (borrador).
     *
     * Una vez eliminado, el Flow no podrá recuperarse.
     *
     * Endpoint:
     * DELETE /{FLOW-ID}
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - flowId: ID del Flow que se desea eliminar.
     *
     * Respuesta exitosa:
     * Devuelve la respuesta de Meta con el resultado de la eliminación.
     */
    public function deleteFlow(string $accessToken, string $flowId)
    {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->delete("https://graph.facebook.com/{$version}/{$flowId}");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar el Flow de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }
}
