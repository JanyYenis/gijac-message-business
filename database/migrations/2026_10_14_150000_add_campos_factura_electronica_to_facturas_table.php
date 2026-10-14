<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Seguimiento de la factura electrónica, que se emite aparte con la herramienta de la DIAN.
     */
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            if (!Schema::hasColumn('facturas', 'estado_fe')) {
                $table->integer('estado_fe')->default(1)->index();
            }
            if (!Schema::hasColumn('facturas', 'numero_fe')) {
                $table->string('numero_fe', 50)->nullable();
            }
            if (!Schema::hasColumn('facturas', 'cufe')) {
                $table->string('cufe', 255)->nullable();
            }
            if (!Schema::hasColumn('facturas', 'fe_observaciones')) {
                $table->text('fe_observaciones')->nullable();
            }
            if (!Schema::hasColumn('facturas', 'fe_solicitada_at')) {
                $table->timestamp('fe_solicitada_at')->nullable();
            }
            if (!Schema::hasColumn('facturas', 'fe_emitida_at')) {
                $table->timestamp('fe_emitida_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn(['estado_fe', 'numero_fe', 'cufe', 'fe_observaciones', 'fe_solicitada_at', 'fe_emitida_at']);
        });
    }
};
