<?php

namespace App\Models;

use App\Classes\Models\Model;

class Plantilla extends Model
{
    protected $table = 'plantillas';

    const TC_ESTADO = 'TC_ESTADO_PLANTILLAS';
    const APROBADO  = 1;
    const PENDIENTE = 2;
    const RECHAZADO = 3;

    const VALIDAR_VALOR_ESTADO = [
        'APPROVED' => self::APROBADO,
        'PENDING'  => self::PENDIENTE,
        'REJECTED' => self::RECHAZADO,
    ];

    const TC_CATEGORIA_PLANTILLA  = 'TC_CATEGORIA_PLANTILLA';
    const AUTENTICACION = 1;
    const MARKETING     = 2;
    const SERVICIOS     = 3;

    const VALIDAR_VALOR_CATEGORIA = [
        'AUTHENTICATION' => self::AUTENTICACION,
        'MARKETING'      => self::MARKETING,
        'UTILITY'        => self::SERVICIOS,
    ];

    const TC_FORMATO_PARAMETRO = 'TC_FORMATO_PARAMETRO';
    const POSIONAL = 1;
    const NOMBRE   = 2;

    const VALIDAR_VALOR_FORMATO_PARAMETRO = [
        'POSITIONAL' => self::POSIONAL,
        'NAMED'      => self::NOMBRE,
    ];

    protected $fillable = [
        'id',
        'cod_config',
        'name',
        'parameter_format',
        'language',
        'status',
        'category',
        'sub_category',
        'meta_id',
    ];

    public function infoEstado()
    {
        return darInfoConcepto($this, self::TC_ESTADO, 'status')->selectRaw('conceptos.*');
    }

    public static function darEstado()
    {
        return darConceptos(self::TC_ESTADO, false);
    }

    public function infoCategoria()
    {
        return darInfoConcepto($this, self::TC_CATEGORIA_PLANTILLA, 'category')->selectRaw('conceptos.*');
    }

    public static function darCategoria()
    {
        return darConceptos(self::TC_CATEGORIA_PLANTILLA, false);
    }

    public function infoFormatoParametro()
    {
        return darInfoConcepto($this, self::TC_FORMATO_PARAMETRO, 'parameter_format')->selectRaw('conceptos.*');
    }

    public static function darFormatoParametro()
    {
        return darConceptos(self::TC_FORMATO_PARAMETRO, false);
    }

    public function componentes()
    {
        return $this->hasMany(PlantillaComponente::class, 'plantilla_id', 'id');
    }

    // public function componentesActivas()
    // {
    //     return $this->componentes()->where('estado', PlantillaComponente::ACTIVO);
    // }

    public function componente()
    {
        return $this->hasOne(PlantillaComponente::class, 'plantilla_id', 'id');
    }

    public function header()
    {
        return $this->componente()->where('type', PlantillaComponente::HEADER);
    }

    public function body()
    {
        return $this->componente()->where('type', PlantillaComponente::BODY);
    }

    public function footer()
    {
        return $this->componente()->where('type', PlantillaComponente::FOOTER);
    }

    public function buttons()
    {
        return $this->componente()->where('type', PlantillaComponente::BUTTONS);
    }
}
