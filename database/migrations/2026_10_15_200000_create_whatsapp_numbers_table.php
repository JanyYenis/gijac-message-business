<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Un registro por número de teléfono. whatsapp_accounts sigue siendo la conexión (WABA + token)
     * y refleja el número principal, así que el resto del código no cambia.
     */
    public function up(): void
    {
        Schema::create('whatsapp_numbers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('whatsapp_account_id')->index();
            $table->uuid('cod_empresa')->nullable()->index();
            $table->string('waba_id');
            $table->string('phone_number_id')->unique();
            $table->string('phone_number', 30);
            $table->string('display_name')->nullable();
            $table->text('two_factor_pin')->nullable();        // Crypt::encrypt()
            $table->boolean('number_registered')->default(false);
            $table->boolean('principal')->default(false);
            $table->boolean('es_prueba')->default(false);      // número de prueba de Meta (+1 555)
            $table->integer('estado')->default(1);             // 1: conectado, 0: desconectado
            $table->timestamps();
        });

        // Los números que ya estaban conectados pasan a ser el principal de su cuenta
        DB::table('whatsapp_accounts')->whereNotNull('phone_number_id')->orderBy('created_at')->get()
            ->each(function ($a) {
                $digitos = preg_replace('/\D/', '', (string) $a->phone_number);
                DB::table('whatsapp_numbers')->insert([
                    'id' => (string) Str::uuid(),
                    'whatsapp_account_id' => $a->id,
                    'cod_empresa' => $a->cod_empresa,
                    'waba_id' => $a->waba_id,
                    'phone_number_id' => $a->phone_number_id,
                    'phone_number' => $a->phone_number,
                    'display_name' => $a->display_name,
                    'two_factor_pin' => $a->two_factor_pin,
                    'number_registered' => $a->number_registered,
                    'principal' => true,
                    'es_prueba' => str_starts_with($digitos, '1555'),
                    'estado' => $a->estado,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_numbers');
    }
};
