<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\Campana;
use App\Models\EnvioCampana;
use App\Models\Etiqueta;
use App\Models\Mensaje;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    public function index(Request $request)
    {
        $info['campanas_programadas'] = Campana::where('estado', Campana::PENDIENTE)
            ->where('cod_empresa', $this->uuid)
            ->count();

        $info['campanas_enviadas'] = Campana::where('estado', Campana::ENVIADO)
            ->where('cod_empresa', $this->uuid)
            ->count();

        $info['mensajes_enviados'] = Mensaje::where('wa_from', $this->phone_number_id)
            ->count();

        $cantidad_envios = EnvioCampana::whereHas('campana', function($query) {
                $query->where('cod_empresa', $this->uuid)
                    ->where('estado', Campana::ENVIADO);
            })
            ->where('estado', EnvioCampana::ACTIVO)
            ->count();
        $cantidad_aperturas = EnvioCampana::whereHas('campana', function($query) {
                $query->where('cod_empresa', $this->uuid)
                    ->where('estado', Campana::ENVIADO);
            })
            ->where('estado', EnvioCampana::ACTIVO)
            ->where('apertura', EnvioCampana::ABIERTO)
            ->count();

        $cantidad_efectividad = $cantidad_aperturas && $cantidad_envios ? ($cantidad_aperturas / $cantidad_envios) * 100 : 0;
        $info['cantidad_efectividad'] = round($cantidad_efectividad, 2);

        $info['etiquetas'] = Etiqueta::where('cod_empresa', $this->uuid)->get();
        $info['estados'] = Campana::darEstados();
        $info['usuarios'] = Usuario::where('estado', Usuario::ACTIVO)
            ->where('cod_empresa', $this->uuid)
            ->get();

        $info['tipos'] = Campana::darTipo();
        $info['categorias'] = Campana::darCategoria();

        return view('calendario.index', $info);
    }

    public function listado(Request $request)
    {
        $inicio = $request->filled('inicio') ? Carbon::parse($request->input('inicio'))->startOfDay() : null;
        $fin    = $request->filled('fin')    ? Carbon::parse($request->input('fin'))->startOfDay()    : null;

        $campanas = Campana::with(
                'enviosActivos',
                'infoEstado',
                'infoCategoria',
                'usuario',
                'etiqueta',
                'plantilla',
                'mensajesAbiertos',
                'empresa'
            )
            ->where('cod_empresa', $this->uuid)
            ->when($inicio && $fin, fn ($q) => $q
                ->where('fecha_envio', '>=', $inicio)
                ->where('fecha_envio', '<', $fin))
            ->when($request->filled('etiqueta_id'),    fn ($q) => $q->where('cod_etiqueta', $request->input('etiqueta_id')))
            ->when($request->filled('estado'),         fn ($q) => $q->where('estado', $request->input('estado')))
            ->when($request->filled('tipo'),           fn ($q) => $q->where('tipo', $request->input('tipo')))
            ->when($request->filled('categoria'),      fn ($q) => $q->where('categoria', $request->input('categoria')))
            ->when($request->filled('responsable_id'), fn ($q) => $q->where('uuid', $request->input('responsable_id')))
            ->get();

        return [
            'estado' => 'success',
            'campanas' => $campanas
        ];
    }

    public function listadoHoy(Request $request)
    {
        $fechaActual = Carbon::now();
        $campanas = Campana::with(
                'enviosActivos',
                'infoEstado',
                'infoCategoria',
            )
            ->whereDate('fecha_envio', $fechaActual)
            ->where(function($query) use($request) {
                if ($request->input('etiqueta_id')) {
                    $query->where('cod_etiqueta', $request->input('etiqueta_id'));
                }
                if ($request->input('estado')) {
                    $query->where('estado', $request->input('estado'));
                }
                if ($request->input('tipo')) {
                    $query->where('tipo', $request->input('tipo'));
                }
                if ($request->input('categoria')) {
                    $query->where('categoria', $request->input('categoria'));
                }
                if ($request->input('responsable_id')) {
                    $query->where('uuid', $request->input('responsable_id'));
                }
            })
            ->get();

        return [
            'estado' => 'success',
            'campanas' => $campanas
        ];
    }

    public function listadoProxima(Request $request)
    {
        $fechaActual = Carbon::now();

        $campanas = Campana::with(
                'enviosActivos',
                'infoEstado',
                'infoCategoria',
            )
            ->where('fecha_envio', '>', $fechaActual)
            ->where(function($query) use($request) {
                if ($request->input('etiqueta_id')) {
                    $query->where('cod_etiqueta', $request->input('etiqueta_id'));
                }
                if ($request->input('estado')) {
                    $query->where('estado', $request->input('estado'));
                }
                if ($request->input('tipo')) {
                    $query->where('tipo', $request->input('tipo'));
                }
                if ($request->input('categoria')) {
                    $query->where('categoria', $request->input('categoria'));
                }
                if ($request->input('responsable_id')) {
                    $query->where('uuid', $request->input('responsable_id'));
                }
            })
            ->orderBy('fecha_envio', 'asc')
            ->limit(5)
            ->get();

        return [
            'estado' => 'success',
            'campanas' => $campanas
        ];
    }
}
