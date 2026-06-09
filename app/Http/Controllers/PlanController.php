<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use App\Models\PlanServicio;
use App\Models\Servicio;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PlanController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!can(Usuario::PERMISO_PLANES_LISTADO) && !can(Usuario::PERMISO_PLANES_CREAR) &&
        !can(Usuario::PERMISO_PLANES_EDITAR) && !can(Usuario::PERMISO_PLANES_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $info['servicios'] = Servicio::where('estado', Servicio::ACTIVO)
            ->get();
        $info['categorias'] = Plan::darCategoria();
        $info['tipos'] = Plan::darTipo();

        return view('planes.index', $info);
    }

    public function listado(Request $request)
    {
        if (!can(Usuario::PERMISO_PLANES_LISTADO) && !can(Usuario::PERMISO_PLANES_CREAR) &&
        !can(Usuario::PERMISO_PLANES_EDITAR) && !can(Usuario::PERMISO_PLANES_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $planes = Plan::with(
            'servicios',
            'infoEstado',
            'infoTipo',
            'infoCategoria',
        )->where('estado', '!=', Plan::ELIMINADO);

        return DataTables::eloquent($planes)
            ->addColumn("estado", function ($model) {
                $info['concepto'] = $model?->infoEstado;
                return view("sistema.estado", $info);
            })
            ->addColumn("action", "planes.columnas.acciones")
            ->addColumn("servicios", "planes.columnas.servicios")
            ->rawColumns(["action", "servicios"])
            ->make(true);
    }

    public function store(StorePlanRequest $request)
    {
        if (!can(Usuario::PERMISO_PLANES_LISTADO) && !can(Usuario::PERMISO_PLANES_CREAR) &&
        !can(Usuario::PERMISO_PLANES_EDITAR) && !can(Usuario::PERMISO_PLANES_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $datos = $request->all();
        $plan = Plan::create($datos);

        if (!$plan) {
            throw new ErrorException('Error al intentar crear el nuevo plan.');
        }

        $plan->refresh();
        if ($request->has('servicios')) {
            foreach ($request->input('servicios') as $servicioId) {
                PlanServicio::create([
                    'plan_id' => $plan->id,
                    'servicio_id' => $servicioId,
                    'habilitado' => PlanServicio::APLICA,
                ]);
            }
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se creo correctamente el plan.',
        ];
    }

    public function show($plan)
    {
        $plan = Plan::where('estado', Plan::ACTIVO)
            ->where('id', $plan)
            ->first() ?? null;

        if (!$plan) {
            throw new ErrorException('Error al intentar encontrar el plan.');
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se cargo la información correctamente.',
            'plan' => $plan
        ];
    }

    public function edit(Request $request, Plan $plan)
    {
        if (!can(Usuario::PERMISO_PLANES_LISTADO) && !can(Usuario::PERMISO_PLANES_CREAR) &&
        !can(Usuario::PERMISO_PLANES_EDITAR) && !can(Usuario::PERMISO_PLANES_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $plan->load('serviciosHabilitados');
        $info["plan"] = $plan;
        $info["servicios_seleccionados"] = $plan->serviciosHabilitados->pluck('id')->toArray();
        $info['servicios'] = Servicio::where('estado', Servicio::ACTIVO)
            ->get();
        $info['categorias'] = Plan::darCategoria();
        $info['tipos'] = Plan::darTipo();

        $respuesta["estado"] = "success";
        $respuesta["mensaje"] = "Datos cargados correctamente";
        $respuesta['html'] = view("planes.modals.form-editar", $info)->render();

        return response()->json($respuesta);
    }

    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        if (!can(Usuario::PERMISO_PLANES_LISTADO) && !can(Usuario::PERMISO_PLANES_CREAR) &&
        !can(Usuario::PERMISO_PLANES_EDITAR) && !can(Usuario::PERMISO_PLANES_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $datos = $request->all();
        $actualizar = $plan->update($datos);

        if (!$actualizar) {
            throw new ErrorException('Error al intentar actualizar el plan.');
        }

        if ($request->has('servicios')) {
            $serviciosSeleccionados = $request->input('servicios');

            // 1. Actualizar o crear los servicios seleccionados
            foreach ($serviciosSeleccionados as $servicioId) {
                PlanServicio::updateOrCreate([
                    'plan_id' => $plan->id,
                    'servicio_id' => $servicioId,
                ], [
                    'habilitado' => PlanServicio::APLICA,
                    'estado' => PlanServicio::ACTIVO,
                ]);
            }

            // 2. Desactivar los servicios NO seleccionados
            PlanServicio::where('plan_id', $plan->id)
                ->whereNotIn('servicio_id', $serviciosSeleccionados)
                ->update([
                    'estado' => PlanServicio::ELIMINADO,
                    'habilitado' => PlanServicio::NO_APLICA,
                ]);
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se actualizo correctamente el plan.',
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Plan $plan)
    {
        if (!can(Usuario::PERMISO_PLANES_LISTADO) && !can(Usuario::PERMISO_PLANES_CREAR) &&
        !can(Usuario::PERMISO_PLANES_EDITAR) && !can(Usuario::PERMISO_PLANES_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $eliminar = $plan->eliminar();

        if (!$eliminar) {
            throw new ErrorException('A ocurrido un error al intentar eliminar el plan.');
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se eliminado correctamente el plan.',
        ];
    }
}
