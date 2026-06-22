<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tipos de nodo soportados (alineados con NODE_DEFS del frontend):
     *
     * MENSAJES:     text | image | video | doc | audio
     * INTERACCIÓN:  buttons | list | question | capture
     * LÓGICA:       condition | variable | tag | goto
     * ACCIONES:     webhook | api | agent | end
     * IA:           ai | pdf | generate
     * ESPECIAL:     start
     */
    public function up(): void
    {
        Schema::create('chatbot_nodes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('flow_id');

            // Tipo de nodo (string para no romper con nuevos tipos sin migrar)
            $table->integer('tipo')->nullable();

            // Etiqueta visible en el canvas
            $table->string('etiqueta')->nullable();

            // Posición en el canvas de Drawflow
            $table->decimal('pos_x', 10, 2)->default(0);
            $table->decimal('pos_y', 10, 2)->default(0);

            // Entradas y salidas del nodo (para reconstruir el Drawflow)
            $table->unsignedTinyInteger('inputs')->default(1);
            $table->unsignedTinyInteger('outputs')->default(1);

            // ID original de Drawflow (para mapear el JSON exportado)
            $table->string('drawflow_id')->nullable();

            // Nodo de inicio del flujo (solo uno por flujo)
            $table->integer('principal')->default(0);
            $table->integer('auto_send')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_nodes');
    }
};
