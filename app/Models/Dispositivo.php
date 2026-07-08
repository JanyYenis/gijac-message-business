<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class Dispositivo extends Model
{
    protected $table = 'dispositivos';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $fillable = [
        'usuario_id',
        'token',
        'nombre_dispositivo',
        'sistema_operativo',
        'version_so',
        'ip',
        'modelo',
        'expira_en',
        'vinculado_en',
    ];

    protected $casts = [
        'id' => 'string',
        'usuario_id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

}
