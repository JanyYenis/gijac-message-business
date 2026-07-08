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
        Schema::create('dispositivos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('usuario_id')->nullable();
            $table->string('token')->unique();
            $table->string('nombre_dispositivo')->nullable();
            $table->string('sistema_operativo')->nullable();
            $table->string('version_so')->nullable();
            $table->string('ip')->nullable();
            $table->string('modelo')->nullable();
            $table->timestamp('expira_en');
            $table->timestamp('vinculado_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispositivos');
    }
};
