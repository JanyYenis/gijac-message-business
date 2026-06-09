<?php

namespace App\Http\Controllers;

use App\Events\CallsEvent;
use App\Jobs\WebhookContacts;
use App\Jobs\WebhookMessage;
use App\Jobs\WebhookStatus;
use App\Jobs\WebhookUserPreferences;
use App\Models\ConfiguracionMeta;
use App\Models\Contacto;
use App\Models\Mensaje;
use Exception;
use Illuminate\Http\Request;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

class WebhookController extends Controller
{
    public function webhook(Request $request, $app_id)
    {
        try {
            $config = ConfiguracionMeta::where('app_id', $app_id)->where('estado', ConfiguracionMeta::ACTIVO)?->first() ?? null;
            $token = $config?->token_1;
            $query = $request->query();

            $mode = $query['hub_mode'];
            $palabraReto = $query['hub_challenge'];
            $tokenVerificacion = $query['hub_verify_token'];

            if ($mode && $tokenVerificacion) {
                if ($mode === 'subscribe' && $token == $tokenVerificacion) {
                    return response($palabraReto, 200)->header('Content-Type', 'text/plain');
                }
            }

            throw new Exception("Peticion invalida");
        } catch (Exception $e) {
            return response()->json([
                'estado' => 'error',
                'mensaje' => $e->getMessage(),
            ], 500);
        }
    }

    public function acctionWebhook(Request $request, $app_id)
    {
        try {
            // $fileName = "calls.json";

            // // Escribe el JSON en el archivo
            // if (file_put_contents($fileName, $request->getContent())) {
            //     echo "El archivo $fileName fue creado exitosamente.";
            // } else {
            //     echo "Hubo un error al crear el archivo.";
            // }
            $bodyContent = json_decode($request->getContent(), true);
            $datos = $bodyContent['entry'][0]['changes'][0]['value'];
            if (array_key_exists('contacts', $datos)) {
                $telefono = $datos['contacts'][0]['wa_id'];
                $contacto = Contacto::whereRaw("CONCAT(codigo_telefono, telefono) = ?", [$telefono])?->first() ?? null;
                if (!$contacto) {
                    dispatch(new WebhookContacts($datos, $app_id));
                }
            }

            if (array_key_exists('statuses', $datos)) {
                dispatch(new WebhookStatus($datos, $app_id));
            }

            if (array_key_exists('user_preferences', $datos)) {
                dispatch(new WebhookUserPreferences($datos, $app_id));
            }

            if (array_key_exists('messages', $datos)) {
                dispatch(new WebhookMessage($datos, $app_id));
            }

            if (array_key_exists('calls', $datos)) {
                // broadcast(new CallsEvent($nuevo_mensaje));
            }

            return response()->json([
                'message' => 'ok'
            ], 200);
        } catch (Exception $e) {
            $mensaje = Mensaje::updateOrCreate([
                'wa_message_id' => 'error'
            ], [
                'wa_from'   => 1,
                'wa_to'     => 1,
                'type'      => Mensaje::TEXTO,
                'body'      => $e->getMessage(),
                'estado'    => Mensaje::ERROR,
                'sent_at'   => now(),
            ]);
            return response()->json([
                'estado' => 'error',
                'mensaje' => $e->getMessage(),
            ], 500);
        }
    }
}
