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
        Schema::create('meta_phone_numbers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cod_empresa');
            $table->string('waba_id');
            $table->string('phone_number_id');
            $table->string('verified_name');
            $table->string('code_verification_status');
            $table->string('display_phone_number');
            $table->string('quality_rating');
            $table->string('platform_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_phone_numbers');
    }
};
