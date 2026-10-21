<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    // Permisos (scopes) que se pueden dar a una llave. scopes = null -> acceso completo (llaves anteriores)
    const SCOPE_MENSAJES_ENVIAR = 'messages.send';
    const SCOPE_CONTACTOS_LEER  = 'contacts.read';
    const SCOPE_CONTACTOS_ESCRIBIR = 'contacts.write';
    const SCOPE_ETIQUETAS_LEER  = 'tags.read';
    const SCOPE_ETIQUETAS_ESCRIBIR = 'tags.write';
    const SCOPE_CAMPANAS_LEER   = 'campaigns.read';

    const SCOPES = [
        self::SCOPE_MENSAJES_ENVIAR    => 'Enviar mensajes y plantillas',
        self::SCOPE_CONTACTOS_LEER     => 'Consultar contactos',
        self::SCOPE_CONTACTOS_ESCRIBIR => 'Crear y editar contactos',
        self::SCOPE_ETIQUETAS_LEER     => 'Consultar etiquetas',
        self::SCOPE_ETIQUETAS_ESCRIBIR => 'Asignar y quitar etiquetas',
        self::SCOPE_CAMPANAS_LEER      => 'Consultar campañas',
    ];

    protected $table = 'api_keys';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'cod_empresa',
        'etiqueta',
        'prefijo',
        'key_hash',
        'scopes',
        'ultimo_uso',
        'estado',
    ];

    protected $hidden = ['key', 'key_hash'];

    protected $casts = [
        'id' => 'string',
        'scopes' => 'array',
        'ultimo_uso' => 'datetime',
        "created_at" => "date:d/m/Y",
    ];

    protected $dates = [
        "created_at" => "date:d/m/Y",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public static function generate()
    {
        return 'GMB-' . Str::random(40);
    }

    public static function hashear(string $llave): string
    {
        return hash('sha256', $llave);
    }

    /** Crea la llave guardando solo su hash. Devuelve [modelo, llave en texto plano] (la llave no se puede volver a ver). */
    public static function crearConLlave(array $datos): array
    {
        $llave = self::generate();
        $modelo = self::create($datos + [
            'prefijo' => substr($llave, 0, 8),
            'key_hash' => self::hashear($llave),
        ]);

        return [$modelo, $llave];
    }

    public function tieneScope(string $scope): bool
    {
        return $this->scopes === null || in_array($scope, $this->scopes, true);
    }

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'uuid', 'uuid');
    }
}
