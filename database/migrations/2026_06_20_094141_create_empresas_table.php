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
        Schema::create('empresas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('razon_social');
            $table->string('nit');
            $table->string('direccion');
            $table->string('email');
            $table->string('telefono');
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('linkendin')->nullable();
            $table->string('web')->nullable();
            $table->text('descripcion');
            $table->uuid('cod_usuario');
            $table->string('foto')->nullable();
            $table->integer('estado')->default(1);
            $table->integer('publicar')->default(1);
            $table->integer('notificacion')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
