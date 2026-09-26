<?php

namespace App\Rules;

use App\Models\Plan;
use App\Models\Usuario;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CantidadUsuario implements ValidationRule
{
    protected $usuarioId;

    public function __construct($usuarioId = null)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $usuarioAuth = auth()->user();

        $empresa = $usuarioAuth?->empresa;

        if (!$empresa) {
            $fail(__('No se encontró la empresa asociada al usuario.'));
            return;
        }

        $tienePlan = $empresa->facturaVigente?->cod_plan;
        $esDemo = $usuarioAuth?->demo;

        if (!$tienePlan) {
            $fail(__('Por favor selecciona uno de nuestros planes para crear un usuario (Agente).'));
            return;
        }

        $plan = Plan::find($tienePlan);

        if (!$plan?->max_usuario) {
            return;
        }

        $cantidadUsuariosActivos = Usuario::where('estado', Usuario::ACTIVO)
            ->where('cod_empresa', $empresa->id)
            ->count();

        /*
         * Si estamos editando un usuario que ya está activo,
         * no estamos aumentando la cantidad de usuarios activos.
         */
        if ($this->usuarioId) {

            $usuario = Usuario::where('id', $this->usuarioId)
                ->where('cod_empresa', $empresa->id)
                ->first();

            if ($usuario && $usuario->estado == Usuario::ACTIVO) {
                return;
            }
        }

        if ($cantidadUsuariosActivos >= $plan->max_usuario) {
            $fail(__('Has superado el límite de usuarios (Agentes) activos para tu plan.'));
        }
    }
}
