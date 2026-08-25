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
        Schema::create('chatbot_mensajes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('chatbot_configuracion_id')->index();
            $table->integer('tipo');
            $table->string('titulo', 150);
            $table->string('descripcion', 255)->nullable();
            $table->text('mensaje')->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('estado')->default(1);
            $table->timestamps();
            $table->index(['chatbot_configuracion_id', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_mensajes');
    }
};
