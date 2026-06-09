<?php

namespace App\Providers;

use App\Models\Usuario;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::prefix('paises')
                ->as("paises.")
                ->middleware(['web', 'auth', '2fa', 'verified'])
                ->group(base_path('routes/web/paises/principal.php'));

            Route::prefix('ciudades')
                ->as("ciudades.")
                ->middleware(['web', 'auth', '2fa', 'verified'])
                ->group(base_path('routes/web/ciudades/principal.php'));

            Route::prefix('roles')
                ->as("roles.")
                ->middleware(['web', 'auth', '2fa', 'verified'])
                ->group(base_path('routes/web/sistema/roles.php'));

            Route::prefix('notificaciones')
                ->as("notificaciones.")
                ->middleware(['web', 'auth'])
                ->group(base_path('routes/web/sistema/notificaciones.php'));

            Route::prefix('usuarios')
                ->as("usuarios.")
                ->middleware(['web', 'auth', '2fa', 'verified'])
                ->group(base_path('routes/web/usuarios/principal.php'));

            Route::prefix('planes')
                ->as("planes.")
                ->middleware(['web', 'auth', 'validaracceso:'.Usuario::ROL_ADMINISTRADOR, '2fa', 'verified'])
                ->group(base_path('routes/web/planes/principal.php'));

            Route::prefix('etiquetas')
                ->as("etiquetas.")
                ->middleware(['web', 'auth', 'validaracceso:'.Usuario::ROL_CLIENTE, '2fa', 'verified'])
                ->group(base_path('routes/web/etiquetas/principal.php'));

            Route::prefix('webhook')
                ->as("webhook.")
                ->group(base_path('routes/web/webhook/principal.php'));

            Route::prefix('campañas')
                ->as("campanas.")
                ->middleware(['web', 'auth', 'validaracceso:'.Usuario::ROL_CLIENTE, '2fa', 'verified'])
                ->group(base_path('routes/web/campanas/principal.php'));

            Route::prefix('plantillas')
                ->as("plantillas.")
                ->middleware(['web', 'auth', 'validaracceso:'.Usuario::ROL_ADMINISTRADOR, '2fa', 'verified'])
                ->group(base_path('routes/web/plantillas/principal.php'));

            Route::prefix('contactos')
                ->as("contactos.")
                ->middleware(['web', 'auth', 'validaracceso:'.Usuario::ROL_CLIENTE, '2fa', 'verified'])
                ->group(base_path('routes/web/contactos/principal.php'));

            Route::prefix('chats')
                ->as("chats.")
                ->middleware(['web', 'auth', 'validaracceso:'.Usuario::ROL_CLIENTE, '2fa', 'verified'])
                ->group(base_path('routes/web/chats/principal.php'));

            Route::prefix('configs')
                ->as("configs.")
                ->middleware(['web', 'auth', 'validaracceso:'.Usuario::ROL_CLIENTE, '2fa', 'verified'])
                ->group(base_path('routes/web/configs/principal.php'));

            Route::prefix('api-keys')
                ->as("api-keys.")
                ->middleware(['web', 'auth', 'validaracceso:'.Usuario::ROL_CLIENTE, '2fa', 'verified'])
                ->group(base_path('routes/web/apis/principal.php'));

            Route::prefix('facturas')
                ->as("facturas.")
                ->middleware(['web', 'auth', 'validaracceso:'.Usuario::ROL_CLIENTE, '2fa', 'verified'])
                ->group(base_path('routes/web/facturas/principal.php'));

            Route::prefix('tickets')
                ->as("tickets.")
                ->middleware(['web', 'auth', '2fa', 'verified'])
                ->group(base_path('routes/web/tickets/principal.php'));

            Route::prefix('comentarios')
                ->as("comentarios.")
                ->middleware(['web', 'auth', '2fa', 'verified'])
                ->group(base_path('routes/web/comentarios/principal.php'));

            Route::prefix('chatbots')
                ->as("chatbots.")
                ->middleware(['web', 'auth', '2fa', 'verified'])
                ->group(base_path('routes/web/chatbots/principal.php'));

            Route::prefix('clasificacion-ia')
                ->as("clasificacion-ia.")
                ->middleware(['web', 'auth', '2fa', 'verified'])
                ->group(base_path('routes/web/clasificacion-ia/principal.php'));
        });
    }
}
