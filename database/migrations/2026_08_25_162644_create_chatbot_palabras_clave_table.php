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
        Schema::create('chatbot_palabras_clave', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('chatbot_configuracion_id')->index();
            $table->string('palabra', 150);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->index(['chatbot_configuracion_id', 'palabra']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_palabras_clave');
    }
};
