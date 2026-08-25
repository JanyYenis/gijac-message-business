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
        Schema::create('chatbot_motores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('chatbot_configuracion_id')->index();
            $table->integer('tipo');
            $table->integer('orden')->default(1);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->unique([
                'chatbot_configuracion_id',
                'tipo'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_motores');
    }
};
