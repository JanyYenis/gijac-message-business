<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Guarda un snapshot completo del flujo en cada publicación.
     *
     * El snapshot es el JSON exportado por Drawflow + la configuración
     * de todos los nodos, conexiones y variables en ese momento.
     * Esto permite restaurar cualquier versión sin reconstruir desde
     * las tablas normalizadas.
     *
     * status:
     *   "published" → versión activa en producción
     *   "draft"     → guardado pero no publicado
     *   "archived"  → versión histórica
     */
    public function up(): void
    {
        Schema::create('chatbot_flow_versions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('flow_id');

            // Número de versión incremental por flujo
            $table->unsignedInteger('numero_version');

            // Snapshot completo del flujo en ese momento
            $table->longText('snapshot');
            // Estructura del snapshot:
            // {
            //   "drawflow": { ... },   ← JSON de Drawflow
            //   "nodes": [ ... ],      ← chatbot_nodes + configs
            //   "connections": [ ... ],
            //   "variables": [ ... ]
            // }

            $table->integer('estado')->default(1);

            // Quién publicó / guardó esta versión
            $table->uuid('creado_por')->nullable();

            // Nota de cambio opcional (como un commit message)
            $table->string('nota_cambio')->nullable();

            $table->timestamp('fecha_publicado')->nullable();
            $table->timestamps();

            $table->unique(['flow_id', 'numero_version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_flow_versions');
    }
};
