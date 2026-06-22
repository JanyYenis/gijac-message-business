<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class Campana extends Model
{
    // Permisos
    const PERMISO_LISTADO = 'campana.listado';
    const PERMISO_CREAR   = 'campana.crear';

    const TC_ESTADO = 'TC_ESTADO_CAMPANAS';
    const ENVIADO    = 1;
    const PENDIENTE  = 2;
    const CANCELADO  = 3;
    const ELIMINADO  = 0;

    const TC_CATEGORIA = 'TC_CATEGORIA';
    const PROMOCIONAL  = 1;
    const INFORMATIVO  = 2;
    const TRANSACIONAL = 3;
    const RECORDATORIO = 4;
    const FORMULARIO   = 5;

    const TC_TIPO_ENVIO = 'TC_TIPO_ENVIO';
    const TEXTO     = 1;
    const IMAGEN    = 2;
    const VIDEO     = 3;
    const DOCUMENTO = 4;
    const UBICACION = 5;

    protected $table = 'campanas';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        "nombre",
        "descripcion",
        "contenido",
        "fecha_envio",
        "uuid",
        "contenido_multimedia",
        'id_plantilla',
        'tipo',
        'categoria',
        'cod_etiqueta',
        'estado',
    ];

    protected $casts = [
        'id' => 'string',
        "fecha_envio" => "date:d/m/Y",
    ];

    protected $dates = [
        "fecha_envio" => "date:d/m/Y",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function infoTipo()
    {
        return darInfoConcepto($this, self::TC_TIPO_ENVIO, 'tipo')->selectRaw('conceptos.*');
    }

    public static function darTipo($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_TIPO_ENVIO, $infoTipoConcepto);
    }

    public function infoCategoria()
    {
        return darInfoConcepto($this, self::TC_CATEGORIA, 'categoria')->selectRaw('conceptos.*');
    }

    public static function darCategoria($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_CATEGORIA, $infoTipoConcepto);
    }

    public function envios()
    {
        return $this->hasMany(EnvioCampana::class, 'cod_campana', 'id');
    }

    public function enviosActivos()
    {
        return $this->envios()->where('estado', EnvioCampana::ACTIVO);
    }

    public function mensajesAbiertos()
    {
        return $this->enviosActivos()->where('apertura', EnvioCampana::ABIERTO);
    }

    public function clicksAbiertos()
    {
        return $this->hasMany(DetalleLink::class, 'cod_campana', 'id')->where('estado', DetalleLink::ABIERTO);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'uuid', 'uuid');
    }

    public function etiqueta()
    {
        return $this->belongsTo(Etiqueta::class, 'cod_etiqueta', 'id');
    }

    public function plantilla()
    {
        return $this->belongsTo(Plantilla::class, 'id_plantilla', 'id');
    }
}
