<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chatbot_questions', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique(); // Para identificar cada pregunta
            $table->text('text'); // Texto de la pregunta o mensaje
            $table->tinyInteger('type'); // Tipo de mensaje (1 = TEXTO, 2 = BOTON, etc.)
            $table->boolean('is_initial')->default(false); // Indica si es la primera pregunta
            $table->boolean('status')->default(true); // Estado activo o inactivo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_questions');
    }
};
