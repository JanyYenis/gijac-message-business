<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class Plan extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    const TC_TIPO_PLANES = 'TC_TIPO_PLANES';
    const PEQUENA_EMPRESA = 1;
    const MEDIANA_EMPRESA = 2;
    const GRAN_EMPRESA    = 3;
    const PERSONALIZADO   = 4;

    const TC_TIPO_PLAN_FECHA = 'TC_TIPO_PLAN_FECHA';
    const MESUAL  = 1;
    const ANUAL   = 2;
    const OTRO    = 3;

    protected $table = 'planes';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nombre',
        'categoria',
        'tipo',
        'valor',
        'max_contactos',
        'estado'
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

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'plan_servicio')
                    ->withPivot('habilitado')
                    ->withTimestamps()
                    ->orderBy('nombre');
    }

    public function serviciosHabilitados()
    {
        return $this->servicios()->wherePivot('habilitado', PlanServicio::APLICA);
    }

    public function tieneServicio($slug)
    {
        return $this->servicios()
                    ->where('slug', $slug)
                    ->wherePivot('habilitado', PlanServicio::APLICA)
                    ->exists();
    }

    public function getValorFormateadoAttribute()
    {
        return '$' . number_format($this->valor, 2);
    }

    public function getTipoFormateadoAttribute()
    {
        return ucfirst($this->tipo);
    }

    public function getMaxContactosFormateadoAttribute()
    {
        return $this->max_contactos ? number_format($this->max_contactos) : 'Ilimitado';
    }

    public function infoTipo()
    {
        return darInfoConcepto($this, self::TC_TIPO_PLAN_FECHA, 'tipo')->selectRaw('conceptos.*');
    }

    public static function darTipo($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_TIPO_PLAN_FECHA, $infoTipoConcepto);
    }

    public function infoCategoria()
    {
        return darInfoConcepto($this, self::TC_TIPO_PLANES, 'categoria')->selectRaw('conceptos.*');
    }

    public static function darCategoria($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_TIPO_PLANES, $infoTipoConcepto);
    }
}
