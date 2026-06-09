<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ChatbotLog extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $table = 'chatbot_logs';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'chatbot_id',
        'user_phone',
        'current_node_id',
        'user_response',
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
        return $this->belongsTo(ChatbotNode::class, 'current_node_id', 'id');
    }

    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class, 'chatbot_id', 'id');
    }
}
