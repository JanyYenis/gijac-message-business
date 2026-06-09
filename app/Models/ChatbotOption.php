<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ChatbotOption extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $table = 'chatbot_options';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    // protected $appends = ['proximoMensaje'];

    protected $fillable = [
        'node_id',
        'label',
        'value',
        'next_node_id',
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

    public function node()
    {
        return $this->belongsTo(ChatbotNode::class, 'node_id', 'id');
    }

    public function proximoMensaje()
    {
        return $this->belongsTo(ChatbotNode::class, 'next_node_id', 'number');
    }
}
