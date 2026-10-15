<?php

namespace App\Console\Commands;

use App\Models\Usuario;
use App\Models\WhatsappAccount;
use App\Notifications\WhatsappTokenNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class CheckWhatsappTokens extends Command
{
    protected $signature = 'whatsapp:check-tokens';
    protected $description = 'Verifica los tokens de WhatsApp contra Meta (debug_token), sincroniza su vencimiento y avisa al cliente';

    /** Días antes del vencimiento en los que se avisa (el comando corre una vez al día) */
    const DIAS_AVISO = [7, 3, 1];

    public function handle()
    {
        $graph = 'https://graph.facebook.com/' . config('facebook.graph_version', 'v26.0');
        $appToken = config('facebook.client_id') . '|' . config('facebook.client_secret');

        $accounts = WhatsappAccount::where('estado', WhatsappAccount::CONECTADO)->get();

        foreach ($accounts as $account) {
            try {
                $token = Crypt::decrypt($account->access_token);

                $res = Http::get("{$graph}/debug_token", [
                    'input_token' => $token,
                    'access_token' => $appToken,
                ]);

                // Si la consulta misma falla (red, credenciales de la app) NO se toca la cuenta
                if ($res->failed()) {
                    $this->error("No se pudo consultar a Meta para {$account->waba_id}: " . ($res->json('error.message') ?? $res->status()));
                    continue;
                }

                $data = $res->json('data', []);

                if (!($data['is_valid'] ?? false)) {
                    $account->update(['estado' => WhatsappAccount::TOKEN_EXPIRADO]);
                    $this->notificar($account, null);
                    $this->warn("TOKEN INVÁLIDO: {$account->waba_id} - {$account->phone_number}");
                    continue;
                }

                // expires_at = 0 significa que el token no vence
                $expiraEn = !empty($data['expires_at']) ? Carbon::createFromTimestamp($data['expires_at']) : null;
                $account->update(['token_expires_at' => $expiraEn]);

                if (!$expiraEn) {
                    $this->info("OK: {$account->waba_id} - no vence");
                    continue;
                }

                $dias = (int) now()->diffInDays($expiraEn, false);
                if (in_array($dias, self::DIAS_AVISO, true)) {
                    $this->notificar($account, $dias);
                }
                $this->info("OK: {$account->waba_id} - vence en {$dias} día(s)");
            } catch (\Throwable $e) {
                $this->error("Error verificando {$account->waba_id}: " . $e->getMessage());
            }
        }

        $this->info('Chequeo terminado.');
        return 0;
    }

    protected function notificar(WhatsappAccount $account, ?int $dias): void
    {
        $usuario = Usuario::where('uuid', $account->usuario_id)->first();
        $usuario?->notify(new WhatsappTokenNotification($dias));
    }
}
