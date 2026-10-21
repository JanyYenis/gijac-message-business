<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\DetalleErrorMensaje;
use App\Models\Mensaje;
use App\Models\WhatsappAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

/** API pública: mensaje de texto (dentro de la ventana de 24 h) y consulta de estado de un mensaje. */
class MensajeApiController extends Controller
{
    public function texto(Request $request)
    {
        $v = Validator::make($request->all(), [
            'telefono' => ['required', 'regex:/^\+?[0-9]{8,15}$/'],
            'mensaje' => 'required|string|max:4096',
        ]);
        if ($v->fails()) {
            return response()->json(['estado' => 'error', 'mensaje' => __('Datos inválidos.'), 'errores' => $v->errors()], 422);
        }

        $empresaId = $request->attributes->get('api_empresa_id');
        $telefono = ltrim($request->input('telefono'), '+');

        $config = WhatsappAccount::where('estado', WhatsappAccount::CONECTADO)->where('cod_empresa', $empresaId)->first();
        if (!$config || !$config->access_token) {
            return response()->json(['estado' => 'error', 'mensaje' => __('No hay una cuenta de WhatsApp conectada.')], 422);
        }

        $contacto = Contacto::where('cod_empresa', $empresaId)->where('numero_completo', $telefono)
            ->where('estado', '!=', Contacto::ELIMINADO)->first();
        if (!$contacto) {
            return response()->json(['estado' => 'error', 'mensaje' => __('Contacto no encontrado. Crea el contacto o envía primero una plantilla.')], 404);
        }

        // WhatsApp solo permite texto libre si el cliente escribió (o recibió una plantilla) en las últimas 24 h
        if (!$contacto->permiso_envio) {
            return response()->json([
                'estado' => 'error',
                'codigo' => 'fuera_de_ventana',
                'mensaje' => __('Pasaron más de 24 horas desde la última interacción. Envía una plantilla aprobada para reabrir la conversación.'),
            ], 422);
        }

        $api = new WhatsAppCloudApi([
            'from_phone_number_id' => $config->phone_number_id,
            'access_token' => Crypt::decrypt($config->access_token),
            'graph_version' => config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0')),
        ]);

        try {
            $respuesta = $api->sendTextMessage($telefono, $request->input('mensaje'));
        } catch (\Throwable $e) {
            Log::warning('API pública: error de conexión al enviar texto', ['error' => $e->getMessage()]);
            return response()->json(['estado' => 'error', 'mensaje' => __('No se pudo contactar a WhatsApp. Intenta de nuevo.')], 502);
        }

        if (!$respuesta || $respuesta->isError()) {
            $error = $respuesta?->decodedBody()['error'] ?? [];
            return response()->json([
                'estado' => 'error',
                'mensaje' => __('WhatsApp rechazó el envío.'),
                'detalle' => ['codigo' => $error['code'] ?? null, 'mensaje' => $error['message'] ?? null],
            ], 422);
        }

        $wamid = json_decode($respuesta->body())->messages[0]->id ?? null;

        Mensaje::create([
            'campaign_id' => null,
            'contact_id' => $contacto->id,
            'wa_message_id' => $wamid ?? 'error',
            'wa_from' => $config->phone_number_id,
            'wa_to' => $contacto->numero_completo,
            'type' => Mensaje::TEXTO,
            'body' => $request->input('mensaje'),
            'metadata' => null,
            'estado' => Mensaje::ENVIADO,
            'sent_at' => now(),
        ]);

        return response()->json(['estado' => 'success', 'mensaje' => __('Mensaje enviado correctamente.'), 'wamid' => $wamid]);
    }

    public function estado(Request $request, string $wamid)
    {
        $config = WhatsappAccount::where('cod_empresa', $request->attributes->get('api_empresa_id'))
            ->where('estado', WhatsappAccount::CONECTADO)->first();

        $mensaje = $config ? Mensaje::where('wa_message_id', $wamid)->where('wa_from', $config->phone_number_id)->first() : null;
        if (!$mensaje) {
            return response()->json(['estado' => 'error', 'mensaje' => __('Mensaje no encontrado.')], 404);
        }

        $nombre = match ((int) $mensaje->estado) {
            Mensaje::ENVIADO => 'enviado',
            Mensaje::ENTREGADO => 'entregado',
            Mensaje::LEIDO => 'leido',
            Mensaje::FALLIDO => 'fallido',
            default => 'desconocido',
        };

        $respuesta = ['estado' => 'success', 'wamid' => $wamid, 'estado_mensaje' => $nombre, 'enviado_en' => $mensaje->sent_at];
        if ($nombre === 'fallido' && ($e = DetalleErrorMensaje::where('wamid', $wamid)->first())) {
            $respuesta['error'] = ['codigo' => $e->code, 'mensaje' => $e->message];
        }

        return response()->json($respuesta);
    }
}
