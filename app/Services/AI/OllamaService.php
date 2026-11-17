<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OllamaService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.ollama.base_url', 'http://127.0.0.1:11434'), '/');
    }

    public function listarModelos(): array
    {
        $response = Http::timeout(10)->get("{$this->baseUrl}/api/tags");

        if (!$response->successful()) {
            return [];
        }

        return collect($response->json('models', []))
            ->filter(fn ($m) => !str_starts_with($m['name'], 'EstebanEscobar/'))
            ->map(fn ($m) => [
                'id'   => $m['name'],
                'name' => $m['name'],
                'size' => $m['size'] ?? null,
            ])
            ->values()
            ->all();
    }

    public function chat(string $systemPrompt, array $mensajes, string $modelo, array $opciones = []): string
    {
        $payload = [
            'model'    => $modelo,
            'messages' => array_merge(
                [['role' => 'system', 'content' => $this->limpiarUtf8($systemPrompt)]],
                array_map(fn ($m) => [
                    'role'    => $m['role'],
                    'content' => $this->limpiarUtf8($m['content']),
                ], $mensajes)
            ),
            'stream'   => false,
            'options'  => [
                'temperature' => $opciones['temperature'] ?? 0.7,
            ],
        ];

        if (!empty($opciones['format'])) {
            $payload['format'] = $opciones['format']; // esquema JSON (botones/listas)
        }

        $response = Http::timeout(60)->post("{$this->baseUrl}/api/chat", $payload);

        if (!$response->successful()) {
            throw new RuntimeException('Ollama no respondió correctamente: ' . $response->body());
        }

        $contenido = $response->json('message.content', '');

        return trim(preg_replace('/<think>.*?<\/think>/s', '', $contenido));
    }

    private function limpiarUtf8(string $texto): string
    {
        $texto = mb_convert_encoding($texto, 'UTF-8', 'UTF-8');
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $texto);
    }
}
