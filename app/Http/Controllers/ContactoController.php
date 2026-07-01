<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Http\Requests\Contactos\StoreContactoRequest;
use App\Models\Contacto;
use App\Models\Etiqueta;
use App\Models\EtiquetaContacto;
use App\Models\Plan;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ContactoController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!can(Usuario::PERMISO_CLIENTES_EDITAR) && !can(Usuario::PERMISO_CLIENTES_CREAR) &&
            !can(Usuario::PERMISO_CLIENTES_ELIMINAR) && !can(Usuario::PERMISO_CLIENTES_LISTADO)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $info['etiquetas'] = Etiqueta::where('estado', Etiqueta::ACTIVO)
            ->where(function($query) {
                $query->where('uuid', $this->uuid)
                    ->orWhere('cod_empresa', auth()->user()->empresa?->id);
            })
            ->get();
        $info['generos'] = Contacto::darTipoGenero();

        return view('contactos.index', $info);
    }

    public function listado(Request $request)
    {
        if (!can(Usuario::PERMISO_CLIENTES_EDITAR) &&
            !can(Usuario::PERMISO_CLIENTES_ELIMINAR) && !can(Usuario::PERMISO_CLIENTES_LISTADO)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $contactos = Contacto::selectRaw(
            "contactos.id,
            CONCAT(contactos.nombre, ' ', COALESCE(contactos.apellido, '')) AS nombre_completo_select,
            CONCAT(contactos.codigo_telefono, '', contactos.telefono) AS numero_completo_select,
            cp.nombre as genero,
            contactos.tratamiento_datos,
            contactos.estado"
        )
        ->join('conceptos as cp', 'cp.codigo', '=', 'contactos.genero')
        ->join('tipos_conceptos as tc', 'tc.id', '=', 'cp.id_tipo')
        ->where('tc.nombre', Contacto::TC_GENERO_USUARIOS)
        ->where('contactos.estado', '!=', Contacto::ELIMINADO)
        ->where('uuid', $this->uuid);

        return DataTables::eloquent($contactos)
            ->filterColumn('nombre_completo_select', function($query, $keyword) {
                $query->whereRaw("CONCAT(contactos.nombre, ' ', COALESCE(contactos.apellido, '')) LIKE ?", ["%{$keyword}%"]);
            })
            ->filterColumn('numero_completo_select', function($query, $keyword) {
                $query->whereRaw("CONCAT(contactos.codigo_telefono, '', contactos.telefono) LIKE ?", ["%{$keyword}%"]);
            })
            ->addColumn('numero_completo_select', function($model) {
                return $model?->numero_completo_select ? formatoTelefono($model->numero_completo_select) : 'N/A';
            })
            ->addColumn("estado", "contactos.columnas.estado")
            ->addColumn("action", "contactos.columnas.acciones")
            ->rawColumns(["action", "estado"])
            ->make(true);
    }

    public function store(StoreContactoRequest $request)
    {
        $datos = $request->all();
        $datos['uuid'] = auth()->user()->uuid;
        $datos['cod_empresa'] = auth()->user()->empresa?->id;
        $contacto = Contacto::create($datos);

        if (!$contacto) {
            throw new ErrorException("Error al intentar crear un contacto.");
        }

        $contacto->refresh();

        // Verifica el ID
        if (empty($contacto->id)) {
            throw new ErrorException("El contacto no tiene un ID asignado.");
        }

        if (count($request->input('etiquetas'))) {
            foreach ($request->input('etiquetas') as $key => $value) {
                EtiquetaContacto::create([
                    "cod_contacto" => $contacto->id,
                    "cod_etiqueta" => $value,
                ]);
            }
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se creo correctamente el contacto.',
        ];
    }

    public function cargarContactos(Request $request)
    {
        $file = $request->file('archivo');
        $datosError = [];
        // Cargar el archivo Excel o CSV
        $data = Excel::toArray([], $file[0]);

        // dd(count($data[0]) - 1);

        $cantidadRegistros = count($data[0]) - 1;
        $tienePlan = auth()->user()?->cod_plan ?? null;
        $esDemo = auth()->user()?->demo ?? null;
        $cantidadContactosActivos = Contacto::where('estado', Contacto::ACTIVO)
            ->where('uuid', $this->uuid)
            ->count();
        if ($tienePlan) {
            $plan = Plan::find($tienePlan);
            if ($plan?->max_contactos) {
                if ($plan?->max_contactos <= ($cantidadContactosActivos + $cantidadRegistros)) {
                    throw new ErrorException('Has superado el limite de contactos activos para tu plan.');
                }
            }
        } else if ($esDemo) {
            if (30 <= ($cantidadContactosActivos + $cantidadRegistros)) {
                throw new ErrorException('Has superado el limite de 30 contactos activos para tu plan demo.');
            }
        } else {
            throw new ErrorException('Por favor selecciona uno de nuestros planes para crear un contacto.');
        }

        // Recorrer los datos y guardar en la base de datos
        foreach ($data[0] as $index => $row) {
            if ($index) {
                if ($row[3] && $row[4]) {
                    $row[3] = preg_replace('/\s+/', '', $row[3]);
                    $row[4] = preg_replace('/\s+/', '', $row[4]);
                    if (is_numeric($row[4]) && is_numeric($row[3])) {
                        $etiqueta = $row[2] ?? null;
                        $cantidadCodigo = strlen($row[4]);
                        $telefono = (string) $row[3];
                        $telefono = preg_replace('/\s+/', '', $telefono);
                        if ((int) substr($telefono, 0, $cantidadCodigo) != $row[4]) {
                            $telefono = $telefono; // Agrega $row[4] al principio
                        }
                        $contacto = Contacto::updateOrCreate([
                            'codigo_telefono' => $row[4] ?? null,
                            'telefono' => $telefono,
                            'uuid' => auth()->user()->uuid,
                        ],[
                            'nombre' => $row[0] ?? null,
                            'apellido' => $row[1] ?? null,
                            'estado' => Contacto::ACTIVO,
                        ]);
                        $contacto->refresh();

                        // Verifica el ID
                        if (empty($contacto->id)) {
                            throw new ErrorException("El contacto no tiene un ID asignado.");
                        }
                        if ($etiqueta) {
                            $slug = Str::slug($row[2], '.');
                            $etiqueta = Etiqueta::where('slug', $slug)
                                ->where(function($query) {
                                    $query->where('uuid', $this->uuid)
                                        ->orWhere('cod_empresa', auth()->user()->empresa?->id);
                                })
                                ->first();

                            if (!$etiqueta) {
                                $etiqueta = Etiqueta::create([
                                    'nombre' => $row[2],
                                    'uuid' => auth()->user()->uuid,
                                    'cod_empresa' => auth()->user()->empresa?->id,
                                ]);
                                $etiqueta->refresh();
                                // Verifica el ID
                                if (empty($etiqueta->id)) {
                                    throw new ErrorException("La etiqueta no tiene un ID asignado.");
                                }
                            }

                            $nuevaEtiqueta = EtiquetaContacto::updateOrCreate([
                                "cod_contacto" => $contacto->id,
                                "cod_etiqueta" => $etiqueta->id,
                            ],[
                                "estado" => EtiquetaContacto::ACTIVO,
                            ]);
                        }
                    } else {
                        $datosError[] = $row;
                    }
                } else {
                    $datosError[] = $row;
                }
            }
        }

        return [
            'estado' => !count($datosError) ? 'success' : 'info',
            'titulo' => !count($datosError) ? '¡Error!' : 'Importante',
            'mensaje' => !count($datosError) ? 'Se cargo el archivo correctamente' : "Por favor revise el archivo, recuerde que el numero de telefono, el codigo del pais y el nombre o abreviacion del pais son indispensables",
        ];
    }

    public function show(Contacto $contacto)
    {
        $resultados = Contacto::select('contactos.id', 'contactos.nombre', 'contactos.apellido', 'contactos.telefono', 'e.nombre as nombre_etiqueta', 'ec.estado', 'ec.id as codigo')
            ->join('etiquetas_contactos as ec', 'contactos.id', '=', 'ec.cod_contacto')
            ->join('etiquetas as e', 'ec.cod_etiqueta', '=', 'e.id')
            ->where('contactos.id', $contacto->id)
            ->where('ec.estado', '!=', EtiquetaContacto::ELIMINADO);

        // dd($resultados->toSql(), $resultados->getBindings());
        // dd($resultados->get());
        return DataTables::eloquent($resultados)
            ->addColumn('telefono', function($model) {
                return $model?->telefono ? formatoTelefono($model->telefono) : 'N/A';
            })
            ->make(true);
    }

    public function showInfo(Contacto $contacto)
    {
        $info['contacto'] = $contacto;

        return view('contactos.info', $info);
    }

    public function edit(Request $request, Contacto $contacto)
    {
        $contacto->load(
            'ciudad.pais',
        );

        $info['contacto'] = $contacto;
        $info['clientesEtiquetas'] = array_column(EtiquetaContacto::where('cod_contacto', $contacto->id)
            ->where('estado', EtiquetaContacto::ACTIVO)
            ->get()
            ->toArray(), 'cod_etiqueta');
        $info['etiquetas'] = Etiqueta::where('estado', Etiqueta::ACTIVO)
            ->where(function($query) {
                $query->where('uuid', $this->uuid)
                    ->orWhere('cod_empresa', auth()->user()->empresa?->id);
            })
            ->get();
        $info['generos'] = Contacto::darTipoGenero();

        $respuesta["estado"] = "success";
        $respuesta["mensaje"] = "Datos cargados correctamente";
        $respuesta['html'] = view("contactos.modals.editar", $info)->render();

        return response()->json($respuesta);
    }

    public function update(Request $request, Contacto $contacto)
    {
        $datos = $request->all();
        $actualizar = $contacto->update($datos);
        $etiquetas = array_filter(explode(',', $request->input('etiquetas')), function ($valor) {
            return $valor !== null && $valor !== '';
        });

        if (!$actualizar) {
            throw new ErrorException('A ocurrido un error al intentar actualizar el contacto.');
        }

        if (count($etiquetas)) {
            foreach ($etiquetas as $key => $value) {
                $etiqueta_actual = EtiquetaContacto::updateOrCreate([
                    "cod_contacto" => $contacto->id,
                    "cod_etiqueta" => $value,
                ], [
                    'estado' => EtiquetaContacto::ACTIVO
                ]);
            }
        }

        EtiquetaContacto::where('cod_contacto', $contacto->id)
            ->whereNotIn("cod_etiqueta", $etiquetas)
            ->update(['estado' => EtiquetaContacto::INACTIVO]);

        return [
            'estado' => 'success',
            'mensaje' => 'Se actualizo correctamente el contacto.',
        ];
    }

    public function delete(Contacto $contacto)
    {
        $eliminar = $contacto->eliminar();

        if (!$eliminar) {
            throw new ErrorException('A ocurrido un error al intentar eliminar el contacto.');
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se eliminado correctamente el contacto.',
        ];
    }

    public function buscar(Request $request)
    {
        $nombre = $request->get("busqueda");
        $filtro = "%$nombre%";

        $contactos = Contacto::selectRaw('id, CONCAT(nombre," ", COALESCE(apellido, "")) as text')
            ->where(function($query) use($filtro){
                $query->whereRaw("LOWER(nombre) LIKE LOWER(?)", $filtro)
                    ->orWhereRaw("LOWER(apellido) LIKE LOWER(?)", $filtro);
            })
            ->where('estado', Contacto::ACTIVO)
            ->where('uuid', $this->uuid)
            ->orderBy('text')
            ->get();

        return response()->json($contactos);
    }

    public function buscarPrediccion(Request $request)
    {
        $contactos = Contacto::selectRaw('
            contactos.id,
            CONCAT(contactos.nombre," ", COALESCE(contactos.apellido, "")) as text,
            CONCAT(contactos.codigo_telefono, contactos.telefono) AS numero_cliente,

            -- Tasa de apertura: aperturas / total enviados * 100
            (
                SELECT
                    ROUND((SUM(CASE WHEN apertura = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2)
                FROM envios_campanas ec
                WHERE ec.cod_contacto = contactos.id
            ) AS tasa_apertura,

            -- Última fecha de apertura del usuario
            (
                SELECT fecha_apertura
                FROM envios_campanas ec2
                WHERE ec2.cod_contacto = contactos.id
                    AND ec2.apertura = 1
                ORDER BY ec2.fecha_apertura DESC
                LIMIT 1
            ) AS ultima_apertura
        ')
        ->where('contactos.estado', Contacto::ACTIVO)
        ->where('contactos.uuid', $this->uuid)
        ->orderBy('text')
        ->get();

        return [
            'estado'    => 'success',
            'mensaje'   => 'Se cargo correctamente los contactos',
            'contactos' => $contactos
        ];
    }
}
