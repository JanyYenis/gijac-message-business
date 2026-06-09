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
        Schema::create('periodos', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('nombre'); // Ejemplo: "Mensual"
            $table->string('codigo')->unique(); // Ejemplo: "MENSUAL"
            $table->integer('multiplicador')->default(1); // Meses o unidades
            $table->decimal('descuento', 5, 2)->default(0); // 0.10 = 10% descuento
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodos');
    }
};
