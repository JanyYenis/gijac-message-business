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
        'es_prueba',
        'estado',
    ];

    protected $casts = [
        'id' => 'string',
        'number_registered' => 'boolean',
        'principal' => 'boolean',
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
}
