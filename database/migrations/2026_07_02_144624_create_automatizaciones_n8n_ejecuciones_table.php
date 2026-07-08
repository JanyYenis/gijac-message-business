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
        Schema::create('automatizaciones_n8n_ejecuciones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cod_automatizacion');
            $table->uuid('cod_contacto')->nullable();
            $table->integer('evento');
            $table->integer('estado')->default(1);
            $table->integer('codigo_respuesta')->nullable();
            $table->integer('duracion_ms')->nullable();
            $table->text('respuesta')->nullable();
            $table->json('payload_enviado')->nullable();
            $table->timestamp('fecha_ejecucion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('automatizaciones_n8n_ejecuciones');
    }
};
