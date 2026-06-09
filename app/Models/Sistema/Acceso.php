<?php

namespace App\Models\Sistema;

use App\Classes\Models\Model;
use App\Models\Usuario;

class Acceso extends Model
{
    const TC_TIPO     = 'TC_TIPO_ACCESOS';
    const TRADICIONAL = 1;
    const GOOGLE      = 2;
    const OUTLOOCK    = 3;

    const TC_ESTADO          = 'TC_ESTADO_ACCESOS';
    const ACTIVO             = 1;
    const SESION_FINALIZADA  = 2;
    const ERROR              = 0;

    protected $table = 'accesos';
    protected $fillable = [
        'cod_usuario',
        'tipo',
        'ip',
        'navegador',
        'sistema',
        'localizacion',
        'fecha_ingreso',
        'fecha_salida',
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
    
    public function infoTipo()
    {
        return darInfoConcepto($this, self::TC_TIPO, 'tipo')->selectRaw('conceptos.*');
    }

    public static function darTipo($infoTipoConcepto = true)
    {
        return darConceptos(self::TC_TIPO, $infoTipoConcepto);
    }
}
