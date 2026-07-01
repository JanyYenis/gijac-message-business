<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class UsuarioEmpresa extends Model
{
    protected $table = 'usuarios_empresas';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    const PRINCIPAL = 1;

    protected $fillable = [
        'cod_empresa',
        'cod_usuario',
        'principal',
        'estado',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
