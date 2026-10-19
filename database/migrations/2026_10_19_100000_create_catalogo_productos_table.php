<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Copia local del catálogo de Meta: el listado se lee de aquí (rápido) y se sincroniza en segundo plano.
     * El catálogo se guarda por conexión (whatsapp_accounts) porque cada empresa tiene el suyo.
     */
    public function up(): void
    {
        Schema::table('whatsapp_accounts', function (Blueprint $table) {
            $table->string('catalogo_id')->nullable();
            $table->timestamp('catalogo_sync_at')->nullable();
        });

        Schema::create('catalogo_productos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cod_config')->index();           // whatsapp_accounts.id
            $table->string('producto_id');                 // id del producto en Meta
            $table->string('retailer_id')->nullable();     // SKU
            $table->string('name', 250)->nullable();
            $table->text('description')->nullable();
            $table->decimal('precio', 14, 2)->nullable();
            $table->decimal('precio_oferta', 14, 2)->nullable();
            $table->string('moneda', 10)->nullable();
            $table->string('availability', 30)->nullable();
            $table->text('image_url')->nullable();
            $table->text('url')->nullable();
            $table->string('brand', 150)->nullable();
            $table->string('condition', 30)->nullable();
            $table->string('category', 250)->nullable();
            $table->string('review_status', 30)->nullable();
            $table->timestamps();

            $table->unique(['cod_config', 'producto_id']);
            $table->index(['cod_config', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogo_productos');

        Schema::table('whatsapp_accounts', function (Blueprint $table) {
            $table->dropColumn(['catalogo_id', 'catalogo_sync_at']);
        });
    }
};
