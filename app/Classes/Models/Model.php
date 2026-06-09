<?php

namespace App\Classes\Models;

use App\Traits\Modelable;
use Illuminate\Database\Eloquent\Model as ModelOriginal;
abstract class Model extends ModelOriginal
{
    use Modelable;

    /**
     *  Constante para especificar el campo donde se insertara la fecha de creación del registro
     * @var string
     */
    const CREATED_AT = 'created_at';

    /**
     *  Constante para especificar el campo donde se insertará la fecha de modificación del registros
     * @var string
     */
    const UPDATED_AT = 'updated_at';

    /** Constante que indica el tipo concepto de estado. */
    const TC_ESTADO = "";

    const ESTADOS_EDITABLES = [];
    const ESTADOS_ELIMINABLES = [];

    /**
     * Función que permite obtener el nombre de la clase actual, el cual contiene
     * también la ruta a esta clase.
     * @return string Retorna un string con el nombre de la clase y la ruta.
     */
    public function darClase()
    {
        return get_class($this);
    }

    /**
     * Función que permite obtener el nombre de la clase actual de manera estática.
     * @return string Retorna un string con el nombre de la clase y la ruta.
     */
    public static function darClaseEstatica()
    {
        return static::class;
    }

    // public function infoEstado()
    // {
    //     return darInfoConcepto($this, self::TC_ESTADO, 'estado')->selectRaw('conceptos.*');
    // }

    // public static function darEstado($infoTipoConcepto = true)
    // {
    //     return darConceptos(self::TC_ESTADO, $infoTipoConcepto);
    // }
}

