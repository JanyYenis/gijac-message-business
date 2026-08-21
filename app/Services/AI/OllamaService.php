<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OllamaService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.ollama.base_url', 'http://localhost:11434'), '/');
    }

    public function listarModelos(): array
    {
        $response = Http::timeout(10)->get("{$this->baseUrl}/api/tags");

        if (!$response->successful()) {
            return [];
        }

        return collect($response->json('models', []))
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
                [['role' => 'system', 'content' => $systemPrompt]],
                $mensajes
            ),
            'stream'   => false,
            'options'  => [
                'temperature' => $opciones['temperature'] ?? 0.7,
            ],
        ];

        $response = Http::timeout(60)->post("{$this->baseUrl}/api/chat", $payload);

        if (!$response->successful()) {
            throw new RuntimeException('Ollama no respondió correctamente: ' . $response->body());
        }

        return $response->json('message.content', '');
    }
}
