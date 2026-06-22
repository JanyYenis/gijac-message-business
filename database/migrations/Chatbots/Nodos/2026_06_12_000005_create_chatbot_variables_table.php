<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Variables disponibles en el flujo para interpolar en mensajes y condiciones.
     *
     * Existen dos scopes:
     *   "flow"    → definidas por el diseñador del flujo (catálogo)
     *   "contact" → capturadas durante la sesión (nombre, teléfono, respuesta, etc.)
     *   "system"  → inyectadas por el motor (fecha, hora, numero_sesion, etc.)
     *
     * El frontend las muestra como chips: ||nombre||, ||telefono||, etc.
     */
    public function up(): void
    {
        Schema::create('chatbot_variables', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('flow_id');

            // Nombre de la variable (sin los ||)
            $table->string('nombre', 100);

            // Tipo de dato esperado
            $table->integer('tipo_dato')->default(1);

            // Ámbito de la variable
            $table->integer('alcance')->default(1);

            // Valor por defecto opcional
            $table->text('valor_defecto')->nullable();

            // Descripción para el panel del constructor
            $table->string('descripcion')->nullable();

            $table->timestamps();

            $table->unique(['flow_id', 'nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_variables');
    }
};
