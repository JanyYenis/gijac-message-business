<?php

namespace Database\Seeders\Conceptos;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Plantilla;
use App\Models\PlantillaComponente;
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

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Plantilla::PAUSADO,
        ], [
            'nombre' => 'Pausado',
            'estado' => Concepto::ACTIVO,
            'color' => 'warning',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Plantilla::DESHABILITADO,
        ], [
            'nombre' => 'Deshabilitado',
            'estado' => Concepto::ACTIVO,
            'color' => 'danger',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Plantilla::EN_APELACION,
        ], [
            'nombre' => 'En apelación',
            'estado' => Concepto::ACTIVO,
            'color' => 'info',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Plantilla::LIMITE_EXCEDIDO,
        ], [
            'nombre' => 'Límite excedido',
            'estado' => Concepto::ACTIVO,
            'color' => 'warning',
        ]);

        // Tipos de componente y formatos de encabezado (incluye los nuevos: oferta limitada/carrusel guardados como "Otro" y encabezado de producto)
        $tc_tipo = TipoConcepto::updateOrCreate([
            'nombre' => PlantillaComponente::TC_TIPO_PLANTILLAS_COMPONENTES,
        ], [
            'descripcion' => 'Tipos de componentes de las plantillas de META.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        $tipos = [
            PlantillaComponente::HEADER => 'Encabezado',
            PlantillaComponente::BODY => 'Cuerpo',
            PlantillaComponente::FOOTER => 'Pie de página',
            PlantillaComponente::BUTTONS => 'Botones',
            PlantillaComponente::OTRO => 'Otro (carrusel, oferta por tiempo limitado…)',
        ];
        foreach ($tipos as $codigo => $nombre) {
            Concepto::updateOrCreate([
                'id_tipo' => $tc_tipo?->id,
                'codigo' => $codigo,
            ], ['nombre' => $nombre, 'estado' => Concepto::ACTIVO, 'color' => 'primary']);
        }

        $tc_formato = TipoConcepto::updateOrCreate([
            'nombre' => PlantillaComponente::TC_FORMATO_ENCABEZADO,
        ], [
            'descripcion' => 'Formatos del encabezado de las plantillas de META.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        $formatos = [
            PlantillaComponente::TEXTO => 'Texto',
            PlantillaComponente::IMAGEN => 'Imagen',
            PlantillaComponente::VIDEO => 'Video',
            PlantillaComponente::DOCUMENTO => 'Documento',
            PlantillaComponente::LOCALIZACION => 'Ubicación',
            PlantillaComponente::N_A => 'N/A',
            PlantillaComponente::PRODUCTO => 'Producto',
        ];
        foreach ($formatos as $codigo => $nombre) {
            Concepto::updateOrCreate([
                'id_tipo' => $tc_formato?->id,
                'codigo' => $codigo,
            ], ['nombre' => $nombre, 'estado' => Concepto::ACTIVO, 'color' => 'primary']);
        }
    }
}
