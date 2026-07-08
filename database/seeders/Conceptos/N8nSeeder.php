<?php

namespace Database\Seeders\Conceptos;

use App\Models\AutomatizacionN8n;
use App\Models\AutomatizacionN8nEjecucion;
use App\Models\AutomatizacionN8nEvento;
use App\Models\Sistema\Concepto;
use App\Models\Sistema\TipoConcepto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class N8nSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->tipoConceptosEstados();
        $this->tipoConceptosMetodosHttp();
        $this->tipoConceptosEventos();
    }

    public function tipoConceptosEstados()
    {
        $tc_estado = TipoConcepto::updateOrCreate([
            'nombre' => AutomatizacionN8n::TC_ESTADO,
        ], [
            'descripcion' => 'Estados de las automaticaciones con n8n.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => AutomatizacionN8n::ACTIVO,
        ], [
            'nombre' => 'Activo',
            'estado' => Concepto::ACTIVO,
            'color' => 'primary',
            'icono' => 'fas fa-check-circle'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => AutomatizacionN8n::INACTIVO,
        ], [
            'nombre' => 'Inactivo',
            'estado' => Concepto::ACTIVO,
            'color' => 'info',
            'icono' => 'far fa-times-fas fa-clock'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => AutomatizacionN8n::SUSPENDIDO,
        ], [
            'nombre' => 'Suspendido',
            'estado' => Concepto::ACTIVO,
            'color' => 'warning',
            'icono' => 'far fa-times-fas fa-clock'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => AutomatizacionN8n::ERROR,
        ], [
            'nombre' => 'Error',
            'estado' => Concepto::ACTIVO,
            'color' => 'danger',
            'icono' => 'far fa-times-circle'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => AutomatizacionN8n::ELIMINADO,
        ], [
            'nombre' => 'Eliminado',
            'estado' => Concepto::ACTIVO,
            'color' => 'danger',
            'icono' => 'far fa-times-circle'
        ]);

        // --------------------------------------

        $tc_estado = TipoConcepto::updateOrCreate([
            'nombre' => AutomatizacionN8nEjecucion::TC_ESTADO,
        ], [
            'descripcion' => 'Estados de las automaticaciones con n8n para l ejecución.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => AutomatizacionN8nEjecucion::PENDIENTE,
        ], [
            'nombre' => 'Pendiente',
            'estado' => Concepto::ACTIVO,
            'color' => 'warning',
            'icono' => 'far fa-times-fas fa-clock'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => AutomatizacionN8nEjecucion::PROCESANDO,
        ], [
            'nombre' => 'Procesando',
            'estado' => Concepto::ACTIVO,
            'color' => 'info',
            'icono' => 'far fa-times-fas fa-clock'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => AutomatizacionN8nEjecucion::EXITOSO,
        ], [
            'nombre' => 'Exitoso',
            'estado' => Concepto::ACTIVO,
            'color' => 'primary',
            'icono' => 'fas fa-check-circle'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => AutomatizacionN8nEjecucion::ERROR,
        ], [
            'nombre' => 'Error',
            'estado' => Concepto::ACTIVO,
            'color' => 'danger',
            'icono' => 'far fa-times-circle'
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_estado?->id,
            'codigo' => AutomatizacionN8nEjecucion::REINTENTANDO,
        ], [
            'nombre' => 'Reintentando',
            'estado' => Concepto::ACTIVO,
            'color' => 'danger',
            'icono' => 'far fa-times-circle'
        ]);
    }

    public function tipoConceptosMetodosHttp()
    {
        $tc_metodo = TipoConcepto::updateOrCreate([
            'nombre' => AutomatizacionN8n::TC_METODO_HTTP,
        ], [
            'descripcion' => 'Metodos HTTPS de las automaticaciones con n8n.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_metodo?->id,
            'codigo' => AutomatizacionN8n::GET,
        ], [
            'nombre' => 'GET',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_metodo?->id,
            'codigo' => AutomatizacionN8n::POST,
        ], [
            'nombre' => 'POST',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_metodo?->id,
            'codigo' => AutomatizacionN8n::PUT,
        ], [
            'nombre' => 'PUT',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_metodo?->id,
            'codigo' => AutomatizacionN8n::PATCH,
        ], [
            'nombre' => 'PATCH',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_metodo?->id,
            'codigo' => AutomatizacionN8n::DELETE,
        ], [
            'nombre' => 'DELETE',
            'estado' => Concepto::ACTIVO,
        ]);
    }

    public function tipoConceptosEventos()
    {
        $tc_eventos = TipoConcepto::updateOrCreate([
            'nombre' => AutomatizacionN8nEvento::TC_EVENTOS,
        ], [
            'descripcion' => 'Eventos de las automaticaciones con n8n.',
            'estado' => TipoConcepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_eventos?->id,
            'codigo' => AutomatizacionN8nEvento::NUEVO_MENSAJE,
        ], [
            'nombre' => 'Nuevo mensaje',
            'estado' => Concepto::ACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_eventos?->id,
            'codigo' => AutomatizacionN8nEvento::MENSAJE_ENVIADO,
        ], [
            'nombre' => 'Mensaje enviado',
            'estado' => Concepto::INACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_eventos?->id,
            'codigo' => AutomatizacionN8nEvento::CONVERSACION_INICIAL,
        ], [
            'nombre' => 'Conversación iniciada',
            'estado' => Concepto::INACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_eventos?->id,
            'codigo' => AutomatizacionN8nEvento::CONVERSACION_CERRADA,
        ], [
            'nombre' => 'Conversación cerrada',
            'estado' => Concepto::INACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_eventos?->id,
            'codigo' => AutomatizacionN8nEvento::ETIQUETA_AGREGADA,
        ], [
            'nombre' => 'Etiqueta agregada',
            'estado' => Concepto::INACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_eventos?->id,
            'codigo' => AutomatizacionN8nEvento::CLIENTE_CREADO,
        ], [
            'nombre' => 'Cliente creado',
            'estado' => Concepto::INACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_eventos?->id,
            'codigo' => AutomatizacionN8nEvento::CAMPANA_ENTREGADA,
        ], [
            'nombre' => 'Campaña entregada',
            'estado' => Concepto::INACTIVO,
        ]);

        Concepto::updateOrCreate([
            'id_tipo' => $tc_eventos?->id,
            'codigo' => AutomatizacionN8nEvento::CAMPANA_LEIDA,
        ], [
            'nombre' => 'Campaña leída',
            'estado' => Concepto::INACTIVO,
        ]);
    }
}
