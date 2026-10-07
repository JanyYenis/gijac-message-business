<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class WhatsappAccount extends Model
{
    const TC_ESTADO    = 'TC_ESTADO_WHATSAPP_ACCOUNT';
    const CONECTADO    = 1;
    const DESCONECTADO = 0;

    const TC_RATING      = 'TC_RATING_WHATSAPP_ACCOUNT';
    const RATING_GREEN   = 1;
    const RATING_YELLOW  = 2;
    const RATING_RED     = 3;
    const RATING_UNKNOWN = 4;

    protected $table = 'whatsapp_accounts';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'usuario_id',
        'waba_id',
        'phone_number_id',
        'business_id',
        'access_token',
        'phone_number',
        'display_name',
        'estado',
        'quality_rating',
        'messaging_limit',
        'webhook_subscribed',
        'number_registered',
        'token_expires_at',
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
