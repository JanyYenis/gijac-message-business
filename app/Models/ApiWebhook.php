<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ApiWebhook extends Model
{
    const ACTIVO   = 1;
    const INACTIVO = 2;

    const EVENTO_MENSAJE_RECIBIDO = 'message.received';
    const EVENTO_MENSAJE_ESTADO   = 'message.status';

    const EVENTOS = [
        self::EVENTO_MENSAJE_RECIBIDO => 'Mensajes recibidos de tus clientes',
        self::EVENTO_MENSAJE_ESTADO   => 'Estado de tus mensajes (enviado, entregado, leído, fallido)',
    ];

    // Tras tantos envíos fallidos seguidos (ya con reintentos) el webhook se desactiva
    const MAX_FALLOS = 20;

    protected $table = 'api_webhooks';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'cod_empresa',
        'url',
        'secreto',
        'eventos',
        'estado',
        'fallos_consecutivos',
        'ultimo_envio_at',
        'ultimo_http',
        'ultimo_error',
    ];

    protected $hidden = ['secreto'];

    protected $casts = [
        'id' => 'string',
        'secreto' => 'encrypted',
        'eventos' => 'array',
        'ultimo_envio_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public static function generarSecreto(): string
    {
        return 'whsec_' . Str::random(40);
    }
}
