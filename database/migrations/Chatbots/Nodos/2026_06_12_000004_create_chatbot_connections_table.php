<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Representa las conexiones (aristas) entre nodos del flujo.
     *
     * source_output / target_input siguen la convención de Drawflow:
     *   "output_1", "output_2", "output_3"  → para nodos con múltiples salidas
     *   "input_1"                            → para nodos con una entrada
     *
     * Ejemplo:
     *   Nodo Condición (id=5) output_1 → Nodo Texto (id=8) input_1   [rama TRUE]
     *   Nodo Condición (id=5) output_2 → Nodo End   (id=9) input_1   [rama FALSE]
     *
     *   Nodo Botones (id=3) output_1 → Nodo Lista (id=6) input_1     [botón 1]
     *   Nodo Botones (id=3) output_2 → Nodo End   (id=9) input_1     [botón 2]
     *   Nodo Botones (id=3) output_3 → ...                            [botón 3]
     *
     * label es opcional y se muestra sobre la arista en el canvas
     * (útil para etiquetar ramas de condiciones: "Sí" / "No").
     */
    public function up(): void
    {
        Schema::create('chatbot_connections', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Desnormalizado para consultas rápidas sin JOIN a chatbot_nodes
            $table->uuid('flow_id');
            $table->uuid('source_node_id');
            $table->uuid('target_node_id');

            // Puerto de salida del nodo origen (output_1, output_2, ...)
            $table->string('source_output', 20)->default('output_1');

            // Puerto de entrada del nodo destino (input_1, ...)
            $table->string('target_input', 20)->default('input_1');

            // Etiqueta opcional sobre la arista
            $table->string('etiqueta')->nullable();

            $table->timestamps();

            // No puede haber dos conexiones iguales en el mismo flujo
            $table->unique(
                ['flow_id', 'source_node_id', 'source_output', 'target_node_id', 'target_input'],
                'unique_connection'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_connections');
    }
};
