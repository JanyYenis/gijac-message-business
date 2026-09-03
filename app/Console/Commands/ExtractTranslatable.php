<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExtractTranslatable extends Command
{
    protected $signature = 'lang:extract
                            {path=resources/views : Carpeta a escanear}
                            {--dry-run : Solo muestra qué cambiaría, no escribe nada}
                            {--lang=es : Idioma base para lang/{lang}.json}';

    protected $description = 'Escanea vistas Blade, envuelve texto plano en __() y genera lang/{lang}.json';

    private array $strings = [];

    public function handle(): int
    {
        $path = base_path($this->argument('path'));
        $dryRun = (bool) $this->option('dry-run');
        $langCode = $this->option('lang');

        if (!File::isDirectory($path)) {
            $this->error("No existe la carpeta: {$path}");
            return self::FAILURE;
        }

        $files = collect(File::allFiles($path))
            ->filter(fn ($f) => str_ends_with($f->getFilename(), '.blade.php'));

        $this->info("Encontrados {$files->count()} archivos .blade.php");

        $modifiedCount = 0;
        $skippedFiles = [];

        foreach ($files as $file) {
            $original = File::get($file->getPathname());
            $this->strings = []; // strings de este archivo únicamente

            $modified = $this->processContent($original);

            // CANDADO DE SEGURIDAD: si algo falló (regex null o placeholders
            // sin restaurar), NUNCA tocamos el archivo. Mejor no traducir
            // ese archivo que corromperlo.
            if ($modified === null) {
                $skippedFiles[] = $file->getRelativePathname();
                continue;
            }

            if ($modified !== $original) {
                $modifiedCount++;
                $this->line("Modificado: {$file->getRelativePathname()}");

                if (!$dryRun) {
                    File::put($file->getPathname(), $modified);
                }
            }
        }

        if (!empty($this->strings)) {
            $langPath = base_path("lang/{$langCode}.json");
            $existing = File::exists($langPath)
                ? json_decode(File::get($langPath), true)
                : [];

            $merged = array_merge($existing, $this->strings);
            ksort($merged);

            if (!$dryRun) {
                File::ensureDirectoryExists(dirname($langPath));
                File::put($langPath, json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }

        $this->info(($dryRun ? 'Se modificarían ' : 'Se modificaron ') . $modifiedCount . ' archivos.');

        if (!empty($skippedFiles)) {
            $this->newLine();
            $this->error(count($skippedFiles) . ' archivo(s) se SALTARON por seguridad (no se tocaron, quedaron 100% intactos):');
            foreach ($skippedFiles as $f) {
                $this->line("  - {$f}");
            }
            $this->warn('Estos archivos probablemente tienen estructuras Blade complejas (ej. @foreach/@if anidados dentro de texto) que el regex no puede procesar con seguridad. Revísalos manualmente o dime cuáles son para ajustar el patrón.');
        }

        if ($dryRun) {
            $this->warn('Corre sin --dry-run para aplicar los cambios.');
        } else {
            $this->warn('Revisa el diff con git antes de commitear.');
        }

        return self::SUCCESS;
    }

    /**
     * Devuelve el contenido procesado, o null si NO es seguro aplicar el
     * cambio (algún paso de regex falló, o quedó algún placeholder sin
     * restaurar). null siempre significa "no tocar este archivo".
     */
    private function processContent(string $content): ?string
    {
        $placeholders = [];
        $failed = false;

        $protect = function (string $pattern) use (&$content, &$placeholders, &$failed) {
            if ($failed) {
                return;
            }

            $result = @preg_replace_callback($pattern, function ($m) use (&$placeholders) {
                $key = '@@PROTECTED_' . count($placeholders) . '@@';
                $placeholders[$key] = $m[0];
                return $key;
            }, $content);

            // preg_replace_callback devuelve null si el motor de regex falla
            // (backtrack limit, UTF-8 inválido, etc.). Si pasa, abortamos
            // este archivo entero en vez de seguir con contenido corrupto.
            if ($result === null) {
                $failed = true;
                return;
            }

            $content = $result;
        };

        $protect('/<script\b[^>]*>.*?<\/script>/is');
        $protect('/<style\b[^>]*>.*?<\/style>/is');
        $protect('/\{\{--.*?--\}\}/s');
        $protect('/<!--.*?-->/s');
        $protect('/\{\{.*?\}\}/s');
        $protect('/\{!!.*?!!\}/s');
        // Regex simplificado (un solo nivel de paréntesis) para evitar
        // backtracking catastrófico en archivos con muchas directivas anidadas.
        $protect('/@[a-zA-Z]+(\([^()]*\))?/s');

        if ($failed) {
            return null;
        }

        $wrapped = @preg_replace_callback('/>([^<>{}\n]{2,})</u', function ($m) {
            $raw = $m[1];
            $text = trim($raw);

            if ($text === '' || str_contains($text, '@@PROTECTED_')) {
                return $m[0];
            }
            if (!preg_match('/\p{L}{2,}/u', $text)) {
                return $m[0];
            }

            preg_match('/^\s*/', $raw, $lead);
            preg_match('/\s*$/', $raw, $trail);

            $escaped = str_replace("'", "\\'", $text);
            $this->strings[$text] = $text;

            return '>' . $lead[0] . "{{ __('{$escaped}') }}" . $trail[0] . '<';
        }, $content);

        if ($wrapped === null) {
            return null;
        }
        $content = $wrapped;

        foreach ($placeholders as $key => $original) {
            $content = str_replace($key, $original, $content);
        }

        // CANDADO FINAL: si por cualquier razón quedó un placeholder sin
        // restaurar, NO devolvemos este contenido. Es preferible dejar el
        // archivo sin tocar que guardarlo corrupto.
        if (str_contains($content, '@@PROTECTED_')) {
            return null;
        }

        return $content;
    }
}
