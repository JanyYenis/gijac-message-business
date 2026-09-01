<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class IdiomaPlantilla extends Model
{
    protected $table = 'idiomas_plantillas';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $fillable = [
        'codigo',
        'nombre',
        'estado',
    ];

    protected $casts = [
        'id' => 'string',
        "created_at" => "date:d/m/Y",
    ];

    protected $dates = [
        "created_at" => "date:d/m/Y",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
