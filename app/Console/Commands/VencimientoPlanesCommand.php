<?php

namespace App\Console\Commands;

use App\Models\Empresa;
use App\Models\Factura;
use App\Models\Usuario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Se ejecuta una vez al día:
 *  1. Avisa por correo a las empresas cuyo plan vence en 5 días y en 1 día.
 *  2. Sincroniza Usuario.cod_plan con la factura vigente de su empresa
 *     (si el plan venció queda en null; si cambió de plan se actualiza).
 *
 * Uso: php artisan planes:vencimientos [--dry-run]
 */
class VencimientoPlanesCommand extends Command
{
    protected $signature = 'planes:vencimientos {--dry-run : Muestra lo que haría sin enviar correos ni modificar datos}';

    protected $description = 'Avisa de planes por vencer y sincroniza el plan de los usuarios con la factura vigente';

    private const DIAS_AVISO = [5, 1];

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');

        $this->avisarVencimientos($dry);
        $this->sincronizarPlanUsuarios($dry);

        return self::SUCCESS;
    }

    private function avisarVencimientos(bool $dry): void
    {
        foreach (self::DIAS_AVISO as $dias) {
            $fecha = now()->addDays($dias)->toDateString();

            $empresas = Factura::where('x_response', 'Aceptada')
                ->whereDate('fecha_vencimiento', $fecha)
                ->whereNotNull('cod_empresa')
                ->pluck('cod_empresa')
                ->unique();

            foreach ($empresas as $empresaId) {
                $empresa = Empresa::find($empresaId);
                $vigente = $empresa?->facturaVigente;

                // Solo avisar si ESTA es la factura que realmente marca el vencimiento (no hay una más reciente que lo extienda)
                if (!$vigente || $vigente->fecha_vencimiento?->toDateString() !== $fecha) {
                    continue;
                }

                $destino = $empresa->email
                    ?: Usuario::where('uuid', $empresa->cod_usuario)->value('email');

                if (!$destino) {
                    Log::warning('Aviso de vencimiento sin correo de destino', ['empresa' => $empresaId]);
                    continue;
                }

                $this->line(sprintf('[%s día(s)] %s -> %s', $dias, $empresa->razon_social, $destino));

                if ($dry) {
                    continue;
                }

                try {
                    $link = route('facturas.pago', ['plan' => $vigente->cod_plan]);
                    $texto = "Hola,\n\nTu plan en " . config('app.name') . " vence el " . $fecha
                        . " (en {$dias} día(s)). Para no perder el acceso a tus funciones, renueva aquí:\n{$link}\n\nGracias.";

                    Mail::raw($texto, function ($mensaje) use ($destino, $dias) {
                        $mensaje->to($destino)
                            ->subject("Tu plan vence en {$dias} día(s)");
                    });
                } catch (\Throwable $e) {
                    Log::error('No se pudo enviar el aviso de vencimiento', [
                        'empresa' => $empresaId,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    private function sincronizarPlanUsuarios(bool $dry): void
    {
        $cambios = 0;

        foreach (Usuario::whereNotNull('cod_plan')->cursor() as $usuario) {
            $planVigente = $usuario->empresa?->facturaVigente?->cod_plan;

            if ((string) $planVigente === (string) $usuario->cod_plan) {
                continue;
            }

            $cambios++;
            $this->line(sprintf('Usuario %s: plan %s -> %s', $usuario->uuid, $usuario->cod_plan, $planVigente ?? 'sin plan'));

            if (!$dry) {
                $usuario->forceFill(['cod_plan' => $planVigente])->save();
            }
        }

        $this->info("Usuarios sincronizados: {$cambios}" . ($dry ? ' (dry-run)' : ''));
    }
}
