<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agentes que atienden cada etiqueta (ventas, soporte…)
        Schema::create('etiqueta_agentes', function (Blueprint $table) {
            $table->id();
            $table->uuid('etiqueta_id');
            $table->uuid('usuario_uuid');
            $table->timestamps();

            $table->unique(['etiqueta_id', 'usuario_uuid']);
        });

        // Tema actual del chat y cuándo llegó el último mensaje del cliente (para detectar conversaciones nuevas)
        Schema::table('conversaciones', function (Blueprint $table) {
            $table->uuid('etiqueta_id')->nullable()->after('agente_hasta');
            $table->timestamp('ultimo_entrante_at')->nullable()->after('etiqueta_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etiqueta_agentes');
        Schema::table('conversaciones', fn (Blueprint $t) => $t->dropColumn(['etiqueta_id', 'ultimo_entrante_at']));
    }
};
