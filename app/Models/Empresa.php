<?php

namespace App\Models;

use App\Classes\Models\Model;
use App\Models\Chatbots\ChatbotAiAssistant;
use App\Models\Chatbots\ChatbotFlow;
use Illuminate\Support\Str;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $fillable = [
        'razon_social',
        'nit',
        'direccion',
        'email',
        'telefono',
        'instagram',
        'facebook',
        'tiktok',
        'linkendin',
        'web',
        'descripcion',
        'cod_usuario',
        'foto',
        'estado',
        'publicar',
        'notificacion',
    ];

    protected $dates = [
        "created_at" => "date:d/m/Y ",
        "updated_at" => "date:d/m/Y ",
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            "created_at" => "date:d/m/Y",
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class, 'cod_empresa', 'id');
    }

    public function factura()
    {
        return $this->hasOne(Factura::class, 'cod_empresa', 'id');
    }

    public function facturaVigente()
    {
        return $this->factura()
            ->whereDate('fecha_vencimiento', '>=', now())
            ->where('x_response', 'Aceptada');
    }

    public function nodos()
    {
        return $this->hasMany(ChatbotFlow::class, 'cod_empresa', 'id');
    }

    public function nodo()
    {
        return $this->hasOne(ChatbotFlow::class, 'cod_empresa', 'id');
    }

    public function nodoActivo()
    {
        return $this->nodo()
            ->where('estado', ChatbotFlow::ACTIVO)
            ->whereDate('fecha_publicado', '<=', now());
    }

    public function asistente()
    {
        return $this->hasOne(ChatbotAiAssistant::class, 'cod_empresa', 'id');
    }

    public function asistenteActivo()
    {
        return $this->asistente()
            ->where('activo', ChatbotAiAssistant::ACTIVO);
    }

    public function automatizacionN8n()
    {
        return $this->hasOne(AutomatizacionN8n::class, 'cod_empresa', 'id');
    }

    public function automatizacionN8nActiva()
    {
        return $this->automatizacionN8n()
            ->where('webhook_activo', AutomatizacionN8n::ACTIVO);
    }
}
