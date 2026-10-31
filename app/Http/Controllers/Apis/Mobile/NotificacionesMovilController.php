<?php

namespace App\Http\Controllers\Apis\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** Las mismas notificaciones de Laravel (tabla notifications) que ve la web en la campana */
class NotificacionesMovilController extends Controller
{
    /** GET /api/mobile/notifications */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $lista = $user->notifications()->latest()->limit(100)->get()->map(function ($n) {
            $d = is_array($n->data) ? $n->data : (json_decode($n->data, true) ?? []);

            return [
                'id' => $n->id,
                'titulo' => $d['titulo'] ?? 'Notificación',
                'mensaje' => $d['mensaje'] ?? '',
                'icono' => $d['icono'] ?? null,
                'color' => $d['color'] ?? 'primary',
                'ruta' => $d['ruta'] ?? null,
                'leida' => $n->read_at !== null,
                'fecha' => $n->created_at?->toIso8601String(),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                // id del canal de tiempo real notificacion.{id} (el mismo que usa la web)
                'usuario_id' => $user->id,
                'no_leidas' => $user->unreadNotifications()->count(),
                'items' => $lista,
            ],
        ]);
    }

    /** POST /api/mobile/notifications/read-all */
    public function marcarTodas(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /** POST /api/mobile/notifications/{id}/read — solo si es del usuario */
    public function marcar(Request $request, string $id): JsonResponse
    {
        $request->user()->notifications()->where('id', $id)->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
