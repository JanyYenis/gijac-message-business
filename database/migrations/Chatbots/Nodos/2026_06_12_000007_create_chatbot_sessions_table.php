<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Una sesión representa una conversación en curso de un contacto con un flujo.
     *
     * status:
     *   "active"     → conversación en progreso
     *   "waiting"    → esperando respuesta del usuario (después de capture/question)
     *   "agent"      → derivada a agente humano (nodo agent)
     *   "completed"  → terminada normalmente (nodo end)
     *   "expired"    → expirada por timeout de sesión
     *   "error"      → terminada por error del motor
     *
     * variables es el estado en tiempo real de las variables del flujo
     * capturadas durante la sesión (se actualiza con cada nodo capture/variable).
     */
    public function up(): void
    {
        Schema::create('chatbot_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('flow_id');

            // Versión del flujo que está ejecutando esta sesión
            $table->unsignedInteger('flow_version')->default(1);

            // Identificador del contacto en el canal (número WhatsApp, etc.)
            $table->string('telefono_contacto');

            // Nombre del contacto si está disponible
            $table->string('nombre_contacto')->nullable();

            // Nodo en el que está actualmente la sesión
            $table->uuid('current_node_id')->nullable();

            // Estado de la sesión
            $table->integer('estado')->default(1);

            // Variables capturadas durante la sesión (JSON key-value)
            $table->json('variables')->nullable();
            // Ejemplo: {"nombre": "Carlos", "ciudad": "Bogotá", "respuesta": "Acepto"}

            // Metadatos del canal (webhook payload original, etc.)
            $table->json('canal_meta')->nullable();

            // Control de timeout
            $table->timestamp('ultima_interacción_en')->nullable();
            $table->timestamp('fecha_inicio')->useCurrent();
            $table->timestamp('fecha_finalizado')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_sessions');
    }
};
