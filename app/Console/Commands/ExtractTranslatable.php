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
        @ini_set('pcre.backtrack_limit', '10000000');
        @ini_set('pcre.recursion_limit', '10000000');

        $path = base_path($this->argument('path'));
        $dryRun = (bool) $this->option('dry-run');
        $langCode = $this->option('lang');

        if (!File::isDirectory($path)) {
            $this->error("No existe la carpeta: {$path}");
            return self::FAILURE;
        }

        $files = collect(File::allFiles($path))
            ->filter(fn($f) => str_ends_with($f->getFilename(), '.blade.php'));

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
                $skippedFiles[] = $file->getRelativePathname() . ' — ' . ($this->lastFailReason ?? 'razón desconocida');
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
    private ?string $lastFailReason = null;

    private function processContent(string $content): ?string
    {
        $content = $this->toUtf8($content);

        $placeholders = [];
        $failed = false;

        $protect = function (string $pattern, string $label) use (&$content, &$placeholders, &$failed) {
            if ($failed) {
                return;
            }
            $result = @preg_replace_callback($pattern, function ($m) use (&$placeholders) {
                $key = '@@PROTECTED_' . count($placeholders) . '@@';
                $placeholders[$key] = $m[0];
                return $key;
            }, $content);

            if ($result === null) {
                $failed = true;
                $this->lastFailReason = "regex '{$label}' falló (" . $this->pcreErrorName() . ")";
                return;
            }
            $content = $result;
        };

        $protect('/<script\b[^>]*>.*?<\/script>/is', 'script');
        $protect('/<style\b[^>]*>.*?<\/style>/is', 'style');
        $protect('/\{\{--.*?--\}\}/s', 'comentario-blade');
        $protect('/<!--.*?-->/s', 'comentario-html');
        $protect('/\{\{.*?\}\}/s', 'interpolacion-{{}}');
        $protect('/\{!!.*?!!\}/s', 'interpolacion-{!!!!}');
        $protect('/@[a-zA-Z]+(\([^()]*\))?/s', 'directivas-@');

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
            $this->lastFailReason = 'regex de envoltura de texto falló (' . $this->pcreErrorName() . ')';
            return null;
        }
        $content = $wrapped;

        foreach ($placeholders as $key => $original) {
            $content = str_replace($key, $original, $content);
        }

        if (str_contains($content, '@@PROTECTED_')) {
            $this->lastFailReason = 'quedó un placeholder @@PROTECTED_ sin restaurar';
            return null;
        }

        return $content;
    }

    private function toUtf8(string $content): string
    {
        // Usamos el validador de PCRE (el mismo que exige /u), no mbstring:
        // mb_check_encoding puede decir "válido" para bytes que PCRE sigue
        // rechazando, lo cual explica por qué el fix anterior no ayudó.
        if (@preg_match('//u', $content) === 1) {
            return $content;
        }

        $converted = @mb_convert_encoding($content, 'UTF-8', 'Windows-1252');

        if ($converted !== false && @preg_match('//u', $converted) === 1) {
            return $converted;
        }

        return $content; // no se pudo normalizar; el candado decidirá
    }

    private function pcreErrorName(): string
    {
        return match (preg_last_error()) {
            PREG_INTERNAL_ERROR => 'PREG_INTERNAL_ERROR',
            PREG_BACKTRACK_LIMIT_ERROR => 'PREG_BACKTRACK_LIMIT_ERROR (subir pcre.backtrack_limit)',
            PREG_RECURSION_LIMIT_ERROR => 'PREG_RECURSION_LIMIT_ERROR (subir pcre.recursion_limit)',
            PREG_BAD_UTF8_ERROR => 'PREG_BAD_UTF8_ERROR (bytes UTF-8 inválidos)',
            PREG_BAD_UTF8_OFFSET_ERROR => 'PREG_BAD_UTF8_OFFSET_ERROR',
            PREG_JIT_STACKLIMIT_ERROR => 'PREG_JIT_STACKLIMIT_ERROR',
            default => 'error PCRE desconocido (' . preg_last_error() . ')',
        };
    }
}
