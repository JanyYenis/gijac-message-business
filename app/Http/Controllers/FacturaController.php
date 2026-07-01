<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Plan;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Epayco\Epayco;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FacturaController extends Controller
{
    public function index($plan = null)
    {
        $info['plan'] = $plan;
        if ($plan) {
            $info['plan'] = Plan::find($plan);
        }

        return view('facturas.index', $info);
    }

    public function pagar($plan = null)
    {
        if ($plan) {
            $plan = Plan::with('serviciosHabilitados')->find($plan);
        } else {
            $plan = Plan::with('serviciosHabilitados')
                ->where('estado', Plan::ACTIVO)
                ->orderBy('categoria')
                ->first();
        }
        $info['plan'] = $plan;
        $planes = Plan::with('serviciosHabilitados')
            ->where('estado', Plan::ACTIVO)
            ->whereNot('categoria', Plan::PERSONALIZADO)
            ->orderBy('categoria')
            ->get();

        $info['planes'] = $planes;

        $info['esPersonalizado'] = in_array($plan?->id, array_column($planes->toArray(), 'id'));

        return view('facturas.index', $info);
    }

    public function listado(Request $request)
    {
        $facturas = Factura::with(
            'plan'
        )->where('cod_empresa', auth()->user()?->empresa?->id);

        return DataTables::eloquent($facturas)
            ->addColumn("action", function($model) {
                $info['model'] = $model;
                $info['puederVer'] = can(Usuario::PERMISO_FACTURA_LISTADO);
                return view("facturas.columnas.acciones", $info);
            })
            ->rawColumns(["action"])
            ->make(true);
    }

    public function ver(Request $request, $factura)
    {
        $factura = Factura::with(
                'plan.serviciosHabilitados',
                'usuario.infoDocumento'
            )
            ->where('invoice', $factura)
            ->first() ?? null;

        $data = [
            'factura' => $factura,
        ];

        $pdf = Pdf::loadView('facturas/pdf/index', $data);
        $fecha = date('d/m/Y');
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf->stream("Factura {$factura->invoice}.pdf");
    }
}
