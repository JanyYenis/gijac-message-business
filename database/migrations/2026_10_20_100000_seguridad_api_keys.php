<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            $table->string('cod_empresa', 64)->nullable()->after('uuid');
            $table->string('prefijo', 16)->nullable()->after('etiqueta');
            $table->string('key_hash', 64)->nullable()->unique()->after('prefijo');
            $table->json('scopes')->nullable();          // null = acceso completo (llaves anteriores)
            $table->timestamp('ultimo_uso')->nullable();
        });

        DB::statement('ALTER TABLE api_keys MODIFY `key` VARCHAR(255) NULL');

        // Las llaves actuales siguen funcionando: se guarda su hash y se elimina el texto plano
        DB::table('api_keys')->whereNotNull('key')->orderBy('id')->chunkById(200, function ($filas) {
            foreach ($filas as $fila) {
                DB::table('api_keys')->where('id', $fila->id)->update([
                    'key_hash' => hash('sha256', $fila->key),
                    'prefijo' => substr($fila->key, 0, 8),
                    'key' => null,
                ]);
            }
        }, 'id');
    }

    public function down(): void
    {
        // El texto plano de las llaves no se puede recuperar: solo se quitan las columnas nuevas.
        Schema::table('api_keys', function (Blueprint $table) {
            $table->dropUnique(['key_hash']);
            $table->dropColumn(['cod_empresa', 'prefijo', 'key_hash', 'scopes', 'ultimo_uso']);
        });
    }
};
