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
        $empresa = auth()->user()?->empresa ?? false;
        $tienePlan = $empresa?->facturaVigente?->cod_plan ?? null;
        $esDemo = auth()->user()?->demo ?? null;
        $uuid = $empresa->id;
        $cantidadContactosActivos = Contacto::where('estado', Contacto::ACTIVO)
            ->where('cod_empresa', $uuid)
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
