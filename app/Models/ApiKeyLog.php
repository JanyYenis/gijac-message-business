<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ApiKeyLog extends Model
{
    const TC_ESTADO = 'TC_ESTADO_API_KEY_LOG';
    const OK    = 1;
    const ERROR  = 2;
    const ALVERTENCIA = 3;

    protected $table = 'api_key_logs';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'api_key_id',
        'ip_address',
        'user_agent',
        'fecha',
        'location',
        'comentario',
        'bloqueado_hasta'
    ];

    protected $casts = [
        'id' => 'string',
        "fecha" => "date:d/m/Y",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function apiKey()
    {
        return $this->belongsTo(ApiKey::class, 'api_key_id', 'id');
    }
}
