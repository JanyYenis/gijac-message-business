<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CampanaAiService
{
    public function analizar(
        string $mensaje,
        array $estadisticas = []
    ): ?array {

        $prompt = $this->generarPrompt(
            $mensaje,
            $estadisticas
        );

        try {

            $response = Http::timeout(60)
                ->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . env('GEMINI_API_KEY'),
                    [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => $prompt
                                    ]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.5,
                            'responseMimeType' => 'application/json'
                        ]
                    ]
                );

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();

            $texto = data_get(
                $data,
                'candidates.0.content.parts.0.text'
            );

            if (!$texto) {
                return null;
            }

            return json_decode(
                $texto,
                true
            );
        } catch (\Throwable $e) {

            Log::error(
                'Error IA Campaña',
                [
                    'error' => $e->getMessage()
                ]
            );

            return null;
        }
    }

    private function generarPrompt(
        string $mensaje,
        array $estadisticas
    ): string {

        return "
Analiza el siguiente mensaje de WhatsApp Marketing.

MENSAJE:
{$mensaje}

ESTADISTICAS:
" . json_encode($estadisticas) . "

Devuelve únicamente JSON válido con esta estructura:

{
    \"explicacion\": \"...\",
    \"probabilidad_apertura\": 0,
    \"nivel\": \"Alta\",
    \"mejor_hora\": \"09:00\",
    \"sugerencias\": [
        \"...\",
        \"...\"
    ],
    \"mensaje_mejorado\": \"...\"
}
";
    }
}
