<?php

namespace App\Console\Commands;

use App\Models\WhatsappAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class CheckWhatsappTokens extends Command
{
    protected $signature = 'whatsapp:check-tokens';
    protected $description = 'Verifica tokens expirados de WhatsApp y cambia su estado';

    public function handle()
    {
        $graph = 'https://graph.facebook.com/' . config('facebook.graph_version', 'v26.0');

        $accounts = WhatsappAccount::where('estado', WhatsappAccount::CONECTADO)->get();

        foreach ($accounts as $account) {
            // 1. Por fecha en tu BD
            if ($account->token_expires_at && $account->token_expires_at->isPast()) {
                $account->update(['estado' => WhatsappAccount::TOKEN_EXPIRADO]);
                $this->warn("VENCIDO por fecha: {$account->waba_id} - {$account->phone_number}");
                continue;
            }

            // 2. Por validación real contra Meta (por si revocaron el token manualmente)
            try {
                $token = Crypt::decrypt($account->access_token);
                $res = Http::withToken($token)->get("{$graph}/{$account->waba_id}", [
                    'fields' => 'id'
                ]);

                if ($res->json('error.code') == 190 || $res->status() == 401) {
                    $account->update(['estado' => WhatsappAccount::TOKEN_EXPIRADO]);
                    $this->warn("VENCIDO por Meta (190): {$account->waba_id}");
                } else {
                    $this->info("OK: {$account->waba_id} - expira en {$account->token_expires_at->diffForHumans()}");
                }

            } catch (\Throwable $e) {
                $this->error("Error verificando {$account->waba_id}: " . $e->getMessage());
            }
        }

        $this->info('Chequeo terminado.');
        return 0;
    }
}
