<?php

namespace App\Services\Mensajes;

use App\Models\Mensaje;
use App\Services\AI\AudioTranscriptionService;

class MessageContentService
{
    public function obtenerTexto(Mensaje $mensaje): ?string
    {
        if ($mensaje->type == Mensaje::TEXTO) {
            return $mensaje->body;
        }

        if ($mensaje->type == Mensaje::AUDIO) {
            $rutaRelativa = str_replace(
                asset('storage') . '/',
                '',
                $mensaje->body
            );

            $rutaCompleta = storage_path(
                'app/public/' . $rutaRelativa
            );

            return app(AudioTranscriptionService::class)
                ->transcribir($rutaCompleta);
        }

        return null;
    }
}
