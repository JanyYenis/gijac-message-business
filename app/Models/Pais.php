<?php

namespace App\Models;

use App\Classes\Models\Model;

class Pais extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $table = 'paises';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'bandera',
        'nombre_corto',
        'alias',
        'latitud',
        'longitud',
        'prefijo',
        'region',
        'poblacion',
        'capital',
        'estado',
    ];

    public function ciudades(){
        return $this->hasMany(Ciudad::class, 'id_pais', 'id');
    }

    public function ciudad(){
        return $this->hasOne(Ciudad::class, 'id_pais', 'id');
    }

    public function ciudadesActivas(){
        return $this->ciudades()->where('estado', Ciudad::ACTIVO);
    }

    public function ciudadActiva(){
        return $this->ciudad()->where('estado', Ciudad::ACTIVO);
    }
}
