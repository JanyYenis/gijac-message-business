<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chatbot_configuracion', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cod_empresa')->index();

            // General
            $table->boolean('activo')->default(true);

            // Horario
            $table->boolean('respetar_horario')->default(true);

            // Palabras clave
            $table->integer('accion_palabra_clave')->default(1);
            $table->boolean('coincidencia_aproximada')->default(true);

            // Transferencias
            $table->boolean('permitir_transferencia')->default(true);
            $table->integer('tiempo_espera_agente')->default(10);
            $table->integer('accion_sin_agente')->default(1);

            // Conversación y contexto
            $table->boolean('mantener_contexto')->default(true);
            $table->integer('tiempo_sesion')->default(30);
            $table->integer('tipo_reinicio_conversacion')->default(1);

            // Comportamiento
            $table->integer('tiempo_respuesta')->default(2);
            $table->boolean('mostrar_escribiendo')->default(true);
            $table->integer('agrupar_mensajes')->default(3);

            // Respuestas no resueltas
            $table->integer('accion_respuesta_no_resuelta')->default(2);
            $table->text('mensaje_no_resuelto')->nullable();

            $table->integer('estado')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_configuracion');
    }
};
