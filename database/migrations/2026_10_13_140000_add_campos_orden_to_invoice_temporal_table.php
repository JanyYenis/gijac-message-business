<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * invoice_temporal pasa a ser la orden de compra: guarda lo que el servidor espera cobrar.
     */
    public function up(): void
    {
        Schema::table('invoice_temporal', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_temporal', 'cod_plan')) {
                $table->uuid('cod_plan')->nullable();
            }
            if (!Schema::hasColumn('invoice_temporal', 'cod_usuario')) {
                $table->uuid('cod_usuario')->nullable();
            }
            if (!Schema::hasColumn('invoice_temporal', 'cod_empresa')) {
                $table->uuid('cod_empresa')->nullable();
            }
            if (!Schema::hasColumn('invoice_temporal', 'tiempo')) {
                $table->unsignedSmallInteger('tiempo')->nullable();
            }
            if (!Schema::hasColumn('invoice_temporal', 'valor')) {
                $table->decimal('valor', 14, 2)->nullable();
            }
            if (!Schema::hasColumn('invoice_temporal', 'currency')) {
                $table->string('currency', 3)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoice_temporal', function (Blueprint $table) {
            $table->dropColumn(['cod_plan', 'cod_usuario', 'cod_empresa', 'tiempo', 'valor', 'currency']);
        });
    }
};
