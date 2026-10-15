<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WhatsappTokenNotification extends Notification
{
    use Queueable;

    /**
     * @param int|null $dias Días que le quedan al token; null o 0 = ya expiró / fue revocado.
     */
    public function __construct(public ?int $dias = null)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function titulo(): string
    {
        return $this->dias
            ? __('Tu conexión con WhatsApp vence pronto.')
            : __('Tu conexión con WhatsApp se desconectó.');
    }

    protected function mensaje(): string
    {
        return $this->dias
            ? __('La autorización de tu cuenta de WhatsApp vence en :dias día(s). Vuelve a conectarla para no interrumpir tus envíos.', ['dias' => $this->dias])
            : __('La autorización de tu cuenta de WhatsApp venció o fue revocada. Vuelve a conectarla para seguir enviando y recibiendo mensajes.');
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->titulo())
            ->line($this->mensaje())
            ->action(__('Reconectar WhatsApp'), route('configs.index'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'id' => 'whatsapp-token',
            'titulo' => $this->titulo(),
            'mensaje' => $this->mensaje(),
            'icono' => 'fab fa-whatsapp',
            'color' => $this->dias ? 'warning' : 'danger',
            'ruta' => route('configs.index'),
        ];
    }
}
