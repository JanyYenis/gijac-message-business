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
        Schema::create('chatbot_nodes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('chatbot_id');
            $table->integer('number')->default(1);
            $table->string('title')->nullable(); // Ej: "Menú principal"
            $table->integer('type');
            $table->text('message')->nullable();
            $table->string('media_url')->nullable(); // imágenes, videos, docs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_nodes');
    }
};
