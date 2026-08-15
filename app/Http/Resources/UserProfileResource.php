<?php

namespace App\Http\Resources;

use App\Models\ConfiguracionMeta;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $configMeta = $this->empresa
            ? ConfiguracionMeta::firstWhere([
                'cod_empresa' => $this->empresa->id,
                'estado' => ConfiguracionMeta::ACTIVO,
            ])
            : null;

        return [
            'id'            => $this->uuid,
            'nombre'        => $this->nombre,
            'apellido'      => $this->apellido,
            'nombre_completo' => $this->nombre_completo,
            'email'         => $this->email,
            'telefono'      => $this->telefono,
            'codigo_telefono' => $this->codigo_telefono,
            'numero_completo' => $this->numero_completo,
            'foto'          => $this->foto ? asset('storage/' . $this->foto) : null,
            'genero'        => $this->whenLoaded('infoGenero', function () {
                return [
                    'id'    => $this->genero,
                    'nombre' => $this->infoGenero?->nombre,
                ];
            }),
            'ciudad'        => $this->whenLoaded('ciudad', function () {
                return [
                    'id'     => $this->ciudad?->id,
                    'nombre' => $this->ciudad?->nombre,
                    'pais'   => $this->whenLoaded('ciudad.pais', function () {
                        return [
                            'id'     => $this->ciudad?->pais?->id,
                            'nombre' => $this->ciudad?->pais?->nombre,
                        ];
                    }),
                ];
            }),
            'empresa'       => $this->whenLoaded('empresa', function () {
                return [
                    'id'     => $this->empresa?->uuid,
                    'nombre' => $this->empresa?->razon_social,
                    'logo'   => $this->empresa?->foto ? $this->empresa->foto : null,
                ];
            }),
            'plan'          => $this->whenLoaded('plan', function () {
                return [
                    'id'     => $this->plan?->id,
                    'nombre' => $this->plan?->nombre,
                ];
            }),
            'roles'         => $this->whenLoaded('roles', function () {
                return $this->roles->pluck('nombre')->toArray();
            }),
            'phone_number_id' => $configMeta?->phone_number_id,
            'tiene_2fa'     => !empty($this->google2fa_secret),
            'verificado'    => !is_null($this->email_verified_at),
        ];
    }
}
