<?php

namespace App\Models;

use App\Classes\Models\Model;

class PlantillaComponente extends Model
{
    protected $table = 'plantilla_componentes';

    const TC_TIPO_PLANTILLAS_COMPONENTES = 'TC_TIPO_PLANTILLAS_COMPONENTES';
    const HEADER  = 1;
    const BODY    = 2;
    const FOOTER  = 3;
    const BUTTONS = 4;

    const VALIDAR_TIPO = [
        'HEADER'  => self::HEADER,
        'BODY'    => self::BODY,
        'FOOTER'  => self::FOOTER,
        'BUTTONS' => self::BUTTONS,
    ];

    const TC_FORMATO_ENCABEZADO   = 'TC_FORMATO_ENCABEZADO';
    const TEXTO        = 1;
    const IMAGEN       = 2;
    const VIDEO        = 3;
    const DOCUMENTO    = 4;
    const LOCALIZACION = 5;

    const VALIDAR_FORMATO = [
        'TEXT'    => self::TEXTO,
        'IMAGE'    => self::IMAGEN,
        'VIDEO'    => self::VIDEO,
        'DOCUMENT' => self::DOCUMENTO,
        'LOCATION' => self::LOCALIZACION,
    ];

    protected $fillable = [
        'plantilla_id',
        'type',
        'format',
        'text',
        'example',
        'buttons',
    ];
}
