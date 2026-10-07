<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class WhatsappWebhookLog extends Model
{
    const TC_ESTADO = 'TC_ESTADO_WHATSAPP_WEBHOOK_LOG';
    const RECIBIDO  = 1;
    const OK        = 2;
    const ERROR     = 3;

    protected $table = 'whatsapp_webhook_logs';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'waba_id',
        'event_type',
        'estado',
        'payload',
    ];

    protected $casts = [
        'id' => 'string',
        "created_at" => "date:d/m/Y",
        "token_expires_at" => "date:d/m/Y",
    ];

    protected $dates = [
        "created_at" => "date:d/m/Y",
        "token_expires_at" => "date:d/m/Y",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
