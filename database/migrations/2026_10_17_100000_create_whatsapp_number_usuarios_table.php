<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Número de WhatsApp asignado a cada usuario/agente.
     * Un usuario tiene como máximo un número; si no tiene ninguno usa el principal.
     */
    public function up(): void
    {
        Schema::create('whatsapp_number_usuarios', function (Blueprint $table) {
            $table->id();
            $table->uuid('whatsapp_number_id')->index();
            $table->uuid('usuario_uuid')->unique();
            $table->uuid('cod_empresa')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_number_usuarios');
    }
};
