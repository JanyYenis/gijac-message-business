<?php

namespace Database\Seeders\Conceptos;

use App\Models\Sistema\Concepto;
use App\Models\Sistema\TipoConcepto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdiomasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->tipoConceptosIdiomas();
    }

    public function tipoConceptosIdiomas()
    {
        $tc_estado = TipoConcepto::updateOrCreate([
            'nombre' => 'TC_IDIOMAS_SISTEMA',
        ], [
            'descripcion' => 'Estos son los idiomas del sistema.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => 1,
        ], [
            'nombre' => 'Español',
            'nombre_corto' => 'es',
            'estado' => Concepto::ACTIVO,
            'icono' => 'assets/media/flags/spain.svg'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => 2,
        ], [
            'nombre' => 'Inglés',
            'nombre_corto' => 'en',
            'estado' => Concepto::ACTIVO,
            'icono' => 'assets/media/flags/united-states.svg'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => 3,
        ], [
            'nombre' => 'Alemán',
            'nombre_corto' => 'de',
            'estado' => Concepto::ACTIVO,
            'icono' => 'assets/media/flags/germany.svg'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => 4,
        ], [
            'nombre' => 'Japonés',
            'nombre_corto' => 'ja',
            'estado' => Concepto::ACTIVO,
            'icono' => 'assets/media/flags/japan.svg'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => 5,
        ], [
            'nombre' => 'Francés',
            'nombre_corto' => 'fr',
            'estado' => Concepto::ACTIVO,
            'icono' => 'assets/media/flags/france.svg'
        ]);
    }
}
