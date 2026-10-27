<?php

namespace App\Services\Fcm;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Envío de notificaciones push por Firebase Cloud Messaging (HTTP v1) sin paquetes extra.
 * Credenciales: FIREBASE_CREDENTIALS = ruta al JSON de la cuenta de servicio
 * (absoluta o relativa a la raíz del proyecto), p. ej. storage/app/firebase/service-account.json
 */
class FcmService
{
    public const OK = 'ok';
    public const TOKEN_INVALIDO = 'invalido';
    public const ERROR = 'error';

    /** Último error (para diagnóstico con el comando fcm:probar) */
    public static ?string $ultimoError = null;
    public const CANAL = 'mensajes';

    private static function credenciales(): ?array
    {
        $ruta = (string) env('FIREBASE_CREDENTIALS', 'storage/app/firebase/service-account.json');
        if ($ruta === '') {
            return null;
        }
        if (! preg_match('#^([a-zA-Z]:[\\\\/]|/)#', $ruta)) {
            $ruta = base_path($ruta);
        }
        if (! is_file($ruta)) {
            Log::warning('FCM: no se encuentra el archivo de credenciales', ['ruta' => $ruta]);
            self::$ultimoError = "No existe el archivo de credenciales: {$ruta}";

            return null;
        }
        $json = json_decode((string) file_get_contents($ruta), true);

        return (is_array($json) && ! empty($json['private_key']) && ! empty($json['client_email'])) ? $json : null;
    }

    private static function b64(string $s): string
    {
        return rtrim(strtr(base64_encode($s), '+/', '-_'), '=');
    }

    private static function accessToken(array $cred): ?string
    {
        return Cache::remember('fcm:access_token', 3300, function () use ($cred) {
            $ahora = time();
            $uri = $cred['token_uri'] ?? 'https://oauth2.googleapis.com/token';
            $cabecera = self::b64(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $claims = self::b64(json_encode([
                'iss' => $cred['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => $uri,
                'iat' => $ahora,
                'exp' => $ahora + 3600,
            ]));
            $firma = '';
            if (! openssl_sign("{$cabecera}.{$claims}", $firma, $cred['private_key'], OPENSSL_ALGO_SHA256)) {
                return null;
            }
            $res = Http::asForm()->timeout(15)->post($uri, [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => "{$cabecera}.{$claims}." . self::b64($firma),
            ]);

            return $res->successful() ? $res->json('access_token') : null;
        });
    }

    /** Envía una notificación a un token FCM. Devuelve OK, TOKEN_INVALIDO o ERROR. */
    public static function enviar(string $fcmToken, string $titulo, string $cuerpo, array $data = [], ?string $tag = null): string
    {
        self::$ultimoError = null;
        $cred = self::credenciales();
        if (! $cred) {
            self::$ultimoError ??= 'Credenciales de Firebase inválidas (faltan private_key o client_email)';

            return self::ERROR;
        }
        $token = self::accessToken($cred);
        if (! $token) {
            Cache::forget('fcm:access_token');
            Log::warning('FCM: no se pudo obtener el access token');
            self::$ultimoError = 'No se pudo obtener el access token de Google (revisa la fecha/hora del servidor y la cuenta de servicio)';

            return self::ERROR;
        }

        // Android: mensaje SOLO de datos (la app arma la notificación con "Responder" y "Marcar como leído").
        // iPhone: alerta APNs con la categoría MENSAJE (acciones registradas en la app).
        $data = array_map('strval', $data + ['titulo' => mb_substr($titulo, 0, 80), 'cuerpo' => mb_substr($cuerpo, 0, 200)]);
        $aps = [
            'alert' => ['title' => mb_substr($titulo, 0, 80), 'body' => mb_substr($cuerpo, 0, 200)],
            'sound' => 'default',
            'category' => 'MENSAJE',
        ];
        if ($tag) {
            $aps['thread-id'] = $tag;
        }

        $res = Http::withToken($token)->timeout(15)
            ->post("https://fcm.googleapis.com/v1/projects/{$cred['project_id']}/messages:send", [
                'message' => [
                    'token' => $fcmToken,
                    'data' => $data,
                    'android' => ['priority' => 'HIGH'],
                    'apns' => ['headers' => ['apns-priority' => '10'], 'payload' => ['aps' => $aps]],
                ],
            ]);

        if ($res->successful()) {
            return self::OK;
        }

        self::$ultimoError = 'HTTP ' . $res->status() . ': ' . mb_substr($res->body(), 0, 300);
        $estado = (string) $res->json('error.status');
        if ($res->status() === 404 || $estado === 'NOT_FOUND' || $estado === 'UNREGISTERED' || $estado === 'INVALID_ARGUMENT') {
            return self::TOKEN_INVALIDO;
        }
        if ($res->status() === 401) {
            Cache::forget('fcm:access_token');
        }
        Log::warning('FCM: error al enviar', ['status' => $res->status(), 'body' => mb_substr($res->body(), 0, 300)]);

        return self::ERROR;
    }
}
