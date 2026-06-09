<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ChatbotNode extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    const PRINCIPAL    = 1;
    const NO_PRINCIPAL = 0;

    const TC_TIPO   = '';
    const TEXTO     = 1;
    const IMAGEN    = 2;
    const VIDEO     = 3;
    const DOCUMENTO = 4;
    const BOTON     = 5;
    const LISTA     = 6;
    const INPUT     = 7;
    const PLANTILLA = 8;
    const CONTACTO  = 9;
    const PDF       = 10;

    const DATOS_TIPO_CHATBOT = [
        'text' => self::TEXTO,
        'image' => self::IMAGEN,
        'video' => self::VIDEO,
        'document' => self::DOCUMENTO,
        'buttons' => self::BOTON,
        'list' => self::LISTA,
        'input' => self::INPUT,
        'plantilla' => self::PLANTILLA,
        'contacto' => self::CONTACTO,
        'pdf' => self::PDF,
    ];

    protected $table = 'chatbot_nodes';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'chatbot_id',
        'number',
        'title',
        'type',
        'message',
        'media_url',
        'estado',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class, 'chatbot_id', 'id');
    }

    public function opciones()
    {
        return $this->hasMany(ChatbotOption::class, 'node_id', 'id');
    }

    public function opcionesOrdenado()
    {
        return $this->opciones()->orderBy('value');
    }

    public function opcion()
    {
        return $this->hasOne(ChatbotOption::class, 'node_id', 'id');
    }
}
