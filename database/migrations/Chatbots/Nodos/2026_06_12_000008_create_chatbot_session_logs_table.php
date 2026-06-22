<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Log de mensajes intercambiados durante una sesión.
     * Registra tanto los mensajes enviados por el bot como las respuestas del usuario.
     *
     * direction:
     *   "out" → bot → usuario  (mensajes enviados por el chatbot)
     *   "in"  → usuario → bot  (respuestas del contacto)
     *
     * message_type:
     *   text | image | video | document | audio | buttons | list |
     *   interactive_reply | location | system
     *
     * status (solo aplica para "out"):
     *   sent | delivered | read | failed
     *
     * Sirve tanto para auditoría como para el simulador de conversación.
     */
    public function up(): void
    {
        Schema::create('chatbot_session_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('session_id');

            // Nodo que originó el mensaje (null para mensajes entrantes del usuario)
            $table->uuid('node_id')->nullable();

            $table->integer('direction')->default(1);

            $table->integer('tipo_mensaje')->default(1);

            // Contenido del mensaje
            $table->text('contenido')->nullable();

            // Payload completo del canal (para debug y reintentos)
            $table->json('raw_payload')->nullable();

            // Estado del mensaje enviado
            $table->integer('estado')->nullable()->default(1);

            // ID del mensaje en el proveedor de WhatsApp (para tracking de estado)
            $table->string('provider_message_id')->nullable();

            $table->timestamp('fecha_envio')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_session_logs');
    }
};
