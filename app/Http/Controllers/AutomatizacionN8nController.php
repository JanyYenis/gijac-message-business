<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\AutomatizacionN8n;
use App\Models\AutomatizacionN8nEjecucion;
use App\Models\AutomatizacionN8nEvento;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AutomatizacionN8nController extends Controller
{
    public function index(Request $request)
    {
        if (!can(Usuario::PERMISO_CHATBOT_CREAR) && !can(Usuario::PERMISO_CHATBOT_EDITAR) &&
            !can(Usuario::PERMISO_CHATBOT_ELIMINAR) && !can(Usuario::PERMISO_CHATBOT_LISTADO)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $info['metodos'] = AutomatizacionN8n::darMetodo();
        $info['eventos'] = AutomatizacionN8nEvento::darEvento();
        $info['automatizacion'] = null;
        $info['total_ejecuciones'] = 0;
        $info['ultima_ejecucion'] = null;
        $automatizacion = AutomatizacionN8n::where('estado', AutomatizacionN8n::ACTIVO)
            ->where('cod_empresa', auth()->user()->empresa?->id)
            ->first() ?? null;
        if ($automatizacion) {
            $info['automatizacion'] = $automatizacion;
            $info['total_ejecuciones'] = AutomatizacionN8nEjecucion::where('cod_automatizacion', $automatizacion?->id)
                ->count();
            $info['ultima_ejecucion'] = AutomatizacionN8nEjecucion::where('cod_automatizacion', $automatizacion?->id)
                ->orderByDesc('fecha_ejecucion')
                ->first();
        }

        return view('chatbots.webhook-n8n.index', $info);
    }

    public function store(Request $request)
    {
        $datos = $request->all();
        $datos['cod_empresa'] = auth()->user()->empresa?->id;
        $automatizacion = AutomatizacionN8n::updateOrCreate([
            'cod_empresa' => $datos['cod_empresa']
        ], $datos);

        if (!$automatizacion) {
            throw new ErrorException('Ha ocurrido un error al intentar registrar el webhook de n8n.');
        }

        $automatizacion->refresh();

        // Verifica el ID
        if (empty($automatizacion->id)) {
            throw new ErrorException("La automatizacion no tiene un ID asignado.");
        }

        AutomatizacionN8nEvento::where('estado', AutomatizacionN8nEvento::ACTIVO)
            ->where('cod_automatizacion', $automatizacion?->id)
            ->update(['estado' => AutomatizacionN8nEvento::ELIMINADO]);

        $eventos = $request->input('eventos') ?? [];
        foreach ($eventos as $evento) {
            AutomatizacionN8nEvento::updateOrCreate([
                'cod_automatizacion' => $automatizacion?->id,
                'evento' => $evento,
            ], ['estado' => AutomatizacionN8nEvento::ACTIVO]);
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se registro correctamente el webhook.'
        ];
    }

    public function probar(Request $request)
    {
        try {

            $inicio = microtime(true);

            $response = Http::timeout(15)
                ->withoutVerifying()
                ->post($request->url_webhook, [
                    'evento' => 'prueba',
                    'numero' => auth()->user()->numero_completo,
                    'nombre' => auth()->user()->nombre_completo,
                    'mensaje' => 'Hola mundo',
                    'mensaje_id' => 'TEST-' . now()->timestamp,
                    'empresa_id' => auth()->user()->empresa->id,
                    'canal' => 'whatsapp',
                    'fecha' => now()->toDateTimeString(),
                ]);

            $tiempo = round((microtime(true) - $inicio) * 1000);

            return response()->json([
                'estado' => $response->successful() ? 'success' : 'error',
                'codigo' => $response->status(),
                'tiempo' => $tiempo,
                'respuesta' => $response->body(),
                'mensaje' => $response->successful()
                    ? 'Webhook respondió correctamente'
                    : 'El webhook respondió con error'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'estado' => 'error',
                'mensaje' => $e->getMessage(),
            ], 500);

        }
    }

    public function enviarPrueba(Request $request)
    {
        $automatizacion = AutomatizacionN8n::query()
            ->where('cod_empresa', auth()->user()->cod_empresa)
            ->where('estado', AutomatizacionN8n::ACTIVO)
            ->where('webhook_activo', AutomatizacionN8n::ACTIVO)
            ->first();

        if (!$automatizacion) {
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'No existe una automatización configurada.'
            ], 422);
        }

        $payload = [
            'numero'     => $request->numero,
            'nombre'     => $request->nombre,
            'mensaje'    => $request->mensaje,
            'mensaje_id' => 'TEST-' . now()->timestamp,
            'empresa_id' => auth()->user()->cod_empresa,
            'canal'      => 'whatsapp',
            'fecha'      => now()->toDateTimeString(),
        ];

        try {

            $inicio = microtime(true);

            $http = Http::timeout(30)->withoutVerifying();

            if ($automatizacion->token_seguridad) {
                $http->withHeaders([
                    'Authorization' => 'Bearer ' . $automatizacion->token_seguridad
                ]);
            }

            $response = match ($automatizacion->metodoHttp?->nombre ?? 'POST') {
                'GET' => $http->get($automatizacion->url_webhook, $payload),
                'PUT' => $http->put($automatizacion->url_webhook, $payload),
                'PATCH' => $http->patch($automatizacion->url_webhook, $payload),
                default => $http->post($automatizacion->url_webhook, $payload),
            };

            $tiempo = round(
                (microtime(true) - $inicio) * 1000
            );

            return response()->json([
                'estado' => $response->successful() ? 'success' : 'error',
                'request' => $payload,
                'response' => $response->json() ?? $response->body(),
                'codigo' => $response->status(),
                'tiempo' => $tiempo,
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'estado' => 'error',
                'request' => $payload,
                'response' => [
                    'error' => $e->getMessage()
                ],
                'tiempo' => 0,
            ], 500);

        }
    }
}
