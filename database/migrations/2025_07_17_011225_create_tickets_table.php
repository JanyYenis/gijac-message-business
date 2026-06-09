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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('titulo');
            $table->text('descripcion');
            $table->unsignedBigInteger('cod_usuario');
            $table->unsignedBigInteger('cod_responsable')->nullable();
            $table->integer('estado')->default(1);
            $table->integer('tipo')->default(1);
            $table->integer('prioridad')->default(1);
            $table->string('url')->nullable();
            $table->string('url_archivo')->nullable();
            $table->timestamp('fecha_hallazgo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
