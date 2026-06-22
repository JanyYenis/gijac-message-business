<?php

namespace App\Models\Chatbots;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ChatbotConnection extends Model
{
    protected $table = 'chatbot_connections';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'flow_id',
        'source_node_id',
        'target_node_id',
        'source_output',
        'target_input',
        'etiqueta',
    ];

    protected $casts = [
        'id'             => 'string',
        'flow_id'        => 'string',
        'source_node_id' => 'string',
        'target_node_id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function flow()
    {
        return $this->belongsTo(ChatbotFlow::class, 'flow_id', 'id');
    }

    public function nodoOrigen()
    {
        return $this->belongsTo(ChatbotNode::class, 'source_node_id', 'id');
    }

    public function nodoDestino()
    {
        return $this->belongsTo(ChatbotNode::class, 'target_node_id', 'id');
    }
}
