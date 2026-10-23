<?php

namespace App\Jobs;

use App\Models\Contacto;
use App\Models\Dispositivo;
use App\Models\Mensaje;
use App\Models\Usuario;
use App\Models\WhatsappAccount;
use App\Models\WhatsappNumber;
use App\Services\Fcm\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/** Notificación push a la app móvil cuando llega un mensaje de un cliente */
class EnviarPushMensaje implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;

    public function __construct(private string $waMessageId, private ?string $phoneNumberId)
    {
    }

    public function handle(): void
    {
        $mensaje = Mensaje::where('wa_message_id', $this->waMessageId)->first();
        if (! $mensaje || ! $this->phoneNumberId) {
            return;
        }

        $empresa = WhatsappNumber::where('phone_number_id', $this->phoneNumberId)->value('cod_empresa')
            ?? WhatsappAccount::where('phone_number_id', $this->phoneNumberId)->value('cod_empresa');
        if (! $empresa) {
            return;
        }

        $contacto = Contacto::where('numero_completo', $mensaje->wa_from)->where('cod_empresa', $empresa)->first();
        $nombre = trim(($contacto->nombre ?? '') . ' ' . ($contacto->apellido ?? '')) ?: $mensaje->wa_from;
        $cuerpo = $this->resumen($mensaje);

        // Solo a los usuarios que atienden ESE número (el asignado o el principal de la cuenta activa)
        $uuids = Usuario::where('cod_empresa', $empresa)->get()
            ->filter(fn ($u) => WhatsappNumber::paraUsuario($u)?->phone_number_id === $this->phoneNumberId)
            ->pluck('uuid')->all();
        if (! $uuids) {
            return;
        }

        $dispositivos = Dispositivo::whereIn('usuario_id', $uuids)->whereNotNull('fcm_token')->get();
        foreach ($dispositivos as $d) {
            if ($d->expira_en && \Carbon\Carbon::parse($d->expira_en)->isPast()) {
                continue;
            }
            $resultado = FcmService::enviar(
                $d->fcm_token,
                $nombre,
                $cuerpo,
                ['tipo' => 'mensaje', 'chat_id' => (string) $mensaje->wa_from, 'nombre' => $nombre],
                'chat_' . $mensaje->wa_from
            );
            if ($resultado === FcmService::TOKEN_INVALIDO) {
                $d->update(['fcm_token' => null]);
            }
        }
    }

    private function resumen(Mensaje $m): string
    {
        return match ((int) $m->type) {
            Mensaje::IMAGEN => '📷 Imagen',
            Mensaje::VIDEO => '🎬 Video',
            Mensaje::AUDIO => '🎤 Audio',
            Mensaje::DOCUMENTO => '📄 Documento',
            Mensaje::LOCALIZACION => '📍 Ubicación',
            default => trim((string) $m->body) !== '' ? (string) $m->body : 'Nuevo mensaje',
        };
    }
}
