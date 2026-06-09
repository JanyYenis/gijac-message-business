<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WompiController extends Controller
{
    public function crearTransaccion(Request $request)
    {
        $response = Http::post(env('WOMPI_URL') . '/transactions', [
            'amount_in_cents' => $request->amount * 100, // Convertir a centavos
            'currency' => 'COP',
            'customer_email' => $request->email,
            'payment_method' => [
                'type' => 'PSE',
                'user_type' => 0, // 0 = Persona Natural, 1 = Empresa
                'user_legal_id' => $request->documento,
                'user_legal_id_type' => 'CC', // CC, CE, NIT, etc.
                'financial_institution_code' => $request->bank_code, // Código del banco
            ],
            'redirect_url' => route('wompi.callback'),
        ]);

        return response()->json($response->json());
    }

    public function callback(Request $request)
    {
        // Aquí se recibe la notificación de pago
        return response()->json(['message' => 'Pago recibido', 'data' => $request->all()]);
    }
}
