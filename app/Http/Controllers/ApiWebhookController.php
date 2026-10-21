<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Jobs\EnviarWebhookApi;
use App\Models\ApiWebhook;
use App\Services\Api\ApiWebhookService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApiWebhookController extends Controller
{
    public function show()
    {
        $wh = $this->webhook();

        return response()->json([
            'estado' => 'success',
            'webhook' => $wh ? [
                'url' => $wh->url,
                'eventos' => $wh->eventos,
                'activo' => $wh->estado == ApiWebhook::ACTIVO,
                'fallos' => $wh->fallos_consecutivos,
                'ultimo_envio' => $wh->ultimo_envio_at?->format('d/m/Y H:i'),
                'ultimo_http' => $wh->ultimo_http,
                'ultimo_error' => $wh->ultimo_error,
            ] : null,
        ]);
    }

    public function guardar(Request $request)
    {
        $empresaId = $this->empresaId();

        $datos = $request->validate([
            'url' => 'required|string|max:500',
            'eventos' => 'required|array|min:1',
            'eventos.*' => ['string', Rule::in(array_keys(ApiWebhook::EVENTOS))],
            'activo' => 'required|boolean',
        ]);

        [$error] = ApiWebhookService::validarUrl($datos['url']);
        if ($error) {
            return response()->json(['estado' => 'error', 'mensaje' => __($error)], 422);
        }

        $wh = $this->webhook();
        $secreto = null;
        if (!$wh) {
            $secreto = ApiWebhook::generarSecreto();
            $wh = new ApiWebhook(['cod_empresa' => $empresaId, 'secreto' => $secreto]);
        }

        $wh->fill([
            'url' => $datos['url'],
            'eventos' => array_values($datos['eventos']),
            'estado' => $datos['activo'] ? ApiWebhook::ACTIVO : ApiWebhook::INACTIVO,
        ]);
        if ($datos['activo']) {
            $wh->fallos_consecutivos = 0;
        }
        $wh->save();
        ApiWebhookService::olvidar($empresaId);

        return response()->json([
            'estado' => 'success',
            'mensaje' => __('Webhook guardado correctamente.'),
            'secreto' => $secreto, // solo se muestra al crearlo
        ]);
    }

    public function secreto()
    {
        $wh = $this->webhook();
        if (!$wh) {
            throw new ErrorException(__('Primero configura el webhook.'));
        }

        $secreto = ApiWebhook::generarSecreto();
        $wh->update(['secreto' => $secreto]);

        return response()->json([
            'estado' => 'success',
            'mensaje' => __('Se generó un nuevo secreto. Actualízalo en tu sistema: el anterior dejó de funcionar.'),
            'secreto' => $secreto,
        ]);
    }

    public function probar()
    {
        $wh = $this->webhook();
        if (!$wh) {
            throw new ErrorException(__('Primero configura el webhook.'));
        }

        [$ok, $http, $error] = EnviarWebhookApi::enviar($wh, 'webhook.test', ['mensaje' => 'Evento de prueba de GIJAC MESSAGE BUSINESS']);

        return response()->json([
            'estado' => $ok ? 'success' : 'error',
            'mensaje' => $ok ? __('Tu URL respondió correctamente (HTTP :c).', ['c' => $http]) : __($error ?? 'Error al enviar la prueba.'),
        ], $ok ? 200 : 422);
    }

    private function empresaId()
    {
        if (!servicioPlan('api')) {
            throw new ErrorException(__('Tu plan no incluye el acceso a la API.'));
        }

        $id = auth()->user()->empresa?->id;
        if (!$id) {
            throw new ErrorException(__('No cuentas con un negocio asociado.'));
        }

        return $id;
    }

    private function webhook(): ?ApiWebhook
    {
        return ApiWebhook::where('cod_empresa', $this->empresaId())->first();
    }
}
