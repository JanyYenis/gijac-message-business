<?php

namespace App\Models;

use App\Classes\Models\Model;

class InvoiceTemporal extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $table = 'invoice_temporal';

    protected $fillable = [
        'invoice',
        'estado',
    ];
}
