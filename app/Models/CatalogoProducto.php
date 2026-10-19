<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class CatalogoProducto extends Model
{
    protected $table = 'catalogo_productos';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'cod_config',
        'producto_id',
        'retailer_id',
        'name',
        'description',
        'precio',
        'precio_oferta',
        'moneda',
        'availability',
        'image_url',
        'url',
        'brand',
        'condition',
        'category',
        'review_status',
    ];

    protected $casts = [
        'id' => 'string',
        'precio' => 'float',
        'precio_oferta' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    /** Misma forma que devolvía la Graph API normalizada, para no tocar las vistas. */
    public function aArreglo(): array
    {
        return [
            'id' => $this->producto_id,
            'retailer_id' => $this->retailer_id,
            'name' => $this->name,
            'description' => $this->description,
            'availability' => $this->availability,
            'image_url' => $this->image_url,
            'url' => $this->url,
            'brand' => $this->brand,
            'condition' => $this->condition,
            'category' => $this->category,
            'review_status' => $this->review_status,
            'precio' => $this->precio,
            'precio_oferta' => $this->precio_oferta,
            'agotado' => str_starts_with((string) $this->availability, 'out'),
            'moneda' => $this->moneda ?: config('whatsapp.moneda'),
        ];
    }
}
