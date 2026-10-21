<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_webhooks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('cod_empresa', 64)->unique();      // un webhook por empresa
            $table->string('url', 500);
            $table->text('secreto');                           // cifrado; sirve para firmar cada envío
            $table->json('eventos');
            $table->unsignedTinyInteger('estado')->default(1); // 1 activo, 2 inactivo
            $table->unsignedInteger('fallos_consecutivos')->default(0);
            $table->timestamp('ultimo_envio_at')->nullable();
            $table->unsignedSmallInteger('ultimo_http')->nullable();
            $table->string('ultimo_error', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_webhooks');
    }
};
