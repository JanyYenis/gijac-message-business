<?php

namespace App\Http\Controllers;

use App\Events\NotificacionEvent;
use App\Exceptions\ErrorException;
use App\Exports\ReporteCampana;
use App\Jobs\SendWhatsAppMessage;
use App\Models\DetalleErrorMensaje;
use App\Models\DetalleLink;
use App\Models\VariableCampana;
use App\Models\Etiqueta;
use App\Models\Campana;
use App\Models\Contacto;
use App\Models\EnvioCampana;
use App\Models\Mensaje;
use App\Models\Plantilla;
use App\Models\PlantillaComponente;
use App\Models\Usuario;
use App\Notifications\NuevaCampana;
use App\Services\PrediccionContenidoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Yajra\DataTables\Facades\DataTables;

class CampanaController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!can(Usuario::PERMISO_CAMPANA_EDITAR) && !can(Usuario::PERMISO_CAMPANA_CREAR) &&
            !can(Usuario::PERMISO_CAMPANA_ELIMINAR) && !can(Usuario::PERMISO_CAMPANA_LISTADO)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $info['etiquetas'] = Etiqueta::where('estado', Etiqueta::ACTIVO)
            ->where(function($query) {
                $query->where('uuid', $this->uuid)
                    ->orWhere('cod_empresa', auth()->user()->empresa?->id);
            })
            ->get();
        $info['plantillas'] = Plantilla::with('body')
            ->where('status', Plantilla::APROBADO)
            ->where('cod_config', $this->cod_config)
            ->whereHas('componentes', function($query) {
                $query->whereNot('format', PlantillaComponente::LOCALIZACION);
            })
            ->get();
        $info['numeroTel'] = $this->numeroG;
        $info['categorias'] = Campana::darCategoria();

        return view('campanas.index', $info);
    }

    public function listado(Request $request)
    {
        if (!can(Usuario::PERMISO_CAMPANA_EDITAR) && !can(Usuario::PERMISO_CAMPANA_CREAR) &&
            !can(Usuario::PERMISO_CAMPANA_ELIMINAR) && !can(Usuario::PERMISO_CAMPANA_LISTADO)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $campanas = Campana::select("campanas.id", "campanas.nombre", "campanas.descripcion", "campanas.tipo",
            DB::Raw("CONCAT(u.nombre, ' ', u.apellido) AS nombre_gerente"), "campanas.fecha_envio",
            "campanas.created_at", "campanas.estado", "campanas.id_plantilla")
            ->join('usuarios as u', 'u.uuid', '=', 'campanas.uuid')
            ->with('infoEstado')
            ->orderByDesc('fecha_envio');

        $campanas = $campanas->where(function($query) {
            $query->where('campanas.uuid', $this->uuid)
                ->orWhere('campanas.cod_empresa', auth()->user()->empresa->id);
        });

        return DataTables::eloquent($campanas)
            ->filterColumn('nombre_gerente', function($query, $keyword) {
                $query->whereRaw("CONCAT(u.nombre, ' ', u.apellido) LIKE ?", ["%{$keyword}%"]);
            })
            ->addColumn("estado", function ($model) {
                $info['concepto'] = $model?->infoEstado;
                return view("sistema.estado", $info);
            })
            ->addColumn("action", function ($model) {
                $info['model'] = $model;
                $info['estado_eliminado'] = $model?->estado == Campana::ELIMINADO;
                $info['estado_enviado'] = $model?->estado == Campana::ENVIADO;
                $info['puede_listado'] = can(Usuario::PERMISO_CAMPANA_LISTADO);
                $info['puede_crear'] = can(Usuario::PERMISO_CAMPANA_CREAR);
                $info['puede_editar'] = can(Usuario::PERMISO_CAMPANA_EDITAR);
                $info['puede_eliminar'] = can(Usuario::PERMISO_CAMPANA_ELIMINAR);
                return view("campanas.columnas.acciones", $info);
            })
            ->rawColumns(["action", "estado"])
            ->make(true);
    }

    public function listadoTarjeta(Request $request)
    {
        if (!can(Usuario::PERMISO_CAMPANA_EDITAR) && !can(Usuario::PERMISO_CAMPANA_CREAR) &&
            !can(Usuario::PERMISO_CAMPANA_ELIMINAR) && !can(Usuario::PERMISO_CAMPANA_LISTADO)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $pagina = $request->input('pagina') ?? 1;
        $cantidad = $request->input("cantidad_pagina", 6);

        $campanasQuery = Campana::with(
            'enviosActivos',
            'usuario',
            'infoEstado'
        )
        ->where(function($query) {
            $query->where('uuid', $this->uuid)
                ->orWhere('cod_empresa', auth()->user()->empresa->id);
        })
        ->orderByDesc('fecha_envio');

        $campanas = $campanasQuery->paginate($cantidad, ["*"], "campanas", $pagina);
        $info['ultimaPagina'] = $campanas->lastPage();
        $info["campanas"] = $campanas;
        $info['paginaActual'] = $pagina;
        $info['puede_listado'] = can(Usuario::PERMISO_CAMPANA_LISTADO);
        $info['puede_crear'] = can(Usuario::PERMISO_CAMPANA_CREAR);
        $info['puede_editar'] = can(Usuario::PERMISO_CAMPANA_EDITAR);
        $info['puede_eliminar'] = can(Usuario::PERMISO_CAMPANA_ELIMINAR);

        return [
            "estado" => "success",
            "html" => view("campanas.listado", $info)->render()
        ];
    }

    public function store(Request $request)
    {
        $datos = $request->all();
        $id_plantilla = $datos['id_plantilla'];
        $url = "https://graph.facebook.com/{$this->version}/{$id_plantilla}";
        $metodo = 'GET';
        $response = consultaBase($url, $metodo, $this->token);
        $plantilla = json_decode($response);
        $datos['contenido'] = extraerContenidoPlantilla($plantilla);
        $datos['tipo'] = conocerTipoPlantilla($plantilla);
        $datos['uuid'] = auth()->user()->uuid;
        $datos['cod_empresa'] = auth()->user()->empresa?->id;
        $datos['cod_etiqueta'] = $request->input('etiquetas') ?? null;
        $urls = $request->input('urls') ?? [];

        if ($datos['estado'] == Campana::PENDIENTE) {
            $datos['fecha_envio'] = Carbon::parse($request->input('fecha_envio'));
        } else {
            $datos['fecha_envio'] = Carbon::now();
        }

        $variablesMensaje = [];
        $nombres_variables = $request->input('nombres_variables') ? explode(',', $request->input('nombres_variables')) : [];
        if ($request->input('variables')) {
            $variablesMensaje['variables'] = $request->input('variables');
            $valores = [];
            foreach ($variablesMensaje['variables'] as $key => $value) {
                $llave = "{{".($nombres_variables[$key])."}}";
                $valores[$llave] = $value;
            }

            $datos['contenido'] = str_replace(array_keys($valores), array_values($valores), $datos['contenido']);
        }

        if ($request->input('header_text')) {
            $variablesMensaje['header_text'] = $request->input('header_text') ?? null;
        }

        if ((int) $request->input('usar_recurso')) {
            $url = $request->input('url_recurso'); // URL del archivo
            $headers = get_headers($url, 1); // Obtener cabeceras

            $extension = '';
            if (isset($headers["Content-Type"])) {
                $mimeType = is_array($headers["Content-Type"]) ? end($headers["Content-Type"]) : $headers["Content-Type"];
                if (strpos($mimeType, "image") !== false) {
                    $extension = '.png';
                } elseif (strpos($mimeType, "video") !== false) {
                    $extension = '.mp4';
                } elseif (strpos($mimeType, "pdf") !== false) {
                    $extension = '.pdf';
                }
            }
            if ($extension == '') {
                throw new ErrorException('Error al intentar crear la imagen de la campaña.');
            }
            $rutaDestino = "descargas/meta_".time().$extension; // Ruta donde guardar

            file_put_contents($rutaDestino, file_get_contents($url));
            $variablesMensaje['file'] = asset($rutaDestino);
            $datos['contenido_multimedia'] = asset($rutaDestino);
        } else {
            $variablesMensaje['file'] = null;
            $datos['contenido_multimedia'] = null;
        }

        if ($request->hasFile('archivo') && $request->file('archivo')) {
            $archivo = $request->file('archivo');
            $nombreOriginal = time() . '.' . $archivo->getClientOriginalExtension();
            $mimeType = $archivo->getMimeType();

            // Verificar si el archivo es un PDF
            if ($mimeType == 'application/pdf') {
                $path = $archivo->storeAs('campanas/documentos', $nombreOriginal, 'public');
                $variablesMensaje['file'] = url(Storage::url($path));
                $datos['contenido_multimedia'] = url(Storage::url($path));
            } elseif (str_starts_with($mimeType, 'image/')) {
                $path = $archivo->storeAs('campanas/img', $nombreOriginal, 'public');
                $variablesMensaje['file'] = url(Storage::url($path));
                $datos['contenido_multimedia'] = url(Storage::url($path));
            } elseif ($mimeType == 'video/mp4') {
                $path = $archivo->storeAs('campanas/videos', $nombreOriginal, 'public');
                $variablesMensaje['file'] = url(Storage::url($path));
                $datos['contenido_multimedia'] = url(Storage::url($path));
            } else {
                throw new ErrorException('Error al intentar cargar la imagen.');
            }

            // if (!file_exists($datos['contenido_multimedia'])) {
            //     throw new ErrorException('Error al intentar guardar el archivo.');
            // }

            if (!array_key_exists('file', $variablesMensaje)) {
                throw new ErrorException("Error al intenatr guardar el archivo.");
            }
        } else {
            if ($request->file('archivo')) {
                throw new ErrorException('Por favor, revise si el archivo cuenta con las condiciones establecidas para el envío.');
            }
        }

        $contactosKey = array_values(array_unique(explode(',', $request->input('contactos'))));
        if (!count($contactosKey)) {
            throw new ErrorException('Por favor, seleccione el o los contactos.');
        }

        $contactos = Contacto::whereIn('id', $contactosKey)
            ->get();

        $erroresEnvio = 0;
        if (count($contactos)) {
            $campana = Campana::create($datos);
            if (!$campana) {
                throw new ErrorException('Error al intentar crear la campaña.');
            }

            $campana->refresh();

            // Verifica el ID
            if (empty($campana->id)) {
                throw new ErrorException("La campaña no tiene un ID asignado.");
            }

            Notification::send(auth()->user(), new NuevaCampana($campana->id));
            broadcast(new NotificacionEvent(auth()->user()));

            if (isset($variablesMensaje['variables'])) {
                foreach ($variablesMensaje['variables'] as $index => $variable) {
                    $variable_campana = VariableCampana::create([
                        "cod_campana" => $campana->id,
                        "tipo" => VariableCampana::TEXT,
                        "numero" => $nombres_variables[$index],
                        "valor" => $variable,
                    ]);
                }
            }

            if (count($urls)) {
                $index = 0;
                foreach ($plantilla->components as $datos_plantilla) {
                    if ($datos_plantilla->type == 'BUTTONS') {
                        foreach ($datos_plantilla->buttons as $key => $boton) {
                            if (isset($boton->example)) {
                                $index = $key;
                                break 2; // Salimos de ambos bucles
                            }
                        }
                    }
                }
                foreach ($urls as $nombreUrl => $url) {
                    $variable_campana = VariableCampana::create([
                        "cod_campana" => $campana->id,
                        "tipo" => VariableCampana::URL,
                        "numero" => $index,
                        "nombre" => $nombreUrl,
                        "valor" => $url,
                    ]);
                    $index++;
                }
            }

            if (array_key_exists('header_text', $variablesMensaje)) {
                $variable_campana = VariableCampana::create([
                    "cod_campana" => $campana->id,
                    "tipo" => VariableCampana::HEADER,
                    "numero" => 1,
                    "valor" => $variablesMensaje['header_text'],
                    "estado" => VariableCampana::ACTIVO
                ]);
            }

            foreach ($contactos as $conta) {
                $respuesta = $this->generarCampana($conta, $plantilla, $variablesMensaje, $campana->id, $datos['estado'], $nombres_variables);
                if ($respuesta) {
                    $erroresEnvio++;
                }
            }
        }

        return [
            'estado' =>  $erroresEnvio ? 'info' : 'success',
            'mensaje' => $erroresEnvio ? $erroresEnvio.' mensajes no enviados' :  'Se creo correctamente la campaña.',
        ];
    }

    public function generarCampana($contacto, $plantilla, $variables, $idCampana, $estado, $nombres_variables)
    {
        $errores = 0;
        $info['cod_campana'] = $idCampana;
        $info['telefono'] = $contacto->numero_completo;
        $info['cod_contacto'] = $contacto->id;
        $info['wamid'] = null;
        $info['estado'] = EnvioCampana::ACTIVO;

        if ($estado == Campana::ENVIADO) {
            dispatch(new SendWhatsAppMessage($contacto, $plantilla, $variables, $idCampana, auth()->user()->empresa?->id, $nombres_variables));
        } else {
            $envio_campana = EnvioCampana::updateOrCreate([
                'cod_campana' => $info['cod_campana'],
                'telefono' => $info['telefono'],
                'cod_contacto' => $info['cod_contacto'],
            ], $info);

            if (!$envio_campana) {
                throw new ErrorException("Error al intentar registar el detalle de la campaña.");
            }

            $envio_campana->refresh();
            // Verifica el ID
            if (empty($envio_campana->id)) {
                throw new ErrorException("El envio de campaña no tiene un ID asignado.");
            }

            $variablesDetalleUrls = VariableCampana::where('cod_campana', $idCampana)
                ->where('tipo', VariableCampana::URL)
                ->get();
            if (count($variablesDetalleUrls)) {
                foreach ($variablesDetalleUrls as $value) {
                    DetalleLink::updateOrCreate([
                        "cod_campana" => $info['cod_campana'],
                        "cod_detalle" => $envio_campana?->id,
                        "cod_contacto" => $contacto->id,
                        "cod_detalle_wpp" => $value->id,
                    ], [
                        "estado" => DetalleLink::ACTIVO
                    ]);
                }
            }
        }
        return $errores;
    }

    public function cargarContactos(Request $request)
    {
        $campana = $request->input('campana') ?? null;
        $etiquetas = $request->input('etiquetas') ?? null;

        $contactos = [];
        $enviados = [];
        if ($campana) {
            $enviados = EnvioCampana::selectRaw('c.id as id_key')
                ->join('contactos as c', 'c.id', '=', 'envios_campanas.cod_contacto')
                ->join('etiquetas_contactos as ec', 'c.id', '=', 'ec.cod_contacto')
                ->where('cod_campana', $campana)
                ->where('envios_campanas.estado', EnvioCampana::ACTIVO)
                ->get()
                ->pluck('id_key')
                ->toArray();
            // dd($enviados->toSql(), $enviados->getBindings());
            // dd($enviados);
        }

        $contactos = Contacto::selectRaw("contactos.id,
            CONCAT(contactos.nombre, ' ', COALESCE(contactos.apellido, '')) AS nombre_completo_select,
            CONCAT(contactos.codigo_telefono, '', contactos.telefono) AS numero_completo_select,
            GROUP_CONCAT(e.nombre SEPARATOR ',') as etiquetas_texto
            ")
            ->leftJoin('etiquetas_contactos as ec', 'ec.cod_contacto', '=', 'contactos.id')
            ->leftJoin('etiquetas as e', 'ec.cod_etiqueta', '=', 'e.id')
            ->groupBy('contactos.id', 'contactos.nombre', 'contactos.apellido', 'contactos.codigo_telefono',
                'contactos.telefono')
            ->where(function($query) use($etiquetas){
                if ($etiquetas) {
                    $query->where('ec.cod_etiqueta', $etiquetas);
                }
            })
            ->get()
            ->map(function($contacto) {
                // Convertir el string separado por | a array
                $etiquetas_contacto = !empty($contacto->etiquetas_texto) ? explode(',', $contacto->etiquetas_texto) : [];

                return [
                    'id' => $contacto->id,
                    'nombre_completo_select' => $contacto->nombre_completo_select,
                    'numero_completo_select' => $contacto->numero_completo_select,
                    'etiquetas' => $etiquetas_contacto
                ];
            });

        return DataTables::of($contactos)
            ->addColumn("id", function($model) use($enviados) {
                $info['model'] = $model;
                $info['enviados'] = $enviados ?? [];
                return view("campanas.columnas.check", $info);
            })
            ->addColumn("etiquetas", function($model) use($enviados) {
                $info['etiquetas'] = $model['etiquetas'] ?? [];
                return view("contactos.columnas.etiquetas", $info);
            })
            ->addColumn("nombre_completo", function($model) use($enviados) {
                $info['nombre_completo'] = $model['nombre_completo_select'];
                return view("contactos.columnas.nombre", $info);
            })
            ->addColumn('numero_completo', function($model) {
                return $model['numero_completo_select'] ? formatoTelefono($model['numero_completo_select']) : 'N/A';
            })
            ->rawColumns(["id", "nombre_completo", "etiquetas"])
            ->make(true);
    }

    public function listadoDetalle(Request $request, Campana $campana)
    {
        $envios = EnvioCampana::with(
            'campana.etiqueta',
            'contacto',
            'mensaje',
            'linksActivos',
        )
        ->where('cod_campana', $campana->id)
        ->where('estado', EnvioCampana::ACTIVO)
        ->orderByDesc('id');

        return DataTables::eloquent($envios)
            ->addColumn("action", function ($model) use($campana) {
                $info['model'] = $model;
                $info['formulario'] = $campana?->categoria == Campana::FORMULARIO;
                $info['error'] = $model?->mensaje?->estado == Mensaje::FALLIDO;
                $info['links'] = count($model?->linksActivos) ?? 0;
                return view("campanas.columnas.acciones-detalle", $info);
            })
            ->addColumn("click_links", function($model) {
                return count($model?->linksActivos) ? 'SI' : 'NO';
            })
            ->rawColumns(["action", "estado"])
            ->make(true);
    }

    public function redireccionLink($telefono, $idCampana, $indexBtn)
    {
        $actualizarClick = EnvioCampana::where('cod_campana', $idCampana)
            ->where('telefono', $telefono)
            ->first() ?? null;

        if ($actualizarClick) {
            $actualizarClick->update([
                'apertura' => EnvioCampana::ABIERTO,
                'fecha_apertura' => Carbon::now()
            ]);
        }

        $url = VariableCampana::where('cod_campana', $idCampana)
            ->where('tipo', VariableCampana::URL)
            ->where('numero', $indexBtn)
            ->first();
        DetalleLink::where('cod_campana', $idCampana)
            ->where('cod_detalle_wpp', $url->id)
            ->where('cod_detalle', $actualizarClick->id)
            ->update(['click' => DetalleLink::ABIERTO]);
        $info['url'] = $url?->valor ?? '';

        return view('campanas.vista-chat', $info);
    }

    public function delete(Campana $campana)
    {
        $eliminar = $campana->eliminar();

        if (!$eliminar) {
            throw new ErrorException('A ocurrido un error al intentar eliminar la campaña.');
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se eliminado correctamente la campaña.',
        ];
    }

    public function edit(Request $request, Campana $campana)
    {
        $campana->load('enviosActivos');
        $info['etiquetas'] = Etiqueta::where('estado', Etiqueta::ACTIVO)
            ->where(function($query) {
                $query->where('uuid', $this->uuid)
                    ->orWhere('cod_empresa', auth()->user()->empresa?->id);
            })
            ->get();
        $info['plantillas'] = Plantilla::with('body')
            ->where('status', Plantilla::APROBADO)
            ->where('cod_config', $this->cod_config)
            ->whereHas('componentes', function($query) {
                $query->whereNot('format', PlantillaComponente::LOCALIZACION);
            })
            ->get();
        $info['numeroTel'] = $this->numeroG;
        $info['campana'] = $campana;
        $info['categorias'] = Campana::darCategoria();
        $info['etiquetaSeleccionada'] = $campana?->cod_etiqueta ?? null;

        $respuesta["estado"] = "success";
        $respuesta["mensaje"] = "Datos cargados correctamente";
        $respuesta['html'] = view("campanas.modals.editar", $info)->render();

        return response()->json($respuesta);
    }

    public function update(Request $request, Campana $campana)
    {
        $datos = $request->all();
        $id_plantilla = $request->input('id_plantilla');
        $url = "https://graph.facebook.com/{$this->version}/{$id_plantilla}";
        $metodo = 'GET';
        $response = consultaBase($url, $metodo, $this->token);
        $plantilla = json_decode($response);
        $datos['contenido'] = extraerContenidoPlantilla($plantilla);
        $datos['tipo'] = conocerTipoPlantilla($plantilla);
        $datos['uuid'] = auth()->user()->uuid;
        $urls = json_decode($request->input('urls'), true) ?? [];

        if ($datos['estado'] == Campana::PENDIENTE) {
            $datos['fecha_envio'] = Carbon::parse($request->input('fecha_envio'));
        } else {
            $datos['fecha_envio'] = Carbon::now();
        }

        $variablesMensaje = [];
        $nombres_variables = $request->input('nombres_variables') ? explode(',', $request->input('nombres_variables')) : [];
        if ($request->input('variables')) {
            $variablesMensaje['variables'] = json_decode($request->input('variables'), true) ?? [];
            $valores = [];
            foreach ($variablesMensaje['variables'] as $key => $value) {
                $llave = "{{".($nombres_variables[$key])."}}";
                $valores[$llave] = $value;
            }

            $datos['contenido'] = str_replace(array_keys($valores), array_values($valores), $datos['contenido']);
        }

        if ($request->input('header_text')) {
            $variablesMensaje['header_text'] = $request->input('header_text') ?? null;
        }

        if ((int) $request->input('usar_recurso')) {
            $url = $request->input('url_recurso'); // URL del archivo
            $headers = get_headers($url, 1); // Obtener cabeceras

            $extension = '';
            if (isset($headers["Content-Type"])) {
                $mimeType = is_array($headers["Content-Type"]) ? end($headers["Content-Type"]) : $headers["Content-Type"];
                if (strpos($mimeType, "image") !== false) {
                    $extension = '.png';
                } elseif (strpos($mimeType, "video") !== false) {
                    $extension = '.mp4';
                } elseif (strpos($mimeType, "pdf") !== false) {
                    $extension = '.pdf';
                }
            }
            if ($extension == '') {
                throw new ErrorException('Error al intentar crear la imagen de la campaña.');
            }
            $rutaDestino = "descargas/meta_".time().$extension; // Ruta donde guardar

            file_put_contents($rutaDestino, file_get_contents($url));
            $variablesMensaje['file'] = asset($rutaDestino);
            $datos['contenido_multimedia'] = asset($rutaDestino);
        } else {
            $variablesMensaje['file'] = null;
            $datos['contenido_multimedia'] = null;
        }

        if ($request->hasFile('archivo') && $request->file('archivo')) {
            $archivo = $request->file('archivo');
            $nombreOriginal = time() . '.' . $archivo->getClientOriginalExtension();
            $mimeType = $archivo->getMimeType();

            // Verificar si el archivo es un PDF
            if ($mimeType == 'application/pdf') {
                $path = $archivo->storeAs('campanas/documentos', $nombreOriginal, 'public');
                $variablesMensaje['file'] = url(Storage::url($path));
                $datos['contenido_multimedia'] = url(Storage::url($path));
            } elseif (str_starts_with($mimeType, 'image/')) {
                $path = $archivo->storeAs('campanas/img', $nombreOriginal, 'public');
                $variablesMensaje['file'] = url(Storage::url($path));
                $datos['contenido_multimedia'] = url(Storage::url($path));
            } elseif ($mimeType == 'video/mp4') {
                $path = $archivo->storeAs('campanas/videos', $nombreOriginal, 'public');
                $variablesMensaje['file'] = url(Storage::url($path));
                $datos['contenido_multimedia'] = url(Storage::url($path));
            } else {
                throw new ErrorException('Error al intentar cargar la imagen.');
            }

            if (!file_exists($datos['contenido_multimedia'])) {
                throw new ErrorException('Error al intentar guardar el archivo.');
            }

            if (!array_key_exists('file', $variablesMensaje)) {
                throw new ErrorException("Error al intenatr guardar el archivo.");
            }
        } else {
            if ($request->file('archivo')) {
                throw new ErrorException('Por favor, revise si el archivo cuenta con las condiciones establecidas para el envío.');
            }
        }

        $contactosKey = array_values(array_unique(explode(',', $request->input('contactos'))));
        if (!count($contactosKey)) {
            throw new ErrorException('Por favor, seleccione el o los contactos.');
        }

        $contactos = Contacto::whereIn('id', $contactosKey)
            ->get();

        $erroresEnvio = 0;

        $actualizar = $campana->update($datos);
        if (!$actualizar) {
            throw new ErrorException('Error al intentar actualizar la campaña.');
        }

        if (count($contactos)) {
            DetalleLink::where('cod_campana', $campana->id)
                    ->update(['estado' => DetalleLink::ELIMINADO]);
            VariableCampana::where('cod_campana', $campana->id)
                ->update(['estado' => VariableCampana::ELIMINADO]);
            if (isset($variablesMensaje['variables'])) {
                foreach ($variablesMensaje['variables'] as $index => $variable) {
                    $variable_campana = VariableCampana::updateOrCreate([
                        "cod_campana" => $campana->id,
                        "tipo" => VariableCampana::TEXT,
                        "numero" => $nombres_variables[$index],
                    ],[
                        "valor" => $variable,
                        "estado" => VariableCampana::ACTIVO
                    ]);
                }
            }

            if (count($urls)) {
                $index = 0;
                foreach ($plantilla->components as $datos_plantilla) {
                    if ($datos_plantilla->type == 'BUTTONS') {
                        foreach ($datos_plantilla->buttons as $key => $boton) {
                            if (isset($boton->example)) {
                                $index = $key;
                                break 2; // Salimos de ambos bucles
                            }
                        }
                    }
                }

                foreach ($urls as $nombreUrl => $url) {
                    $variable_campana = VariableCampana::updateOrCreate([
                        "cod_campana" => $campana->id,
                        "tipo" => VariableCampana::URL,
                        "numero" => $index,
                    ], [
                        "nombre" => $nombreUrl,
                        "valor" => $url,
                        "estado" => VariableCampana::ACTIVO
                    ]);
                    $index++;
                }
            }

            if (array_key_exists('header_text', $variablesMensaje)) {
                $variable_campana = VariableCampana::updateOrCreate([
                    "cod_campana" => $campana->id,
                    "tipo" => VariableCampana::HEADER,
                    "numero" => 1,
                ],[
                    "valor" => $variablesMensaje['header_text'],
                    "estado" => VariableCampana::ACTIVO
                ]);
            }

            EnvioCampana::where('cod_campana', $campana->id)
                ->update(['estado' => EnvioCampana::ELIMINADO]);

            foreach ($contactos as $conta) {
                $respuesta = $this->generarCampana($conta, $plantilla, $variablesMensaje, $campana->id, $datos['estado'], $nombres_variables);
                if ($respuesta) {
                    $erroresEnvio++;
                }
            }
        }

        return [
            'estado' =>  $erroresEnvio ? 'info' : 'success',
            'mensaje' => $erroresEnvio ? 'No se pudieron enviar '.$erroresEnvio.' mensajes' :  'Se creo correctamente la campaña.',
        ];
    }

    public function verFormulario($wamid)
    {
        $respuesta = Mensaje::where('wa_message_id', $wamid)->where('type', Mensaje::FLOWS)->first();
        $contenidos = [];
        $contenidoFinal = [];
        if ($respuesta?->body) {
            $contenidos = json_decode($respuesta->body, true);
            // dd($contenidos);
            foreach ($contenidos as $seccion => $contenido) {
                if ($seccion != 'flow_token') {
                    $valor = str_replace("_", " ", $contenido);
                    $datos = explode('_', $seccion);
                    $nombre = '';
                    $key = '';
                    foreach ($datos as $index => $dato) {
                        if ($index == 1) {
                            $key = 'Seccion '.(((int) $dato) +1);
                        }

                        if ($index >= 2 && $index < (count($datos) - 1)) {
                            $nombre = $nombre . ' ' .$dato;
                        }
                    }

                    if ($valor === true) {
                        $valor = 'Si';
                    }

                    if (array_key_exists($key, $contenidoFinal)) {
                        array_push($contenidoFinal[$key], ['nombre_campo' => $nombre, 'valor_campo' => $valor]);
                    } else {
                        $contenidoFinal[$key][] = ['nombre_campo' => $nombre, 'valor_campo' => $valor];
                    }
                }
            }
        }

        // dd($contenidoFinal);
        // dd($respuesta, $contenidos);
        ksort($contenidoFinal);
        $info['respuesta'] = $contenidoFinal;
        $respuesta["estado"] = "success";
        $respuesta["mensaje"] = "Datos cargados correctamente";
        $respuesta["html"] = view("campanas.modals.form", $info)->render();

        return response()->json($respuesta);
    }

    public function verErrores($wamid)
    {
        $error = DetalleErrorMensaje::where('wamid', $wamid)->first();
        $tr = new GoogleTranslate('es');
        foreach ($error->toArray() as $key => $value) {
            if ($key == 'title' || $key == 'message' || $key == 'details') {
                $error->$key = $tr->translate($value);
            }
        }
        $info['error'] = $error;
        $respuesta["estado"] = "success";
        $respuesta["mensaje"] = "Datos cargados correctamente";
        $respuesta["html"] = view("sistema.error", $info)->render();

        return response()->json($respuesta);
    }

    public function verLinks($id)
    {
        $links = DetalleLink::with('variableCampana')
            ->where('cod_detalle', $id)
            ->where('estado', DetalleLink::ACTIVO)
            ->orderBy('cod_detalle_wpp');

        return DataTables::eloquent($links)
            ->make(true);
    }

    public function reenviar(Request $request, Campana $campana)
    {
        $datos = $campana->toArray();
        $datos['estado'] = Campana::PENDIENTE;
        $datos['fecha_envio'] = Carbon::now()->addDays(1);
        $datos['uuid'] = auth()->user()->uuid;
        unset($datos['cod_campana']);

        $campana_nueva = Campana::create($datos);

        if (!$campana_nueva) {
            throw new ErrorException('A ocurrido un error al intentar reenviar la campaña.');
        }

        $contactosEnvio = EnvioCampana::where('estado', EnvioCampana::ACTIVO)
            ->where('cod_campana', $campana_nueva->id)
            ->get()
            ->toArray();

        if (count($contactosEnvio)) {
            foreach ($contactosEnvio as $key => $contacto) {
                unset($contactosEnvio[$key]['id']);
                unset($contactosEnvio[$key]['cod_campana']);
                unset($contactosEnvio[$key]['apertura']);
                unset($contactosEnvio[$key]['fecha_apertura']);
                unset($contactosEnvio[$key]['wamid']);
                unset($contactosEnvio[$key]['created_at']);
                unset($contactosEnvio[$key]['updated_at']);
                $contactosEnvio[$key]['id'] = $campana_nueva->id;
            }

            foreach ($contactosEnvio as $key => $contacto) {
                EnvioCampana::create($contacto);
            }
        }

        $variable_campana = VariableCampana::where('estado', VariableCampana::ACTIVO)
            ->where('cod_campana', $campana->id)
            ->get()
            ->toArray();

        if (count($variable_campana)) {
            foreach ($variable_campana as $key => $detalle) {
                $variable_campana[$key]['cod_campana'] = $campana_nueva->id;
                unset($variable_campana[$key]['created_at']);
                unset($variable_campana[$key]['updated_at']);
            }

            foreach ($variable_campana as $key => $detalle) {
                VariableCampana::create($detalle);
            }
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se duplico correctamente la campaña.',
            'cod_campana' => $campana_nueva->id,
        ];
    }

    public function show(Request $request, Campana $campana)
    {
        $campana->load(
            'infoEstado',
            'enviosActivos',
            'mensajesAbiertos',
            'clicksAbiertos',
        );

        $info['campana'] = $campana;
        $info['campanas'] = Campana::where('uuid', $this->uuid)
            ->get();

        return view('campanas.ver', $info);
    }

    public function filtroShow(Request $request, Campana $campana)
    {
        $horario = EnvioCampana::selectRaw("
                CASE
                    WHEN HOUR(fecha_apertura) BETWEEN 0 AND 3 THEN '00:00-03:59'
                    WHEN HOUR(fecha_apertura) BETWEEN 4 AND 7 THEN '04:00-07:59'
                    WHEN HOUR(fecha_apertura) BETWEEN 8 AND 11 THEN '08:00-11:59'
                    WHEN HOUR(fecha_apertura) BETWEEN 12 AND 15 THEN '12:00-15:59'
                    WHEN HOUR(fecha_apertura) BETWEEN 16 AND 19 THEN '16:00-19:59'
                    WHEN HOUR(fecha_apertura) BETWEEN 20 AND 23 THEN '20:00-23:59'
                END AS rango_horas,
                COUNT(*) as cantidad
            ")
            ->join('campanas as c', 'envios_campanas.cod_campana', '=', 'c.id')
            ->where('c.uuid', $this->uuid)
            ->where('envios_campanas.cod_campana', $campana?->id)
            ->where('apertura', EnvioCampana::ABIERTO)
            ->orderBy('rango_horas')
            ->groupBy('rango_horas')
            ->get()
            ->toArray();

        $cantidad_aperturas = EnvioCampana::where('cod_campana', $campana->id)
            ->where('apertura', EnvioCampana::ABIERTO)
            ->where('estado', EnvioCampana::ACTIVO)
            ->count() ?? 0;
        $cantidad_no_aperturas = EnvioCampana::where('cod_campana', $campana->id)
            ->where('apertura', EnvioCampana::SIN_ABRIR)
            ->where('estado', EnvioCampana::ACTIVO)
            ->count() ?? 0;
        $cantidad_clicks = DetalleLink::where('cod_campana', $campana->id)
            ->where('click', DetalleLink::ABIERTO)
            ->where('estado', DetalleLink::ACTIVO)
            ->count() ?? 0;

        return [
            'estado' => 'success',
            'mensaje' => 'Se cargo correctamente la información.',
            'datos' => [
                'labelHorarios' => array_column($horario, 'rango_horas'),
                'serieHorarios' => array_column($horario, 'cantidad'),
                'cantidad_aperturas' => $cantidad_aperturas,
                'cantidad_no_aperturas' => $cantidad_no_aperturas,
                'cantidad_clicks' => $cantidad_clicks,
            ],
        ];
    }

    public function exportar(Request $request, Campana $campana)
    {
        $campana->load(
            'etiqueta',
            'enviosActivos.mensaje',
            'enviosActivos.contacto',
            'enviosActivos.linksActivos',
        );
        $info = [];
        $info['campana'] = $campana;
        return Excel::download(new ReporteCampana($info), "Campaña - {$campana->nombre} - {$campana->fecha_envio}.xlsx");
    }

    // Inyección automática en el método
    public function predecir(Request $request, PrediccionContenidoService $prediccionService)
    {
        $resultado = $prediccionService->predecirApertura(
            explode(',', $request->contactos_ids),
            $request->contenido_mensaje,
            $request->nombre_campana
        );

        return response()->json($resultado);
    }
}
