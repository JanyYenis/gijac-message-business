<?php

namespace App\Console\Commands\Sistemas;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

/**
 * Exporta el código del proyecto a uno o varios .txt para compartirlo como contexto.
 *
 * Uso:
 *   php artisan project:context                      # todo lo por defecto
 *   php artisan project:context --list               # solo muestra qué entraría y pesos
 *   php artisan project:context --group=backend --group=routes
 *   php artisan project:context --exclude=app/Console --exclude=resources/views/emails
 *   php artisan project:context --path=public/js/app.js --max-kb=150
 *
 * Salida: storage/app/context/project-context-01.txt, -02.txt, ...
 */
class ExportProjectContext extends Command
{
    protected $signature = 'project:context
        {--group=* : backend, routes, db, views, frontend, config, root (opt-in: lang, tests). Por defecto todos los no opt-in}
        {--path=* : Archivos o carpetas extra (relativos a la raíz del proyecto)}
        {--exclude=* : Fragmentos de ruta a excluir}
        {--max-kb=80 : Omite archivos más grandes que esto}
        {--part-kb=250 : Tamaño máximo por parte (0 = un solo archivo)}
        {--output=context : Subcarpeta dentro de storage/app}
        {--list : Solo lista archivos y pesos, no escribe nada}';

    protected $description = 'Genera un resumen del proyecto (controladores, modelos, servicios, vistas, js, css...) en archivos .txt';

    private const GROUPS = [
        'root'     => ['files' => ['composer.json', 'package.json', 'vite.config.js', 'vite.config.ts', 'tailwind.config.js', 'webpack.mix.js']],
        'config'   => ['dirs' => ['config'], 'files' => ['bootstrap/app.php', 'bootstrap/providers.php']],
        'routes'   => ['dirs' => ['routes']],
        'backend'  => ['dirs' => ['app']],
        'db'       => ['dirs' => ['database/migrations', 'database/seeders', 'database/factories']],
        'views'    => ['dirs' => ['resources/views']],
        'frontend' => ['dirs' => ['resources/js', 'resources/css', 'resources/sass', 'resources/scss']],
        'lang'     => ['dirs' => ['lang', 'resources/lang'], 'optin' => true],
        'tests'    => ['dirs' => ['tests'], 'optin' => true],
    ];

    private const EXT = '/\.(php|js|mjs|ts|vue|jsx|tsx|css|scss|sass|json|html)$/i';

    private const ALWAYS_EXCLUDE = ['/vendor/', 'node_modules/', 'public/build/', '.min.js', '.min.css', 'package-lock.json', 'composer.lock'];

    public function handle(): int
    {
        $selected = $this->option('group') ?: array_keys(array_filter(self::GROUPS, fn ($g) => empty($g['optin'])));
        $excludes = array_merge(self::ALWAYS_EXCLUDE, $this->option('exclude'));
        $maxBytes = (int) $this->option('max-kb') * 1024;

        $files = $this->collect($selected, $excludes);

        $entries = [];
        $skipped = [];
        foreach ($files as $rel => [$abs, $group]) {
            $size = filesize($abs);
            if ($size === false) {
                continue;
            }
            if ($maxBytes > 0 && $size > $maxBytes) {
                $skipped[] = [$rel, $size, 'excede --max-kb'];
                continue;
            }
            $raw = file_get_contents($abs);
            if ($raw === false || str_contains($raw, "\0")) {
                $skipped[] = [$rel, $size, 'binario/ilegible'];
                continue;
            }
            $entries[$rel] = ['group' => $group, 'size' => $size, 'content' => $this->redact($raw)];
        }

        if ($this->option('list')) {
            return $this->report($entries, $skipped);
        }

        $this->write($entries, $skipped);

        return self::SUCCESS;
    }

    private function collect(array $selected, array $excludes): array
    {
        $files = [];

        $add = function (string $rel, string $abs, string $group) use (&$files, $excludes) {
            $rel = ltrim(str_replace('\\', '/', $rel), '/');
            foreach ($excludes as $frag) {
                if ($frag !== '' && str_contains('/' . $rel, $frag)) {
                    return;
                }
            }
            // Nunca archivos sensibles
            if (preg_match('#(^|/)(\.env[^/]*|auth\.json)$|\.(pem|key|p12|pfx)$#i', $rel)) {
                return;
            }
            $files[$rel] = [$abs, $group];
        };

        foreach ($selected as $g) {
            $def = self::GROUPS[$g] ?? null;
            if (! $def) {
                $this->warn("Grupo desconocido: {$g}");
                continue;
            }
            foreach ($def['files'] ?? [] as $f) {
                if (is_file(base_path($f))) {
                    $add($f, base_path($f), $g);
                }
            }
            foreach ($def['dirs'] ?? [] as $d) {
                $this->scanDir($d, $g, $add);
            }
        }

        foreach ($this->option('path') as $p) {
            $abs = base_path($p);
            if (is_file($abs)) {
                $add($p, $abs, 'extra');
            } elseif (is_dir($abs)) {
                $this->scanDir($p, 'extra', $add);
            } else {
                $this->warn("No existe: {$p}");
            }
        }

        ksort($files);

        return $files;
    }

