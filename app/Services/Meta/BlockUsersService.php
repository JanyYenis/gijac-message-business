<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class BlockUsersService
{
    /**
     * Get blocked users
     *
     * Consulta los usuarios bloqueados de un número de WhatsApp Business
     * mediante la API de WhatsApp Cloud API.
     *
     * Recupera la lista de usuarios bloqueados asociados al número de
     * WhatsApp Business identificado por su Phone Number ID.
     *
     * Método HTTP: GET
     *
     * Endpoint:
     * /{phoneNumberId}/block_users
     *
     * Parámetros:
     * @param string $accessToken Token de acceso válido para la API de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business.
     *
     * Documentación original:
     *
     * Guide: Block Users
     * https://developers.facebook.com/docs/whatsapp/cloud-api/block-users
     *
     * Endpoint reference: GET WhatsApp Business Phone Number > block_users
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account-to-number-current-status/block_users/#Reading
     *
     * Consideraciones:
     * - El token debe contar con los permisos necesarios para consultar
     *   los usuarios bloqueados.
     * - El Phone Number ID debe corresponder a un número de WhatsApp
     *   Business al que tenga acceso el token.
     * - La respuesta puede incluir información de paginación si existen
     *   más resultados de los que devuelve una sola consulta.
     *
     * @return array|mixed Respuesta JSON de Meta con los usuarios bloqueados.
     */
    public function getBlockedUsers(
        string $accessToken,
        string $phoneNumberId
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/block_users";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get($url);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible consultar los usuarios bloqueados de WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Block user(s)
     *
     * Guide: Block Users
     * https://developers.facebook.com/docs/whatsapp/cloud-api/block-users
     *
     * Endpoint reference: POST WhatsApp Business Phone Number > block_users
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account-to-number-current-status/block_users/
     *
     * Descripción:
     * Bloquea uno o varios usuarios para el número de WhatsApp Business
     * indicado. La solicitud se realiza mediante un POST a Graph API.
     *
     * Método HTTP: POST
     *
     * Endpoint:
     * /{phoneNumberId}/block_users
     *
     * Parámetros:
     * @param string $accessToken Token de acceso válido de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business.
     * @param array $users Lista de números de teléfono que se desean bloquear.
     *
     * Cada elemento de $users puede ser:
     * - Un string con el número de teléfono del usuario.
     * - Un array asociativo con la clave "user" y el número de teléfono.
     *
     * Ejemplo de estructura enviada a Meta:
     * {
     *   "messaging_product": "whatsapp",
     *   "block_users": [
     *     {
     *       "user": "573001234567"
     *     }
     *   ]
     * }
     *
     * Consideraciones:
     * - Los números deben enviarse en el formato que acepta WhatsApp,
     *   normalmente con el código de país y sin el signo "+".
     * - El token debe contar con los permisos necesarios para administrar
     *   el número de WhatsApp Business.
     * - La respuesta de Meta determina si la operación fue aceptada.
     *
     * @return array|mixed Respuesta JSON de Meta o respuesta HTTP de error.
     */
    public function blockUsers(
        string $accessToken,
        string $phoneNumberId,
        array $users
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $blockUsers = [];

        foreach ($users as $user) {
            // Acepta tanto strings como arrays asociativos.
            $phoneNumber = is_array($user)
                ? ($user['user'] ?? null)
                : $user;

            if (!is_string($phoneNumber) || trim($phoneNumber) === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cada usuario debe contener un número de teléfono válido.',
                ], 422);
            }

            $blockUsers[] = [
                'user' => trim($phoneNumber),
            ];
        }

        if (empty($blockUsers)) {
            return response()->json([
                'success' => false,
                'message' => 'Debes proporcionar al menos un usuario para bloquear.',
            ], 422);
        }

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/block_users";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'block_users' => $blockUsers,
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible bloquear los usuarios de WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Unblock user(s)
     *
     * Guide: Block Users
     * https://developers.facebook.com/docs/whatsapp/cloud-api/block-users
     *
     * Endpoint reference: DELETE WhatsApp Business Phone Number > block_users
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account-to-number-current-status/block_users/#Deleting
     *
     * Descripción:
     * Desbloquea uno o varios usuarios previamente bloqueados para el número
     * de WhatsApp Business indicado.
     *
     * Método HTTP: DELETE
     *
     * Endpoint:
     * /{phoneNumberId}/block_users
     *
     * Parámetros:
     * @param string $accessToken Token de acceso válido de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business.
     * @param array $users Lista de números de teléfono que se desean desbloquear.
     *
     * Cada elemento de $users puede ser:
     * - Un string con el número de teléfono.
     * - Un array asociativo con la clave "user" y el número de teléfono.
     *
     * Ejemplo del cuerpo enviado:
     * {
     *   "messaging_product": "whatsapp",
     *   "block_users": [
     *     {
     *       "user": "573001234567"
     *     }
     *   ]
     * }
     *
     * Consideraciones:
     * - La solicitud utiliza DELETE y envía un cuerpo JSON.
     * - Los números deben estar en el formato aceptado por WhatsApp,
     *   normalmente con el código de país y sin el signo "+".
     * - El token debe contar con los permisos necesarios para administrar
     *   el número de WhatsApp Business.
     * - La respuesta de Meta indica si la operación fue aceptada.
     *
     * @return array|mixed Respuesta JSON de Meta o respuesta HTTP de error.
     */
    public function unblockUsers(
        string $accessToken,
        string $phoneNumberId,
        array $users
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $blockUsers = [];

        foreach ($users as $user) {
            // Acepta strings o arrays asociativos con la clave "user".
            $phoneNumber = is_array($user)
                ? ($user['user'] ?? null)
                : $user;

            if (!is_string($phoneNumber) || trim($phoneNumber) === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cada usuario debe contener un número de teléfono válido.',
                ], 422);
            }

            $blockUsers[] = [
                'user' => trim($phoneNumber),
            ];
        }

        if (empty($blockUsers)) {
            return response()->json([
                'success' => false,
                'message' => 'Debes proporcionar al menos un usuario para desbloquear.',
            ], 422);
        }

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/block_users";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->delete($url, [
                'messaging_product' => 'whatsapp',
                'block_users' => $blockUsers,
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible desbloquear los usuarios de WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }
}
