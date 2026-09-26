<?php

namespace App\Rules;

use App\Models\Contacto;
use App\Models\Plan;
use App\Models\Usuario;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CantidadContacto implements ValidationRule
{
    protected $contactoId;

    public function __construct($contactoId = null)
    {
        $this->contactoId = $contactoId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $usuario = auth()->user();
        $empresa = $usuario?->empresa;

        if (!$empresa) {
            $fail(__('No se encontró la empresa asociada.'));
            return;
        }

        $tienePlan = $empresa->facturaVigente?->cod_plan;
        $esDemo = $usuario?->demo;

        /*
         * Si estamos editando un contacto que ya está activo,
         * no estamos aumentando la cantidad de contactos activos.
         */
        if ($this->contactoId) {
            $contacto = Contacto::where('id', $this->contactoId)
                ->where('cod_empresa', $empresa->id)
                ->first();

            if ($contacto && $contacto->estado == Contacto::ACTIVO) {
                return;
            }
        }

        $cantidadContactosActivos = Contacto::where('estado', Contacto::ACTIVO)
            ->where('cod_empresa', $empresa->id)
            ->count();

        /*
         * Si no tiene plan, pero está en demo,
         * el límite es de 30 contactos.
         */
        if (!$tienePlan && $esDemo) {
            if ($cantidadContactosActivos >= 30) {
                $fail(__('Has superado el límite de 30 contactos activos para tu plan demo.'));
            }

            return;
        }

        /*
         * Si no tiene plan y tampoco está en demo.
         */
        if (!$tienePlan) {
            $fail(__('Por favor selecciona uno de nuestros planes para crear un contacto.'));
            return;
        }

        $plan = Plan::find($tienePlan);

        /*
         * Si el plan no tiene límite definido,
         * no bloqueamos.
         */
        if (!$plan?->max_contactos) {
            return;
        }

        /*
         * Ya alcanzó el máximo permitido.
         */
        if ($cantidadContactosActivos >= $plan->max_contactos) {
            $fail(__('Has superado el límite de contactos activos para tu plan.'));
        }
    }
}
