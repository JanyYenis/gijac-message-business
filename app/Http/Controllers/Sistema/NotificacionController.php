<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Sistema\Notification;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        $usuario = auth()->user();
        $cantidad = $usuario->unreadNotifications->count() ?? 0;
        $info['cantidad'] = $cantidad;
        $info['unreadNotifications'] = $usuario->unreadNotifications;
        $info['notifications'] = $usuario->notifications;

        return [
            'estado' => 'success',
            'mensaje' => 'Se actualizaron las notificaciones correctamente.',
            'html' => view('layouts.componentes.notificaciones', $info)->render(),
        ];
    }

    public function marcarNotificaciones()
    {
        $usuario = auth()->user();
        $usuario->unreadNotifications->markAsRead();
        $info['cantidad'] = 0;
        $info['unreadNotifications'] = $usuario->unreadNotifications;
        $info['notifications'] = $usuario->notifications;

        return [
            'estado' => 'success',
            'mensaje' => 'Se marcaron todas las notificaciones como leido.',
            'html' => view('layouts.componentes.notificaciones', $info)->render(),
        ];
    }

    public function marcarNotificacion(Notification $notificacion)
    {
        $notificacion->update(['read_at' => now()]);
        return redirect(json_decode($notificacion->data, true)['ruta'] ?? '/');
    }
}
