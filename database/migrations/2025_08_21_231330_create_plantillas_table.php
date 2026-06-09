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
        Schema::create('plantillas', function (Blueprint $table) {
            $table->id();
            $table->integer('cod_config');
            $table->string('name');
            $table->integer('parameter_format')->default(1);
            $table->string('language', 10)->default('es_ES');
            $table->integer('status')->default(2);
            $table->integer('category')->default(2);
            $table->string('sub_category')->nullable();
            $table->string('meta_id')->nullable(); // ID de Meta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantillas');
    }
};
