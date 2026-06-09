<?php

namespace App\Models\Sistema;

use App\Classes\Models\Model;
use App\Models\Usuario;

class ActividadUsuario extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $table = 'actividades_usuarios';
    protected $fillable = [
        'cod_usuario',
        'url',
        'duracion',
        'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'cod_usuario', 'id');
    }

    public function infoEstado()
    {
        return darInfoConcepto($this, self::TC_ESTADO, 'estado')->selectRaw('conceptos.*');
    }

    public static function darEstado($infoTipoConcepto = true)
    {
        return darConceptos(self::TC_ESTADO, $infoTipoConcepto);
    }
}
