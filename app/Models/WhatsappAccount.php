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

    /** Texto de Meta => entero de la BD */
    const RATING_FROM_META = [
        'GREEN'   => self::RATING_GREEN,
        'YELLOW'  => self::RATING_YELLOW,
        'RED'     => self::RATING_RED,
        'UNKNOWN' => self::RATING_UNKNOWN,
    ];

    /** Entero de la BD => texto de Meta (el JS usa estos para mostrar "Excelente", etc.) */
    const RATING_TO_META = [
        self::RATING_GREEN   => 'GREEN',
        self::RATING_YELLOW  => 'YELLOW',
        self::RATING_RED     => 'RED',
        self::RATING_UNKNOWN => 'UNKNOWN',
    ];

    protected $table = 'whatsapp_accounts';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $hidden = ['access_token', 'two_factor_pin'];

    protected $fillable = [
        'usuario_id',
        "cod_empresa",
        'waba_id',
        'phone_number_id',
        'business_id',
        'access_token',
        'two_factor_pin',
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
        "token_expires_at" => "datetime",
    ];

    protected $dates = [
        "created_at" => "date:d/m/Y",
        "token_expires_at" => "datetime",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public static function ratingFromMeta(?string $value): int
    {
        return self::RATING_FROM_META[strtoupper((string) $value)] ?? self::RATING_UNKNOWN;
    }

    public static function ratingToMeta($value): string
    {
        return self::RATING_TO_META[(int) $value] ?? 'UNKNOWN';
    }
}
