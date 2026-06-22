<?php

namespace App\Models;

use App\Classes\Models\User;
use App\Notifications\CustomVerifyEmail;
use App\Notifications\RecuperarContrasena;
use App\Traits\Actividable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class Usuario extends User implements MustVerifyEmail
{
    use HasRoles, Actividable;

    // Roles
    const ROL_SUPER_ADMINISTRADOR = 'super_admin';
    const ROL_ADMINISTRADOR       = 'admin';
    const ROL_CLIENTE             = 'cliente';
    const ROL_AGENTE              = 'agente';

    const PERMISO_ROLES_LISTADO   = 'roles.listado';
    const PERMISO_ROLES_CREAR     = 'roles.crear';
    const PERMISO_ROLES_EDITAR    = 'roles.editar';
    const PERMISO_ROLES_ELIMINAR  = 'roles.eliminar';

    // PERMISOS USUARIOS
    const PERMISO_LISTADO   = 'usuarios.listado';
    const PERMISO_CREAR     = 'usuarios.crear';
    const PERMISO_EDITAR    = 'usuarios.editar';
    const PERMISO_ELIMINAR  = 'usuarios.eliminar';
    const PERMISO_AGREGAR_ROL      = 'usuarios.agregar-rol';
    const PERMISO_AGREGAR_PERMIDO  = 'usuarios.agregar-permiso';

    const PERMISO_PLANES_LISTADO   = 'planes.listado';
    const PERMISO_PLANES_CREAR     = 'planes.crear';
    const PERMISO_PLANES_EDITAR    = 'planes.editar';
    const PERMISO_PLANES_ELIMINAR  = 'planes.eliminar';

    const PERMISO_PLANTILLAS_LISTADO   = 'plantillas.listado';
    const PERMISO_PLANTILLAS_CREAR     = 'plantillas.crear';
    const PERMISO_PLANTILLAS_EDITAR    = 'plantillas.editar';
    const PERMISO_PLANTILLAS_ELIMINAR  = 'plantillas.eliminar';

    const PERMISO_CAMPANA_LISTADO   = 'campana.listado';
    const PERMISO_CAMPANA_CREAR     = 'campana.crear';
    const PERMISO_CAMPANA_EDITAR    = 'campana.editar';
    const PERMISO_CAMPANA_ELIMINAR  = 'campana.eliminar';

    const PERMISO_CLIENTES_LISTADO   = 'clientes.listado';
    const PERMISO_CLIENTES_CREAR     = 'clientes.crear';
    const PERMISO_CLIENTES_EDITAR    = 'clientes.editar';
    const PERMISO_CLIENTES_ELIMINAR  = 'clientes.eliminar';

    const PERMISO_ETIQUETAS_LISTADO   = 'etiquetas.listado';
    const PERMISO_ETIQUETAS_CREAR     = 'etiquetas.crear';
    const PERMISO_ETIQUETAS_EDITAR    = 'etiquetas.editar';
    const PERMISO_ETIQUETAS_ELIMINAR  = 'etiquetas.eliminar';

    const PERMISO_PERFIL_WHATSAPP_LISTADO   = 'perfil.whatsapp.listado';
    const PERMISO_PERFIL_WHATSAPP_CREAR     = 'perfil.whatsapp.crear';
    const PERMISO_PERFIL_WHATSAPP_EDITAR    = 'perfil.whatsapp.editar';
    const PERMISO_PERFIL_WHATSAPP_ELIMINAR  = 'perfil.whatsapp.eliminar';

    // PERMISOS TICKETS
    const PERMISO_TICKETS_CREAR = 'tickets.crear';
    const PERMISO_TICKETS_LISTADO = 'tickets.listado';
    const PERMISO_TICKETS_EDITAR = 'tickets.editar';
    const PERMISO_TICKETS_ELIMINAR = 'tickets.eliminar';
    const PERMISO_TICKETS_ASIGNAR_RESPONSABLE = 'tickets.asignar.responsable';

    // PERMISOS CHATBOTS
    const PERMISO_CHATBOT_LISTADO   = 'chatbot.listado';
    const PERMISO_CHATBOT_CREAR     = 'chatbot.crear';
    const PERMISO_CHATBOT_EDITAR    = 'chatbot.editar';
    const PERMISO_CHATBOT_ELIMINAR  = 'chatbot.eliminar';

    // PERMISOS CONDIGURACION WHATSAPP
    const PERMISO_CONFIGURACION_META_LISTADO   = 'configuracion.meta.listado';
    const PERMISO_CONFIGURACION_META_CREAR     = 'configuracion.meta.crear';
    const PERMISO_CONFIGURACION_META_EDITAR    = 'configuracion.meta.editar';
    const PERMISO_CONFIGURACION_META_ELIMINAR  = 'configuracion.meta.eliminar';

    // PERMISOS CLASIFICACION IA
    const PERMISO_CLASIFICACION_IA_LISTADO   = 'clasificacion.ia.listado';
    const PERMISO_CLASIFICACION_IA_CREAR     = 'clasificacion.ia.crear';
    const PERMISO_CLASIFICACION_IA_EDITAR    = 'clasificacion.ia.editar';
    const PERMISO_CLASIFICACION_IA_ELIMINAR  = 'clasificacion.ia.eliminar';

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

    protected $table = 'usuarios';
    protected $appends = ['nombre_completo', 'numero_completo'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'nombre',
        'apellido',
        'genero',
        'tipo_identificacion',
        'identificacion',
        'email',
        'password',
        'telefono',
        'codigo_telefono',
        'cod_ciudad',
        'foto',
        'estado',
        'google2fa_secret',
        'demo',
        'cod_plan',
        'cod_empresa',
        'external_id',
    ];

    protected $with = [
        'ciudad.pais',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'uuid' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        // 'identificacion' => 'encrypted',
    ];

    protected $dates = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    /**
     * Interact with the user's first name.
     *
     * @param  string  $value
     */
    protected function google2faSecret(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) {
                    return null;
                }

                try {
                    return Crypt::decrypt($value);
                } catch (\Exception $e) {
                    // Si no puede desencriptar, devuelve el valor original para evitar errores
                    return $value;
                }
            },

            set: function ($value) {
                if (!$value) {
                    return null;
                }

                // Si llega encriptado, no lo vuelvas a encriptar
                if ($this->isEncrypted($value)) {
                    return $value;
                }

                return Crypt::encrypt($value);
            }
        );
    }

    private function isEncrypted($value)
    {
        try {
            Crypt::decrypt($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'cod_ciudad', 'id');
    }

    public function autenticaciones()
    {
        return $this->hasMany(Autenticacion::class, 'cod_usuario', 'id');
    }

    public function autenticacion()
    {
        return $this->hasOne(Autenticacion::class, 'cod_usuario', 'id');
    }

    public function configuracion()
    {
        return $this->hasOne(ConfiguracionMeta::class, 'cod_usuario', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'cod_plan', 'id');
    }

    public function infoGenero()
    {
        return darInfoConcepto($this, self::TC_GENERO_USUARIOS, 'genero')->selectRaw('conceptos.*');
    }

    public function infoDocumento()
    {
        return darInfoConcepto($this, self::TC_TIPO_DOCUMENTO, 'tipo_identificacion')->selectRaw('conceptos.*');
    }

    public static function darTipoGenero($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_GENERO_USUARIOS, $infoTipoConcepto);
    }

    public static function darTipoDocumento($infoTipoConcepto = false)
    {
        return darConceptos(self::TC_TIPO_DOCUMENTO, $infoTipoConcepto);
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

    public function getNumeroCompletoAttribute()
    {
        $tel = $this->codigo_telefono.$this->telefono;

        return $tel;
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'cod_empresa', 'id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new RecuperarContrasena($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
}
