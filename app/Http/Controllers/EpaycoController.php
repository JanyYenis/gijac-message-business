<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\InvoiceTemporal;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Epayco\Epayco;
use Illuminate\Support\Facades\Log;

class EpaycoController extends Controller
{
    protected $epayco;

    public function __construct()
    {
        $this->epayco = new Epayco([
            "apiKey" => env('EPAYCO_PUBLIC_KEY'),
            "privateKey" => env('EPAYCO_PRIVATE_KEY'),
            "lenguage" => "ES",  // Corregido: debe ser "lenguage"
            "test" => true
        ]);
    }

    public function obtenerBancos()
    {
        $bancos = $this->epayco->bank->pseBank(false);
        // $bancos = $this->epayco->bank->pseBank();
        return response()->json($bancos);
    }

    public function transaccionPse(Request $request)
    {
        $pseData = [
            "bank" => $request->input('bank_code'), // Código del banco
            "invoice" => "Factura_" . time(),
            "description" => "Pago de prueba",
            "value" => $request->input('amount'),
            "tax" => "0",
            "tax_base" => "0",
            "currency" => "COP",
            "type_person" => $request->input('type_person'), // 0 = Natural, 1 = Jurídica
            "doc_type" => $request->input('doc_type'), // CC, CE, NIT
            "doc_number" => $request->input('doc_number'),
            "name" => $request->input('name'),
            "last_name" => $request->input('last_name'),
            "email" => $request->input('email'),
            "country" => "CO",
            "cell_phone" => $request->input('cell_phone'),
            "ip" => $_SERVER["REMOTE_ADDR"], // Obtiene la IP del usuario
            "url_response" => route('epayco.callback'),
            "url_confirmation" => route('epayco.confirmation'),
            "method_confirmation" => "POST",
            // "extra1" => $request->input('cod_plan'),
            // "extra2" => auth()->user()->id,
            // "extra3" => $request->input('tiempo'),
        ];

        $transaction = $this->epayco->bank->create($pseData);
        return response()->json($transaction);
    }

    public function callback(Request $request)
    {
        return response()->json(['message' => 'Pago procesado', 'data' => $request->all()]);
    }

    public function confirmation(Request $request)
    {
        $datos = $request->all();
        Log::info('Datos Transferencia: '. json_encode($datos));
        // 1. Validar IPs de ePayco (Opcional pero recomendado)
        // $this->validateEpaycoIp($request->ip());

        // 2. Obtener parámetros clave
        $receivedSignature = $request->input('x_signature');
        $transactionId = $request->input('x_ref_payco');
        $testMode = $request->input('x_test_request') === 'TRUE';

        // 3. Construir cadena para firma (según documentación ePayco)
        $p_cust_id_cliente = $request->input('x_cust_id_cliente');
        $x_ref_payco = $request->input('x_ref_payco');
        $x_amount = $request->input('x_amount');
        $x_currency_code = $request->input('x_currency_code');
        $x_transaction_id = $request->input('x_transaction_id');
        $p_key = env('EPAYCO_P_KEY');

        $expectedSignature = hash('sha256',$p_cust_id_cliente.'^'.$p_key.'^'.$x_ref_payco.'^'.$x_transaction_id.'^'.$x_amount.'^'.$x_currency_code);

        // 4. Validación estricta de la firma
        if (!hash_equals($expectedSignature, $receivedSignature)) {
            Log::error('Firma inválida de ePayco');
            abort(403, 'Firma no válida');
        }

        $fecha_vencimiento = isset($datos['x_extra3']) ? Carbon::now()->addMonths((int) $datos['x_extra3']) : null;
        $factura = Factura::updateOrCreate([
            'x_cust_id_cliente' => isset($datos['x_cust_id_cliente']) ? $datos['x_cust_id_cliente'] : null,
            'x_ref_payco' => isset($datos['x_ref_payco']) ? $datos['x_ref_payco'] : null,
            'invoice' => isset($datos['x_id_invoice']) ? $datos['x_id_invoice'] : null,
            'x_signature' => isset($datos['x_signature']) ? $datos['x_signature'] : null,
        ],[
            'bank' => isset($datos['x_bank_name']) ? $datos['x_bank_name'] : null,
            'description' => isset($datos['x_description']) ? $datos['x_description'] : null,
            'value' => isset($datos['x_amount']) ? $datos['x_amount'] : null,
            'tax' => isset($datos['x_tax']) ? $datos['x_tax'] : null,
            'tax_base' => isset($datos['x_amount_base']) ? $datos['x_amount_base'] : null,
            'x_franchise' => isset($datos['x_franchise']) ? $datos['x_franchise'] : null,
            'x_response' => isset($datos['x_response']) ? $datos['x_response'] : null,
            'currency' => isset($datos['x_currency_code']) ? $datos['x_currency_code'] : null,
            'type_person' => null,
            'doc_type' => isset($datos['x_customer_doctype']) ? $datos['x_customer_doctype'] : null,
            'doc_number' => isset($datos['x_customer_document']) ? $datos['x_customer_document'] : null,
            'name' => isset($datos['x_customer_name']) ? $datos['x_customer_name'] : null,
            'last_name' => isset($datos['x_customer_lastname']) ? $datos['x_customer_lastname'] : null,
            'email' => isset($datos['x_customer_email']) ? $datos['x_customer_email'] : null,
            'country' => isset($datos['x_customer_country']) ? $datos['x_customer_country'] : null,
            'cell_phone' => isset($datos['x_customer_movil']) ? $datos['x_customer_movil'] : null,
            'x_customer_ip' => isset($datos['x_customer_ip']) ? $datos['x_customer_ip'] : null,
            'x_customer_address' => isset($datos['x_customer_address']) ? $datos['x_customer_address'] : null,
            'cod_plan' => isset($datos['x_extra1']) ? $datos['x_extra1'] : null,
            'cod_usuario' => isset($datos['x_extra2']) ? $datos['x_extra2'] : null,
            'cod_empresa' => isset($datos['x_extra4']) ? $datos['x_extra4'] : null,
            'tiempo' => isset($datos['x_extra3']) ? $datos['x_extra3'] : null,
            'fecha_vencimiento' => $fecha_vencimiento,
        ]);

        InvoiceTemporal::where('invoice', $factura->invoice)
            ->update(['estado' => InvoiceTemporal::ELIMINADO]);

        if (isset($datos['x_extra2']) && isset($datos['x_extra1'])) {
            Usuario::find((int) $datos['x_extra2'])->update([
                'cod_plan' => (int) $datos['x_extra1'],
                'demo' => 0
            ]);
        }

        return response()->json(['message' => 'OK']);
    }

    protected function validateEpaycoIp($ip)
    {
        $allowedIps = ['186.116.10.48', '186.116.10.49']; // IPs oficiales ePayco
        if (!in_array($ip, $allowedIps)) {
            Log::warning('Intento de acceso desde IP no autorizada: '.$ip);
            abort(403, 'IP no autorizada');
        }
    }
}
