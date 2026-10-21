<?php

namespace App\Services\Meta;

use Illuminate\Http\Client\Response;

/**
 * Adaptador: expone la misma interfaz mínima que la respuesta de netflie/whatsapp-cloud-api
 * (isError, decodedBody, httpStatusCode, body) para que SendWhatsAppMessage maneje igual
 * un envío por Cloud API y uno por Marketing Messages API.
 */
class MensajeRespuesta
{
    public function __construct(private Response $respuesta)
    {
    }

    public function isError(): bool
    {
        return $this->respuesta->failed();
    }

    public function decodedBody(): array
    {
        return $this->respuesta->json() ?? [];
    }

    public function httpStatusCode(): int
    {
        return $this->respuesta->status();
    }

    public function body(): string
    {
        return $this->respuesta->body();
    }
}
