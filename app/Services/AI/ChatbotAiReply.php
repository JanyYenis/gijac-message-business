<?php

namespace App\Services\AI;

use App\Models\Chatbots\ChatbotAiAssistant as Asistente;

/**
 * Respuestas del asistente IA con botones / listas de WhatsApp.
 * El modelo responde JSON (restringido por un esquema); aquí se valida, se recorta a los límites
 * de WhatsApp y se arma el mensaje interactivo de la Cloud API.
 */
class ChatbotAiReply
{
    /** ¿El asistente tiene botones o listas activos? (entonces el modelo responde en JSON) */
    public static function interactivo(array $cap): bool
    {
        return in_array(Asistente::ENVIAR_BOTONES, $cap) || in_array(Asistente::ENVIAR_LISTAS, $cap);
    }

    /** Esquema JSON que se le exige a Ollama. Null si el asistente responde en texto plano. */
    public static function esquema(array $cap): ?array
    {
        if (! self::estructurado($cap)) {
            return null;
        }

        $props = ['texto' => ['type' => 'string']];

        if (in_array(Asistente::ENVIAR_BOTONES, $cap)) {
            $props['botones'] = ['type' => 'array', 'items' => ['type' => 'string'], 'maxItems' => 3];
        }

        if (in_array(Asistente::ENVIAR_LISTAS, $cap)) {
            $props['lista'] = [
                'type'       => 'object',
                'properties' => [
                    'boton'    => ['type' => 'string'],
                    'opciones' => [
                        'type'     => 'array',
                        'maxItems' => 10,
                        'items'    => [
                            'type'       => 'object',
                            'properties' => ['titulo' => ['type' => 'string'], 'descripcion' => ['type' => 'string']],
                            'required'   => ['titulo'],
                        ],
                    ],
                ],
                'required'   => ['opciones'],
            ];
        }

        if (in_array(Asistente::TRANSFERIR_A_AGENTE_HUMANO, $cap)) {
            $props['transferir'] = ['type' => 'boolean'];
        }

        return ['type' => 'object', 'properties' => $props, 'required' => ['texto']];
    }

    /**
     * Convierte la salida del modelo en ['texto','botones','lista','transferir'] ya recortada a los límites de WhatsApp.
     * Si no es JSON válido, se usa tal cual como texto.
     */
    public static function interpretar(string $raw, array $cap, bool $estructurado): array
    {
        $vacio = ['texto' => trim($raw), 'botones' => [], 'lista' => null, 'transferir' => false];

        if (! $estructurado) {
            return $vacio;
        }

        $json = json_decode($raw, true);
        if (! is_array($json) || ! is_string($json['texto'] ?? null)) {
            // JSON cortado o inválido: se rescata el "texto" si se alcanza a leer; nunca se envía JSON crudo al cliente
            if (preg_match('/"texto"\s*:\s*"((?:[^"\\\\]|\\\\.)*)"/s', $raw, $m)) {
                $vacio['texto'] = trim((string) (json_decode('"' . $m[1] . '"') ?? $m[1]));
            } elseif (str_starts_with(ltrim($raw), '{')) {
                $vacio['texto'] = '';
            }

            return $vacio;
        }

        $botones = [];
        if (in_array(Asistente::ENVIAR_BOTONES, $cap)) {
            $botones = collect($json['botones'] ?? [])
                ->filter(fn ($b) => is_string($b))
                ->map(fn ($b) => mb_substr(trim($b), 0, 20))
                ->filter()
                ->reject(fn ($b) => self::generico($b))
                ->unique()->take(3)->values()->all();

            if (count($botones) < 2) {
                $botones = []; // un solo botón no es una elección
            }
        }

        $lista = null;
        if (in_array(Asistente::ENVIAR_LISTAS, $cap) && is_array($json['lista'] ?? null)) {
            $opciones = collect($json['lista']['opciones'] ?? [])
                ->filter(fn ($o) => is_array($o) && is_string($o['titulo'] ?? null) && trim($o['titulo']) !== '')
                ->map(fn ($o) => [
                    'titulo'      => mb_substr(trim($o['titulo']), 0, 24),
                    'descripcion' => is_string($o['descripcion'] ?? null) && trim($o['descripcion']) !== '' ? mb_substr(trim($o['descripcion']), 0, 72) : null,
                ])
                ->reject(fn ($o) => self::generico($o['titulo']))
                ->unique('titulo')->take(10)->values()->all();

            if (count($opciones) >= 2) {
                $boton = trim((string) ($json['lista']['boton'] ?? ''));
                $lista = ['boton' => mb_substr($boton !== '' ? $boton : 'Ver opciones', 0, 20), 'opciones' => $opciones];
                $botones = []; // la lista tiene prioridad
            }
        }

        // Una lista de 3 opciones o menos (títulos cortos) se envía como botones: es más cómodo para el cliente
        if ($lista && count($lista['opciones']) <= 3 && in_array(Asistente::ENVIAR_BOTONES, $cap)) {
            $titulos = array_column($lista['opciones'], 'titulo');
            if (collect($titulos)->every(fn ($t) => mb_strlen($t) <= 20)) {
                $botones = $titulos;
                $lista   = null;
            }
        }

        return [
            'texto'      => trim($json['texto']),
            'botones'    => $botones,
            'lista'      => $lista,
            'transferir' => in_array(Asistente::TRANSFERIR_A_AGENTE_HUMANO, $cap) && ($json['transferir'] ?? false) === true,
        ];
    }

    /** Mensaje interactivo para POST /{phone_number_id}/messages. Null si no hay botones ni lista. */
    public static function payloadWhatsApp(string $to, array $r): ?array
    {
        $body = mb_substr($r['texto'], 0, 1024);

        if ($r['lista']) {
            $rows = [];
            foreach ($r['lista']['opciones'] as $i => $o) {
                $rows[] = array_filter(['id' => 'ia:' . $o['titulo'], 'title' => $o['titulo'], 'description' => $o['descripcion']]);
            }
            $interactive = [
                'type'   => 'list',
                'body'   => ['text' => $body],
                'action' => ['button' => $r['lista']['boton'], 'sections' => [['title' => 'Opciones', 'rows' => $rows]]],
            ];
        } elseif ($r['botones']) {
            $interactive = [
                'type'   => 'button',
                'body'   => ['text' => $body],
                'action' => ['buttons' => collect($r['botones'])->map(fn ($b) => [
                    'type'  => 'reply',
                    'reply' => ['id' => 'ia:' . $b, 'title' => $b],
                ])->values()->all()],
            ];
        } else {
            return null;
        }

        return [
            'messaging_product' => 'whatsapp',
            'recipient_type'    => 'individual',
            'to'                => $to,
            'type'              => 'interactive',
            'interactive'       => $interactive,
        ];
    }

    /** Versión en texto (para guardar en el chat o como respaldo si falla el interactivo). */
    public static function comoTexto(array $r): string
    {
        $opciones = $r['lista'] ? array_column($r['lista']['opciones'], 'titulo') : $r['botones'];

        return $opciones
            ? $r['texto'] . "\n\n" . implode("\n", array_map(fn ($o) => '• ' . $o, $opciones))
            : $r['texto'];
    }

    /** ¿El modelo responde en JSON? (botones, listas o decisión de transferir) */
    public static function estructurado(array $cap): bool
    {
        return self::interactivo($cap) || in_array(Asistente::TRANSFERIR_A_AGENTE_HUMANO, $cap);
    }

    private static function generico(string $titulo): bool
    {
        return (bool) preg_match('/^(opci[oó]n|opcion|option|bot[oó]n)\s*\d*$/iu', trim($titulo));
    }
}
