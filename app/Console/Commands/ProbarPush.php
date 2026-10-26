<?php

namespace App\Console\Commands;

use App\Models\Dispositivo;
use App\Models\Usuario;
use App\Services\Fcm\FcmService;
use Illuminate\Console\Command;

/** Diagnóstico de notificaciones push: php artisan fcm:probar correo@usuario.com */
class ProbarPush extends Command
{
    protected $signature = 'fcm:probar {email : Correo del usuario}';
    protected $description = 'Envía una notificación de prueba a los dispositivos móviles de un usuario';

    public function handle(): int
    {
        $usuario = Usuario::where('email', $this->argument('email'))->first();
        if (! $usuario) {
            $this->error('No existe un usuario con ese correo.');

            return self::FAILURE;
        }

        $dispositivos = Dispositivo::where('usuario_id', $usuario->uuid)->get();
        $this->info("Dispositivos del usuario: {$dispositivos->count()}");

        foreach ($dispositivos as $d) {
            $this->line("- {$d->nombre_dispositivo} ({$d->modelo}) · fcm_token: " . ($d->fcm_token ? substr($d->fcm_token, 0, 20) . '…' : 'VACÍO'));
            if (! $d->fcm_token) {
                $this->warn('  Sin token: la app no lo ha registrado (¿ruta POST /api/mobile/device/fcm?, ¿permiso de notificaciones?).');
                continue;
            }
            $res = FcmService::enviar($d->fcm_token, 'Prueba GIJAC', 'Si ves esto, las notificaciones funcionan', ['tipo' => 'prueba']);
            $res === FcmService::OK
                ? $this->info('  ✔ Enviada')
                : $this->error("  ✘ {$res}: " . (FcmService::$ultimoError ?? 'sin detalle'));
        }

        return self::SUCCESS;
    }
}
