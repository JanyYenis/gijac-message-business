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
        Schema::create('chatbot_ai_assistants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('creado_por');
            $table->uuid('cod_empresa')->nullable(); // ajusta el tipo si tu tabla empresas usa otro

            $table->string('nombre');
            $table->string('rol')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('system_prompt');

            $table->string('provider')->default('ollama');
            $table->string('modelo');

            $table->unsignedTinyInteger('creatividad')->default(60);
            $table->unsignedTinyInteger('formalidad')->default(75);
            $table->unsignedTinyInteger('brevedad')->default(50);
            $table->unsignedTinyInteger('empatia')->default(80);

            $table->json('capacidades')->nullable();

            $table->boolean('respetar_horario')->default(true);
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();

            $table->json('palabras_clave')->nullable();

            $table->text('mensaje_bienvenida')->nullable();
            $table->text('mensaje_fuera_horario')->nullable();
            $table->text('mensaje_transferencia')->nullable();

            $table->boolean('activo')->default(true);
            $table->integer('estado')->default(1); // 1: activo, 0: inactivo, 2: eliminado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_ai_assistants');
    }
};
