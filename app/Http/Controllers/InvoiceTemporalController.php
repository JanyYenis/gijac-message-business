<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use App\Models\Plan;
use Illuminate\Http\Request;

class InvoiceTemporalController extends Controller
{
    public function validarInvoiceActivo(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:planes,id',
            'currency' => 'required|string',
            'period' => 'required|string',
            'currency_rate' => 'nullable|numeric', // tasa de conversión que viene del frontend
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $period = Periodo::where('codigo', $request->period)->firstOrFail();

        // Calcular valor base del plan (en COP)
        $amount = $plan->valor * $period->multiplicador;

        // Aplicar descuento del periodo
        if ($period->descuento > 0) {
            $amount = $amount * (1 - $period->descuento);
        }

        // Si no es COP, convertir usando la tasa enviada
        $currency = strtoupper($request->currency);
        $currencyRate = $request->input('currency_rate', 1); // por defecto 1 si es COP

        if ($currency !== 'COP') {
            $amount = $amount * $currencyRate;
        }

        // Formatear monto de forma legible
        $formattedAmount = $currency === 'COP'
            ? number_format($amount, 0, ',', '.')
            : number_format($amount, 2, '.', ',');

        // Validar que el usuario no tenga ya una factura activa, etc.
        $invoice = generarInvoice();

        return response()->json([
            'estado' => 'success',
            'mensaje' => 'Validación correcta.',
            'data' => [
                'invoice' => $invoice,
                'name' => $plan->nombre,
                'description' => $plan->descripcion ?? 'Suscripción ' . $plan->nombre,
                'amount' => $amount,
                'formatted_amount' => $formattedAmount,
                'currency' => $currency,
                'currency_rate' => $currencyRate,
                'cod_plan' => $plan->id,
                'cod_usuario' => auth()->user()->uuid,
                'cod_empresa' => auth()->user()->cod_empresa,
                'tiempo' => $period->multiplicador,
                'public_key' => env('EPAYCO_PUBLIC_KEY'),
                'epayco_test' => env('EPAYCO_TEST'),
            ]
        ]);
    }
}
