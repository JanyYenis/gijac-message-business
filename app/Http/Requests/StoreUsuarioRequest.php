<?php

namespace App\Http\Requests;

use App\Classes\FormRequest\FormRequest;
use App\Rules\UsuarioEmail;
use App\Rules\UsuarioIdentificacion;
use App\Rules\UsuarioTelefono;

class StoreUsuarioRequest extends FormRequest
{
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El campo nombre es requerido.',
            'apellido.required' => 'El campo apellido es requerido.',
            'tipo_identificacion.required' => 'El campo tipo identificacion es requerido.',
            'tipo_identificacion.numeric' => 'El campo tipo identificacion debe ser numerico.',
            'identificacion.required' => 'El campo identificacion es requerido.',
            'identificacion.numeric' => 'El campo identificacion debe ser numerico.',
            'email.required' => 'El campo email es requerida.',
            'telefono.required' => 'El campo telefono es requerido.',
            'genero.required' => 'El campo genero es requerido.',
            'pais_id.required' => 'El campo pais es requerido.',
            'cod_ciudad.required' => 'El campo ciudad es requerido.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $reglas = [
            'nombre' => [
                "required",
            ],
            'apellido' => [
                "required",
            ],
            'tipo_identificacion' => [
                "required",
                "numeric",
            ],
            'identificacion' => [
                "required",
                "numeric",
                new UsuarioIdentificacion()
            ],
            'telefono' => [
                "required",
                new UsuarioTelefono()
            ],
            'genero' => [
                "required",
            ],
            'pais_id' => [
                "required",
            ],
            'cod_ciudad' => [
                "required",
            ],
        ];

        if ($this->has('email')) {
            $reglas['email'] = [
                "required",
                new UsuarioEmail()
            ];
        }

        return $reglas;
    }
}
