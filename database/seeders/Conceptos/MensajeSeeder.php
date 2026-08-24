<?php

namespace Database\Seeders\Conceptos;

use App\Models\Mensaje;
use App\Models\Sistema\Concepto;
use App\Models\Sistema\TipoConcepto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MensajeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->tipoConceptosTipoRespuesta();
    }

    public function tipoConceptosTipoRespuesta()
    {
        $tc_estado = TipoConcepto::updateOrCreate([
            'nombre' => Mensaje::TC_TIPO_RESPUESTA,
        ], [
            'descripcion' => 'Tipos de respuesta para los mensajes.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Mensaje::HUMANA,
        ], [
            'nombre' => 'Humana',
            'estado' => Concepto::ACTIVO,
            'icono' => 'fa-solid fa-user',
            'color' => 'info',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Mensaje::NODO,
        ], [
            'nombre' => 'Nodo',
            'estado' => Concepto::ACTIVO,
            'icono' => 'fa-solid fa-sitemap',
            'color' => 'info',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Mensaje::IA,
        ], [
            'nombre' => 'IA',
            'estado' => Concepto::ACTIVO,
            'icono' => 'fa-solid fa-robot',
            'color' => 'info',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Mensaje::N8N,
        ], [
            'nombre' => 'n8n',
            'estado' => Concepto::ACTIVO,
            'icono' => 'fa-solid fa-code',
            'color' => 'info',
        ]);
    }
}
