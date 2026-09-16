<?php

namespace Database\Seeders\Conceptos;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Plantilla;
use App\Models\Sistema\Concepto;
use App\Models\Sistema\TipoConcepto;
use Illuminate\Database\Seeder;

class PlantillaSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->tipoConceptos();
    }

    public function tipoConceptos()
    {
        $tc_estado = TipoConcepto::updateOrCreate([
            'nombre' => Plantilla::TC_ESTADO,
        ], [
            'descripcion' => 'Estados de las plantillas de META.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Plantilla::APROBADO,
        ], [
            'nombre' => 'Aprobado',
            'estado' => Concepto::ACTIVO,
            'color' => 'primary',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Plantilla::PENDIENTE,
        ], [
            'nombre' => 'Pendiente',
            'estado' => Concepto::ACTIVO,
            'color' => 'warning',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Plantilla::RECHAZADO,
        ], [
            'nombre' => 'Rechazado',
            'estado' => Concepto::ACTIVO,
            'color' => 'danger',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Plantilla::ELIMINADO,
        ], [
            'nombre' => 'Eliminado',
            'estado' => Concepto::ACTIVO,
            'color' => 'danger',
        ]);
    }
}
