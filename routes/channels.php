<?php

use App\Models\WhatsappAccount;
use App\Models\WhatsappNumber;
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

/**
 * El número (phone_number_id) pertenece a una cuenta o número conectado de la empresa del usuario.
 * Antes solo se aceptaba el de la primera cuenta, y los demás números quedaban sin tiempo real.
 */
$numeroDeLaEmpresa = function ($user, $id): bool {
    $empresa = $user->empresa?->id ?? $user->cod_empresa ?? null;
    if (! $empresa || $id === null || $id === '') {
        return false;
    }
    $id = (string) $id;

    return WhatsappAccount::where('cod_empresa', $empresa)
            ->where('estado', WhatsappAccount::CONECTADO)
            ->where('phone_number_id', $id)->exists()
        || WhatsappNumber::where('cod_empresa', $empresa)
            ->where('estado', WhatsappAccount::CONECTADO)
            ->where('phone_number_id', $id)->exists();
};

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{id}', function ($user, $id) use ($numeroDeLaEmpresa) {
    return $numeroDeLaEmpresa($user, $id) ? $user : null;
});

Broadcast::channel('chat.leido.{id}', function ($user, $id) use ($numeroDeLaEmpresa) {
    return $numeroDeLaEmpresa($user, $id) ? $user : null;
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
    $config = WhatsappAccount::firstWhere([
        'cod_empresa' => $user->empresa?->id,
        'estado' => WhatsappAccount::CONECTADO
    ]) ?? null;

    if ($config) {
        if ($config->phone_number_id == (int) $id) {
            return $user;
        }
    }
});

Broadcast::channel('dispositivos.{usuarioId}', function ($user, $usuarioId) {
    return (string) $user->uuid === (string) $usuarioId;
});
