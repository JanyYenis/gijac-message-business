<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (string) $this->id, // Flutter prefiere strings para IDs
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'codigo_telefono' => $this->codigo_telefono,
            'telefono' => $this->telefono,
            'fecha' => $this->fecha ? Carbon::parse($this->fecha)->toIso8601String() : null,
            'mensajes_no_leidos' => (int) $this->mensajes_no_leidos,
            'mensaje' => $this->mensaje,
            'tipo_mensaje' => $this->tipo_mensaje,
        ];
    }
}
