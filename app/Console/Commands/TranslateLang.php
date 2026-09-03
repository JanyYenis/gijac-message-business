<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class TranslateLang extends Command
{
    protected $signature = 'lang:translate
                            {targets* : Códigos de idioma destino, ej: en de fr ja}
                            {--source=es : Idioma base (debe existir lang/{source}.json)}
                            {--chunk=20 : Cuántos strings enviar por request al modelo}
                            {--retries=2 : Reintentos por chunk si la respuesta no es JSON válido}';

    protected $description = 'Traduce lang/{source}.json a otros idiomas usando un modelo local de Ollama';

    private array $languageNames = [
        'es' => 'Spanish', 'en' => 'English', 'de' => 'German',
        'fr' => 'French', 'ja' => 'Japanese', 'pt' => 'Portuguese',
        'it' => 'Italian', 'zh' => 'Chinese', 'ko' => 'Korean',
    ];

    public function handle(): int
    {
        $source = $this->option('source');
        $sourcePath = base_path("lang/{$source}.json");

        if (!File::exists($sourcePath)) {
            $this->error("No existe lang/{$source}.json. Corre primero: php artisan lang:extract");
            return self::FAILURE;
        }

        $ollamaUrl = rtrim(config('services.ollama.base_url', 'http://localhost:11434'), '/');
        $model = config('services.ollama.model', 'gpt-oss:120b-cloud');

        if (!$this->checkOllama($ollamaUrl)) {
            $this->error("No se pudo conectar a Ollama en {$ollamaUrl}. ¿Está corriendo? Prueba: ollama serve");
            return self::FAILURE;
        }

        $strings = json_decode(File::get($sourcePath), true) ?? [];
        $keys = array_keys($strings);
        $texts = array_values($strings);

        if (empty($texts)) {
            $this->warn("lang/{$source}.json está vacío, nada que traducir.");
            return self::SUCCESS;
        }

        $chunkSize = (int) $this->option('chunk');
        $maxRetries = (int) $this->option('retries');
        $chunks = array_chunk($texts, $chunkSize, true);

        foreach ($this->argument('targets') as $target) {
            $this->info("Traduciendo " . count($texts) . " strings a [{$target}] con modelo {$model}...");
            $bar = $this->output->createProgressBar(count($chunks));
            $bar->start();

            $translatedAll = [];
            $failedChunks = 0;

            foreach ($chunks as $chunkIndex => $chunk) {
                $translated = $this->translateChunkWithRetry(
                    array_values($chunk), $source, $target, $ollamaUrl, $model, $maxRetries
                );

                if ($translated === null) {
                    $failedChunks++;
                    // fallback: deja el texto original si el modelo falla en este chunk
                    $translated = array_values($chunk);
                }

                $translatedAll = array_merge($translatedAll, $translated);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();

            if ($failedChunks > 0) {
                $this->warn("{$failedChunks} de " . count($chunks) . " chunks fallaron y quedaron sin traducir (texto original). Puedes reintentar solo esos ajustando --chunk o revisando el modelo.");
            }

            $result = array_combine($keys, $translatedAll);
            $targetPath = base_path("lang/{$target}.json");

            $existing = File::exists($targetPath)
                ? json_decode(File::get($targetPath), true)
                : [];
            // No sobreescribe ediciones manuales previas
            $merged = array_merge($result, $existing);
            ksort($merged);

            File::put($targetPath, json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $this->info("Guardado lang/{$target}.json (" . count($merged) . " strings)");
        }

        $this->warn('Son traducciones automáticas de un modelo local. Calidad variable según el modelo — revisa textos legales, de marca o con doble sentido.');

        return self::SUCCESS;
    }

    private function checkOllama(string $url): bool
    {
        try {
            return Http::timeout(3)->get("{$url}/api/tags")->successful();
        } catch (\Throwable) {
            return false;
        }
    }

    private function translateChunkWithRetry(
        array $texts, string $source, string $target, string $url, string $model, int $maxRetries
    ): ?array {
        for ($attempt = 0; $attempt <= $maxRetries; $attempt++) {
            $result = $this->translateChunk($texts, $source, $target, $url, $model);

            if ($result !== null && count($result) === count($texts)) {
                return $result;
            }
        }

        return null;
    }

    private function translateChunk(array $texts, string $source, string $target, string $url, string $model): ?array
    {
        $sourceLangName = $this->languageNames[$source] ?? $source;
        $targetLangName = $this->languageNames[$target] ?? $target;

        $numbered = collect($texts)
            ->map(fn ($t, $i) => ($i + 1) . '. ' . $t)
            ->implode("\n");

        $prompt = <<<PROMPT
        Translate the following UI strings from {$sourceLangName} to {$targetLangName}.
        Rules:
        - Return ONLY a JSON array of strings, same order, same count as input.
        - Do not add explanations, numbering, or markdown code fences.
        - Keep placeholders like :name, {0}, %s exactly as they are, untranslated.
        - Keep short UI labels short and natural, not literal word-for-word.

        Input ({$sourceLangName}):
        {$numbered}

        Output (JSON array of {$targetLangName} translations only):
        PROMPT;

        try {
            $response = Http::timeout(120)->post("{$url}/api/generate", [
                'model' => $model,
                'prompt' => $prompt,
                'stream' => false,
                'format' => 'json',
                'options' => ['temperature' => 0.2],
            ]);

            if (!$response->successful()) {
                return null;
            }

            $raw = trim($response->json('response', ''));
            $clean = preg_replace('/^```json\s*|\s*```$/m', '', $raw);
            $decoded = json_decode($clean, true);

            // Algunos modelos devuelven {"translations": [...]} en vez de [...]
            if (is_array($decoded) && isset($decoded['translations'])) {
                $decoded = $decoded['translations'];
            }

            return is_array($decoded) ? array_values($decoded) : null;
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }
}
