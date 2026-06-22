<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_flows', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Identificación
            $table->string('nombre');
            $table->text('descripcion')->nullable();

            // Canal de despliegue (por ahora solo WhatsApp, extensible)
            $table->integer('canal')->default(1);

            // Control de estado del flujo
            $table->integer('estado')->default(1);

            // Versión publicada actualmente activa
            $table->unsignedInteger('versión_actual')->default(1);
            $table->timestamp('fecha_publicado')->nullable();

            // Propietario / creador
            $table->uuid('creado_por');

            // Configuración general del flujo (timeout de sesión, idioma, etc.)
            $table->json('ajustes')->nullable();
            // Ejemplo de settings:
            // {
            //   "session_timeout_minutes": 30,
            //   "language": "es",
            //   "fallback_message": "No entendí tu respuesta."
            // }

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_flows');
    }
};
