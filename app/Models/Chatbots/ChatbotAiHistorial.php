<?php

namespace App\Models\Chatbots;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ChatbotAiHistorial extends Model
{
    protected $table = 'chatbot_ai_historiales';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'contacto_id',
        'cod_empresa',
        'telefono',
        'historial'
    ];
    protected $casts = [
        'id' => 'string',
        'contacto_id' => 'string',
        'cod_empresa' => 'string',
        'historial' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->id = Str::uuid());
    }
}
