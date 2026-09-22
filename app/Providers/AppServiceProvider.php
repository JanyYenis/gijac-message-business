<?php

namespace App\Providers;

use App\Models\ConfiguracionMeta;
use App\Models\Sistema\Concepto;
use App\Models\Usuario;
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
                $config = ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)
                    ->where('cod_empresa', $uuid)
                    ->select('phone_number_id')
                    ->first();

                // Compartir la variable (incluso si es null)
                $view->with('numeroTelefono', optional($config)->phone_number_id);
            } else {
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
            $configs = ConfiguracionMeta::where('cod_empresa', auth()->user()->empresa->id)
                ->get();
            $view->with('configs', $configs);
        });
    }
}
