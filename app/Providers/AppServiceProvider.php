<?php

namespace App\Providers;

use App\Models\Campana;
use App\Models\Contacto;
use App\Models\Plantilla;
use App\Models\WhatsappAccount;
use App\Models\Sistema\Concepto;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (str_contains(request()->getHost(), 'ngrok-free.dev') || str_contains(request()->getHost(), 'gijac.com')) {
            \URL::forceScheme('https');
        }

        // Compartir variable con todas las vistas
        view()->composer('layouts.index', function ($view) {
            // Verificar primero si el usuario está autenticado
            if (auth()->check()) {
                $uuid = auth()->user()->empresa?->id ?? false;
                // Cargar la configuración solo con los campos necesarios
                $config = WhatsappAccount::where('estado', WhatsappAccount::CONECTADO)
                    ->where('cod_empresa', $uuid)
                    ->select('phone_number_id')
                    ->first();

                // Compartir la variable (incluso si es null)
                $view->with('numeroTelefono', optional($config)->phone_number_id);
                $empresa = auth()->user()?->empresa;
                $cod     = $empresa?->id;

                $cuentas = $cod
                    ? WhatsappAccount::where('cod_empresa', $cod)->where('estado', WhatsappAccount::CONECTADO)->pluck('id')
                    : collect();

                $pasos = [
                    $empresa !== null,
                    $cuentas->isNotEmpty(),
                    (bool) $cod && Contacto::where('cod_empresa', $cod)->where('estado', '!=', Contacto::ELIMINADO)->exists(),
                    $cuentas->isNotEmpty() && Plantilla::whereIn('cod_config', $cuentas)->where('status', Plantilla::APROBADO)->exists(),
                    (bool) $cod && Campana::where('cod_empresa', $cod)->where('estado', '!=', Campana::ELIMINADO)->exists(),
                    $empresa !== null && $empresa->facturaVigente()->exists(),
                ];

                $view->with('accesos', ! in_array(false, $pasos, true));
            } else {
                $view->with('accesos', false);
                $view->with('numeroTelefono', null);
            }
        });

        // Compartir variable con todas las vistas
        view()->composer('layouts.componentes.sider', function ($view) {
            $idioma_actual = Concepto::where('nombre_corto', (auth()->user()->locale ?? app()->getLocale()))
                ->where('estado', Concepto::ACTIVO)->first();
            $view->with('idioma_actual', $idioma_actual);
        });

        // Compartir variable con todas las vistas
        view()->composer('layouts.componentes.toolbar', function ($view) {
            $configs = null;
            if (auth()->user()?->empresa) {
                $configs = WhatsappAccount::where('cod_empresa', auth()->user()?->empresa?->id)
                    ->get();
            }
            $view->with('configs', $configs);
        });

        view()->composer('layouts.componentes.guia', function ($view) {
            $empresa = auth()->user()?->empresa;
            $cod     = $empresa?->id;

            $cuentas = $cod
                ? WhatsappAccount::where('cod_empresa', $cod)->where('estado', WhatsappAccount::CONECTADO)->pluck('id')
                : collect();

            $pasos = [
                $empresa !== null,
                $cuentas->isNotEmpty(),
                (bool) $cod && Contacto::where('cod_empresa', $cod)->where('estado', '!=', Contacto::ELIMINADO)->exists(),
                $cuentas->isNotEmpty() && Plantilla::whereIn('cod_config', $cuentas)->where('status', Plantilla::APROBADO)->exists(),
                (bool) $cod && Campana::where('cod_empresa', $cod)->where('estado', '!=', Campana::ELIMINADO)->exists(),
                $empresa !== null && $empresa->facturaVigente()->exists(),
            ];

            $view->with('pasos', $pasos);
            $view->with('accesos', ! in_array(false, $pasos, true));
        });
    }
}
