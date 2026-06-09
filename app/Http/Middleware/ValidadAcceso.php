<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidadAcceso
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Verifica si el usuario está autenticado
        if (Auth::check()) {
            // Verifica el rol del usuario
            if (auth()->user()->hasRole($role) || auth()->user()->hasRole(Usuario::ROL_SUPER_ADMINISTRADOR)) {
                return $next($request);
            }
        }

        return $next($request);
        // Redirige al usuario a una URL específica según su rol
        return redirect(RouteServiceProvider::HOME);
    }
}
