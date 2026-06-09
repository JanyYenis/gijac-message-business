<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class Periodo extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $table = 'periodos';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nombre',
        'codigo',
        'multiplicador',
        'descuento',
        'activo',
    ];

    protected $casts = [
        'id' => 'string',
        'activo' => 'boolean',
        'descuento' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function getDescuentoPorcentajeAttribute()
    {
        return $this->descuento * 100 . '%';
    }
}
