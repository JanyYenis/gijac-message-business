<?php

namespace App\Jobs;

use App\Models\WhatsappAccount;
use App\Services\Meta\CatalogoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Throwable;

/** Actualiza en segundo plano la copia local del catálogo de una empresa. */
class SincronizarCatalogo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 180;

    public function __construct(public string $codConfig)
    {
    }

    public function handle(): void
    {
        try {
            $cuenta = WhatsappAccount::find($this->codConfig);
            if (! $cuenta || ! $cuenta->catalogo_id || $cuenta->estado != WhatsappAccount::CONECTADO) {
                return;
            }

            (new CatalogoService(Crypt::decrypt($cuenta->access_token), $cuenta->catalogo_id, $cuenta->id))->sincronizar();
        } catch (Throwable $e) {
            Log::warning('Sincronización de catálogo fallida', ['cod_config' => $this->codConfig, 'error' => $e->getMessage()]);
        } finally {
            Cache::forget("catalogo:sync:{$this->codConfig}");
        }
    }
}
