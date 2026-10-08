<?php

namespace App\Observers;

use App\Models\WhatsappAccount;

class ConfigObserver
{
    /**
     * Handle the Config "created" event.
     */
    public function created(WhatsappAccount $config): void
    {
        // $this->actualizarConfig($config);
    }

    /**
     * Handle the Config "updated" event.
     */
    public function updated(WhatsappAccount $config): void
    {
        // $this->actualizarConfig($config);
    }

    /**
     * Handle the Config "deleted" event.
     */
    public function deleted(WhatsappAccount $config): void
    {
        //
    }

    /**
     * Handle the Config "restored" event.
     */
    public function restored(WhatsappAccount $config): void
    {
        //
    }

    /**
     * Handle the Config "force deleted" event.
     */
    public function forceDeleted(WhatsappAccount $config): void
    {
        //
    }

    public function actualizarConfig(WhatsappAccount $config)
    {
        if ($config->wasChanged('estado')) {
            if ($config->estado == WhatsappAccount::CONECTADO) {
                $configs = WhatsappAccount::where('estado', WhatsappAccount::CONECTADO)
                    ->where('cod_empresa', $config->cod_empresa)
                    ->whereNot('id', $config->id)
                    ->get();

                foreach ($configs as $item) {
                    $item->updateQuietly([
                        'estado' => WhatsappAccount::DESCONECTADO,
                    ]);
                }
            }
        }
    }
}
