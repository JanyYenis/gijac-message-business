<?php

namespace App\Notifications;

use App\Models\ContactoPagina;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactoFormulario extends Notification
{
    use Queueable;

    public $contacto = null;

    /**
     * Create a new notification instance.
     */
    public function __construct($contacto)
    {
        $this->contacto = ContactoPagina::find($contacto);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->view('mail.contactos.formulario-contacto', [
            'contacto' => $this->contacto
        ])->subject('GIJAC MESSAGE BUSINESS: Nueva solicitud');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->contacto->id,
            'titulo' => 'Nueva solicitud.',
            'mensaje' => $this->contacto->nombre . ' acaba de enviar una solicitud',
            'icono' => 'fas fa-users',
            'color' => 'primary',
            'ruta' => route('home'),
            // 'ruta' => route('tickets.edit', ['ticket' => $this->ticket->id]),
        ];
    }
}
