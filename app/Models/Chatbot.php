<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class Chatbot extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $table = 'chatbots';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'name',
        'estado',
    ];

    protected $casts = [
        'id' => 'string',
        'created_at' => 'date:d/m/Y',
    ];

    protected $dates = [
        'created_at' => 'date:d/m/Y',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function nodes()
    {
        return $this->hasMany(ChatbotNode::class, 'chatbot_id', 'id');
    }

    public function nodesActivos()
    {
        return $this->nodes()->where('estado', ChatbotNode::ACTIVO);
    }

    public function nodesOrdenados()
    {
        return $this->nodesActivos()->orderBy('number');
    }

    public function node()
    {
        return $this->hasOne(ChatbotNode::class, 'chatbot_id', 'id');
    }

    public function nodeActivo()
    {
        return $this->node()->where('estado', ChatbotNode::ACTIVO);
    }

    public function nodePrincipal()
    {
        return $this->nodeActivo()->where('number', 1);
    }

    public function nodeNoPrincipal()
    {
        return $this->nodesActivos()->whereNot('number', 1)->orderBy('number');
    }

    public function nodeInmediato()
    {
        return $this->nodesActivos()
            ->whereNot('number', 1)
            ->where('immediately', 1)
            ->orderBy('number');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'uuid', 'uuid');
    }
}
