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
        Schema::create('automatizaciones_n8n', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cod_empresa');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('url_webhook', 1000);
            $table->string('token_seguridad')->nullable();
            $table->integer('metodo_http');
            $table->integer('estado')->default(1);
            $table->integer('webhook_activo')->default(1);
            $table->integer('cantidad_reintentos')->default(3);
            $table->integer('tiempo_entre_intentos')->default(5);
            $table->integer('timeout_segundos')->default(30);
            $table->timestamp('ultima_ejecucion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('automatizaciones_n8n');
    }
};
