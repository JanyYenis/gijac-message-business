<?php

namespace App\Models\Chatbots;

use App\Classes\Models\Model;
use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Support\Str;

class ChatbotAiAssistant extends Model
{
    protected $table = 'chatbot_ai_assistants';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nombre', 'rol', 'descripcion', 'system_prompt',
        'provider', 'modelo',
        'creatividad', 'formalidad', 'brevedad', 'empatia',
        'capacidades',
        'respetar_horario', 'hora_inicio', 'hora_fin',
        'palabras_clave',
        'mensaje_bienvenida', 'mensaje_fuera_horario', 'mensaje_transferencia',
        'activo', 'creado_por', 'cod_empresa', 'estado',
    ];

    protected $casts = [
        'id'               => 'string',
        'creado_por'       => 'string',
        'cod_empresa'      => 'string',
        'capacidades'      => 'array',
        'palabras_clave'   => 'array',
        'respetar_horario' => 'boolean',
        'activo'           => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function creadoPor()
    {
        return $this->belongsTo(Usuario::class, 'creado_por', 'uuid');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'cod_empresa', 'uuid');
    }
}
