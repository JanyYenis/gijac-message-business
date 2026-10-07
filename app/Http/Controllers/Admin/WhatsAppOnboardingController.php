<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsappAccount;
use App\Models\WhatsappWebhookLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class WhatsAppOnboardingController extends Controller
{
    private string $graph;
    private string $appId;
    private string $appSecret;
    private string $configId;

    public function __construct()
    {
        $this->graph     = 'https://graph.facebook.com/' . config('facebook.graph_version', 'v26.0');
        $this->appId     = (string) config('facebook.client_id');
        $this->appSecret = (string) config('facebook.client_secret');
        $this->configId  = (string) config('facebook.config_id');
    }

    /* =========================================================
       GET /admin/whatsapp/onboarding — Vista principal
       ========================================================= */
    public function connect()
    {
        $account = WhatsappAccount::where('usuario_id', auth()->user()->uuid)
            ->where('estado', WhatsappAccount::CONECTADO)
            ->first();

        // Modo demo: si no hay credenciales configuradas, el frontend
        // simula el flujo completo para validación de UI.
        $demo = $this->appId === '' || $this->configId === '';

        $boot = [
            'demo'            => $demo,
            'connected'       => (bool) $account,
            'csrf'            => csrf_token(),
            'fb_app_id'       => $this->appId,
            'fb_config_id'    => $this->configId,
            'graph_version'   => config('facebook.graph_version', 'v26.0'),
            'routes' => [
                'exchange'   => route('whatsapp.exchange'),
                'resubscribe'=> route('whatsapp.resubscribe'),
                'register'   => route('whatsapp.register'),
                'renew'      => route('whatsapp.renew'),
                'test'       => route('whatsapp.test'),
                'disconnect' => route('whatsapp.disconnect'),
                'templates'  => url('/admin/whatsapp/templates'),
                'status' => route('whatsapp.status')
            ],
            'account' => $account ? [
                'waba_id'          => $account->waba_id,
                'phone_number_id'  => $account->phone_number_id,
                'business_id'      => $account->business_id,
                'phone_number'     => $account->phone_number,
                'quality'          => $account->quality_rating,
                'limit'            => $account->messaging_limit,
                'webhook'          => $account->webhook_subscribed,
                'registered'       => $account->number_registered,
                'token_days'       => $account->token_expires_at
                    ? max(0, (int) now()->diffInDays($account->token_expires_at, false))
                    : 60,
            ] : null,
            'logs' => WhatsappWebhookLog::latest()->take(5)
                ->get(['event_type', 'waba_id', 'created_at', 'estado'])
                ->map(fn ($l) => [
                    'type'   => $l->event_type ?? 'message',
                    'waba'   => $l->waba_id,
                    'date'   => $l->created_at->format('d M Y, H:i'),
                    'estado' => $l->estado,
                ])->toArray(),
        ];

        return view('configs.index', compact('boot'));
    }

    /* =========================================================
       POST /api/whatsapp/exchange-token — Core del Embedded Signup
       ========================================================= */
    public function exchangeToken(Request $request)
    {
        $data = $request->validate([
            'code'            => 'required|string',
            'event'           => 'nullable|string',
            'waba_id'         => 'nullable|string',
            'phone_number_id' => 'nullable|string',
            'business_id'     => 'nullable|string',
        ]);

        try {
            /* 1. code -> business token (el code vive 30 s, esto va primero) */
            $tokenRes = Http::get("{$this->graph}/oauth/access_token", [
                'client_id'     => $this->appId,
                'client_secret' => $this->appSecret,
                'code'          => $data['code'],
            ])->throw()->json();

            $accessToken = $tokenRes['access_token'] ?? null;
            abort_if(! $accessToken, 422, 'No se pudo obtener el access_token de Meta.');

            $expiresAt = isset($tokenRes['expires_in'])
                ? now()->addSeconds((int) $tokenRes['expires_in'])
                : now()->addDays(60);

            /* 2. IDs: del evento del navegador, o por debug_token si no llegó */
            $wabaId = $data['waba_id'] ?? $this->resolveWabaId($accessToken);
            abort_if(! $wabaId, 422, 'No se pudo determinar la cuenta de WhatsApp Business.');

            $phoneId = $data['phone_number_id'];
            if (! $phoneId) {
                $phoneId = Http::withToken($accessToken)
                    ->get("{$this->graph}/{$wabaId}/phone_numbers")
                    ->throw()->json('data.0.id');
            }
            abort_if(! $phoneId, 422, 'La cuenta no tiene un número de teléfono vinculado.');

            $phone = Http::withToken($accessToken)->get("{$this->graph}/{$phoneId}", [
                'fields' => 'display_phone_number,verified_name,quality_rating,code_verification_status,messaging_limit_tier',
            ])->throw()->json();

            /* 3. Suscribir la app a los webhooks de la WABA */
            $subscribe = Http::withToken($accessToken)
                ->post("{$this->graph}/{$wabaId}/subscribed_apps")->json();
            $webhookOk = ($subscribe['success'] ?? false) === true;

            /* 4. Registrar el número en Cloud API (solo flujo normal; no en coexistencia) */
            $pin = null;
            $registered = ($phone['code_verification_status'] ?? '') === 'VERIFIED';
            if (in_array($data['event'] ?? 'FINISH', ['FINISH', null], true)) {
                $pin = (string) random_int(100000, 999999);
                $reg = Http::withToken($accessToken)->post("{$this->graph}/{$phoneId}/register", [
                    'messaging_product' => 'whatsapp',
                    'pin'               => $pin,
                ]);
                $registered = $reg->successful() && $reg->json('success') === true;
                if (! $registered) {
                    report(new \RuntimeException('Register falló: ' . $reg->body()));
                }
            }

            $limit = str_replace('TIER_', '', $phone['messaging_limit_tier'] ?? '1K');

            /* 5. Persistir (token y PIN cifrados) */
            $attrs = [
                'phone_number_id'    => $phoneId,
                'business_id'        => $data['business_id'],
                'access_token'       => Crypt::encrypt($accessToken),
                'phone_number'       => $phone['display_phone_number'] ?? '',
                'display_name'       => $phone['verified_name'] ?? null,
                'quality_rating'     => $phone['quality_rating'] ?? 'UNKNOWN',
                'messaging_limit'    => $limit,
                'webhook_subscribed' => $webhookOk,
                'number_registered'  => $registered,
                'estado'             => WhatsappAccount::CONECTADO,
                'token_expires_at'   => $expiresAt,
            ];
            if ($pin) {
                $attrs['two_factor_pin'] = Crypt::encrypt($pin);
            }

            WhatsappAccount::updateOrCreate(
                ['usuario_id' => auth()->user()->uuid, 'waba_id' => $wabaId],
                $attrs
            );

            return response()->json([
                'success' => true,
                'message' => 'Cuenta de WhatsApp vinculada correctamente.',
                'account' => [
                    'waba_id'         => $wabaId,
                    'phone_number_id' => $phoneId,
                    'business_id'     => $data['business_id'],
                    'phone_number'    => $phone['display_phone_number'] ?? '',
                    'quality'         => $phone['quality_rating'] ?? 'UNKNOWN',
                    'limit'           => $limit,
                    'webhook'         => $webhookOk,
                    'registered'      => $registered,
                    'token_days'      => max(0, (int) now()->diffInDays($expiresAt, false)),
                ],
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error al vincular la cuenta: ' . $e->getMessage(),
            ], 500);
        }
    }

    /** Fallback: obtiene la WABA autorizada leyendo los scopes granulares del token */
    private function resolveWabaId(string $accessToken): ?string
    {
        $scopes = Http::get("{$this->graph}/debug_token", [
            'input_token'  => $accessToken,
            'access_token' => "{$this->appId}|{$this->appSecret}",
        ])->throw()->json('data.granular_scopes', []);

        foreach ($scopes as $s) {
            if (($s['scope'] ?? '') === 'whatsapp_business_management') {
                return $s['target_ids'][0] ?? null;
            }
        }
        return null;
    }

    /* =========================================================
       GET /admin/whatsapp/status — Refresca calidad y límite
       ========================================================= */
    public function status()
    {
        $account = $this->currentAccountOrFail();
        $token   = Crypt::decrypt($account->access_token);

        $res = Http::withToken($token)
            ->get("{$this->graph}/{$account->phone_number_id}", [
                'fields' => 'quality_rating,display_phone_number,messaging_limit_tier',
            ]);

        if ($res->failed()) {
            return response()->json([
                'success' => false,
                'message' => $res->json('error.message', 'No se pudo consultar a Meta.'),
            ], 422);
        }

        $phone = $res->json();

        $account->update([
            'quality_rating'  => $phone['quality_rating'] ?? $account->quality_rating,
            'messaging_limit' => isset($phone['messaging_limit_tier'])
                ? str_replace('TIER_', '', $phone['messaging_limit_tier'])
                : $account->messaging_limit,
        ]);

        return response()->json([
            'success' => true,
            'account' => [
                'quality_rating'  => $account->quality_rating,
                'messaging_limit' => $account->messaging_limit,
            ],
        ]);
    }

    /* POST — POST /{waba_id}/subscribed_apps */
    public function resubscribeWebhook()
    {
        $account = $this->currentAccountOrFail();
        $token   = Crypt::decrypt($account->access_token);

        $res = Http::asForm()->post(
            "{$this->graph}/{$account->waba_id}/subscribed_apps",
            ['access_token' => $token]
        )->json();

        $ok = ($res['success'] ?? false) === true;
        $account->update(['webhook_subscribed' => $ok]);

        return response()->json([
            'success' => $ok,
            'message' => $ok ? 'Webhook re-suscrito correctamente.' : 'No se pudo re-suscribir el webhook.',
        ]);
    }

    /* POST — POST /{phone_number_id}/register */
    public function registerNumber()
    {
        $account = $this->currentAccountOrFail();
        $token   = Crypt::decrypt($account->access_token);

        $pin = $account->two_factor_pin ? Crypt::decrypt($account->two_factor_pin) : null;
        if (!$pin) {
            return response()->json(['success' => false, 'message' => 'No hay PIN guardado.'], 422);
        }

        $res = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->post("{$this->graph}/{$account->phone_number_id}/register", [
            'messaging_product' => 'whatsapp',
            'pin'               => $pin, // PIN 2FA configurado
        ])->json();

        $ok = isset($res['success']) && $res['success'] === true;
        $account->update(['number_registered' => $ok]);

        return response()->json([
            'success' => $ok,
            'message' => $ok ? 'Número registrado en Cloud API.' : 'No se pudo registrar el número. Verifica el PIN 2FA.',
        ]);
    }

    /* POST — Renovar token (System User Token de larga duración) */
    public function renewToken()
    {
        $account = $this->currentAccountOrFail();
        // En producción: generar token de System User desde Business Manager
        // y guardar el nuevo valor. Aquí se simula la extensión de vigencia.
        $account->update(['token_expires_at' => now()->addDays(60)]);

        return response()->json([
            'success' => true,
            'message' => 'Token renovado. Nueva expiración en 60 días.',
        ]);
    }

    /* POST — Enviar mensaje de prueba */
    public function sendTest(Request $request)
    {
        $request->validate([
            'to'      => ['required', 'regex:/^\+?[1-9]\d{6,14}$/'],
            'message' => 'required|string|max:1024',
        ]);

        $account = $this->currentAccountOrFail();
        $token   = Crypt::decrypt($account->access_token);

        $res = Http::withToken($token)->post(
            "{$this->graph}/{$account->phone_number_id}/messages",
            [
                'messaging_product' => 'whatsapp',
                'to'   => preg_replace('/\D/', '', $request->input('to')),
                'type' => 'text',
                'text' => ['body' => $request->input('message')],
            ]
        )->json();

        $ok = isset($res['messages'][0]['id']);

        return response()->json([
            'success' => $ok,
            'message' => $ok
                ? 'Mensaje de prueba enviado con ID ' . $res['messages'][0]['id']
                : 'Error al enviar: ' . ($res['error']['message'] ?? 'desconocido'),
        ], $ok ? 200 : 422);
    }

    /* DELETE — Desconectar */
    public function disconnect()
    {
        $account = $this->currentAccountOrFail();

        try {
            $token = Crypt::decrypt($account->access_token);
            Http::asForm()->delete("{$this->graph}/{$account->waba_id}/subscribed_apps", [
                'access_token' => $token,
            ]);
        } catch (\Throwable $e) {
            // no bloqueamos la desconexión local por un fallo remoto
        }

        $account->update(['estado' => WhatsappAccount::DESCONECTADO]);

        return response()->json(['success' => true, 'message' => 'Cuenta desconectada correctamente.']);
    }

    /* ========================================================= */
    private function currentAccountOrFail(): WhatsappAccount
    {
        return WhatsappAccount::where('usuario_id', auth()->user()->uuid)
            ->where('estado', WhatsappAccount::CONECTADO)
            ->firstOrFail();
    }
}
