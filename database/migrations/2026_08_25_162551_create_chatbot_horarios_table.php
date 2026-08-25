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
        Schema::create('chatbot_horarios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('chatbot_configuracion_id')->index();
            $table->integer('dia')->comment('1=Lunes, 2=Martes, ... 7=Domingo');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_horarios');
    }
};
