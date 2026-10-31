<?php

namespace App\Http\Controllers\Apis\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Conversacion;
use App\Models\Mensaje;
use App\Models\WhatsappNumber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** Resumen de la pantalla de inicio, calculado sobre el número de WhatsApp que usa el usuario */
class ResumenMovilController extends Controller
{
    /** GET /api/mobile/summary */
    public function index(Request $request): JsonResponse
    {
        $numero = WhatsappNumber::paraUsuario($request->user());
        if (! $numero) {
            return response()->json(['success' => false, 'message' => 'Tu empresa no tiene un número de WhatsApp conectado.'], 404);
        }
        $pid = $numero->phone_number_id;
        $hoy = now()->startOfDay();

        $conversaciones = Conversacion::where('phone_number_id', $pid);

        return response()->json([
            'success' => true,
            'data' => [
                // Conversaciones con actividad en las últimas 24 h (ventana de atención de WhatsApp)
                'conversaciones_activas' => (clone $conversaciones)->where('ultima_fecha', '>=', now()->subDay())->count(),
                'chats_sin_leer' => (clone $conversaciones)->where('mensajes_no_leidos', '>', 0)->count(),
                'mensajes_sin_leer' => (int) (clone $conversaciones)->sum('mensajes_no_leidos'),
                'recibidos_hoy' => Mensaje::where('wa_to', $pid)->where('created_at', '>=', $hoy)->count(),
                'enviados_hoy' => Mensaje::where('wa_from', $pid)->where('created_at', '>=', $hoy)->count(),
            ],
        ]);
    }
}
