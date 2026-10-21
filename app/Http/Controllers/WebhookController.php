<?php

namespace App\Http\Controllers;

use App\Jobs\WebhookContacts;
use App\Jobs\WebhookMessage;
use App\Jobs\WebhookStatus;
use App\Jobs\WebhookUserPreferences;
use App\Models\Contacto;
use App\Models\WhatsappAccount;
use App\Services\Api\ApiWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Verificación inicial del webhook (la hace Meta una vez al registrarlo).
     */
    public function webhook(Request $request)
    {
        $mode = $request->query('hub_mode');
        $challenge = $request->query('hub_challenge');
        $verifyToken = (string) $request->query('hub_verify_token');
        $token = (string) config('facebook.webhook_token');

        if ($mode === 'subscribe' && $token !== '' && hash_equals($token, $verifyToken)) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response()->json([
            'estado' => 'error',
            'mensaje' => 'Petición inválida',
        ], 403);
    }

    /**
     * Eventos de WhatsApp. Solo se procesan si vienen firmados por Meta.
     * Todo el trabajo pesado va a la cola; aquí se responde rápido con 200.
     */
    public function acctionWebhook(Request $request)
    {
        if (!$this->firmaValida($request)) {
            Log::warning('Webhook de WhatsApp con firma inválida', ['ip' => $request->ip()]);
            return response()->json(['estado' => 'error', 'mensaje' => 'Firma no válida'], 403);
        }

        $body = json_decode($request->getContent(), true);
        if (!is_array($body) || ($body['object'] ?? null) !== 'whatsapp_business_account') {
            return response()->json(['message' => 'ignorado'], 200);
        }

        // Meta puede agrupar varias entradas y varios cambios en una sola petición
        foreach ($body['entry'] ?? [] as $entry) {
            $wabaId = $entry['id'] ?? null;

            foreach ($entry['changes'] ?? [] as $change) {
                try {
                    $this->procesarCambio($change['value'] ?? [], $wabaId, $change['field'] ?? null);
                } catch (\Throwable $e) {
                    // Se responde 200 igual: si no, Meta reintenta el mismo evento durante días
                    Log::error('Error procesando un cambio del webhook de WhatsApp', [
                        'waba_id' => $wabaId,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return response()->json(['message' => 'ok'], 200);
    }

    /**
     * Valida el encabezado X-Hub-Signature-256 (HMAC-SHA256 del cuerpo con el App Secret).
     */
    private function firmaValida(Request $request): bool
    {
        $secret = (string) config('facebook.client_secret');
        $firma = (string) $request->header('X-Hub-Signature-256');

        if ($secret === '' || $firma === '') {
            return false;
        }

        $esperada = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);

        return hash_equals($esperada, $firma);
    }

    private function procesarCambio(array $datos, ?string $wabaId, ?string $campo = null): void
    {
        $empresaId = WhatsappAccount::where('estado', WhatsappAccount::CONECTADO)
            ->where('waba_id', $wabaId)
            ->value('cod_empresa');

        // Contactos nuevos: uno por contacto, solo si no existen en ESA empresa
        foreach ($datos['contacts'] ?? [] as $contacto) {
            $telefono = $contacto['wa_id'] ?? null;
            if (!$telefono || !$empresaId) {
                continue;
            }

            $existe = Contacto::where('numero_completo', $telefono)
                ->where('cod_empresa', $empresaId)
                ->exists();

            if (!$existe) {
                dispatch(new WebhookContacts(array_merge($datos, ['contacts' => [$contacto]]), $wabaId));
            }
        }

        // Estados (enviado, entregado, leído, fallido): un job por estado
        foreach ($datos['statuses'] ?? [] as $estado) {
            dispatch(new WebhookStatus(array_merge($datos, ['statuses' => [$estado]]), $wabaId));

            // Webhook del cliente (API): estado del mensaje
            ApiWebhookService::emitir($empresaId, 'message.status', array_filter([
                'wamid' => $estado['id'] ?? null,
                'estado' => $estado['status'] ?? null,
                'destinatario' => $estado['recipient_id'] ?? null,
                'numero' => $datos['metadata']['display_phone_number'] ?? null,
                'timestamp' => $estado['timestamp'] ?? null,
                'error' => isset($estado['errors'][0]) ? [
                    'codigo' => $estado['errors'][0]['code'] ?? null,
                    'mensaje' => $estado['errors'][0]['title'] ?? ($estado['errors'][0]['message'] ?? null),
                ] : null,
            ], fn ($v) => $v !== null));
        }

        if (array_key_exists('user_preferences', $datos)) {
            dispatch(new WebhookUserPreferences($datos, $wabaId));
        }

        // Mensajes entrantes: un job por mensaje, ignorando los que Meta reenvía
        foreach ($datos['messages'] ?? [] as $mensaje) {
            $id = $mensaje['id'] ?? null;
            if ($id && !Cache::add('wa:webhook:msg:' . $id, 1, now()->addDay())) {
                continue;
            }

            $contactos = collect($datos['contacts'] ?? []);
            $contacto = $contactos->firstWhere('wa_id', $mensaje['from'] ?? null) ?? $contactos->first();

            // Webhook del cliente (API): mensaje recibido
            $tipo = $mensaje['type'] ?? 'text';
            ApiWebhookService::emitir($empresaId, 'message.received', array_filter([
                'wamid' => $id,
                'de' => $mensaje['from'] ?? null,
                'nombre' => $contacto['profile']['name'] ?? null,
                'numero' => $datos['metadata']['display_phone_number'] ?? null,
                'tipo' => $tipo,
                'texto' => $mensaje['text']['body']
                    ?? $mensaje['button']['text']
                    ?? $mensaje['interactive']['button_reply']['title']
                    ?? $mensaje['interactive']['list_reply']['title']
                    ?? $mensaje[$tipo]['caption']
                    ?? null,
                'media_id' => $mensaje[$tipo]['id'] ?? null,
                'timestamp' => $mensaje['timestamp'] ?? null,
            ], fn ($v) => $v !== null));

            dispatch(new WebhookMessage(
                array_merge($datos, [
                    'messages' => [$mensaje],
                    'contacts' => $contacto ? [$contacto] : [],
                ]),
                $wabaId
            ));
        }

        // Cambios en plantillas: Meta manda el tipo de evento en "field" (no dentro de "value").
        // Se sincroniza en cola y con una pausa corta; los eventos que lleguen en ese lapso quedan cubiertos.
        if (in_array($campo, [
            'message_template_components_update',
            'message_template_quality_update',
            'message_template_status_update',
            'template_category_update',
            'template_correct_category_detection',
        ], true) && Cache::add('wa:plantillas:sync:' . $wabaId, 1, 20)) {
            Artisan::queue('sincronizar:plantillas', ['waba_id' => $wabaId])->delay(now()->addSeconds(20));
        }
    }
}
