<?php

namespace App\Jobs;

use App\Events\NotificacionEvent;
use App\Models\ClasificacionIa;
use App\Models\Contacto;
use App\Models\Conversacion;
use App\Models\Empresa;
use App\Models\Etiqueta;
use App\Models\EtiquetaContacto;
use App\Models\Plan;
use App\Models\Usuario;
use App\Models\WhatsappNumber;
use App\Notifications\ChatAsignadoNotification;
use App\Services\AI\OllamaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Throwable;

/**
 * Cuando el cliente abre una conversación nueva (primer mensaje o después de varias horas sin escribir)
 * la IA elige la etiqueta que mejor describe el motivo y el chat se asigna a un agente de esa etiqueta.
 * Solo elige entre etiquetas que tengan agentes asignados.
 */
class ClasificarChat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const HORAS_CONVERSACION_NUEVA = 12;
    public const HORAS_ASIGNACION = 4;

    public int $tries = 2;
    public int $timeout = 90;

    public function __construct(
        private string $empresaId,
        private string $contactoId,
        private string $phoneNumberId,
        private string $texto
    ) {
    }

    /**
     * Punto de entrada desde el webhook: decide si la conversación es nueva y, solo entonces, encola el trabajo.
     */
    public static function evaluar(string $empresaId, string $contactoId, string $phoneNumberId, ?string $texto): void
    {
        $texto = trim((string) $texto);
        if ($texto === '') {
            return;
        }

        $conv = Conversacion::firstOrCreate(['contacto_id' => $contactoId, 'phone_number_id' => $phoneNumberId]);
        $nueva = ! $conv->ultimo_entrante_at
            || $conv->ultimo_entrante_at->lt(now()->subHours(self::HORAS_CONVERSACION_NUEVA));

        $conv->update(['ultimo_entrante_at' => now()]);

        if ($nueva) {
            dispatch(new self($empresaId, $contactoId, $phoneNumberId, mb_substr($texto, 0, 1000)));
        }
    }

    public function handle(OllamaService $ollama): void
    {
        $empresa = Empresa::find($this->empresaId);
        if (! $empresa) {
            return;
        }

        $opciones = Etiqueta::where('cod_empresa', $this->empresaId)
            ->where('estado', Etiqueta::ACTIVO)
            ->whereIn('id', DB::table('etiqueta_agentes')->select('etiqueta_id'))
            ->get(['id', 'nombre', 'descripcion']);

        if ($opciones->isEmpty()) {
            return;
        }

        $etiqueta = $this->elegirEtiqueta($ollama, $empresa, $opciones);
        if (! $etiqueta) {
            return;
        }

        $conv = Conversacion::firstOrCreate(['contacto_id' => $this->contactoId, 'phone_number_id' => $this->phoneNumberId]);
        $conv->update(['etiqueta_id' => $etiqueta->id]);

        EtiquetaContacto::updateOrCreate(
            ['cod_contacto' => $this->contactoId, 'cod_etiqueta' => $etiqueta->id],
            ['estado' => EtiquetaContacto::ACTIVO]
        );

        $this->asignarAgente($conv, $etiqueta);
    }

    private function elegirEtiqueta(OllamaService $ollama, Empresa $empresa, $opciones): ?Etiqueta
    {
        $lista = $opciones->map(fn ($e) => "- {$e->id} | {$e->nombre}" . ($e->descripcion ? ": {$e->descripcion}" : ''))->implode("\n");
        $criterios = ClasificacionIa::where('estado', ClasificacionIa::ACTIVO)
            ->where('cod_usuario', $empresa->cod_usuario)
            ->value('prompt');

        $sistema = "Eres un clasificador de mensajes de clientes de una empresa. Elige UNA categoría de la lista según el motivo del mensaje.\n"
            . "Responde únicamente con el ID de la categoría (sin texto adicional). Si ninguna aplica responde NINGUNA.\n\n"
            . "Categorías:\n{$lista}"
            . ($criterios ? "\n\nCriterios adicionales del negocio:\n{$criterios}" : '');

        $modelo = $empresa->asistenteActivo?->modelo ?: config('services.ollama.model', 'gpt-oss:120b-cloud');

        try {
            $respuesta = $ollama->chat($sistema, [['role' => 'user', 'content' => $this->texto]], $modelo, ['temperature' => 0]);
        } catch (Throwable $e) {
            report($e);
            return null;
        }

        // Se acepta solo un ID de la lista, nunca texto inventado
        foreach ($opciones as $e) {
            if (stripos($respuesta, $e->id) !== false) {
                return Etiqueta::find($e->id);
            }
        }

        return null;
    }

    /** Entre los agentes de la etiqueta que usan este número, el de menos chats activos (empate: el que lleva más tiempo sin recibir uno) */
    private function asignarAgente(Conversacion $conv, Etiqueta $etiqueta): void
    {
        $uuids = DB::table('etiqueta_agentes')->where('etiqueta_id', $etiqueta->id)->pluck('usuario_uuid');

        $candidatos = Usuario::whereIn('uuid', $uuids)
            ->where('estado', Usuario::ACTIVO)
            ->get()
            ->filter(fn ($u) => WhatsappNumber::paraUsuario($u)?->phone_number_id === $this->phoneNumberId);

        if ($candidatos->isEmpty()) {
            Log::info("CLASIFICACION: sin agentes disponibles para la etiqueta {$etiqueta->id} en el número {$this->phoneNumberId}");
            return;
        }

        $elegido = $candidatos->sortBy(function ($u) {
            $carga = Conversacion::where('agente_uuid', $u->uuid)->where('agente_hasta', '>', now())->count();
            $ultimo = Conversacion::where('agente_uuid', $u->uuid)->max('agente_hasta');

            return [$carga, $ultimo ? strtotime($ultimo) : 0];
        })->first();

        $conv->update([
            'agente_uuid'  => $elegido->uuid,
            'agente_hasta' => now()->addHours(self::HORAS_ASIGNACION),
        ]);

        // Aviso al agente en la campana de notificaciones (y en tiempo real si está conectado)
        try {
            $nombre = Contacto::find($this->contactoId)?->nombre_completo ?: __('Un cliente');
            Notification::send($elegido, new ChatAsignadoNotification($nombre, $etiqueta->nombre));
            broadcast(new NotificacionEvent($elegido));
        } catch (Throwable $e) {
            report($e); // el chat ya quedó asignado; el aviso no debe romper el trabajo
        }
    }
}
