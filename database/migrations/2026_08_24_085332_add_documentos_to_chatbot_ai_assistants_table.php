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
        Schema::table('chatbot_ai_assistants', function (Blueprint $table) {
            $table->string('documento_path')->nullable()->after('palabras_clave');
            $table->string('documento_nombre')->nullable()->after('documento_path');
            $table->unsignedBigInteger('documento_size')->nullable()->after('documento_nombre');
            $table->longText('documento_contenido')->nullable()->after('documento_size');
            $table->timestamp('documento_procesado_en')->nullable()->after('documento_contenido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chatbot_ai_assistants', function (Blueprint $table) {
            $table->dropColumn(['documento_path', 'documento_nombre', 'documento_size', 'documento_contenido', 'documento_procesado_en']);
        });
    }
};
