<?php

namespace App\Models;

use App\Classes\Models\Model;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Etiqueta extends Model
{
    protected $table = 'etiquetas';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $fillable = [
        "slug",
        "nombre",
        "descripcion",
        "color",
        "nombre",
        "uuid",
        "cod_empresa",
        "estado",
    ];

    protected $casts = [
        'id' => 'string',
        'uuid' => 'string',
        "updated_at" => "date:d/m/Y",
        "created_at" => "date:d/m/Y",
    ];

    protected $dates = [
        "created_at" => "date:d/m/Y ",
        "updated_at" => "date:d/m/Y ",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
            $model->slug = Str::slug($model->nombre, '.');
        });

        static::updating(function ($model) {
            if ($model->isDirty('nombre')) {
                $model->slug = Str::slug($model->nombre, '.');
            }
        });
    }

    public function contactosEtiquetas()
    {
        return $this->hasMany(EtiquetaContacto::class, 'cod_etiqueta', 'id');
    }

    public function contactoEtiqueta()
    {
        return $this->hasOne(EtiquetaContacto::class, 'cod_etiqueta', 'id');
    }

    public function contactosEtiquetasActivos()
    {
        return $this->contactosEtiquetas()->where('estado', EtiquetaContacto::ACTIVO);
    }

    public function contactoEtiquetaActivo()
    {
        return $this->contactoEtiqueta()->where('estado', EtiquetaContacto::ACTIVO);
    }

    /** Usuarios activos de la empresa del usuario autenticado, para elegir quién atiende una etiqueta */
    public static function agentesDisponibles()
    {
        $uuids = DB::table('usuarios_empresas')
            ->where('cod_empresa', auth()->user()->empresa?->id)
            ->pluck('cod_usuario');

        return Usuario::whereIn('uuid', $uuids)
            ->where('estado', Usuario::ACTIVO)
            ->orderBy('nombre')
            ->get(['uuid', 'nombre', 'apellido']);
    }

    /** Deja exactamente estos agentes (solo de la misma empresa) atendiendo la etiqueta */
    public function sincronizarAgentes(array $uuids): void
    {
        $validos = self::agentesDisponibles()->pluck('uuid')->intersect($uuids)->values();

        DB::transaction(function () use ($validos) {
            DB::table('etiqueta_agentes')->where('etiqueta_id', $this->id)->delete();
            foreach ($validos as $uuid) {
                DB::table('etiqueta_agentes')->insert([
                    'etiqueta_id' => $this->id,
                    'usuario_uuid' => $uuid,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
