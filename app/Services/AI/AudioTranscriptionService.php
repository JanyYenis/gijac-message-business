<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class AudioTranscriptionService
{
    public function transcribir(string $rutaAudio): ?string
    {
        if (!file_exists($rutaAudio)) {
            return null;
        }

        $audioBase64 = base64_encode(file_get_contents($rutaAudio));

        $response = Http::post(
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . env('GEMINI_API_KEY'),
            [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => 'Transcribe este audio a texto. Devuelve únicamente la transcripción.'
                            ],
                            [
                                'inline_data' => [
                                    'mime_type' => 'audio/mpeg',
                                    'data' => $audioBase64,
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );

        if (!$response->successful()) {
            return null;
        }

        return data_get(
            $response->json(),
            'candidates.0.content.parts.0.text'
        );
    }
}
