<?php

namespace App\Traits;

use App\Models\Comentario;
use Illuminate\Support\Facades\Auth;

trait Comentariable
{
    /**
     * Función que permite obtener los comentarios relacionados a un registro.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany Relación polimórfica.
     */
    public function comentarios()
    {
        return $this->morphMany(Comentario::class, "comentariable");
    }

    /**
     * Función que permite obtener un comentario relacionado a un registro.
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne Relación polimórfica.
     */
    public function comentario()
    {
        return $this->morphOne(Comentario::class, "comentariable");
    }

    /**
     * Función que permite crear un comentario y relacionarlo inmediatamente con el modelo
     * seleccionado.
     * @param array $datos Datos a registrar sobre el comentario.
     */
    public function crearComentario($datos)
    {
        $comentario = new Comentario($datos);
        $comentario = $this->comentario()->save($comentario);
        if (!$comentario) {
            return [
                'estados' => 'error',
                'mensaje' => 'Ha ocurrido un error al intentar agregar el comentario'
            ];
        }
        return $comentario;
    }
}
