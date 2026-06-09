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
        Schema::create('plantilla_componentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plantilla_id')->constrained()->onDelete('cascade');
            $table->integer('type');
            $table->string('format')->nullable(); // IMAGE, TEXT, VIDEO, DOCUMENT, LOCATION
            $table->text('text')->nullable();
            $table->json('example')->nullable();
            $table->json('buttons')->nullable(); // solo si type = BUTTONS
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantilla_componentes');
    }
};
