<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\Campana;
use App\Models\IdiomaPlantilla;
use App\Models\Plantilla;
use App\Models\PlantillaComponente;
use App\Models\Usuario;
use App\Models\VariableCampana;
use App\Services\Meta\MediaService;
use App\Services\Meta\TemplatesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Yajra\DataTables\Facades\DataTables;

class PlantillaController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $info['numeroTel'] = $this->numeroG;
        $info['estados'] = Plantilla::darEstados();
        $info['categorias'] = Plantilla::darCategoria();
        $info['idiomas'] = IdiomaPlantilla::where('estado', IdiomaPlantilla::ACTIVO)
            ->orderBy('nombre')
            ->get();

        return view('plantillas.index', $info);
    }

    public function listado()
    {
        $plantillas = Plantilla::with(
            'infoEstado',
            'infoCategoria',
            'body',
            'idioma.pais'
        )
            ->where('cod_config', $this->cod_config)
            ->orderByDesc('created_at');

        return DataTables::eloquent($plantillas)
            ->addColumn("action", function ($model) {
                $info['model'] = $model;
                return view("plantillas.columnas.acciones", $info);
            })
            ->addColumn("estado", function ($model) {
                $info['concepto'] = $model?->infoEstado;
                return view("sistema.estado", $info);
            })
            ->addColumn("name", "plantillas.columnas.nombre")
            ->addColumn("category", "plantillas.columnas.categoria")
            ->addColumn("language", "plantillas.columnas.lenguaje")
            ->rawColumns(["action", "name", "category", "language"])
            ->make(true);
    }

    public function show(Request $request, $plantilla = null)
    {
        if (!$plantilla) {
            $respuesta['numeroTel'] = $this->numeroG;
            $respuesta["estado"] = "success";
            $respuesta["mensaje"] = "Sin datos de plantilla";

            return response()->json($respuesta);
        }

        $plantilla = Plantilla::find($plantilla);

        $plantilla->load(
            'header',
            'body',
            'footer',
            'buttons',
        );

        $campana = null;
        $respuesta['url_campana'] = null;
        $respuesta['urls_de_campana'] = [];
        $respuesta['datos_variables'] = [];
        if ($request->input('campana')) {
            $campana = Campana::find($request->input('campana')) ?? null;
            if ($campana) {
                if ($campana->tipo == Campana::IMAGEN || $campana->tipo == Campana::VIDEO) {
                    $respuesta['url_campana'] = $campana->contenido_multimedia;
                }
                $variables_campana = VariableCampana::where('cod_campana', $campana->id)
                    ->where('estado', VariableCampana::ACTIVO)
                    ->where('tipo', VariableCampana::URL)
                    ->get();
                if (count($variables_campana)) {
                    foreach ($variables_campana as $detalle) {
                        $respuesta['urls_de_campana'][] = $detalle->valor;
                    }
                }

                $datosVariables = VariableCampana::where('estado', VariableCampana::ACTIVO)
                    ->where('cod_campana', $campana->id)
                    ->where('tipo', VariableCampana::TEXT)
                    ->orderBy('numero')
                    ->get();
                if (count($datosVariables)) {
                    $datosVariables = $datosVariables->pluck('valor')
                        ->toArray();
                    $respuesta['datos_variables'] = $datosVariables;
                }
            }
        }

        $respuesta['numeroTel'] = $this->numeroG;
        $respuesta['plantilla'] = $plantilla;
        $respuesta["estado"] = "success";
        $respuesta["mensaje"] = "Datos cargados correctamente";

        return response()->json($respuesta);
    }

    public function store(Request $request, TemplatesService $templatesService, MediaService $uploadService)
    {
        // dd($request->all());
        // El frontend ya envía el payload completo y correcto en el formato
        // de Meta (buildMetaPayload() -> name, language, category, components).
        $payload = json_decode($request->input('payload'), true);

        if (! is_array($payload) || empty($payload['name']) || empty($payload['category']) || empty($payload['components'])) {
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'El payload de la plantilla es inválido o está incompleto.',
            ], 422);
        }

        $components = $payload['components'];

        // Si el header es IMAGE/VIDEO/DOCUMENT, el JSON llega SIN header_handle
        // (el frontend no puede generarlo). El archivo real viaja en el mismo
        // FormData bajo "archivo"; aquí se sube con la Resumable Upload
        // API y se inyecta el handle en el componente HEADER correspondiente.
        $headerIndex = collect($components)->search(function ($c) {
            return ($c['type'] ?? '') === 'HEADER' && in_array($c['format'] ?? '', ['IMAGE', 'VIDEO', 'DOCUMENT']);
        });

        if ($headerIndex !== false) {
            $archivo = $request->file('archivo');

            if (! $archivo || ! $archivo->isValid()) {
                return response()->json([
                    'estado' => 'error',
                    'mensaje' => 'Falta el archivo del encabezado (imagen, video o documento).',
                ], 422);
            }

            try {
                $handle = $uploadService->uploadHeaderMedia(
                    $this->version,
                    $this->token,
                    $this->app_id,
                    $archivo->getRealPath(),
                    $archivo->getMimeType()
                );
            } catch (\Throwable $e) {
                return response()->json([
                    'estado' => 'error',
                    'mensaje' => 'No se pudo subir el archivo del encabezado a Meta.',
                    'error' => $e->getMessage(),
                ], 502);
            }

            $components[$headerIndex]['example'] = ['header_handle' => [$handle]];
        }

        $respuestaMeta = $templatesService->createTemplateFromComponents(
            $this->version,
            $this->token,
            $this->waba_id,
            $payload['name'],
            $payload['language'],
            $payload['category'],
            $components
        );

        // createTemplateFromComponents() devuelve el array decodificado de Meta
        // cuando todo sale bien, o una JsonResponse de error si Meta rechazó
        // la plantilla (nombre repetido, texto inválido, etc.).
        if ($respuestaMeta instanceof \Illuminate\Http\JsonResponse) {
            $error = $respuestaMeta->getData(true);

            return response()->json([
                'estado' => 'error',
                'mensaje' => $error['error']['error']['message']
                    ?? $error['message']
                    ?? 'Meta rechazó la plantilla.',
                'validaciones' => [],
            ], $respuestaMeta->getStatusCode());
        }

        // Aquí, si además quieres guardar un registro local de la plantilla
        // (nombre, categoría, estado PENDING, id devuelto por Meta, etc.),
        // es el lugar: Plantilla::create([...]) con $respuestaMeta['id'].

        return response()->json([
            'estado' => 'success',
            'mensaje' => 'Plantilla enviada a revisión de Meta.',
            'plantilla' => $respuestaMeta,
        ]);
    }

    // Pega esto en el mismo controlador donde ya tienes store(). Asume que
    // TemplatesService y ResumableUploadService están inyectados (ver el
    // store() que armamos antes) y que tienes App\Models\PlantillaComponente
    // importado.

    public function edit(Request $request, Plantilla $plantilla)
    {
        $plantilla->load(
            'componentes',
            'infoFormatoParametro',
            'infoCategoria',
            'infoEstado'
        );

        $info['plantilla'] = $plantilla;
        $info['numeroTel'] = $this->numeroG;
        $info['estados'] = Plantilla::darEstados();
        $info['categorias'] = Plantilla::darCategoria();
        $info['idiomas'] = IdiomaPlantilla::where('estado', IdiomaPlantilla::ACTIVO)
            ->orderBy('nombre')
            ->get();

        $respuesta['estado'] = 'success';
        $respuesta['mensaje'] = 'Datos cargados correctamente';
        $respuesta['html'] = view('plantillas.modals.editar', $info)->render();

        // La vista Blade solo precarga nombre/categoría/idioma (son campos
        // simples con value="{{ }}" / selected). El header/body/footer/botones
        // dependen de JS (HeaderBuilder, BodyBuilder, ButtonBuilder...), así
        // que aquí se manda aparte en el mismo formato {header, body, footer,
        // buttons} que ya usa el formulario, para que editar.js los precargue.
        $respuesta['datos'] = $this->mapearComponentesParaFormulario($plantilla);

        return response()->json($respuesta);
    }

    /**
     * Convierte las filas de plantilla_componentes (una por HEADER/BODY/
     * FOOTER/BUTTONS) al formato {header, body, footer, buttons} que
     * consume el formulario (mismo formato que usan HeaderBuilder.load(),
     * BodyBuilder.load(), FooterBuilder.load() y ButtonBuilder.load() en
     * crear.js/editar.js).
     */
    protected function mapearComponentesParaFormulario(Plantilla $plantilla): array
    {
        $datos = ['header' => null, 'body' => null, 'footer' => null, 'buttons' => []];

        foreach ($plantilla->componentes as $componente) {
            $example = is_string($componente->example) ? json_decode($componente->example, true) : $componente->example;
            $botones = is_string($componente->buttons) ? json_decode($componente->buttons, true) : $componente->buttons;
            $example = $example ?: [];

            switch ($componente->type) {
                case PlantillaComponente::HEADER:
                    // El campo "format" se guarda con el código interno (1..5);
                    // se traduce de vuelta al string que espera el JS (TEXT/IMAGE/...).
                    $formatoMeta = array_search($componente->format, PlantillaComponente::VALIDAR_FORMATO) ?: 'TEXT';

                    $datos['header'] = [
                        'format' => $formatoMeta,
                        'text' => $componente->text,
                        'examples' => $example['header_text'] ?? [],
                    ];
                    break;

                case PlantillaComponente::BODY:
                    // Admite tanto variables numeradas (example.body_text) como
                    // con nombre (example.body_text_named_params).
                    $examples = $example['body_text'][0]
                        ?? (isset($example['body_text_named_params'])
                            ? array_column($example['body_text_named_params'], 'example')
                            : []);

                    $datos['body'] = [
                        'text' => $componente->text,
                        'examples' => $examples,
                    ];
                    break;

                case PlantillaComponente::FOOTER:
                    $datos['footer'] = [
                        'text' => $componente->text,
                        'code_expiration_minutes' => $example['code_expiration_minutes'] ?? null,
                    ];
                    break;

                case PlantillaComponente::BUTTONS:
                    $datos['buttons'] = $botones ?: [];
                    break;
            }
        }

        return $datos;
    }

    /**
     * Actualiza una plantilla existente. Espera el mismo "payload" armado
     * por buildMetaPayload() en editar.js (name, language, category,
     * components), más el id de la plantilla local y, si aplica, el
     * archivo del header bajo "header_media" — igual que store().
     */
    public function update(Request $request, Plantilla $plantilla, TemplatesService $templatesService, MediaService $uploadService)
    {
        $payload = json_decode($request->input('payload'), true);

        if (! is_array($payload) || empty($payload['name']) || empty($payload['category']) || empty($payload['components'])) {
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'El payload de la plantilla es inválido o está incompleto.',
            ], 422);
        }

        if (empty($plantilla->meta_id)) {
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'Esta plantilla no tiene un ID de Meta asociado (meta_id); no se puede actualizar en la API.',
            ], 422);
        }

        $components = $payload['components'];

        $headerIndex = collect($components)->search(function ($c) {
            return ($c['type'] ?? '') === 'HEADER' && in_array($c['format'] ?? '', ['IMAGE', 'VIDEO', 'DOCUMENT']);
        });

        if ($headerIndex !== false) {
            $archivo = $request->file('header_media');

            if ($archivo && $archivo->isValid()) {
                try {
                    $handle = $uploadService->uploadHeaderMedia(
                        $this->version,
                        $this->token,
                        $this->app_id,
                        $archivo->getRealPath(),
                        $archivo->getMimeType()
                    );
                    $components[$headerIndex]['example'] = ['header_handle' => [$handle]];
                } catch (\Throwable $e) {
                    return response()->json([
                        'estado' => 'error',
                        'mensaje' => 'No se pudo subir el archivo del encabezado a Meta.',
                        'error' => $e->getMessage(),
                    ], 502);
                }
            }
            // Si no llegó un archivo nuevo, se asume que el header no cambió;
            // Meta conserva el media_handle previo de la plantilla existente.
        }

        $respuestaMeta = $templatesService->updateTemplateFromComponents(
            $this->version,
            $this->token,
            $plantilla->meta_id,
            $payload['name'],
            $payload['language'],
            $payload['category'],
            $components
        );

        if ($respuestaMeta instanceof \Illuminate\Http\JsonResponse) {
            $error = $respuestaMeta->getData(true);

            return response()->json([
                'estado' => 'error',
                'mensaje' => $error['error']['error']['message']
                    ?? $error['message']
                    ?? 'Meta rechazó la actualización de la plantilla.',
                'validaciones' => [],
            ], $respuestaMeta->getStatusCode());
        }

        // Aquí, si además sincronizas la copia local (tabla plantillas /
        // plantilla_componentes) con lo que el usuario acaba de editar,
        // es el lugar: $plantilla->update([...]) + reemplazar sus
        // componentes con los nuevos.

        return response()->json([
            'estado' => 'success',
            'mensaje' => 'Plantilla actualizada y enviada a revisión de Meta.',
            'plantilla' => $respuestaMeta,
        ]);
    }

    public function buscar(Request $request)
    {
        $plantillas = Plantilla::with(
            'infoEstado',
            'infoCategoria',
            'body'
        )
            ->where('cod_config', $this->cod_config)
            ->get();

        return [
            'estado'     => 'success',
            'mensaje'    => 'Se cargo correctamente.',
            'plantillas' => $plantillas,
        ];
    }

    public function delete(Request $request, $plantilla)
    {
        $plantilla = Plantilla::find($plantilla);
        if (!$plantilla) {
            return [
                'estado'  => 'error',
                'mensaje' => 'No se encontró la plantilla.',
            ];
        }

        $eliminar = $plantilla->eliminar();
        if (!$eliminar) {
            throw new ErrorException("No se pudo eliminar la plantilla.");
        }

        app(TemplatesService::class)->deleteMessageTemplate($plantilla->name);

        return [
            'estado'  => 'success',
            'mensaje' => 'Plantilla eliminada correctamente.',
        ];
    }

    public function sincronizar(Request $request)
    {
        Artisan::call('sincronizar:plantillas', [
            'app_id' => $this->app_id
        ]);

        return [
            'estado'  => 'success',
            'mensaje' => 'Se ha sincronizado correctamente.',
        ];
    }
}