    private function scanDir(string $dir, string $group, callable $add): void
    {
        $abs = base_path($dir);
        if (! is_dir($abs)) {
            return;
        }
        $finder = Finder::create()->files()->in($abs)->ignoreUnreadableDirs()->name(self::EXT);
        foreach ($finder as $file) {
            $add($dir . '/' . $file->getRelativePathname(), $file->getRealPath(), $group);
        }
    }

    /** Oculta valores que parecen secretos escritos a mano en el código. */
    private function redact(string $c): string
    {
        $c = preg_replace(
            '/((?:secret|passw(?:or)?d|token|api[_-]?key|private[_-]?key|authorization)[\w\-]*[\'"]?\s*(?:=>|=|:)\s*)([\'"])(?![^\'"\n]*\|)[^\'"\n\s]{8,}\2/i',
            '$1$2***REDACTED***$2',
            $c
        ) ?? $c;

        return preg_replace('/\b(EAA[A-Za-z0-9]{40,}|sk-[A-Za-z0-9]{20,}|AIza[0-9A-Za-z_\-]{30,})\b/', '***REDACTED***', $c) ?? $c;
    }

    private function report(array $entries, array $skipped): int
    {
        $byGroup = [];
        foreach ($entries as $e) {
            $byGroup[$e['group']]['n'] = ($byGroup[$e['group']]['n'] ?? 0) + 1;
            $byGroup[$e['group']]['kb'] = ($byGroup[$e['group']]['kb'] ?? 0) + $e['size'] / 1024;
        }
        $rows = [];
        foreach ($byGroup as $g => $v) {
            $rows[] = [$g, $v['n'], round($v['kb'], 1)];
        }
        $this->table(['Grupo', 'Archivos', 'KB'], $rows);

        $sizes = array_map(fn ($e) => $e['size'], $entries);
        arsort($sizes);
        $this->line('Los 10 más pesados:');
        foreach (array_slice($sizes, 0, 10, true) as $rel => $s) {
            $this->line(sprintf('  %6.1f KB  %s', $s / 1024, $rel));
        }
        foreach ($skipped as [$rel, $s, $why]) {
            $this->line(sprintf('  OMITIDO (%s) %6.1f KB  %s', $why, $s / 1024, $rel));
        }
        $total = array_sum($sizes);
        $this->info(sprintf('Total: %d archivos, %.0f KB (~%s tokens)', count($entries), $total / 1024, number_format($total / 4)));

        return self::SUCCESS;
    }

    private function write(array $entries, array $skipped): void
    {
        $dir = storage_path('app/' . trim($this->option('output'), '/'));
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        foreach (glob($dir . '/project-context-*.txt') ?: [] as $old) {
            unlink($old);
        }

        // Índice (va en la parte 1)
        $index = '';
        foreach ($entries as $rel => $e) {
            $index .= sprintf("- [%s] %s (%.1f KB)\n", $e['group'], $rel, $e['size'] / 1024);
        }
        foreach ($skipped as [$rel, $s, $why]) {
            $index .= sprintf("- [OMITIDO: %s] %s (%.1f KB)\n", $why, $rel, $s / 1024);
        }

        $head = sprintf(
            "# CONTEXTO DEL PROYECTO: %s\n# Generado: %s | Laravel %s | PHP %s\n# Cada archivo va entre '===== FILE: ruta =====' y '===== END FILE ====='.\n# Valores que parecían secretos fueron reemplazados por ***REDACTED***.\n\n## ÍNDICE (%d archivos)\n%s\n",
            config('app.name'),
            now()->toDateTimeString(),
            app()->version(),
            PHP_VERSION,
            count($entries),
            $index
        );

        $partBytes = (int) $this->option('part-kb') * 1024;
        $parts = [$head];
        $i = 0;
        foreach ($entries as $rel => $e) {
            $lines = substr_count($e['content'], "\n") + 1;
            $block = "\n===== FILE: {$rel} ({$lines} líneas) =====\n" . rtrim($e['content']) . "\n===== END FILE =====\n";
            if ($partBytes > 0 && strlen($parts[$i]) > 0 && strlen($parts[$i]) + strlen($block) > $partBytes && $parts[$i] !== $head) {
                $parts[++$i] = '';
            }
            $parts[$i] .= $block;
        }

        $n = count($parts);
        $total = 0;
        foreach ($parts as $k => $body) {
            $name = sprintf('%s/project-context-%02d.txt', $dir, $k + 1);
            $body = sprintf("# PARTE %d de %d\n", $k + 1, $n) . $body;
            file_put_contents($name, $body);
            $total += strlen($body);
            $this->line(sprintf('  %s (%.0f KB)', basename($name), strlen($body) / 1024));
        }

        $this->info(sprintf('Listo: %d archivos en %d parte(s), ~%s tokens. Carpeta: %s', count($entries), $n, number_format($total / 4), $dir));
        $this->comment('No subas esa carpeta a git ni la dejes en public/. storage/app/ ya está ignorado por defecto.');
    }
}
