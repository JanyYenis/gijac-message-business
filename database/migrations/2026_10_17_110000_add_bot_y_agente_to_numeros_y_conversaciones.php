<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Interruptor del bot por número (activo por defecto, como funcionaba hasta ahora)
        Schema::table('whatsapp_numbers', function (Blueprint $table) {
            $table->boolean('bot_activo')->default(true)->after('principal');
        });

        // Agente que está atendiendo el chat (contacto + número) y hasta cuándo lo tiene reservado
        Schema::table('conversaciones', function (Blueprint $table) {
            $table->uuid('agente_uuid')->nullable()->after('mensajes_no_leidos');
            $table->timestamp('agente_hasta')->nullable()->after('agente_uuid');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_numbers', fn (Blueprint $t) => $t->dropColumn('bot_activo'));
        Schema::table('conversaciones', fn (Blueprint $t) => $t->dropColumn(['agente_uuid', 'agente_hasta']));
    }
};
