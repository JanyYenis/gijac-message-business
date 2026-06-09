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
        Schema::create('mensajes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Relación con campañas (si aplica)
            $table->uuid('campaign_id')->nullable();

            // Relación con contacto (cliente final)
            $table->uuid('contact_id')->nullable();

            // Identificadores de WhatsApp
            $table->string('wa_message_id')->nullable(); // ID del mensaje en WhatsApp
            $table->string('wa_from')->nullable();       // Número del remitente
            $table->string('wa_to')->nullable();         // Número del destinatario

            // Tipo de mensaje soportado
            $table->integer('type');

            // Contenido principal
            $table->longText('body')->nullable(); // texto, caption, o título del botón/lista
            $table->json('metadata')->nullable(); // JSON con la estructura completa (ej: archivo, botones, flows, contactos, reacciones)

            // Estado del mensaje
            $table->integer('estado')->default(1);

            $table->timestamp('sent_at')->nullable();   // Fecha real de WhatsApp
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};
