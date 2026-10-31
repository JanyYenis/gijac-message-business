<?php

namespace App\Http\Resources;

use App\Models\WhatsappAccount;
use App\Models\WhatsappNumber;
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
            ? WhatsappAccount::firstWhere([
                'cod_empresa' => $this->empresa->id,
                'estado' => WhatsappAccount::CONECTADO,
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
            'foto'          => $this->urlFoto($this->foto),
            'tipo_identificacion' => $this->tipo_identificacion,
            'identificacion' => $this->identificacion,
            'genero_id'     => $this->genero,
            'pais_id'       => $this->ciudad?->id_pais,
            'cod_ciudad'    => $this->cod_ciudad,
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
                    'logo'   => $this->urlLogo($this->empresa?->foto),
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
            // El mismo número que usan los chats de la app (asignado al usuario o principal de la cuenta activa)
            'phone_number_id' => WhatsappNumber::paraUsuario($this->resource)?->phone_number_id ?? $configMeta?->phone_number_id,
            'tiene_2fa'     => !empty($this->google2fa_secret),
            'verificado'    => !is_null($this->email_verified_at),
        ];
    }

    /** Logo de la empresa: URL completa, ruta pública (img/..., storage/...) o ruta del disco public */
    private function urlLogo(?string $foto): ?string
    {
        if (! $foto) {
            return null;
        }
        if (preg_match('#^https?://#i', $foto)) {
            return $foto;
        }
        $foto = ltrim($foto, '/');

        return asset(preg_match('#^(storage|img|images|assets)/#', $foto) ? $foto : 'storage/' . $foto);
    }

    /**
     * La web guarda 'storage/perfil/x.jpg'; antes se guardaba la URL completa. Se admiten ambos
     * (y 'perfil/x.jpg' a secas) sin duplicar el prefijo 'storage/'.
     */
    private function urlFoto(?string $foto): ?string
    {
        if (! $foto) {
            return null;
        }
        if (preg_match('#^https?://#i', $foto)) {
            return $foto;
        }
        $foto = ltrim($foto, '/');

        return asset(str_starts_with($foto, 'storage/') ? $foto : 'storage/' . $foto);
    }
}
