<?php

namespace App\Services\Chats;

use App\Models\Contacto;
use App\Models\Conversacion;
use App\Models\Usuario;

/**
 * Evita que dos agentes le escriban a la vez al mismo contacto por el mismo número.
 * El primero que responde se queda el chat; si pasan MINUTOS sin que escriba, otro puede tomarlo.
 * Admin y cliente nunca quedan bloqueados.
 */
class ConversacionAgente
{
    public const MINUTOS = 30;

    /**
     * Reserva el chat para el usuario y pausa los bots de ese contacto.
     * Devuelve un mensaje de error si lo está atendiendo otra persona, o null si puede escribir.
     */
    public static function reclamar(?string $contactoId, ?string $phoneNumberId, $usuario): ?string
    {
        if (! $contactoId || ! $phoneNumberId || ! $usuario) {
            return null;
        }

        $conv = Conversacion::firstOrCreate([
            'contacto_id'     => $contactoId,
            'phone_number_id' => $phoneNumberId,
        ]);

        $supervisor = $usuario->hasAnyRole([Usuario::ROL_ADMINISTRADOR, Usuario::ROL_CLIENTE]);

        if (! $supervisor
            && $conv->agente_uuid
            && $conv->agente_uuid !== $usuario->uuid
            && $conv->agente_hasta?->isFuture()) {
            $otro = Usuario::where('uuid', $conv->agente_uuid)->first();

            return __('Este chat lo está atendiendo :nombre. Podrás responder cuando lo libere.', [
                'nombre' => $otro?->nombre_completo ?? __('otro agente'),
            ]);
        }

        $conv->update([
            'agente_uuid'  => $usuario->uuid,
            'agente_hasta' => now()->addMinutes(self::MINUTOS),
        ]);

        // Con una persona atendiendo, los bots de este contacto se pausan
        Contacto::where('id', $contactoId)->update([
            'estado_chatbot'    => Contacto::INACTIVO,
            'estado_chatbot_ia' => Contacto::INACTIVO,
        ]);

        return null;
    }
}
