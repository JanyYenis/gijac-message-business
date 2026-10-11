<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class MigrationService
{
    /**
     * Nombre: Migrate Account
     *
     * Migra una cuenta de WhatsApp desde On-Premises hacia WhatsApp Cloud API.
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/register
     *
     * Documentación oficial:
     * - Set Two-Step Verification:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/two-step-verification
     *
     * - Backup and Restore:
     *   https://developers.facebook.com/docs/whatsapp/api/settings/backup-and-restore
     *
     * Comentario original:
     *
     * To migrate your account, make a POST call to the
     * /{{Phone-Number-ID}}/register endpoint.
     *
     * La solicitud puede tardar hasta 15 segundos. Durante este período,
     * la implementación On-Premises se desconecta automáticamente de
     * los servidores de WhatsApp y se apaga. La cuenta empresarial
     * comienza a funcionar en el servicio alojado en la nube.
     *
     * Una vez que la solicitud finaliza correctamente, se pueden enviar
     * mensajes inmediatamente.
     *
     * Parámetros:
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId Identificador del número de teléfono.
     * @param string $pin PIN de seis dígitos configurado para la
     *                    verificación en dos pasos.
     * @param string $backupData Datos de la copia de seguridad On-Premises.
     * @param string $backupPassword Contraseña utilizada en la copia
     *                               de seguridad On-Premises.
     *
     * Parámetros del cuerpo de la solicitud:
     * - messaging_product: obligatorio, debe ser "whatsapp".
     * - pin: obligatorio, PIN de seis dígitos.
     * - backup.data: obligatorio, datos de la copia de seguridad.
     * - backup.password: obligatorio, contraseña de la copia de seguridad.
     *
     * @return mixed Respuesta de Meta o error HTTP.
     */
    public function migrateAccount(
        string $accessToken,
        string $phoneNumberId,
        string $pin,
        string $backupData,
        string $backupPassword
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        if (!preg_match('/^\d{6}$/', $pin)) {
            return response()->json([
                'success' => false,
                'message' => 'El PIN debe contener exactamente 6 dígitos.',
            ], 422);
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->timeout(30)
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/register",
                [
                    'messaging_product' => 'whatsapp',
                    'pin' => $pin,
                    'backup' => [
                        'data' => $backupData,
                        'password' => $backupPassword,
                    ],
                ]
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible migrar la cuenta de WhatsApp a Cloud API.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }
}
