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
        Schema::create('chatbot_motivos_transferencia', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('configuracion_id')->index();
            $table->integer('motivo');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->unique([
                'configuracion_id',
                'motivo'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_motivos_transferencia');
    }
};
