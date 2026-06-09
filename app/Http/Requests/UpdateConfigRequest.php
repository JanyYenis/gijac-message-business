<?php

namespace App\Http\Requests;

use App\Classes\FormRequest\FormRequest;

class UpdateConfigRequest extends FormRequest
{
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'version.required' => 'El campo version es requerido.',
            'waba_id.required' => 'El campo waba id es requerido.',
            'waba_id.numeric' => 'El campo waba id debe ser numerico.',
            'app_id.required' => 'El campo app id es requerido.',
            'app_id.numeric' => 'El campo app id debe ser numerico.',
            'phone_number_id.required' => 'El campo phone number id es requerido.',
            'phone_number_id.numeric' => 'El campo phone number id debe ser numerico.',
            'token.required' => 'El campo token de META es requerida.',
            'token_1.required' => 'El campo identificador de verificación es requerido.',
            'numero.required' => 'El campo numero es requerido.',
            'numero.numeric' => 'El campo numero debe ser numerico.',
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
                'version' => [
                    "required",
                ],
                'waba_id' => [
                    "required",
                    "numeric",
                ],
                'app_id' => [
                    "required",
                    "numeric",
                ],
                'phone_number_id' => [
                    "required",
                    "numeric",
                ],
                'token_1' => [
                    "required",
                ],
                'numero' => [
                    "required",
                    "numeric",
                ],
            ];
        }

        return $reglas;
    }
}