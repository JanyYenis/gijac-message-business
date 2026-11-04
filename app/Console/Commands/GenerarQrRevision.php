<?php

namespace App\Console\Commands;

use App\Models\Dispositivo;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Genera un QR de acceso para los revisores de Google Play / App Store.
 *
 *   php artisan app:qr-revision usuario@demo.com
 *   php artisan app:qr-revision <uuid-del-usuario> --dias=120
 *   php artisan app:qr-revision usuario@demo.com --revocar
 *
 * Usa el mismo flujo de /device-link: es un token de dispositivo normal,
 * pero con vencimiento largo y reutilizable por varios revisores.
 */
class GenerarQrRevision extends Command
{
    protected $signature = 'app:qr-revision
                            {usuario : Correo o UUID del usuario demo}
                            {--dias=90 : Días de vigencia del QR}
                            {--revocar : Borra todos los dispositivos/QR de ese usuario demo}';

    protected $description = 'Genera (o revoca) un QR de larga duración para la revisión de las tiendas';

    private const NOMBRE = 'REVISION-TIENDAS';

    public function handle(): int
    {
        $modelo = config('auth.providers.users.model');
        $valor = $this->argument('usuario');

        $usuario = null;
        try {
            $usuario = $modelo::where('email', $valor)->first();
        } catch (\Throwable $e) {
            // la tabla no tiene columna email: se busca solo por UUID
        }
        $usuario ??= $modelo::where('uuid', $valor)->first();

        if (!$usuario) {
            $this->error('No se encontró el usuario.');
            return self::FAILURE;
        }

        if ($this->option('revocar')) {
            // Al escanear, la app sobrescribe nombre/modelo, así que se revocan todos los dispositivos del usuario demo
            $n = Dispositivo::where('usuario_id', $usuario->uuid)->delete();
            $this->info("Dispositivos revocados: {$n}. La app de los revisores cerrará sesión en su próxima petición.");
            return self::SUCCESS;
        }

        $dias = max(1, (int) $this->option('dias'));

        $dispositivo = Dispositivo::create([
            'usuario_id'         => $usuario->uuid,
            'token'              => (string) Str::uuid(),
            'expira_en'          => now()->addDays($dias),
            'nombre_dispositivo' => self::NOMBRE,
            'modelo'             => self::NOMBRE,
        ]);

        $url = config('app.url')
            . '/device-link?token=' . urlencode($dispositivo->token)
            . '&server=' . urlencode(config('app.url'));

        $ruta = storage_path('app/qr-revision-' . now()->format('Ymd-His') . '.png');

        $resultado = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->size(700)
            ->margin(20)
            ->build();
        $resultado->saveToFile($ruta);

        $this->info('QR generado.');
        $this->line("Usuario:   {$usuario->uuid}");
        $this->line('Vence:     ' . $dispositivo->expira_en->format('Y-m-d'));
        $this->line("Imagen:    {$ruta}");
        $this->line("Contenido: {$url}");

        return self::SUCCESS;
    }
}
