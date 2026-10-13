<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Factura;
use App\Models\InvoiceTemporal;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Epayco\Epayco;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EpaycoController extends Controller
{
    protected $epayco;

    public function __construct()
    {
        $this->epayco = new Epayco([
            "apiKey" => config('services.epayco.public_key'),
            "privateKey" => config('services.epayco.private_key'),
            "lenguage" => "ES",  // Corregido: debe ser "lenguage"
            "test" => config('services.epayco.test')
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
        return response()->json(['message' => __('Pago procesado'), 'data' => $request->all()]);
    }

    public function confirmation(Request $request)
    {
        $datos = $request->all();
        // 1. Validar IPs de ePayco (Opcional pero recomendado)
        // $this->validateEpaycoIp($request->ip());

        // 2. Obtener parámetros clave
        $receivedSignature = (string) $request->input('x_signature');

        // 3. Construir cadena para firma (según documentación ePayco)
        $p_cust_id_cliente = $request->input('x_cust_id_cliente');
        $x_ref_payco = $request->input('x_ref_payco');
        $x_amount = $request->input('x_amount');
        $x_currency_code = $request->input('x_currency_code');
        $x_transaction_id = $request->input('x_transaction_id');
        $p_key = config('services.epayco.p_key');

        $expectedSignature = hash('sha256',$p_cust_id_cliente.'^'.$p_key.'^'.$x_ref_payco.'^'.$x_transaction_id.'^'.$x_amount.'^'.$x_currency_code);

        // 4. Validación estricta de la firma
        if ($receivedSignature === '' || !hash_equals($expectedSignature, $receivedSignature)) {
            Log::error('Firma inválida de ePayco', ['ref' => $x_ref_payco, 'ip' => $request->ip()]);
            abort(403, 'Firma no válida');
        }

        // Log solo después de validar la firma y sin datos personales
        Log::info('Confirmación ePayco', [
            'ref' => $x_ref_payco,
            'invoice' => $request->input('x_id_invoice'),
            'cod_response' => $request->input('x_cod_response'),
            'amount' => $x_amount,
            'currency' => $x_currency_code,
            'test' => $request->input('x_test_request'),
        ]);

        // 5. En producción, ignorar transacciones de prueba
        $esPrueba = strtoupper((string) $request->input('x_test_request')) === 'TRUE';
        if ($esPrueba && !config('services.epayco.test')) {
            Log::warning('Transacción de prueba ignorada en producción', ['ref' => $x_ref_payco]);
            return response()->json(['message' => 'Prueba ignorada']);
        }

        // 6. Solo x_cod_response = 1 es un pago aprobado (2 rechazada, 3 pendiente, 4 fallida)
        $aprobada = (int) $request->input('x_cod_response') === 1;

        // 7. Todo se decide con la orden creada en el servidor (invoice_temporal), no con los x_extra* del navegador
        $resultado = DB::transaction(function () use ($datos, $aprobada, $x_ref_payco, $x_amount, $x_currency_code) {
            $orden = InvoiceTemporal::where('invoice', $datos['x_id_invoice'] ?? null)
                ->lockForUpdate()
                ->first();

            if (!$orden || !$orden->cod_plan || !$orden->cod_usuario) {
                return 'orden_desconocida';
            }

            // Idempotencia: ignorar reintentos y estados tardíos (ej. "Pendiente" después de "Aceptada")
            if (Factura::where('x_ref_payco', $x_ref_payco)->where('x_response', 'Aceptada')->exists()) {
                return 'ya_procesada';
            }

            // El monto y la moneda cobrados deben ser los de la orden
            if (abs((float) $x_amount - (float) $orden->valor) >= 1
                || strtoupper((string) $x_currency_code) !== strtoupper((string) $orden->currency)) {
                return 'monto_invalido';
            }

            // Otro pago aprobado para la misma orden: no extender el plan dos veces
            if ($aprobada && Factura::where('invoice', $orden->invoice)->where('x_response', 'Aceptada')->exists()) {
                return 'pago_duplicado';
            }

            // Renovación del mismo plan: suma los meses a lo que le quedaba; si cambia de plan, cuenta desde hoy
            $fecha_vencimiento = null;
            if ($aprobada) {
                $base = Carbon::now();
                $vigente = Empresa::find($orden->cod_empresa)?->facturaVigente;
                if ($vigente && (string) $vigente->cod_plan === (string) $orden->cod_plan && $vigente->fecha_vencimiento > $base) {
                    $base = $vigente->fecha_vencimiento->copy();
                }
                $fecha_vencimiento = $base->addMonths((int) $orden->tiempo);
            }

            Factura::updateOrCreate([
                'x_cust_id_cliente' => isset($datos['x_cust_id_cliente']) ? $datos['x_cust_id_cliente'] : null,
                'x_ref_payco' => isset($datos['x_ref_payco']) ? $datos['x_ref_payco'] : null,
                'invoice' => $orden->invoice,
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
                'cod_plan' => $orden->cod_plan,
                'cod_usuario' => $orden->cod_usuario,
                'cod_empresa' => $orden->cod_empresa,
                'tiempo' => $orden->tiempo,
                'fecha_vencimiento' => $fecha_vencimiento,
            ]);

            if ($aprobada) {
                $orden->update(['estado' => InvoiceTemporal::ELIMINADO]);
                Usuario::where('uuid', $orden->cod_usuario)->update([
                    'cod_plan' => $orden->cod_plan,
                    'demo' => 0
                ]);
            }

            return $aprobada ? 'aprobada' : 'registrada';
        });

        // Casos que requieren revisión manual (posible manipulación o pago duplicado): el dinero pudo haberse cobrado
        if (in_array($resultado, ['orden_desconocida', 'monto_invalido', 'pago_duplicado'])) {
            Log::critical('Confirmación ePayco NO aplicada: ' . $resultado, [
                'ref' => $x_ref_payco,
                'invoice' => $datos['x_id_invoice'] ?? null,
                'amount' => $x_amount,
                'currency' => $x_currency_code,
                'cod_response' => $request->input('x_cod_response'),
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
