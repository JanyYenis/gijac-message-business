<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Un cliente fue transferido del chatbot a un agente humano.
 * Se guarda en la campana de notificaciones (se refresca en vivo con NotificacionEvent).
 */
class AtencionHumanaSolicitada extends Notification
{
    use Queueable;

    public function __construct(
        public string $contactoId,
        public ?string $nombre = null,
        public ?string $telefono = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $quien = trim(($this->nombre ?: __('Un cliente')) . ($this->telefono ? " ({$this->telefono})" : ''));

        return [
            'id'      => $this->contactoId,
            'titulo'  => __('Atención humana solicitada.'),
            'mensaje' => $quien . ' ' . __('espera hablar con un asesor.'),
            'icono'   => 'fas fa-headset',
            'color'   => 'warning',
            'ruta'    => route('chats.index'), // TODO: ruta del módulo de chats
        ];
    }
}
