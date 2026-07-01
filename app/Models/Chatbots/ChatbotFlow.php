<?php

namespace App\Models\Chatbots;

use App\Classes\Models\Model;
use App\Models\Usuario;
use Illuminate\Support\Str;

class ChatbotFlow extends Model
{
    // Permisos
    const PERMISO_LISTADO   = 'chatbot_flow.listado';
    const PERMISO_CREAR     = 'chatbot_flow.crear';
    const PERMISO_EDITAR    = 'chatbot_flow.editar';
    const PERMISO_ELIMINAR  = 'chatbot_flow.eliminar';
    const PERMISO_PUBLICAR  = 'chatbot_flow.publicar';

    // Estados del flujo
    const TC_ESTADO     = 'TC_ESTADO_CHATBOT_FLOW';
    const BORRADOR      = 1;
    const ACTIVO        = 2;
    const INACTIVO      = 3;
    const ARCHIVADO     = 4;

    // Canales de despliegue
    const TC_CANAL      = 'TC_CANAL_CHATBOT';
    const WHATSAPP      = 1;
    const TELEGRAM      = 2;
    const WEB           = 3;

    protected $table = 'chatbot_flows';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'canal',
        'estado',
        'versión_actual',
        'fecha_publicado',
        'creado_por',
        'cod_empresa',
        'ajustes',
    ];

    protected $casts = [
        'id'             => 'string',
        'creado_por'     => 'string',
        'ajustes'        => 'array',
        'fecha_publicado' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    // ─── Helpers de constantes ───────────────────────────────────────────────

    public function infoEstado()
    {
        return darInfoConcepto($this, self::TC_ESTADO, 'estado')->selectRaw('conceptos.*');
    }

    public static function darEstado($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_ESTADO, $infoTipoConcepto);
    }

    public function infoCanal()
    {
        return darInfoConcepto($this, self::TC_CANAL, 'canal')->selectRaw('conceptos.*');
    }

    public static function darCanal($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_CANAL, $infoTipoConcepto);
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function nodos()
    {
        return $this->hasMany(ChatbotNode::class, 'flow_id', 'id');
    }

    public function conexiones()
    {
        return $this->hasMany(ChatbotConnection::class, 'flow_id', 'id');
    }

    public function variables()
    {
        return $this->hasMany(ChatbotVariable::class, 'flow_id', 'id');
    }

    public function versiones()
    {
        return $this->hasMany(ChatbotFlowVersion::class, 'flow_id', 'id');
    }

    public function versionPublicada()
    {
        return $this->hasOne(ChatbotFlowVersion::class, 'flow_id', 'id')
            ->where('estado', ChatbotFlowVersion::PUBLICADO)
            ->latest('numero_version');
    }

    public function sesiones()
    {
        return $this->hasMany(ChatbotSession::class, 'flow_id', 'id');
    }

    public function sesionesActivas()
    {
        return $this->sesiones()->where('estado', ChatbotSession::ACTIVO);
    }

    public function creadoPor()
    {
        return $this->belongsTo(Usuario::class, 'creado_por', 'uuid');
    }

    public function nodoInicio()
    {
        return $this->hasOne(ChatbotNode::class, 'flow_id', 'id')
            ->where('tipo', ChatbotNode::START); // Usamos la constante definida en el modelo Node
    }
}
