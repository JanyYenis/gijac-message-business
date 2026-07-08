<?php

namespace App\Services\WhatsApp;

use App\DTO\ParsedWhatsAppMessage;
use App\Models\Mensaje;
use Illuminate\Support\Facades\Storage;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

class IncomingMessageParser
{
    public function parse(array $mensajeData, int $tipoMapped, string $tipoOriginal, WhatsAppCloudApi $api): ParsedWhatsAppMessage
    {
        return match (true) {
            $tipoMapped === Mensaje::TEXTO => $this->parseTexto($mensajeData),
            $tipoMapped === Mensaje::IMAGEN => $this->parseMedia($mensajeData, 'image', 'chats/img', 'jpg', $api),
            $tipoMapped === Mensaje::VIDEO  => $this->parseMedia($mensajeData, 'video', 'chats/videos', 'mp4', $api),
            $tipoMapped === Mensaje::DOCUMENTO => $this->parseDocumento($mensajeData, $api),
            $tipoMapped === Mensaje::AUDIO  => $this->parseAudio($mensajeData, $api),
            $tipoOriginal === 'interactive'  => $this->parseInteractive($mensajeData),
            $tipoMapped === Mensaje::REACCION => $this->parseReaccion($mensajeData),
            $tipoMapped === Mensaje::CONTACTO => $this->parseContacto($mensajeData),
            default => new ParsedWhatsAppMessage($tipoMapped, null, null, null, null, null),
        };
    }

    private function parseTexto(array $d): ParsedWhatsAppMessage
    {
        return new ParsedWhatsAppMessage(Mensaje::TEXTO, $d['text']['body'] ?? 'N/A', null, null, null, null);
    }

    private function parseMedia(array $d, string $key, string $dir, string $ext, WhatsAppCloudApi $api): ParsedWhatsAppMessage
    {
        $idMedia = $d[$key]['id'] ?? null;
        $header = null;

        if ($idMedia) {
            $response = $api->downloadMedia($idMedia);
            Storage::disk('public')->put(
                "chats/{$dir}/{$idMedia}.{$ext}",
                $response->body()
            );

            $header = Storage::disk('public')->url(
                "{$dir}/{$idMedia}.{$ext}"
            );
        }

        $tipoMapped = match ($key) {
            'image' => Mensaje::IMAGEN,
            'video' => Mensaje::VIDEO,
        };

        return new ParsedWhatsAppMessage($tipoMapped, $d[$key]['caption'] ?? null, $header, strtoupper($key), null, $idMedia);
    }

    private function parseDocumento(array $d, WhatsAppCloudApi $api): ParsedWhatsAppMessage
    {
        $idMedia = $d['document']['id'] ?? null;
        $nombreDoc = $d['document']['filename'] ?? ($idMedia . '.pdf');
        $header = null;

        if ($idMedia) {
            $response = $api->downloadMedia($idMedia);
            Storage::disk('public')->put(
                "chats/documentos/{$nombreDoc}",
                $response->body()
            );

            $header = Storage::disk('public')->url(
                "chats/documentos/{$nombreDoc}"
            );
        }

        return new ParsedWhatsAppMessage(Mensaje::DOCUMENTO, $d['document']['caption'] ?? null, $header, 'DOCUMENT', null, $idMedia);
    }

    private function parseAudio(array $d, WhatsAppCloudApi $api): ParsedWhatsAppMessage
    {
        $idMedia = $d['audio']['id'] ?? null;
        $header = null;

        if ($idMedia) {
            $response = $api->downloadMedia($idMedia);
            Storage::disk('public')->put(
                "chats/audios/{$idMedia}.mp3",
                $response->body()
            );

            $header = Storage::disk('public')->url(
                "chats/audios/{$idMedia}.mp3"
            );
        }

        return new ParsedWhatsAppMessage(Mensaje::AUDIO, $header, $header, 'AUDIO', null, $idMedia);
    }

    private function parseInteractive(array $d): ParsedWhatsAppMessage
    {
        if (isset($d['interactive']['button_reply'])) {
            return new ParsedWhatsAppMessage(
                Mensaje::INTERACCION_BOTON,
                $d['interactive']['button_reply']['title'] ?? null,
                null, null,
                $d['interactive']['button_reply']['id'] ?? null,
                null
            );
        }

        if (isset($d['interactive']['list_reply'])) {
            return new ParsedWhatsAppMessage(
                Mensaje::INTERACCION_LISTADO,
                $d['interactive']['list_reply']['description'] ?? $d['interactive']['list_reply']['title'] ?? null,
                null, null,
                $d['interactive']['list_reply']['id'] ?? null,
                null
            );
        }

        if (isset($d['interactive']['nfm_reply'])) {
            return new ParsedWhatsAppMessage(Mensaje::FLOWS, $d['interactive']['nfm_reply']['response_json'] ?? null, null, null, null, null);
        }

        return new ParsedWhatsAppMessage(null, null, null, null, null, null);
    }

    private function parseReaccion(array $d): ParsedWhatsAppMessage
    {
        return new ParsedWhatsAppMessage(Mensaje::REACCION, $d['reaction']['emoji'] ?? null, null, null, $d['reaction']['message_id'] ?? null, null);
    }

    private function parseContacto(array $d): ParsedWhatsAppMessage
    {
        return new ParsedWhatsAppMessage(Mensaje::CONTACTO, json_encode($d['contacts'] ?? []), null, null, null, null);
    }
}
