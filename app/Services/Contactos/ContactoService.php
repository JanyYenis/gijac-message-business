<?php

namespace App\Services\Contactos;

use App\Models\Contacto;
use App\Models\Plan;
use App\Models\Empresa;
use RuntimeException;

class ContactoService
{
    public function validarLimite(Empresa $empresa, $esDemo = false): void
    {
        $cantidad = Contacto::where('estado', Contacto::ACTIVO)
            ->where('cod_empresa', $empresa->id)
            ->count();

        $codPlan = $empresa->facturaVigente?->cod_plan;

        if ($codPlan) {
            $plan = Plan::find($codPlan);

            if ($plan?->max_contactos && $cantidad >= $plan->max_contactos) {
                throw new RuntimeException(
                    __('Has superado el límite de contactos activos para tu plan.')
                );
            }

            return;
        }

        if ($esDemo) {
            if ($cantidad >= 30) {
                throw new RuntimeException(
                    __('Has superado el límite de 30 contactos activos para tu plan demo.')
                );
            }

            return;
        }

        throw new RuntimeException(
            __('Por favor selecciona uno de nuestros planes para crear un contacto.')
        );
    }

    public function crearAutomaticamente(
        Empresa $empresa,
        string $telefono,
        ?string $nombre = null,
        ?string $uuid = null,
        bool $esDemo = false
    ): Contacto {
        $this->validarLimite($empresa, $esDemo);

        // Normalizar teléfono
        if (!str_starts_with($telefono, '+')) {
            $telefono = '+' . $telefono;
        }

        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        $parsedNumber = $phoneUtil->parse($telefono, null);

        $countryCode = $parsedNumber->getCountryCode();
        $nationalNumber = $parsedNumber->getNationalNumber();

        return Contacto::create([
            'nombre' => $nombre,
            'telefono' => $nationalNumber,
            'codigo_telefono' => $countryCode,
            'uuid' => $uuid ?? $empresa->cod_usuario,
            'cod_empresa' => $empresa->id,
        ]);
    }
}
