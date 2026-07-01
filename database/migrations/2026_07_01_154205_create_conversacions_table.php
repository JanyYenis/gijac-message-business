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
        Schema::create('conversaciones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('contacto_id');
            $table->string('phone_number_id');
            $table->text('ultimo_mensaje')->nullable();
            $table->integer('tipo_ultimo_mensaje')->nullable();
            $table->timestamp('ultima_fecha')->nullable();

            $table->integer('mensajes_no_leidos')->default(0);

            $table->timestamps();

            $table->unique([
                'contacto_id',
                'phone_number_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversaciones');
    }
};
