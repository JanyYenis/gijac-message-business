<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use App\Models\Campana;
use App\Models\EnvioCampana;
use App\Models\Mensaje;
use App\Models\Plan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function precios()
    {
        $info['planes'] = Plan::with('serviciosHabilitados', 'infoTipo')
            ->where('estado', Plan::ACTIVO)
            ->where('tipo', Plan::MESUAL)
            ->orderBy('categoria')
            ->get();

        return view('precios', $info);
    }

    public function filtro(Request $request)
    {
        $fechas = explode(' - ', $request->input('fechas'));

        $dias = [
            1 => ['nombre' => 'domingo', 'dayOfWeek' => 1],
            2 => ['nombre' => 'lunes', 'dayOfWeek' => 2],
            3 => ['nombre' => 'martes', 'dayOfWeek' => 3],
            4 => ['nombre' => 'miercoles', 'dayOfWeek' => 4],
            5 => ['nombre' => 'jueves', 'dayOfWeek' => 5],
            6 => ['nombre' => 'viernes', 'dayOfWeek' => 6],
            7 => ['nombre' => 'sabado', 'dayOfWeek' => 7],
        ];

        $resultados = [];

        foreach ($dias as $dia) {
            $data = EnvioCampana::selectRaw("
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
            ->whereRaw('DAYOFWEEK(fecha_apertura) = ?', [$dia['dayOfWeek']])
            ->join('campanas as c', 'envios_campanas.cod_campana', '=', 'c.id')
            ->leftjoin('etiquetas as et', 'et.id', '=', 'c.cod_etiqueta')
            ->where('apertura', EnvioCampana::ABIERTO)
            ->whereBetween('fecha_envio', [$fechas[0].' 00:00:00', $fechas[1].' 23:59:59'])
            ->where(function ($query) use ($request) {
                if ($request->input('etiquetas')) {
                    $query->whereIn('c.cod_etiqueta', $request->input('etiquetas'));
                }
                if ($request->input('contactos')) {
                    $query->whereIn('envios_campanas.cod_contacto', $request->input('contactos'));
                }
                $query->where('c.uuid', $this->uuid);
            })
            ->orderBy('rango_horas')
            ->groupBy('rango_horas')
            ->get();

            $resultados[$dia['nombre']] = $data;
        }

        // Procesar los resultados
        $response = [];
        foreach ($dias as $dia) {
            $nombre = $dia['nombre'];
            $data = $resultados[$nombre];

            $labels = [];
            $series = [['name' => 'Apertura', 'data' => []]];

            foreach ($data as $dato) {
                $labels[] = $dato->rango_horas;
                $series[0]['data'][] = $dato->cantidad ?? 0;
            }

            $response["series" . ucfirst($nombre)] = $series;
            $response["labels" . ucfirst($nombre)] = $labels;
        }

        $etiquetas = Campana::with('enviosActivos')->selectRaw('et.nombre, et.color, count(DISTINCT campanas.id) as cantidad')
            ->join('envios_campanas as ec', 'campanas.id', '=', 'ec.cod_campana')
            ->join('etiquetas as et', 'et.id', '=', 'campanas.cod_etiqueta')
            ->whereBetween('fecha_envio', [$fechas[0].' 00:00:00', $fechas[1].' 23:59:59'])
            ->whereHas('enviosActivos', function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    if ($request->input('etiquetas')) {
                        $query->whereIn('cod_etiqueta', $request->input('etiquetas'));
                    }
                    $query->where('campanas.uuid', $this->uuid);
                });
            })
            ->where(function($query) use($request){
                if ($request->input('contactos')) {
                    $query->whereIn('ec.cod_contacto', $request->input('contactos'));
                }
            })
            ->groupBy('et.nombre', 'et.color')
            ->orderByDesc('et.nombre')
            ->get();

        $seriesEtiquetas = [];
        foreach ($etiquetas as $dato) {
            $seriesEtiquetas['etiqueta'][] = $dato?->nombre ?? 'N/A';
            $seriesEtiquetas['sales'][] = $dato?->cantidad ?? 0;
            $seriesEtiquetas['colores'][] = $dato?->color ?? 0;
        }

        $cantidad_campanas = Campana::where('uuid', $this->uuid)
            ->whereBetween('fecha_envio', [$fechas[0].' 00:00:00', $fechas[1].' 23:59:59'])
            ->where(function ($query) use ($request) {
                if ($request->input('etiquetas')) {
                    $query->whereIn('cod_etiqueta', $request->input('etiquetas'));
                }
            })
            ->whereHas('enviosActivos', function($query) use($request) {
                if ($request->input('contactos')) {
                    $query->whereIn('cod_contacto', $request->input('contactos'));
                }
            })
            ->whereNot('estado', Campana::ELIMINADO)
            ->count();
        $cantidad_contactos = Contacto::where('uuid', $this->uuid)
            ->whereNot('estado', Contacto::ELIMINADO)
            ->whereHas('etiquetasActivas', function($query) use($request) {
                if ($request->input('etiquetas')) {
                    $query->whereIn('cod_etiqueta', $request->input('etiquetas'));
                }
            })
            ->where(function($query) use($request){
                if ($request->input('contactos')) {
                    $query->whereIn('uuid', $request->input('contactos'));
                }
            })
            ->count();
        $cantidad_envios = EnvioCampana::whereHas('campana', function($query) use($fechas, $request) {
                $query->where('uuid', $this->uuid)
                    ->where('estado', Campana::ENVIADO)
                    ->whereBetween('fecha_envio', [$fechas[0].' 00:00:00', $fechas[1].' 23:59:59']);
                if ($request->input('etiquetas')) {
                    $query->whereIn('cod_etiqueta', $request->input('etiquetas'));
                }
            })
            ->where(function($query) use($request) {
                if ($request->input('contactos')) {
                    $query->whereIn('cod_contacto', $request->input('contactos'));
                }
            })
            ->where('estado', EnvioCampana::ACTIVO)
            ->count();
        $cantidad_aperturas = EnvioCampana::whereHas('campana', function($query) use($fechas, $request) {
                $query->where('uuid', $this->uuid)
                    ->where('estado', Campana::ENVIADO)
                    ->whereBetween('fecha_envio', [$fechas[0].' 00:00:00', $fechas[1].' 23:59:59']);
                if ($request->input('etiquetas')) {
                    $query->whereIn('cod_etiqueta', $request->input('etiquetas'));
                }
            })
            ->where(function($query) use($request) {
                if ($request->input('contactos')) {
                    $query->whereIn('cod_contacto', $request->input('contactos'));
                }
            })
            ->where('estado', EnvioCampana::ACTIVO)
            ->where('apertura', EnvioCampana::ABIERTO)
            ->count();

        $cantidad_efectividad = $cantidad_aperturas && $cantidad_envios ? ($cantidad_aperturas / $cantidad_envios) * 100 : 0;

        $resultados = EnvioCampana::whereHas('campana', function($query) use($fechas, $request) {
            $query->where('c.uuid', $this->uuid)
                ->where('c.estado', Campana::ENVIADO)
                ->whereBetween('c.fecha_envio', [$fechas[0].' 00:00:00', $fechas[1].' 23:59:59']);
            if ($request->input('etiquetas')) {
                $query->whereIn('cod_etiqueta', $request->input('etiquetas'));
            }
        })
        ->where(function($query) use($request) {
            if ($request->input('contactos')) {
                $query->whereIn('cod_contacto', $request->input('contactos'));
            }
        })
        ->where('envios_campanas.estado', EnvioCampana::ACTIVO)
        ->selectRaw('DATE(c.fecha_envio) as fecha, COUNT(*) as total_envios, SUM(CASE WHEN apertura = '.EnvioCampana::ABIERTO.' THEN 1 ELSE 0 END) as total_aperturas')
        ->join('campanas as c', 'c.id', '=', 'envios_campanas.cod_campana')
        ->groupByRaw('DATE(c.fecha_envio)')
        ->orderBy('fecha')
        ->get();
        $diaMaxEfectividad = null;
        $maxEfectividad = 0;

        foreach ($resultados as $resultado) {
            $efectividad = ($resultado->total_aperturas / $resultado->total_envios) * 100;

            if ($efectividad > $maxEfectividad) {
                $maxEfectividad = $efectividad;
                $diaMaxEfectividad = $resultado->fecha;
            }
        }

        $response['cantidad_campanas'] = $cantidad_campanas;
        $response['cantidad_contactos'] = $cantidad_contactos;
        $response['cantidad_efectividad'] = round($cantidad_efectividad, 2);
        $response['diaMaxEfectividad'] = $diaMaxEfectividad;
        $response['seriesEtiquetas'] = $seriesEtiquetas;
        $response['alcance'] = EnvioCampana::with('campana')
            ->whereHas('campana', function($query) use($fechas, $request) {
                $query->whereBetween('fecha_envio', [$fechas[0].' 00:00:00', $fechas[1].' 23:59:59']);
                $query->where('uuid', $this->uuid);
                if ($request->input('etiquetas')) {
                    $query->whereIn('cod_etiqueta', $request->input('etiquetas'));
                }
            })
            ->where(function($query) use($request) {
                if ($request->input('contactos')) {
                    $query->whereIn('cod_contacto', $request->input('contactos'));
                }
            })
            ->count() ?? 0;
        $response['aperturas'] = EnvioCampana::with('campana')
            ->whereHas('campana', function($query) use($fechas, $request) {
                $query->whereBetween('fecha_envio', [$fechas[0].' 00:00:00', $fechas[1].' 23:59:59']);
                $query->where('uuid', $this->uuid);
                if ($request->input('etiquetas')) {
                    $query->whereIn('cod_etiqueta', $request->input('etiquetas'));
                }
            })
            ->where(function($query) use($request) {
                if ($request->input('contactos')) {
                    $query->whereIn('cod_contacto', $request->input('contactos'));
                }
            })
            ->where('apertura', EnvioCampana::ABIERTO)
            ->count() ?? 0;
        $response['fallos'] = Mensaje::where('mensajes.type', Mensaje::PLANTILLA)
            ->join('envios_campanas as ec', 'ec.wamid', '=', 'mensajes.wa_message_id')
            ->join('campanas as c', 'ec.cod_campana', '=', 'c.id')
            ->where(function($query) use($request) {
                if ($request->input('contactos')) {
                    $query->whereIn('ec.cod_contacto', $request->input('contactos'));
                }
                if ($request->input('etiquetas')) {
                    $query->whereIn('c.cod_etiqueta', $request->input('etiquetas'));
                }
            })
            ->where('mensajes.estado', Mensaje::FALLIDO)
            ->whereBetween('mensajes.created_at', [$fechas[0].' 00:00:00', $fechas[1].' 23:59:59'])
            ->where('mensajes.wa_to', $this->phone_number_id)
            ->count() ?? 0;

        $campanasPorMes = Campana::selectRaw('
            MONTH(fecha_envio) as mes,
            COUNT(*) as total
        ')
        ->where('uuid', $this->uuid)
        ->whereBetween('fecha_envio', [$fechas[0].' 00:00:00', $fechas[1].' 23:59:59'])
        ->where(function ($query) use ($request) {
            if ($request->input('etiquetas')) {
                $query->whereIn('cod_etiqueta', $request->input('etiquetas'));
            }
        })
        ->whereHas('enviosActivos', function($query) use($request) {
            if ($request->input('contactos')) {
                $query->whereIn('cod_contacto', $request->input('contactos'));
            }
        })
        ->whereNot('estado', Campana::ELIMINADO)
        ->groupByRaw('MONTH(fecha_envio)')
        ->orderByRaw('MONTH(fecha_envio)')
        ->get();

    // Preparar array con 12 meses (inicializados en 0)
    $datosMensuales = array_fill(0, 12, 0);

    // Llenar el array con los datos de la consulta
    foreach ($campanasPorMes as $dato) {
        $indice = $dato->mes - 1; // Mes 1 = índice 0 (Enero)
        $datosMensuales[$indice] = $dato->total;
    }

    // Agregar al response
    $response['campanas_por_mes'] = $datosMensuales;

        return response()->json($response);
    }
}
