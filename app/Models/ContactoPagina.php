<?php

namespace App\Models;

use App\Classes\Models\Model;
use Illuminate\Support\Str;

class ContactoPagina extends Model
{
    protected $table = 'contacto_paginas';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        "nombre" ,
        "apellido" ,
        "email",
        "phone",
        "company" ,
        "asunto",
        "mensaje",
        "privacidad",
        "newsletter",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
