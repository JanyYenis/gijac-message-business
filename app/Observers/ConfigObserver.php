<?php

namespace App\Observers;

use App\Models\ConfiguracionMeta;

class ConfigObserver
{
    /**
     * Handle the Config "created" event.
     */
    public function created(ConfiguracionMeta $config): void
    {
        $this->actualizarConfig($config);
    }

    /**
     * Handle the Config "updated" event.
     */
    public function updated(ConfiguracionMeta $config): void
    {
        $this->actualizarConfig($config);
    }

    /**
     * Handle the Config "deleted" event.
     */
    public function deleted(ConfiguracionMeta $config): void
    {
        //
    }

    /**
     * Handle the Config "restored" event.
     */
    public function restored(ConfiguracionMeta $config): void
    {
        //
    }

    /**
     * Handle the Config "force deleted" event.
     */
    public function forceDeleted(ConfiguracionMeta $config): void
    {
        //
    }

    public function actualizarConfig(ConfiguracionMeta $config)
    {
        if ($config->wasChanged('estado')) {
            if ($config->estado == ConfiguracionMeta::ACTIVO) {
                ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)
                    ->where('uuid', $config->uuid)
                    ->whereNot('id', $config->id)
                    ->update(['estado' => ConfiguracionMeta::INACTIVO]);
            }
        }
    }
}
