<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $fillable = [
        'razon_social',
        'nit',
        'direccion',
        'email',
        'telefono',
        'instagram',
        'facebook',
        'tiktok',
        'linkendin',
        'web',
        'descripcion',
        'cod_usuario',
        'foto',
        'estado',
        'publicar',
        'notificacion',
    ];

    protected $dates = [
        "created_at" => "date:d/m/Y ",
        "updated_at" => "date:d/m/Y ",
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            "created_at" => "date:d/m/Y",
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
