<?php

namespace App\Notifications;

use App\Models\Campana;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevaCampana extends Notification
{
    use Queueable;

    public $campana = null;
    public $id_campana = null;

    /**
     * Create a new notification instance.
     */
    public function __construct($campana)
    {
        $this->id_campana = $campana;
        $this->campana = Campana::find($campana);
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
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->campana?->id ?? $this->id_campana,
            'titulo' => 'Nueva campaña.',
            'mensaje' => 'Se creo una nueva campaña con nombre: '.$this->campana?->nombre ?? 'N/A',
            'icono' => 'fas fa-bullhorn',
            'color' => 'info',
            // 'ruta' => route('campanas.index'),
            'ruta' => route('campanas.show', ['campana' => $this->campana->id]),
        ];
    }
}
