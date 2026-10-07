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
        Schema::create('whatsapp_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('usuario_id');
            $table->string('waba_id');
            $table->string('phone_number_id');
            $table->string('business_id')->nullable();
            $table->text('access_token');              // Crypt::encrypt()
            $table->string('phone_number', 30);
            $table->string('display_name')->nullable();
            $table->integer('estado')->default(1); // 1: connected, 0: disconnected
            $table->integer('quality_rating')->default(4); // 1: GREEN, 2: YELLOW, 3: RED, 4: UNKNOWN
            $table->string('messaging_limit', 20)->default('1K');
            $table->boolean('webhook_subscribed')->default(false);
            $table->boolean('number_registered')->default(false);
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamps();

            $table->unique(['usuario_id', 'waba_id']);
        });

        Schema::create('whatsapp_webhook_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('waba_id')->nullable();
            $table->string('event_type')->nullable();
            $table->integer('estado')->default(1); // 1: received, 2: ok, 3: error
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_webhook_logs');
        Schema::dropIfExists('whatsapp_accounts');
    }
};
