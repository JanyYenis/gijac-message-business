<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    const TC_ESTADO = 'TC_ESTADO_GENERAL';
    const ACTIVO    = 1;
    const INACTIVO  = 2;
    const ELIMINADO = 0;

    protected $table = 'facturas';

    protected $fillable = [
        'x_cust_id_cliente',
        'x_ref_payco',
        'bank',
        'invoice',
        'description',
        'value',
        'tax',
        'tax_base',
        'x_franchise',
        'x_response',
        'x_signature',
        'currency',
        'type_person',
        'doc_type',
        'doc_number',
        'name',
        'last_name',
        'email',
        'country',
        'cell_phone',
        'x_customer_ip',
        'x_customer_address',
        'cod_plan',
        'cod_usuario',
        'tiempo',
        'fecha_vencimiento',
    ];

    protected $casts = [
        "fecha_vencimiento" => "date:d/m/Y g:i a",
        "created_at" => "date:d/m/Y",
        "updated_at" => "date:d/m/Y",
    ];

    protected $dates = [
        "fecha_vencimiento" => "date:d/m/Y g:i a",
        "created_at" => "date:d/m/Y",
        "updated_at" => "date:d/m/Y",
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'cod_plan', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'cod_usuario', 'id');
    }
}
