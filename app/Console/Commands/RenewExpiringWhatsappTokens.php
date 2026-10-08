<?php

namespace App\Console\Commands;

use App\Models\WhatsappAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class RenewExpiringWhatsappTokens extends Command
{
    protected $signature = 'whatsapp:renew-expiring-soon {--days=10 : Renovar si expira en menos de X días}';
    protected $description = 'Renueva automáticamente tokens de WhatsApp que están por vencer';

    public function handle()
    {
        $days = (int) $this->option('days');
        $graph = 'https://graph.facebook.com/' . config('facebook.graph_version', 'v26.0');
        $appId = config('facebook.client_id');
        $appSecret = config('facebook.client_secret');

        $accounts = WhatsappAccount::where('estado', WhatsappAccount::CONECTADO)
            ->whereNotNull('token_expires_at')
            ->where('token_expires_at', '<=', now()->addDays($days))
            ->get();

        if ($accounts->isEmpty()) {
            $this->info("No hay tokens por vencer en {$days} días.");
            return 0;
        }

        $this->info("Encontrados {$accounts->count()} tokens para renovar...");

        foreach ($accounts as $account) {
            try {
                $oldToken = Crypt::decrypt($account->access_token);

                /* 1. Intercambiar por uno nuevo */
                $res = Http::get("{$graph}/oauth/access_token", [
                    'grant_type' => 'fb_exchange_token',
                    'client_id' => $appId,
                    'client_secret' => $appSecret,
                    'fb_exchange_token' => $oldToken,
                ]);

                if ($res->failed()) {
                    $error = $res->json('error.message') ?? $res->body();
                    throw new \Exception($error);
                }

                $data = $res->json();
                $newToken = $data['access_token'];
                $expiresAt = isset($data['expires_in'])
                    ? now()->addSeconds((int) $data['expires_in'])
                    : now()->addDays(60);

                /* 2. Guardar */
                $account->update([
                    'access_token' => Crypt::encrypt($newToken),
                    'token_expires_at' => $expiresAt,
                ]);

                $this->info("RENOVADO: {$account->waba_id} ({$account->phone_number}) -> nuevo vencimiento {$expiresAt->format('Y-m-d')}");

            } catch (\Throwable $e) {
                // Si falla la renovación es porque ya está muy cerca de vencer y Meta no lo deja extender
                // Lo marcamos para que el check-tokens lo ponga como expirado mañana
                $this->error("FALLO renovación {$account->waba_id}: " . $e->getMessage());

                if (str_contains($e->getMessage(), 'expired') || str_contains($e->getMessage(), '190')) {
                    $account->update(['estado' => WhatsappAccount::TOKEN_EXPIRADO]);
                }
            }
        }

        return 0;
    }
}
