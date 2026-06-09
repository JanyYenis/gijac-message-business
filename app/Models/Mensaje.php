<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class Mensaje extends Model
{
    const TC_ESTADO = 'TC_ESTADO_MENSAJE';
    const ENVIADO   = 1;
    const ENTREGADO = 2;
    const LEIDO     = 3;
    const FALLIDO   = 4;
    const ERROR     = 0;

    const TC_TIPO_MENSAJE = 'TC_TIPO_MENSAJE';
    const TEXTO = 1;
    const IMAGEN = 2;
    const VIDEO = 3;
    const DOCUMENTO = 4;
    const LOCALIZACION = 5;
    const AUDIO = 6;
    const PLANTILLA = 7;
    const CONTACTO = 8;
    const REACCION = 9;
    const FLOWS = 10;
    const INTERACCION_BOTON = 11;
    const INTERACCION_LISTADO = 12;
    const STICKER = 13;

    const DATOS_ESTADO = [
        'sent' => self::ENVIADO,
        'delivered' => self::ENTREGADO,
        'read' => self::LEIDO,
        'failed' => self::FALLIDO,
    ];

    const DATOS_TIPO = [
        'text' => self::TEXTO,
        'image' => self::IMAGEN,
        'video' => self::VIDEO,
        'audio' => self::AUDIO,
        'document' => self::DOCUMENTO,
        'sticker' => self::STICKER,
        'location' => self::LOCALIZACION,
        'contacts' => self::CONTACTO,
        'reaction' => self::REACCION,
        'interactive_button' => self::INTERACCION_BOTON,
        'interactive_list' => self::INTERACCION_LISTADO,
        'interactive_flow' => self::FLOWS,
        'template' => self::PLANTILLA
    ];

    protected $table = 'mensajes';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'campaign_id',
        'contact_id',
        'wa_message_id',
        'wa_from',
        'wa_to',
        'type',
        'body',
        'metadata',
        'estado',
        'sent_at',
    ];

    protected $casts = [
        'id' => 'string',
        'metadata' => 'array',
        'sent_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function envios()
    {
        return $this->belongsTo(EnvioCampana::class, 'wa_message_id', 'wamid');
    }
}
