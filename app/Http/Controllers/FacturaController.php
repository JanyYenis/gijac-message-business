<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Plan;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Epayco\Epayco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        $esAdmin = role(Usuario::ROL_ADMINISTRADOR);

        $facturas = Factura::with('plan');
        if (!$esAdmin) {
            $facturas->where('cod_empresa', auth()->user()?->empresa?->id);
        }

        return DataTables::eloquent($facturas)
            ->addColumn("action", function($model) use ($esAdmin) {
                $info['model'] = $model;
                $info['puederVer'] = can(Usuario::PERMISO_FACTURA_LISTADO);
                $info['esAdmin'] = $esAdmin;
                $info['puedeSolicitarFe'] = !$esAdmin
                    && can(Usuario::PERMISO_FACTURA_LISTADO)
                    && $model->x_response === 'Aceptada'
                    && ($model->estado_fe ?? Factura::FE_NO_SOLICITADA) === Factura::FE_NO_SOLICITADA;
                return view("facturas.columnas.acciones", $info);
            })
            ->rawColumns(["action"])
            ->make(true);
    }

    public function ver(Request $request, $factura)
    {
        $esAdmin = role(Usuario::ROL_ADMINISTRADOR);
        $empresaId = auth()->user()?->empresa?->id;
        abort_unless($empresaId || $esAdmin, 403);

        $consulta = Factura::with(
                'plan.serviciosHabilitados',
                'usuario.infoDocumento'
            )
            ->where('invoice', $factura);

        if (!$esAdmin) {
            $consulta->where('cod_empresa', $empresaId);
        }

        $factura = $consulta->firstOrFail();

        $data = [
            'factura' => $factura,
        ];

        $pdf = Pdf::loadView('facturas/pdf/index', $data);
        $fecha = date('d/m/Y');
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf->stream("Comprobante {$factura->invoice}.pdf");
    }

    /**
     * El cliente pide que se le emita la factura electrónica de un pago aprobado.
     */
    public function solicitarFe(Request $request, $factura)
    {
        $request->validate(['observaciones' => 'nullable|string|max:500']);

        $empresaId = auth()->user()?->empresa?->id;
        abort_unless($empresaId && can(Usuario::PERMISO_FACTURA_LISTADO), 403);

        $factura = Factura::where('invoice', $factura)
            ->where('cod_empresa', $empresaId)
            ->where('x_response', 'Aceptada')
            ->firstOrFail();

        if (($factura->estado_fe ?? Factura::FE_NO_SOLICITADA) !== Factura::FE_NO_SOLICITADA) {
            return response()->json([
                'estado' => 'error',
                'mensaje' => __('La factura electrónica ya fue solicitada o emitida.'),
            ], 422);
        }

        $factura->update([
            'estado_fe' => Factura::FE_SOLICITADA,
            'fe_observaciones' => $request->input('observaciones'),
            'fe_solicitada_at' => now(),
        ]);

        // Aviso al administrador (si falla el correo, la solicitud igual queda registrada)
        try {
            $destino = config('mail.from.address');
            if ($destino) {
                Mail::raw(
                    "Se solicitó la factura electrónica del comprobante {$factura->invoice}.\n"
                    . "Valor: {$factura->value} {$factura->currency}\n"
                    . 'Datos del cliente: ' . ($factura->fe_observaciones ?: 'no indicó') . "\n",
                    fn ($mensaje) => $mensaje->to($destino)->subject("Factura electrónica solicitada: {$factura->invoice}")
                );
            }
        } catch (\Throwable $e) {
            Log::warning('No se pudo avisar la solicitud de factura electrónica', ['invoice' => $factura->invoice, 'error' => $e->getMessage()]);
        }

        return response()->json([
            'estado' => 'success',
            'mensaje' => __('Solicitud enviada. Te haremos llegar la factura electrónica.'),
        ]);
    }

    /**
     * El administrador registra la factura electrónica ya emitida en la DIAN.
     */
    public function registrarFe(Request $request, $factura)
    {
        abort_unless(role(Usuario::ROL_ADMINISTRADOR), 403);

        $datos = $request->validate([
            'numero_fe' => 'required|string|max:50',
            'cufe' => 'required|string|max:255',
        ]);

        $factura = Factura::where('invoice', $factura)
            ->where('x_response', 'Aceptada')
            ->firstOrFail();

        $factura->update([
            'estado_fe' => Factura::FE_EMITIDA,
            'numero_fe' => trim($datos['numero_fe']),
            'cufe' => trim($datos['cufe']),
            'fe_emitida_at' => now(),
        ]);

        return response()->json([
            'estado' => 'success',
            'mensaje' => __('Factura electrónica registrada.'),
        ]);
    }
}
