<?php

namespace App\DTO;

class ParsedWhatsAppMessage
{
    public function __construct(
        public readonly ?string $tipo,
        public readonly ?string $body,
        public readonly ?string $header,
        public readonly ?string $tipoHeader,
        public readonly ?string $valorChat,
        public readonly ?string $idMedia,
    ) {}
}
