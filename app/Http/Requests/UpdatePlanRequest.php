<?php

namespace App\Http\Requests;

use App\Classes\FormRequest\FormRequest;

class UpdatePlanRequest extends FormRequest
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
            'tipo.required' => 'El campo tipo es requerido.',
            'tipo.numeric' => 'El campo tipo debe ser numerico.',
            'valor.required' => 'El campo valor es requerido.',
            'valor.numeric' => 'El campo valor debe ser numerico.',
            'max_contactos.numeric' => 'El campo valor de maximo envio debe ser numerico.',
            'servicios.array' => 'El campo servicios debe de tipo array.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $reglas = [];
        if (!$this->has('estado')) {
            $reglas = [
                'nombre' => [
                    "required",
                ],
                'tipo' => [
                    "required",
                    "numeric"
                ],
                'categoria' => [
                    "required",
                    "numeric"
                ],
                'valor' => [
                    "required",
                    "numeric",
                    "min:0",
                ],
                'max_contactos' => [
                    "nullable",
                    "numeric",
                    "min:1",
                ],
                'servicios' => [
                    "array",
                ],
            ];
        }

        return $reglas;
    }
}
