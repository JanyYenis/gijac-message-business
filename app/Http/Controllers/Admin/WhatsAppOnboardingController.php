<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsappAccount;
use App\Models\WhatsappWebhookLog;
use App\Services\Meta\PhoneNumbersService;
use App\Services\Meta\RegistrationService;
use App\Services\Meta\WehbookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class WhatsAppOnboardingController extends Controller
{
    private const PHONE_FIELDS = 'display_phone_number,verified_name,quality_rating,code_verification_status,messaging_limit_tier';

    private string $graph;
    private string $appId;
    private string $appSecret;
    private string $configId;

    public function __construct(
        private PhoneNumbersService $phones,
        private RegistrationService $registration,
        private WehbookService $webhooks,
    ) {
        $this->graph     = 'https://graph.facebook.com/' . config('facebook.graph_version', 'v26.0');
        $this->appId     = (string) config('facebook.client_id');
        $this->appSecret = (string) config('facebook.client_secret');
        $this->configId  = (string) config('facebook.config_id');
    }

    /* =========================================================
       GET /admin/whatsapp/onboarding
       ========================================================= */
    public function connect()
    {
        $account = WhatsappAccount::where('usuario_id', auth()->user()->uuid)
            ->where('cod_empresa', auth()->user()->empresa?->id)
            ->where('estado', WhatsappAccount::CONECTADO)
            ->first();

        $demo = $this->appId === '' || $this->configId === '';

        $boot = [
            'demo'          => $demo,
            'connected'     => (bool) $account,
            'csrf'          => csrf_token(),
            'fb_app_id'     => $this->appId,
            'fb_config_id'  => $this->configId,
            'graph_version' => config('facebook.graph_version', 'v26.0'),
            'routes' => [
                'exchange'    => route('whatsapp.exchange'),
                'resubscribe' => route('whatsapp.resubscribe'),
                'register'    => route('whatsapp.register'),
                'renew'       => route('whatsapp.renew'),
                'test'        => route('whatsapp.test'),
                'disconnect'  => route('whatsapp.disconnect'),
                'templates'   => url('/admin/whatsapp/templates'),
                'status'      => route('whatsapp.status'),
            ],
            'account' => $account ? [
                'waba_id'         => $account->waba_id,
                'phone_number_id' => $account->phone_number_id,
                'business_id'     => $account->business_id,
                'phone_number'    => $account->phone_number,
                'quality'         => WhatsappAccount::ratingToMeta($account->quality_rating),
                'limit'           => $account->messaging_limit,
                'webhook'         => $account->webhook_subscribed,
                'registered'      => $account->number_registered,
                'token_days'      => $account->token_expires_at
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
       POST /admin/whatsapp/exchange-token
       ========================================================= */
    public function exchangeToken(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string',
            'event' => 'nullable|string',
            'waba_id' => 'nullable|string',
            'phone_number_id' => 'nullable|string',
            'business_id' => 'nullable|string',
            'pin' => 'nullable|string|digits:6', // PIN solo si el cliente lo pone en el popup para número nuevo
        ]);

        try {
            /* 1. code -> business token (no hay servicio para esto; el code vive 30 s) */
            $tokenRes = Http::get("{$this->graph}/oauth/access_token", [
                'client_id' => $this->appId,
                'client_secret' => $this->appSecret,
                'code' => $data['code'],
            ])->throw()->json();

            $token = $tokenRes['access_token']?? null;
            abort_if(! $token, 422, 'No se pudo obtener el access_token de 【entity-Meta¦canonical_name=Meta】.');

            $expiresAt = isset($tokenRes['expires_in'])
               ? now()->addSeconds((int) $tokenRes['expires_in'])
                : now()->addDays(60);

            /* 2. IDs: del evento del navegador, o por fallback */
            $wabaId = $data['waba_id']?? $this->resolveWabaId($token);
            abort_if(! $wabaId, 422, 'No se pudo determinar la cuenta de WhatsApp Business.');

            $phoneId = $data['phone_number_id'];
            if (! $phoneId) {
                $list = $this->phones->getPhoneNumbers($token, $wabaId);
                abort_if($this->failed($list), 422, $this->errorMessage($list, 'No se pudieron listar los números.'));
                $phoneId = $list['data'][0]['id']?? null;
            }
            abort_if(! $phoneId, 422, 'La cuenta no tiene un número de teléfono vinculado.');

            $phone = $this->phones->getPhoneNumber($token, $phoneId, self::PHONE_FIELDS);
            abort_if($this->failed($phone), 422, $this->errorMessage($phone, 'No se pudo consultar el número.'));

            /* 3. Suscribir webhooks */
            $sub = $this->webhooks->subscribeWaba($token, $wabaId);
            $webhookOk =! $this->failed($sub) && ($sub['success']?? false) === true;

            /* 4. Guardar PRIMERO (con PIN), para no perderlo si algo falla después */
            // CORREGIDO: Ya no se genera PIN aleatorio. Si el número ya viene VERIFIED de Embedded Signup, no hay que registrar nada.
            $isAlreadyVerified = ($phone['code_verification_status']?? '') === 'VERIFIED';
            $pinFromFrontend = $data['pin']?? null; // Solo viene si es número nuevo creado en el flujo
            $registered = $isAlreadyVerified;
            $limit = str_replace('TIER_', '', $phone['messaging_limit_tier']?? '1K');
            $rating = $phone['quality_rating']?? 'UNKNOWN';

            $attrs = [
                'phone_number_id' => $phoneId,
                'business_id' => $data['business_id'],
                'access_token' => Crypt::encrypt($token),
                'phone_number' => $phone['display_phone_number']?? '',
                'display_name' => $phone['verified_name']?? null,
                'quality_rating' => WhatsappAccount::ratingFromMeta($rating),
                'messaging_limit' => $limit,
                'webhook_subscribed' => $webhookOk,
                'number_registered' => $registered,
                'estado' => WhatsappAccount::CONECTADO,
                'token_expires_at' => $expiresAt,
            ];
            // Solo guardamos PIN si el cliente realmente creó un número nuevo y nos envió el PIN que él mismo puso en el popup
            if ($pinFromFrontend) {
                $attrs['two_factor_pin'] = Crypt::encrypt($pinFromFrontend);
            }

            $account = WhatsappAccount::updateOrCreate([
                'usuario_id' => auth()->user()->uuid,
                'waba_id' => $wabaId,
                'cod_empresa' => auth()->user()->empresa?->id,
            ], $attrs);

            /* 5. Registrar el número en Cloud API (no aplica en coexistencia) */
            // CORREGIDO: Solo se registra si NO está verificado. Si ya está VERIFIED (caso 99% de Embedded Signup), no se hace nada para no cambiarle el PIN al cliente.
            if (! $isAlreadyVerified) {
                abort_if(! $pinFromFrontend, 422, 'El número no está verificado. Se requiere el PIN de 2FA que el cliente configuró en el popup de 【entity-Meta¦canonical_name=Meta】.');

                $reg = $this->registration->registerPhone($token, $phoneId, $pinFromFrontend);
                $registered =! $this->failed($reg) && ($reg['success']?? false) === true;

                if (! $registered) {
                    report(new \RuntimeException('Register falló: '. json_encode($this->failed($reg)? $reg->getData(true) : $reg)));
                }
                $account->update(['number_registered' => $registered]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cuenta de WhatsApp vinculada correctamente.',
                'account' => [
                    'waba_id' => $wabaId,
                    'phone_number_id' => $phoneId,
                    'business_id' => $data['business_id'],
                    'phone_number' => $phone['display_phone_number']?? '',
                    'quality' => WhatsappAccount::ratingToMeta($account->quality_rating),
                    'limit' => $limit,
                    'webhook' => $webhookOk,
                    'registered' => $registered,
                    'token_days' => max(0, (int) now()->diffInDays($expiresAt, false)),
                ],
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error al vincular la cuenta: '. $e->getMessage(),
            ], 500);
        }
    }

    /* =========================================================
       GET /admin/whatsapp/status
       ========================================================= */
    public function status()
    {
        $account = $this->currentAccountOrFail();
        $token   = Crypt::decrypt($account->access_token);

        $phone = $this->phones->getPhoneNumber($token, $account->phone_number_id, self::PHONE_FIELDS);

        if ($this->failed($phone)) {
            return response()->json([
                'success' => false,
                'message' => $this->errorMessage($phone, 'No se pudo consultar a Meta.'),
            ], 422);
        }

        $account->update([
            'quality_rating'  => isset($phone['quality_rating'])
                ? WhatsappAccount::ratingFromMeta($phone['quality_rating'])
                : $account->quality_rating,
            'messaging_limit' => isset($phone['messaging_limit_tier'])
                ? str_replace('TIER_', '', $phone['messaging_limit_tier'])
                : $account->messaging_limit,
            'number_registered' => ($phone['code_verification_status'] ?? '') === 'VERIFIED'
                ? true
                : $account->number_registered,
        ]);

        return response()->json([
            'success' => true,
            'account' => [
                'quality_rating'  => WhatsappAccount::ratingToMeta($account->quality_rating),
                'messaging_limit' => $account->messaging_limit,
            ],
        ]);
    }

    /* POST — re-suscribir webhook */
    public function resubscribeWebhook()
    {
        $account = $this->currentAccountOrFail();
        $token   = Crypt::decrypt($account->access_token);

        $res = $this->webhooks->subscribeWaba($token, $account->waba_id);
        $ok  = ! $this->failed($res) && ($res['success'] ?? false) === true;

        $account->update(['webhook_subscribed' => $ok]);

        return response()->json([
            'success' => $ok,
            'message' => $ok ? 'Webhook re-suscrito correctamente.' : 'No se pudo re-suscribir el webhook.',
        ]);
    }

    /* POST — registrar número */
    public function registerNumber()
    {
        $account = $this->currentAccountOrFail();
        $token   = Crypt::decrypt($account->access_token);

        $pin = $account->two_factor_pin ? Crypt::decrypt($account->two_factor_pin) : null;
        if (! $pin) {
            return response()->json(['success' => false, 'message' => 'No hay PIN guardado.'], 422);
        }

        $res = $this->registration->registerPhone($token, $account->phone_number_id, $pin);
        $ok  = ! $this->failed($res) && ($res['success'] ?? false) === true;

        $account->update(['number_registered' => $ok]);

        return response()->json([
            'success' => $ok,
            'message' => $ok ? 'Número registrado en Cloud API.' : 'No se pudo registrar el número. Verifica el PIN 2FA.',
        ], $ok ? 200 : 422);
    }

    /* POST — renovar token (sigue siendo un placeholder) */
    public function renewToken()
    {
        $account = $this->currentAccountOrFail();

        try {
            $oldToken = Crypt::decrypt($account->access_token);

            /* 1. Intercambiar token viejo por uno nuevo de 60 días */
            $res = Http::get("{$this->graph}/oauth/access_token", [
                'grant_type' => 'fb_exchange_token',
                'client_id' => $this->appId,
                'client_secret' => $this->appSecret,
                'fb_exchange_token' => $oldToken,
            ])->throw()->json();

            $newToken = $res['access_token'] ?? null;
            abort_if(! $newToken, 422, 'Meta no devolvió un nuevo token.');

            $expiresAt = isset($res['expires_in'])
                ? now()->addSeconds((int) $res['expires_in'])
                : now()->addDays(60);

            /* 2. Verificar que el nuevo token aún tiene permisos sobre la WABA */
            $check = Http::withToken($newToken)->get("{$this->graph}/{$account->waba_id}")->json();
            abort_if(isset($check['error']), 422, 'El nuevo token no tiene acceso a la WABA: ' . ($check['error']['message'] ?? ''));

            /* 3. Guardar nuevo token encriptado */
            $account->update([
                'access_token' => Crypt::encrypt($newToken),
                'token_expires_at' => $expiresAt,
                'webhook_subscribed' => true, // si pudimos consultar la WABA, el token sirve
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Token renovado hasta ' . $expiresAt->format('d/m/Y'),
                'account' => [
                    'token_days' => max(0, (int) now()->diffInDays($expiresAt, false)),
                ]
            ]);

        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Si falla es porque el token ya expiró totalmente y no se puede extender
            $msg = $e->response->json('error.message') ?? $e->getMessage();

            // Marcar como desconectado para que el cliente vuelva a hacer login
            if (str_contains($msg, 'expired') || $e->response->status() === 400) {
                $account->update(['estado' => WhatsappAccount::TOKEN_EXPIRADO]);
                return response()->json([
                    'success' => false,
                    'message' => 'El token ya expiró. El cliente debe volver a conectar con Facebook.',
                    'needs_reauth' => true
                ], 422);
            }

            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error al renovar: ' . $msg
            ], 500);

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Error al renovar: ' . $e->getMessage()
            ], 500);
        }
    }

    /* POST — mensaje de prueba (no tienes MessagesService, queda con Http) */
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

    /* DELETE — desconectar */
    public function disconnect()
    {
        $account = $this->currentAccountOrFail();

        try {
            $this->webhooks->unsubscribeWaba(Crypt::decrypt($account->access_token), $account->waba_id);
        } catch (\Throwable $e) {
            // no bloqueamos la desconexión local por un fallo remoto
        }

        $account->update(['estado' => WhatsappAccount::DESCONECTADO]);

        return response()->json(['success' => true, 'message' => 'Cuenta desconectada correctamente.']);
    }

    /* ========================================================= */

    /** Tus servicios devuelven array en éxito y JsonResponse en error */
    private function failed($res): bool
    {
        return $res instanceof JsonResponse;
    }

    private function errorMessage($res, string $default): string
    {
        if (! $this->failed($res)) {
            return $default;
        }
        $body = $res->getData(true);

        return $body['error']['error']['message'] ?? $body['message'] ?? $default;
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

    private function currentAccountOrFail(): WhatsappAccount
    {
        return WhatsappAccount::where('usuario_id', auth()->user()->uuid)
            ->where('cod_empresa', auth()->user()->empresa?->id)
            ->where('estado', WhatsappAccount::CONECTADO)
            ->firstOrFail();
    }
}
