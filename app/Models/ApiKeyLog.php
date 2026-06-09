<?php

namespace App\Models;

use App\Classes\Models\Model;

class ApiKeyLog extends Model
{
    const TC_ESTADO = 'TC_ESTADO_API_KEY_LOG';
    const OK    = 1;
    const ERROR  = 2;
    const ALVERTENCIA = 3;

    protected $table = 'api_key_logs';
    protected $fillable = [
        'api_key_id',
        'ip_address',
        'user_agent',
        'fecha',
        'location',
        'comentario',
        'bloqueado_hasta'
    ];

    public function apiKey()
    {
        return $this->belongsTo(ApiKey::class, 'api_key_id', 'id');
    }
}
