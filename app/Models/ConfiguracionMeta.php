<?php

namespace App\Models;

use App\Classes\Models\Model;

class ConfiguracionMeta extends Model
{
    protected $table = 'configuraciones_meta';

    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO = 1;
    const INACTIVO = 2;
    const ELIMINADO = 0;

    protected $fillable = [
        "version",
        "waba_id",
        "app_id",
        "phone_number_id",
        "token",
        "token_1",
        "numero",
        "estado",
        "uuid",
    ];
}
