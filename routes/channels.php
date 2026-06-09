<?php

use App\Models\ConfiguracionMeta;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{id}', function ($user, $id) {
    $config = ConfiguracionMeta::firstWhere([
        'uuid' => (int) $user->uuid,
        'estado' => ConfiguracionMeta::ACTIVO
    ]) ?? null;

    if ($config) {
        if ($config->phone_number_id == (int) $id) {
            return $user;
        }
    }
});

Broadcast::channel('chat.leido.{id}', function ($user, $id) {
    $config = ConfiguracionMeta::firstWhere([
        'uuid' => (int) $user->uuid,
        'estado' => ConfiguracionMeta::ACTIVO
    ]) ?? null;

    if ($config) {
        if ($config->phone_number_id == (int) $id) {
            return $user;
        }
    }
});

Broadcast::channel('notificacion.{id}', function ($user, $id) {
    if ((int) $user->id == (int) $id) {
        return $user;
    }
});

Broadcast::channel('online', function ($user) {
    return $user;
});

Broadcast::channel('calls.{id}', function ($user, $id) {
    $config = ConfiguracionMeta::firstWhere([
        'uuid' => (int) $user->uuid,
        'estado' => ConfiguracionMeta::ACTIVO
    ]) ?? null;

    if ($config) {
        if ($config->phone_number_id == (int) $id) {
            return $user;
        }
    }
});
