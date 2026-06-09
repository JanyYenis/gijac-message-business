<?php

namespace App\Models\Sistema;

use App\Classes\Models\Model;
use App\Models\Usuario;
use Illuminate\Support\Str;

class Actividad extends Model
{
    protected $table = 'actividades';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'accion',
        'actividable_type',
        'actividable_id',
        'changes',
    ];

    protected $casts = [
        'id' => 'string',
        'created_at' => 'date:d/m/Y',
    ];

    protected $dates = [
        'created_at' => 'date:d/m/Y',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function actividable()
    {
        return $this->morphTo();
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'uuid', 'uuid');
    }
}
