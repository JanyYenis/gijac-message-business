<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class WhatsappNumber extends Model
{
    protected $table = 'whatsapp_numbers';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $hidden = ['two_factor_pin'];

    protected $fillable = [
        'whatsapp_account_id',
        'cod_empresa',
        'waba_id',
        'phone_number_id',
        'phone_number',
        'display_name',
        'two_factor_pin',
        'number_registered',
        'principal',
        'bot_activo',
        'es_prueba',
        'estado',
    ];

    protected $casts = [
        'id' => 'string',
        'number_registered' => 'boolean',
        'principal' => 'boolean',
        'bot_activo' => 'boolean',
        'es_prueba' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function cuenta()
    {
        return $this->belongsTo(WhatsappAccount::class, 'whatsapp_account_id', 'id');
    }

    /**
     * Número que debe usar un usuario: el que tiene asignado (si sigue conectado)
     * o, si no tiene ninguno, el principal de su empresa.
     */
    public static function paraUsuario($usuario): ?self
    {
        $empresa = $usuario?->empresa?->id ?? $usuario?->cod_empresa;
        if (! $empresa) {
            return null;
        }

        // Cuenta activa: la conectada más reciente de la empresa (la que se está usando ahora)
        $cuentaActiva = WhatsappAccount::where('cod_empresa', $empresa)
            ->where('estado', WhatsappAccount::CONECTADO)
            ->orderByDesc('created_at')
            ->first();

        $asignados = fn () => static::query()
            ->join('whatsapp_number_usuarios as a', 'a.whatsapp_number_id', '=', 'whatsapp_numbers.id')
            ->where('a.usuario_uuid', $usuario->uuid)
            ->where('whatsapp_numbers.cod_empresa', $empresa)
            ->where('whatsapp_numbers.estado', WhatsappAccount::CONECTADO)
            ->select('whatsapp_numbers.*');

        $principales = fn () => static::where('cod_empresa', $empresa)
            ->where('estado', WhatsappAccount::CONECTADO)
            ->where('principal', true);

        if ($cuentaActiva) {
            // 1) el asignado al usuario en la cuenta activa  2) el principal de la cuenta activa
            $numero = $asignados()->where('whatsapp_numbers.whatsapp_account_id', $cuentaActiva->id)->first()
                ?? $principales()->where('whatsapp_account_id', $cuentaActiva->id)->first();
            if ($numero) {
                return $numero;
            }
        }

        // Respaldo: cualquier asignado o principal conectado de la empresa
        return $asignados()->first() ?? $principales()->first();
    }

    /** phone_number_id que debe usar el usuario (asignado o principal); 404 si la empresa no tiene número */
    public static function idParaUsuario($usuario): string
    {
        return static::paraUsuario($usuario)?->phone_number_id
            ?? WhatsappAccount::where('cod_empresa', $usuario?->empresa?->id)->firstOrFail()->phone_number_id;
    }
}
