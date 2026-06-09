<?php

namespace App\Models\Sistema;

use App\Classes\Models\Model;

class Concepto extends Model
{
    const ACTIVO = 1;
    const INACTIVO = 0;

    protected $table = "conceptos";

    const TODAS_DEPENDENCIAS = -1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'id_tipo',
        'codigo',
        'nombre',
        'estado',
        'color',
        'icono'
    ];

    /**
     * Get the user that owns the phone.
     */
    public function tipoConcepto()
    {
        return $this->belongsTo(TipoConcepto::class, 'id_tipo', 'id');
    }
}