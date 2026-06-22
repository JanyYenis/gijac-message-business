<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return $next($request);
        }

        // Verifica si tiene empresa
        if (!$user->empresa) {

            // Evita bucle infinito
            if (!$request->routeIs('negocios.index') &&
                !$request->routeIs('negocios.store')) {

                return redirect()->route('negocios.index');
            }
        }

        return $next($request);
    }
}
