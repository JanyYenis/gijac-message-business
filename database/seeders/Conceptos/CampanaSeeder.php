<?php

namespace Database\Seeders\Conceptos;

use App\Models\Campana;
use App\Models\Sistema\Concepto;
use App\Models\Sistema\TipoConcepto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->tipoConceptosEstados();
        $this->tipoConceptosTipos();
        $this->tipoConceptosCategoria();
    }

    public function tipoConceptosEstados()
    {
        $tc_estado = TipoConcepto::updateOrCreate([
            'nombre' => Campana::TC_ESTADO,
        ], [
            'descripcion' => 'Estados de las campañas.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Campana::ENVIADO,
        ], [
            'nombre' => 'Enviado',
            'estado' => Concepto::ACTIVO,
            'icono' => 'fas fa-check-circle',
            'color' => 'primary',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Campana::PENDIENTE,
        ], [
            'nombre' => 'Pendiente',
            'estado' => Concepto::ACTIVO,
            'icono' => 'far fa-times-fas fa-clock',
            'color' => 'warning',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Campana::CANCELADO,
        ], [
            'nombre' => 'Cancelado',
            'estado' => Concepto::ACTIVO,
            'icono' => 'far fa-times-fas fa-clock',
            'color' => 'danger',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => Campana::ELIMINADO,
        ], [
            'nombre' => 'Eliminado',
            'estado' => Concepto::ACTIVO,
            'icono' => 'far fa-times-circle',
            'color' => 'danger',
        ]);
    }

    public function tipoConceptosTipos()
    {
        $tc_tipo = TipoConcepto::updateOrCreate([
            'nombre' => Campana::TC_TIPO_ENVIO,
        ], [
            'descripcion' => 'Tipos de campañas.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo?->id,
            'codigo' => Campana::TEXTO,
        ], [
            'nombre' => 'Texto',
            'estado' => Concepto::ACTIVO,
            'icono' => 'fa-solid fa-align-left',
            'color' => 'primary',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo?->id,
            'codigo' => Campana::IMAGEN,
        ], [
            'nombre' => 'Imagen',
            'estado' => Concepto::ACTIVO,
            'icono' => 'fa-solid fa-image',
            'color' => 'warning',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo?->id,
            'codigo' => Campana::VIDEO,
        ], [
            'nombre' => 'Video',
            'estado' => Concepto::ACTIVO,
            'icono' => 'fa-solid fa-video',
            'color' => 'danger',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo?->id,
            'codigo' => Campana::DOCUMENTO,
        ], [
            'nombre' => 'Documento',
            'estado' => Concepto::ACTIVO,
            'icono' => 'fa-solid fa-file-lines',
            'color' => 'danger',
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_tipo?->id,
            'codigo' => Campana::UBICACION,
        ], [
            'nombre' => 'Ubicacion',
            'estado' => Concepto::ACTIVO,
            'icono' => 'fa-solid fa-location-dot',
            'color' => 'danger',
        ]);
    }

    public function tipoConceptosCategoria()
    {
        $tc_categoria = TipoConcepto::updateOrCreate([
            'nombre' => Campana::TC_CATEGORIA,
        ], [
            'descripcion' => 'Tipos de categorias.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_categoria?->id,
            'codigo' => Campana::PROMOCIONAL,
        ], [
            'nombre' => 'Promocional',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_categoria?->id,
            'codigo' => Campana::INFORMATIVO,
        ], [
            'nombre' => 'Informativo',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_categoria?->id,
            'codigo' => Campana::TRANSACIONAL,
        ], [
            'nombre' => 'Transacional',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_categoria?->id,
            'codigo' => Campana::RECORDATORIO,
        ], [
            'nombre' => 'Recordatorio',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_categoria?->id,
            'codigo' => Campana::FORMULARIO,
        ], [
            'nombre' => 'Formulario',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_categoria?->id,
            'codigo' => Campana::SERVICIO,
        ], [
            'nombre' => 'Servicio',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_categoria?->id,
            'codigo' => Campana::AUTENTICACION,
        ], [
            'nombre' => 'Autenticación',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_categoria?->id,
            'codigo' => Campana::OTRO,
        ], [
            'nombre' => 'Otro',
            'estado' => Concepto::ACTIVO,
        ]);
    }
}
