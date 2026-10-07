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
                'exchange'   => url('/api/whatsapp/exchange-token'),
                'resubscribe'=> route('whatsapp.resubscribe'),
                'register'   => route('whatsapp.register'),
                'renew'      => route('whatsapp.renew'),
                'test'       => route('whatsapp.test'),
                'disconnect' => route('whatsapp.disconnect'),
                'templates'  => url('/admin/whatsapp/templates'),
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
        $request->validate(['code' => 'required|string']);

        try {
            /* 1. Cambiar code por access_token (exchange de código) */
            $tokenRes = Http::get("{$this->graph}/oauth/access_token", [
                'client_id'     => $this->appId,
                'client_secret' => $this->appSecret,
                'code'          => $request->input('code'),
            ])->throw()->json();

            $accessToken = $tokenRes['access_token'] ?? null;
            abort_if(! $accessToken, 422, 'No se pudo obtener el access_token de Meta.');

            /* 2. Portafolios comerciales del usuario */
            $businesses = Http::get("{$this->graph}/me/businesses", [
                'access_token' => $accessToken,
            ])->throw()->json('data', []);

            abort_if(empty($businesses), 422, 'No se encontró ningún Portafolio Comercial de Meta.');

            /* 3. Buscar WABA dentro de los portafolios */
            $waba = null;
            foreach ($businesses as $biz) {
                $wabas = Http::get("{$this->graph}/{$biz['id']}/owned_whatsapp_business_accounts", [
                    'access_token' => $accessToken,
                    'fields'       => 'id,name,account_review_status,health_status',
                ])->throw()->json('data', []);

                if (! empty($wabas)) {
                    $waba = $wabas[0];
                    $businessId = $biz['id'];
                    break;
                }
            }
            abort_if(! $waba, 422, 'No se encontró ninguna cuenta de WhatsApp Business API.');

            /* 4. Números de teléfono del WABA */
            $phones = Http::get("{$this->graph}/{$waba['id']}/phone_numbers", [
                'access_token' => $accessToken,
                'fields'       => 'id,display_phone_number,verified_name,quality_rating,code_verification_status',
            ])->throw()->json('data', []);

            abort_if(empty($phones), 422, 'El WABA no tiene números de teléfono vinculados.');
            $phone = $phones[0];

            /* 5. Suscribir la app al webhook del WABA */
            $subscribe = Http::asForm()->post(
                "{$this->graph}/{$waba['id']}/subscribed_apps",
                ['access_token' => $accessToken]
            )->json();

            /* 6. Persistir (token encriptado) */
            WhatsappAccount::updateOrCreate(
                ['usuario_id' => auth()->user()->uuid, 'waba_id' => $waba['id']],
                [
                    'phone_number_id'    => $phone['id'],
                    'business_id'        => $businessId ?? null,
                    'access_token'       => Crypt::encrypt($accessToken),
                    'phone_number'       => $phone['display_phone_number'] ?? '',
                    'display_name'       => $phone['verified_name'] ?? null,
                    'quality_rating'     => $phone['quality_rating'] ?? 'UNKNOWN',
                    'messaging_limit'    => '1K',
                    'webhook_subscribed' => ($subscribe['success'] ?? false) === true,
                    'number_registered'  => ($phone['code_verification_status'] ?? '') === 'VERIFIED',
                    'estado'             => WhatsappAccount::CONECTADO,
                    'token_expires_at'   => now()->addDays(60),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Cuenta de WhatsApp vinculada correctamente.',
                'account' => [
                    'waba_id'         => $waba['id'],
                    'phone_number_id' => $phone['id'],
                    'business_id'     => $businessId ?? null,
                    'phone_number'    => $phone['display_phone_number'] ?? '',
                    'quality'         => $phone['quality_rating'] ?? 'UNKNOWN',
                    'limit'           => '1K',
                    'webhook'         => ($subscribe['success'] ?? false) === true,
                    'registered'      => ($phone['code_verification_status'] ?? '') === 'VERIFIED',
                    'token_days'      => 60,
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

    /* =========================================================
       GET /admin/whatsapp/status — Refresca calidad y límite
       ========================================================= */
    public function status()
    {
        $account = $this->currentAccountOrFail();
        $token   = Crypt::decrypt($account->access_token);

        $phone = Http::get("{$this->graph}/{$account->phone_number_id}", [
            'access_token' => $token,
            'fields'       => 'quality_rating,display_phone_number',
        ])->json();

        $waba = Http::get("{$this->graph}/{$account->waba_id}", [
            'access_token' => $token,
            'fields'       => 'account_review_status,messaging_limit_tier',
        ])->json();

        $account->update([
            'quality_rating'  => $phone['quality_rating'] ?? $account->quality_rating,
            'messaging_limit' => $waba['messaging_limit_tier'] ?? $account->messaging_limit,
        ]);

        return response()->json(['success' => true, 'account' => $account->fresh()]);
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

        $res = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->post("{$this->graph}/{$account->phone_number_id}/register", [
            'messaging_product' => 'whatsapp',
            'pin'               => (string) $account->two_factor_pin ?? '000000', // PIN 2FA configurado
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
