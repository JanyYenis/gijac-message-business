<?php

namespace App\Models;

use App\Classes\Models\Model;

class ConfiguracionAi extends Model
{
    protected $table = 'configuraciones_ai';

    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $fillable = [
        "cod_usuario",
        "prompt",
        "app_id",
        "estado",
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'cod_usuario', 'id');
    }
}