<?php

namespace App\Rules;

use App\Models\Contacto;
use App\Models\Plan;
use App\Models\Usuario;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CantidadContacto implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $tienePlan = auth()->user()?->cod_plan ?? null;
        $esDemo = auth()->user()?->demo ?? null;
        $uuid = auth()->user()->hasRole(Usuario::ROL_AGENTE) ? auth()->user()->cod_empresa : auth()->user()->uuid;
        $cantidadContactosActivos = Contacto::where('estado', Contacto::ACTIVO)
            ->where('uuid', $uuid)
            ->count();
        if ($tienePlan) {
            $plan = Plan::find($tienePlan);
            if ($plan?->max_contactos) {
                if ($plan?->max_contactos <= $cantidadContactosActivos) {
                    $fail('Has superado el limite de contactos activos para tu plan.');
                }
            }
        } else if ($esDemo) {
            if (30 <= $cantidadContactosActivos) {
                $fail('Has superado el limite de 30 contactos activos para tu plan demo.');
            }
        } else {
            $fail('Por favor selecciona uno de nuestros planes para crear un contacto.');
        }
    }
}
