<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Aviso al agente cuando se le asigna un chat. Aparece en la campana de notificaciones.
 */
class ChatAsignadoNotification extends Notification
{
    use Queueable;

    public function __construct(public string $contacto, public ?string $etiqueta = null)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'id' => 'chat-asignado',
            'titulo' => __('Te asignaron un chat'),
            'mensaje' => $this->etiqueta
                ? __(':contacto escribió por ":etiqueta".', ['contacto' => $this->contacto, 'etiqueta' => $this->etiqueta])
                : __(':contacto te escribió.', ['contacto' => $this->contacto]),
            'icono' => 'fab fa-whatsapp',
            'color' => 'success',
            'ruta' => route('chats.index'),
        ];
    }
}
