<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarServicioPlan
{
     /**
     * Verifica si el plan vigente tiene habilitado al menos uno
     * de los servicios solicitados.
     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$servicios
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$servicios)
    {
        $usuario = auth()->user();
        $empresa = $usuario?->empresa;
        $factura = $empresa?->facturaVigente;

        if (!$factura) {
            abort(403, 'No tienes acceso a este servicio.');
        }

        $plan = \App\Models\Plan::find($factura->cod_plan);

        if (!$plan) {
            abort(403, 'No se encontró un plan vigente.');
        }

        // Validar si tiene al menos uno de los servicios.
        $tieneServicio = collect($servicios)->contains(
            fn($servicio) => $plan->tieneServicio($servicio)
        );

        if (!$tieneServicio) {
            abort(403, 'Tu plan actual no incluye este servicio.');
        }

        return $next($request);
    }
}
