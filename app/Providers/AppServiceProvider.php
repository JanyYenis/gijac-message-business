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
            $idiomas = Concepto::whereHas('tipoConcepto', function ($query) {
                    $query->where('nombre', 'TC_IDIOMAS_SISTEMA');
                })
                ->where('estado', Concepto::ACTIVO)
                ->get();

            $idioma_actual = $idiomas->firstWhere('nombre_corto', app()->getLocale());
            $view->with('idioma_actual', $idioma_actual);
            $view->with('idiomas', $idiomas);
        });
    }
}
