<?php

namespace App\Jobs;

use App\Events\NotificacionEvent;
use App\Models\Contacto;
use App\Models\Dispositivo;
use App\Models\Usuario;
use App\Models\UsuarioEmpresa;
use App\Models\WhatsappNumber;
use App\Notifications\AtencionHumanaSolicitada;
use App\Services\Fcm\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Throwable;

/** Avisa (campana web en vivo + push a la app móvil) que un cliente fue transferido a un agente humano */
class EnviarAvisoAtencionHumana implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;

    public function __construct(private string $contactoId, private ?string $phoneNumberId)
    {
    }

    public function handle(): void
    {
        $contacto = Contacto::find($this->contactoId);
        if (! $contacto) {
            return;
        }

        $nombre = trim(($contacto->nombre ?? '') . ' ' . ($contacto->apellido ?? '')) ?: (string) $contacto->numero_completo;

        $usuarios = $this->destinatarios((string) $contacto->cod_empresa);
        if ($usuarios->isEmpty()) {
            Log::info('HUMANO: sin usuarios a quienes avisar', ['empresa' => $contacto->cod_empresa, 'pn' => $this->phoneNumberId]);
            return;
        }

        // Web: campana de notificaciones (se refresca en vivo)
        foreach ($usuarios as $usuario) {
            try {
                Notification::send($usuario, new AtencionHumanaSolicitada($contacto->id, $nombre, $contacto->numero_completo));
                broadcast(new NotificacionEvent($usuario));
            } catch (Throwable $e) {
                report($e);
            }
        }

        // App móvil: se abre el chat del cliente (mismo 'tipo' que los mensajes entrantes)
        $dispositivos = Dispositivo::whereIn('usuario_id', $usuarios->pluck('uuid')->all())
            ->whereNotNull('fcm_token')->whereNotNull('vinculado_en')->get();

        Log::info('HUMANO: avisando', ['usuarios' => $usuarios->count(), 'dispositivos' => $dispositivos->count(), 'chat' => $contacto->numero_completo]);

        foreach ($dispositivos as $d) {
            $resultado = FcmService::enviar(
                $d->fcm_token,
                __('Atención humana solicitada'),
                "{$nombre} " . __('espera hablar con un asesor.'),
                ['tipo' => 'mensaje', 'motivo' => 'atencion_humana', 'chat_id' => (string) $contacto->numero_completo, 'nombre' => $nombre],
                'humano_' . $contacto->numero_completo
            );
            Log::info('HUMANO: push', ['resultado' => $resultado, 'token_fin' => substr($d->fcm_token, -12), 'error' => FcmService::$ultimoError]);
            if ($resultado === FcmService::TOKEN_INVALIDO) {
                $d->update(['fcm_token' => null]);
            }
        }
    }

    /** Usuarios activos de la empresa que atienden ese número; si ninguno lo atiende en concreto, todos los de la empresa. */
    private function destinatarios(string $empresa)
    {
        $uuids = UsuarioEmpresa::where('cod_empresa', $empresa)
            ->where('estado', UsuarioEmpresa::ACTIVO)
            ->pluck('cod_usuario');

        $usuarios = Usuario::whereIn('uuid', $uuids)->get();

        if (! $this->phoneNumberId) {
            return $usuarios;
        }

        $atienden = $usuarios->filter(fn ($u) => WhatsappNumber::paraUsuario($u)?->phone_number_id === $this->phoneNumberId);

        return $atienden->isNotEmpty() ? $atienden : $usuarios;
    }
}
