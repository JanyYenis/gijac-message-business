<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;

        // 1. Usuario autenticado y preferencia guardada
        if (auth()->check() && auth()->user()->locale) {
            $locale = auth()->user()->locale;
        }

        // 2. Preferencia de sesión
        if (!$locale && Session::has('locale')) {
            $locale = Session::get('locale');
        }

        // 3. Primera visita: IP
        if (!$locale) {
            $locale = $this->getLocaleFromIp($request);
        }

        // 4. Fallback
        $locale = $locale ?: config('app.locale', 'es');

        App::setLocale($locale);
        Session::put('locale', $locale);

        return $next($request);
    }

    private function getLocaleFromIp(Request $request): string
    {
        // Aquí irá la detección real por IP.

        return config('app.locale', 'es');
    }
}
