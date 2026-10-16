<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsappAccount;
use App\Models\WhatsappNumber;
use App\Models\WhatsappWebhookLog;
use App\Services\Meta\PhoneNumbersService;
use App\Services\Meta\RegistrationService;
use App\Services\Meta\WehbookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
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
                'checklist'   => route('whatsapp.checklist'),
                'numbers'     => url('/admin/whatsapp/numbers'),
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
                    : null,
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

            // Sin expires_in = token que no vence (se corrige con el valor real de debug_token en el chequeo diario)
            $expiresAt = isset($tokenRes['expires_in'])
               ? now()->addSeconds((int) $tokenRes['expires_in'])
                : null;

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

            // El número recién conectado queda como principal en whatsapp_numbers
            $this->guardarNumero($account, $phone + ['id' => $phoneId], [
                'registered' => $registered,
                'pin' => $pinFromFrontend,
            ], true);

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
                    'token_days' => $expiresAt ? max(0, (int) now()->diffInDays($expiresAt, false)) : null,
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

    /* =========================================================
       GET /admin/whatsapp/checklist
       Estado real de la cuenta frente a Meta, paso a paso
       ========================================================= */
    public function checklist()
    {
        $account = WhatsappAccount::where('usuario_id', auth()->user()->uuid)
            ->where('cod_empresa', auth()->user()->empresa?->id)
            ->whereIn('estado', [WhatsappAccount::CONECTADO, WhatsappAccount::TOKEN_EXPIRADO])
            ->latest('updated_at')
            ->first();

        $items = [];
        $item = function (string $clave, string $titulo, string $estado, string $detalle, bool $requerido = true) use (&$items) {
            $items[] = compact('clave', 'titulo', 'estado', 'detalle', 'requerido');
        };

        // Consulta a Meta que nunca lanza excepción: devuelve [] si falla
        $get = function (string $url, array $query = [], ?string $token = null): array {
            try {
                $req = $token ? Http::withToken($token) : Http::acceptJson();
                $res = $req->timeout(10)->get($url, $query);
                return $res->successful() ? ($res->json() ?? []) : [];
            } catch (\Throwable $e) {
                return [];
            }
        };

        if (! $account) {
            $item('cuenta', 'Cuenta de WhatsApp conectada', 'pendiente', 'Conecta tu cuenta con Facebook para empezar.');
            return response()->json(['success' => true, 'listo' => false, 'items' => $items]);
        }

        $conectada = $account->estado === WhatsappAccount::CONECTADO;
        $item(
            'cuenta',
            'Cuenta de WhatsApp conectada',
            $conectada ? 'ok' : 'error',
            $conectada
                ? "Número {$account->phone_number}"
                : 'La conexión se perdió. Vuelve a conectar tu cuenta con Facebook.'
        );

        $token = Crypt::decrypt($account->access_token);

        /* Token y permisos */
        $dbg = $get("{$this->graph}/debug_token", [
            'input_token' => $token,
            'access_token' => "{$this->appId}|{$this->appSecret}",
        ])['data'] ?? [];

        if (! $dbg) {
            $item('token', 'Autorización (token) vigente', 'pendiente', 'No se pudo verificar con Meta en este momento. Intenta de nuevo.');
        } elseif (! ($dbg['is_valid'] ?? false)) {
            $item('token', 'Autorización (token) vigente', 'error', 'La autorización venció o fue revocada. Vuelve a conectar tu cuenta.');
        } else {
            $scopes = $dbg['scopes'] ?? [];
            $faltan = array_diff(['whatsapp_business_management', 'whatsapp_business_messaging'], $scopes);
            $dias = ! empty($dbg['expires_at']) ? (int) now()->diffInDays(\Carbon\Carbon::createFromTimestamp($dbg['expires_at']), false) : null;

            if ($faltan) {
                $item('token', 'Autorización (token) vigente', 'error', 'Faltan permisos: ' . implode(', ', $faltan) . '. Vuelve a conectar y acepta todos los permisos.');
            } elseif ($dias !== null && $dias <= 7) {
                $item('token', 'Autorización (token) vigente', 'pendiente', "Vence en {$dias} día(s). Renuévala o vuelve a conectar.");
            } else {
                $item('token', 'Autorización (token) vigente', 'ok', $dias === null ? 'No vence.' : "Vence en {$dias} días.");
            }
        }

        if (! $conectada || ! $dbg || ! ($dbg['is_valid'] ?? false)) {
            return response()->json(['success' => true, 'listo' => false, 'items' => $items]);
        }

        /* Webhook: ¿nuestra app está suscrita a la WABA? */
        $subs = $get("{$this->graph}/{$account->waba_id}/subscribed_apps", [], $token)['data'] ?? [];
        $suscrita = false;
        foreach ($subs as $s) {
            if ((string) ($s['whatsapp_business_api_data']['id'] ?? '') === $this->appId) {
                $suscrita = true;
            }
        }
        $item(
            'webhook',
            'Recepción de mensajes (webhook)',
            $suscrita ? 'ok' : 'error',
            $suscrita ? 'Tu cuenta está recibiendo mensajes y estados.' : 'La aplicación no está suscrita a tu cuenta. Usa "Re-suscribir webhook".'
        );

        /* Números de la cuenta (el activo y los demás) */
        $campos = 'id,display_phone_number,verified_name,status,code_verification_status,quality_rating,messaging_limit_tier';
        $lista = $get("{$this->graph}/{$account->waba_id}/phone_numbers", ['fields' => $campos . ',name_status'], $token)['data'] ?? [];
        if (! $lista) {
            $lista = $get("{$this->graph}/{$account->waba_id}/phone_numbers", ['fields' => $campos], $token)['data'] ?? [];
        }

        $locales = WhatsappNumber::where('whatsapp_account_id', $account->id)
            ->where('estado', WhatsappAccount::CONECTADO)
            ->pluck('phone_number_id')->flip();

        $numeros = [];
        foreach ($lista as $n) {
            $digitos = preg_replace('/\D/', '', $n['display_phone_number'] ?? '');
            $numeros[] = [
                'id'            => $n['id'] ?? null,
                'numero'        => $n['display_phone_number'] ?? '',
                'nombre'        => $n['verified_name'] ?? '',
                'nombre_estado' => $n['name_status'] ?? null,
                'estado'        => $n['status'] ?? 'UNKNOWN',
                'verificado'    => ($n['code_verification_status'] ?? '') === 'VERIFIED',
                'calidad'       => $n['quality_rating'] ?? 'UNKNOWN',
                'limite'        => str_replace('TIER_', '', $n['messaging_limit_tier'] ?? ''),
                // Los números de prueba que entrega Meta empiezan por +1 555
                'es_prueba'     => str_starts_with($digitos, '1555'),
                'activo'        => ($n['id'] ?? null) === $account->phone_number_id,
                'en_app'        => isset($locales[$n['id'] ?? '']),
            ];
        }

        $phone = collect($lista)->firstWhere('id', $account->phone_number_id)
            ?? $get("{$this->graph}/{$account->phone_number_id}", [
                'fields' => 'status,code_verification_status,quality_rating,messaging_limit_tier,verified_name',
            ], $token);

        if (! $phone) {
            $item('numero', 'Número verificado y activo', 'pendiente', 'No se pudo consultar el número en Meta.');
        } else {
            $verificado = ($phone['code_verification_status'] ?? '') === 'VERIFIED';
            $estadoNum = $phone['status'] ?? 'UNKNOWN';
            $nombre = $phone['name_status'] ?? null;
            $ok = $verificado && $estadoNum === 'CONNECTED';
            $detalle = $ok
                ? 'Número conectado a la API.'
                : "Estado: {$estadoNum}" . ($verificado ? '' : ' (sin verificar)') . '. Revisa el número en WhatsApp Manager.';
            $item('numero', 'Número verificado y activo', $ok ? 'ok' : 'error', $detalle);

            if ($nombre && $nombre !== 'APPROVED') {
                $item('nombre', 'Nombre de visualización aprobado', 'pendiente', "Estado del nombre: {$nombre}.", false);
            }

            $rating = $phone['quality_rating'] ?? 'UNKNOWN';
            $limite = str_replace('TIER_', '', $phone['messaging_limit_tier'] ?? '');
            $item(
                'calidad',
                'Calidad del número',
                $rating === 'RED' ? 'error' : ($rating === 'YELLOW' ? 'pendiente' : 'ok'),
                'Calidad: ' . $rating . ($limite ? " · Límite: {$limite} contactos nuevos / 24 h" : ''),
                false
            );
        }

        /* WABA: salud de envío, método de pago y verificación del negocio */
        $waba = $get("{$this->graph}/{$account->waba_id}", [
            'fields' => 'health_status,primary_funding_id,business_verification_status',
        ], $token);

        if ($waba) {
            $puede = $waba['health_status']['can_send_message'] ?? null;
            if ($puede) {
                $detalle = 'Meta permite enviar mensajes.';
                if ($puede !== 'AVAILABLE') {
                    $errores = [];
                    foreach (($waba['health_status']['entities'] ?? []) as $e) {
                        foreach (($e['errors'] ?? []) as $er) {
                            $errores[] = trim(($er['error_description'] ?? '') . ' ' . ($er['possible_solution'] ?? ''));
                        }
                    }
                    $detalle = "Envío {$puede}. " . implode(' | ', array_slice($errores, 0, 2));
                }
                $item('salud', 'Meta permite enviar mensajes', $puede === 'AVAILABLE' ? 'ok' : ($puede === 'LIMITED' ? 'pendiente' : 'error'), $detalle);
            }

            $pago = ! empty($waba['primary_funding_id']);
            $hayProduccion = collect($numeros)->contains(fn ($n) => ! $n['es_prueba']);
            if ($pago) {
                $item('pago', 'Método de pago en Meta', 'ok', 'Método de pago configurado.', false);
            } elseif (! $hayProduccion) {
                $item('pago', 'Método de pago en Meta', 'ok', 'No lo necesitas mientras uses el número de prueba. Lo pedirá Meta al enviar con un número de producción.', false);
            } else {
                $item('pago', 'Método de pago en Meta', 'pendiente', 'Sin tarjeta, Meta no deja enviar con tu número de producción.', true);
            }

            $ver = $waba['business_verification_status'] ?? 'not_verified';
            $item(
                'verificacion',
                'Negocio verificado en Meta',
                $ver === 'verified' ? 'ok' : 'pendiente',
                $ver === 'verified' ? 'Negocio verificado.' : 'Sin verificar: tu límite de envío es bajo. Verifica tu negocio en Meta Business Suite para ampliarlo.',
                false
            );
        }

        /* Plan */
        $plan = auth()->user()?->empresa?->facturaVigente;
        $item(
            'plan',
            'Plan activo',
            $plan ? 'ok' : 'pendiente',
            $plan ? 'Plan vigente hasta ' . $plan->fecha_vencimiento->format('d/m/Y') . '.' : 'No tienes un plan vigente. Elige uno para enviar campañas.',
            false
        );

        $listo = collect($items)->where('requerido', true)->every(fn ($i) => $i['estado'] === 'ok');

        // Meta permite 2 números por portafolio nuevo; 20 al verificar el negocio o llegar al límite de 2.000
        $tiers = ['2K', '10K', '100K', 'UNLIMITED'];
        $activo = collect($numeros)->firstWhere('activo', true);
        $verificadoNegocio = ($waba['business_verification_status'] ?? '') === 'verified';
        $maximo = ($verificadoNegocio || in_array($activo['limite'] ?? '', $tiers, true)) ? 20 : 2;

        $biz = urlencode((string) $account->business_id);
        $resumen = [
            'numeros'      => $numeros,
            'cupo'         => ['usados' => count($numeros), 'maximo' => $maximo],
            'verificacion' => $waba['business_verification_status'] ?? 'not_verified',
            'con_pago'     => ! empty($waba['primary_funding_id']),
            'enlaces'      => [
                'pago'         => "https://business.facebook.com/billing_hub/payment_settings?asset_id={$account->waba_id}&business_id={$biz}&placement=standalone",
                'verificacion' => "https://business.facebook.com/settings/security?business_id={$biz}",
                'manager'      => "https://business.facebook.com/latest/whatsapp_manager/overview?asset_id={$account->waba_id}&business_id={$biz}",
            ],
        ];

        return response()->json(['success' => true, 'listo' => $listo, 'items' => $items, 'resumen' => $resumen]);
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

    /* =========================================================
       NÚMEROS · agregar, verificar, registrar y elegir el principal
       ========================================================= */

    /** POST /numbers — crea el número en la WABA (queda sin verificar) */
    public function numberStart(Request $request)
    {
        $request->merge([
            'cc'           => preg_replace('/\D/', '', (string) $request->input('cc')),
            'phone_number' => preg_replace('/\D/', '', (string) $request->input('phone_number')),
        ]);
        $data = $request->validate([
            'cc'            => ['required', 'regex:/^\d{1,3}$/'],
            'phone_number'  => ['required', 'regex:/^\d{6,14}$/'],
            'verified_name' => ['required', 'string', 'min:3', 'max:100'],
        ]);

        $account = $this->currentAccountOrFail();
        $token   = Crypt::decrypt($account->access_token);

        try {
            $res = Http::withToken($token)->timeout(20)
                ->post("{$this->graph}/{$account->waba_id}/phone_numbers", $data);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['success' => false, 'message' => 'No se pudo contactar a Meta. Intenta de nuevo.'], 502);
        }

        if ($res->failed() || ! $res->json('id')) {
            return response()->json([
                'success' => false,
                'message' => $this->metaMessage($res->json(), 'No se pudo crear el número.'),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'phone_number_id' => (string) $res->json('id'),
            'message' => 'Número creado. Ahora debes verificarlo.',
        ]);
    }

    /** POST /numbers/{phone}/code — envía el código por SMS o llamada */
    public function numberCode(Request $request, string $phone)
    {
        $data = $request->validate(['method' => ['required', 'in:SMS,VOICE']]);

        $account = $this->currentAccountOrFail();
        $token   = Crypt::decrypt($account->access_token);
        $this->numeroDeLaCuenta($account, $token, $phone);

        $res = $this->phones->requestPhoneVerificationCode($token, $phone, $data['method']);
        if ($this->failed($res)) {
            return response()->json(['success' => false, 'message' => $this->errorMessage($res, 'No se pudo enviar el código.')], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $data['method'] === 'SMS' ? 'Te enviamos el código por SMS.' : 'Recibirás una llamada con el código.',
        ]);
    }

    /** POST /numbers/{phone}/verify — confirma el código recibido */
    public function numberVerify(Request $request, string $phone)
    {
        $request->merge(['code' => preg_replace('/\D/', '', (string) $request->input('code'))]);
        $data = $request->validate(['code' => ['required', 'regex:/^\d{6}$/']]);

        $account = $this->currentAccountOrFail();
        $token   = Crypt::decrypt($account->access_token);
        $this->numeroDeLaCuenta($account, $token, $phone);

        $res = $this->phones->verifyPhoneCode($token, $phone, $data['code']);
        if ($this->failed($res)) {
            return response()->json(['success' => false, 'message' => $this->errorMessage($res, 'Código incorrecto o vencido.')], 422);
        }

        return response()->json(['success' => true, 'message' => 'Número verificado.']);
    }

    /** POST /numbers/{phone}/register — registra en Cloud API con un PIN y lo guarda en la app */
    public function numberRegister(Request $request, string $phone)
    {
        $data = $request->validate(['pin' => ['required', 'digits:6']]);

        $account = $this->currentAccountOrFail();
        $token   = Crypt::decrypt($account->access_token);
        $n       = $this->numeroDeLaCuenta($account, $token, $phone);

        if (($n['code_verification_status'] ?? '') !== 'VERIFIED') {
            return response()->json(['success' => false, 'message' => 'Primero debes verificar el número con el código.'], 422);
        }

        $res = $this->registration->registerPhone($token, $phone, $data['pin']);
        $ok  = ! $this->failed($res) && ($res['success'] ?? false) === true;
        if (! $ok) {
            return response()->json([
                'success' => false,
                'message' => $this->errorMessage($res, 'No se pudo registrar el número. Revisa el PIN.'),
            ], 422);
        }

        $this->guardarNumero($account, $n, ['registered' => true, 'pin' => $data['pin']]);

        return response()->json(['success' => true, 'message' => 'Número registrado y agregado a tu cuenta.']);
    }

    /** POST /numbers/{phone}/link — agrega a la app un número que ya está conectado en Meta */
    public function numberLink(string $phone)
    {
        $account = $this->currentAccountOrFail();
        $token   = Crypt::decrypt($account->access_token);
        $n       = $this->numeroDeLaCuenta($account, $token, $phone);

        if (($n['code_verification_status'] ?? '') !== 'VERIFIED' || ($n['status'] ?? '') !== 'CONNECTED') {
            return response()->json(['success' => false, 'message' => 'El número aún no está conectado en Meta.'], 422);
        }

        $this->guardarNumero($account, $n, ['registered' => true]);

        return response()->json(['success' => true, 'message' => 'Número agregado a la app.']);
    }

    /** POST /numbers/{phone}/principal — el número pasa a ser el que usan chats y campañas */
    public function numberPrincipal(string $phone)
    {
        $account = $this->currentAccountOrFail();
        $token   = Crypt::decrypt($account->access_token);

        $num = WhatsappNumber::where('whatsapp_account_id', $account->id)
            ->where('phone_number_id', $phone)
            ->where('estado', WhatsappAccount::CONECTADO)
            ->firstOrFail();

        $n = $this->numeroDeLaCuenta($account, $token, $phone);
        if (($n['status'] ?? '') !== 'CONNECTED') {
            return response()->json(['success' => false, 'message' => 'El número no está conectado en Meta todavía.'], 422);
        }

        DB::transaction(function () use ($account, $num, $n) {
            $this->marcarPrincipal($account, $num);

            // whatsapp_accounts refleja el principal: el resto de la app sigue leyendo de ahí
            $account->update([
                'phone_number_id'   => $num->phone_number_id,
                'phone_number'      => $n['display_phone_number'] ?? $num->phone_number,
                'display_name'      => $n['verified_name'] ?? $num->display_name,
                'two_factor_pin'    => $num->two_factor_pin,
                'number_registered' => true,
                'quality_rating'    => WhatsappAccount::ratingFromMeta($n['quality_rating'] ?? null),
                'messaging_limit'   => str_replace('TIER_', '', $n['messaging_limit_tier'] ?? $account->messaging_limit),
            ]);
        });

        $account->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Ahora este es tu número principal.',
            'account' => [
                'phone_number_id' => $account->phone_number_id,
                'phone_number'    => $account->phone_number,
                'quality'         => WhatsappAccount::ratingToMeta($account->quality_rating),
                'limit'           => $account->messaging_limit,
            ],
        ]);
    }

    /** Verifica que el número pertenezca a la WABA del usuario y devuelve sus datos de Meta */
    private function numeroDeLaCuenta(WhatsappAccount $account, string $token, string $phoneId): array
    {
        $res = Http::withToken($token)->timeout(15)->get("{$this->graph}/{$account->waba_id}/phone_numbers", [
            'fields' => 'id,display_phone_number,verified_name,status,code_verification_status,quality_rating,messaging_limit_tier',
        ]);

        foreach ($res->json('data', []) as $n) {
            if ((string) ($n['id'] ?? '') === $phoneId) {
                return $n;
            }
        }

        abort(404, 'Ese número no pertenece a tu cuenta de WhatsApp.');
    }

    /** Crea o actualiza el número en whatsapp_numbers */
    private function guardarNumero(WhatsappAccount $account, array $n, array $extra = [], bool $principal = false): WhatsappNumber
    {
        $digitos = preg_replace('/\D/', '', (string) ($n['display_phone_number'] ?? ''));

        $attrs = [
            'whatsapp_account_id' => $account->id,
            'cod_empresa'         => $account->cod_empresa,
            'waba_id'             => $account->waba_id,
            'phone_number'        => $n['display_phone_number'] ?? '',
            'display_name'        => $n['verified_name'] ?? null,
            'number_registered'   => $extra['registered'] ?? false,
            'es_prueba'           => str_starts_with($digitos, '1555'),
            'estado'              => WhatsappAccount::CONECTADO,
        ];
        if (! empty($extra['pin'])) {
            $attrs['two_factor_pin'] = Crypt::encrypt($extra['pin']);
        }

        $num = WhatsappNumber::updateOrCreate(['phone_number_id' => (string) $n['id']], $attrs);

        if ($principal) {
            $this->marcarPrincipal($account, $num);
        }

        return $num;
    }

    private function marcarPrincipal(WhatsappAccount $account, WhatsappNumber $num): void
    {
        WhatsappNumber::where('whatsapp_account_id', $account->id)
            ->where('id', '!=', $num->id)
            ->update(['principal' => false]);
        $num->update(['principal' => true]);
    }

    /** Mensaje de error de Meta en texto claro, con pistas para los casos más comunes */
    private function metaMessage(?array $json, string $default): string
    {
        $e   = $json['error'] ?? [];
        $msg = $e['error_user_msg'] ?? $e['message'] ?? $default;

        if (stripos($msg, 'already') !== false || stripos($msg, 'en uso') !== false) {
            $msg .= ' Si el número está activo en la app WhatsApp (personal o Business), elimina esa cuenta primero.';
        }

        return $msg;
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
        WhatsappNumber::where('whatsapp_account_id', $account->id)->update(['estado' => WhatsappAccount::DESCONECTADO]);

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
