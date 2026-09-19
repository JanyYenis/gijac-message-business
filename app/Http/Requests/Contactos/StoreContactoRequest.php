<?php

namespace App\Http\Requests\Contactos;

use App\Classes\FormRequest\FormRequest;
use App\Rules\CantidadContacto;

class StoreContactoRequest extends FormRequest
{
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => __('El campo de nombre es requerido.'),
            'nombre.string' => __('El campo de nombre debe ser texto.'),
            'apellido.string' => __('El campo de apellido debe ser texto.'),
            'genero.numeric' => __('El campo de tipo de genero debe ser numerico.'),
            'telefono.required' => __('El campo de telefono es requerido.'),
            'codigo_telefono.required' => __('El campo del codigo del telefono es requerido.'),
            'tratamiento_datos.numeric' => __('El campo de tratamiento de datos debe ser numerico.'),
            'preferencia.string' => __('El campo de preferencias debe ser texto.'),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => [
                'string',
                'required'
            ],
            'apellido' => [
                'string',
                'nullable'
            ],
            'genero' => [
                'numeric',
                'nullable'
            ],
            'telefono' => [
                'required',
                new CantidadContacto
            ],
            'codigo_telefono' => [
                'required',
            ],
            'tratamiento_datos' => [
                'numeric',
                'nullable'
            ],
            'preferencia' => [
                'string',
                'nullable'
            ],
        ];
    }
}
