<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class RegistrationService
{
    /**
     * To register your phone, make a POST call to /{{Phone-Number-ID}}/register.
     * You need to include the following parameters.
     *
     * Request Parameters:
     *
     * - messaging_product: Required. The messaging service used.
     *   This value always needs to be set to "whatsapp".
     *
     * - pin: Required. A 6-digit pin you previously set up.
     *   For more information, see Set Two-Step Verification.
     */
    public function registerPhone(string $version, string $accessToken, string $phoneNumberId, string $pin)
    {
        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/register", [
                'messaging_product' => 'whatsapp',
                'pin' => $pin,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error registering phone number.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Para dar de baja un teléfono registrado, realiza una petición POST
     * a {{Phone-Number-ID}}/deregister.
     *
     * Este método elimina el registro de un teléfono previamente registrado.
     * El teléfono puede volver a registrarse posteriormente repitiendo
     * el proceso de registro.
     *
     * Respuesta exitosa:
     *
     * {
     *     "success": true
     * }
     */
    public function deregisterPhone(string $version, string $accessToken, string $phoneNumberId)
    {
        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/deregister");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al dar de baja el número de teléfono.',
            'error' => $response->json(),
        ], $response->status());
    }
}
