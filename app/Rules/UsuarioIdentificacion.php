<?php

namespace App\Rules;

use App\Models\Usuario;
use Illuminate\Contracts\Validation\Rule;

class UsuarioIdentificacion implements Rule
{
    protected $userId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Verifica si existe un usuario activo con la identificación proporcionada
        $registro = Usuario::where('identificacion', $value)->where('estado', '!=', Usuario::ELIMINADO);

        if ($this->userId) {
            $registro = $registro->where('id', '!=', $this->userId);
        }
        
        $registro = $registro->exists();

        return $registro ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La identificación del usuario ya existe.';
    }
}
