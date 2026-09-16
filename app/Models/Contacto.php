<?php

namespace App\Models;

use App\Classes\Models\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Contacto extends Model
{
    // Permisos
    const PERMISO_LISTADO   = 'contactos.listado';
    const PERMISO_CREAR     = 'contactos.crear';
    const PERMISO_EDITAR    = 'contactos.editar';
    const PERMISO_ELIMINAR  = 'contactos.eliminar';

    protected $table = 'contactos';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $appends = ['nombre_completo', 'permiso_envio'];

    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    const TC_GENERO_USUARIOS = 'TC_GENERO_USUARIOS';
    const MASCULINO = 1;
    const FEMENINO  = 2;

    const TC_TIPO_DOCUMENTO = 'TC_TIPO_DOCUMENTO';
    const CC = 1;
    const TI = 2;
    const PP = 3;

    const ACEPTO_POLITICAS    = 1;
    const NO_ACEPTO_POLITICAS = 2;
    const PENDIENTE_POLITICAS = 0;

    protected $fillable = [
        'nombre',
        'apellido',
        'genero',
        'telefono',
        'codigo_telefono',
        'numero_completo',
        'estado',
        'uuid',
        'cod_empresa',
        'tratamiento_datos',
        'preferencia',
        'estado_chatbot',
        'estado_chatbot_ia',
    ];

    protected $casts = [
        'id' => 'string',
        'uuid' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
        static::saving(function ($model) {
            $model->numero_completo =
                $model->codigo_telefono .
                $model->telefono;
        });
    }

    public function enviosCampanas()
    {
        return $this->hasMany(EnvioCampana::class, 'cod_contacto', 'id');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'cod_ciudad', 'id');
    }

    public function etiquetas()
    {
        return $this->hasMany(EtiquetaContacto::class, 'cod_contacto', 'id');
    }

    public function etiqueta()
    {
        return $this->hasOne(EtiquetaContacto::class, 'cod_contacto', 'id');
    }

    public function etiquetasActivas()
    {
        return $this->etiquetas()->where('estado', EtiquetaContacto::ACTIVO);
    }

    public function etiquetaActiva()
    {
        return $this->etiqueta()->where('estado', EtiquetaContacto::ACTIVO);
    }

    public function infoGenero()
    {
        return darInfoConcepto($this, self::TC_GENERO_USUARIOS, 'genero')->selectRaw('conceptos.*');
    }

    public static function darTipoGenero($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_GENERO_USUARIOS, $infoTipoConcepto);
    }

    public function infoEstadoChatbot()
    {
        return darInfoConcepto($this, self::TC_ESTADO, 'estado_chatbot')->selectRaw('conceptos.*');
    }

    public static function darEstadoChatbot($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_ESTADO, $infoTipoConcepto);
    }

    public function getNombreCompletoAttribute()
    {
        $nombre = $this?->nombre ?? 'N/A';
        $apellido = '';
        if ($this->apellido) {
            $apellido = $this->apellido;
        }

        return $nombre.' '.$apellido;
    }

    public function getPermisoEnvioAttribute()
    {
        $configuracion_meta = ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)
            ->where('cod_empresa', $this->cod_empresa)
            ->first();

        if (!$configuracion_meta) {
            return false;
        }

        // Último mensaje que el contacto te envió (abre ventana de 24h)
        $ultimoMensajeUsuario = Mensaje::where('wa_from', $this->numero_completo)
            ->where('wa_to', $configuracion_meta->phone_number_id)
            ->max('created_at');

        // Última plantilla tuya entregada o leída (también abre/reinicia ventana)
        $ultimaPlantillaEntregada = Mensaje::where('wa_from', $configuracion_meta->phone_number_id)
            ->where('wa_to', $this->numero_completo)
            ->where('type', Mensaje::PLANTILLA)
            ->whereIn('estado', [Mensaje::ENTREGADO, Mensaje::LEIDO])
            ->max('updated_at');

        $inicioVentana = collect([$ultimoMensajeUsuario, $ultimaPlantillaEntregada])
            ->filter()
            ->map(fn ($fecha) => Carbon::parse($fecha))
            ->max();

        if (!$inicioVentana) {
            return false;
        }

        return $inicioVentana->diffInHours(Carbon::now()) < 24;
    }

    public function conversacion()
    {
        return $this->hasOne(Conversacion::class, 'contacto_id', 'id');
    }
}
