<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Almacena la configuración específica de cada nodo en formato clave-valor.
     * El uso de JSON en `value` permite flexibilidad sin romper el esquema
     * cuando se agregan nuevos campos a un tipo de nodo.
     *
     * Ejemplos por tipo de nodo:
     *
     * [text]
     *   key: "message"   → value: "Hola ||nombre||, gracias por escribirnos."
     *
     * [buttons]
     *   key: "message"   → value: "¿Aceptas nuestros términos?"
     *   key: "buttons"   → value: [{"label":"Acepto","target_node_id":5},{"label":"No Acepto","target_node_id":7}]
     *
     * [list]
     *   key: "message"   → value: "Selecciona una opción"
     *   key: "list_title"→ value: "Menú Principal"
     *   key: "sections"  → value: [{"title":"Servicios","rows":[{"id":"ventas","label":"Ventas"}]}]
     *
     * [condition]
     *   key: "variable"  → value: "||respuesta||"
     *   key: "operator"  → value: "equals"   (equals|contains|greater_than|less_than|regex)
     *   key: "compare"   → value: "Acepto"
     *
     * [ai | generate]
     *   key: "system_prompt" → value: "Eres un asistente de ventas..."
     *   key: "model"         → value: "gpt-4o-mini"
     *   key: "temperature"   → value: "0.7"
     *   key: "max_tokens"    → value: "512"
     *
     * [webhook | api]
     *   key: "method"   → value: "POST"
     *   key: "url"      → value: "https://api.ejemplo.com/hook"
     *   key: "headers"  → value: {"Authorization":"Bearer ..."}
     *   key: "body"     → value: {"contact": "||telefono||"}
     *   key: "response_variable" → value: "api_result"
     *
     * [capture]
     *   key: "variable" → value: "respuesta"
     *   key: "data_type"→ value: "text"  (text|number|email|phone|date)
     *
     * [goto]
     *   key: "target_node_id" → value: "12"
     *
     * [agent]
     *   key: "department"         → value: "Ventas"
     *   key: "transition_message" → value: "Te conectamos con un agente..."
     *
     * [image | video | doc | audio]
     *   key: "url"     → value: "https://..."
     *   key: "caption" → value: "Texto opcional"
     *
     * [pdf]
     *   key: "document_id" → value: "1"   (FK a tabla de documentos)
     *   key: "question"    → value: "||input||"
     *
     * [tag]
     *   key: "tag_name" → value: "Cliente Potencial"
     *
     * [variable]
     *   key: "var_name" → value: "nombre"
     *   key: "var_value"→ value: "||input||"
     *
     * [start]
     *   key: "trigger" → value: "any"  (any|keyword)
     *   key: "keywords"→ value: ["hola","inicio","menu"]
     *
     * [end]
     *   key: "close_message" → value: "¡Gracias por contactarnos!"
     *   key: "close_session" → value: "true"
     */
    public function up(): void
    {
        Schema::create('chatbot_node_configs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('node_id');

            // Clave de configuración (ej: "message", "buttons", "url")
            $table->integer('key')->default(1);

            // Valor en JSON (soporta strings, arrays, objetos)
            $table->json('valor')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_node_configs');
    }
};
