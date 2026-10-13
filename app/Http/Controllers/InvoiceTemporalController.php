<?php

namespace App\Http\Controllers;

use App\Models\InvoiceTemporal;
use App\Models\Periodo;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceTemporalController extends Controller
{
    public function validarInvoiceActivo(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:planes,id',
            'currency' => 'required|string',
            'period' => 'required|string',
        ]);

        // Por ahora solo se cobra en COP (PSE). La moneda y la tasa del navegador no se usan para calcular el monto.
        $currency = strtoupper($request->currency);
        if ($currency !== 'COP') {
            return response()->json([
                'estado' => 'error',
                'mensaje' => __('Por ahora solo se aceptan pagos en COP.'),
            ], 422);
        }

        $plan = Plan::where('id', $request->plan_id)
            ->where('estado', Plan::ACTIVO)
            ->firstOrFail();
        $period = Periodo::where('codigo', $request->period)
            ->where('activo', true)
            ->firstOrFail();

        // El monto se calcula solo en el servidor (COP, sin decimales)
        $amount = round($plan->valor * $period->multiplicador * (1 - $period->descuento));

        if ($amount <= 0) {
            return response()->json([
                'estado' => 'error',
                'mensaje' => __('Este plan no se puede pagar en línea.'),
            ], 422);
        }

        $usuario = auth()->user();
        $empresaId = $usuario->empresa?->id ?? $usuario->cod_empresa;

        if (!$empresaId) {
            return response()->json([
                'estado' => 'error',
                'mensaje' => __('No se encontró la empresa asociada.'),
            ], 422);
        }

        // La orden queda guardada en el servidor; el pago solo se acepta si coincide con ella
        $orden = DB::transaction(function () use ($plan, $period, $usuario, $empresaId, $amount) {
            $orden = InvoiceTemporal::create([
                'invoice' => (string) Str::uuid(), // provisional, se reemplaza con el consecutivo
                'estado' => InvoiceTemporal::ACTIVO,
                'cod_plan' => $plan->id,
                'cod_usuario' => $usuario->uuid,
                'cod_empresa' => $empresaId,
                'tiempo' => $period->multiplicador,
                'valor' => $amount,
                'currency' => 'COP',
            ]);

            $orden->update([
                'invoice' => config('services.epayco.invoice_prefix') . str_pad($orden->id, 6, '0', STR_PAD_LEFT),
            ]);

            return $orden;
        });

        $formattedAmount = number_format($amount, 0, ',', '.');

        return response()->json([
            'estado' => 'success',
            'mensaje' => __('Validación correcta.'),
            'data' => [
                'invoice' => $orden->invoice,
                'name' => $plan->nombre,
                'description' => $plan->descripcion ?? __('Suscripción ') . $plan->nombre,
                'amount' => $amount,
                'formatted_amount' => $formattedAmount,
                'currency' => 'COP',
                'currency_rate' => 1,
                'cod_plan' => $plan->id,
                'cod_usuario' => $usuario->uuid,
                'cod_empresa' => $empresaId,
                'tiempo' => $period->multiplicador,
                'public_key' => config('services.epayco.public_key'),
                'epayco_test' => config('services.epayco.test'),
            ]
        ]);
    }
}
