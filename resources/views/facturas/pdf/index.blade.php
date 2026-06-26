@php
    // --- Datos de la empresa emisora (puedes moverlos a config/empresa.php si prefieres) ---
    $empresa = [
        'nombre'      => 'Jany Esteban Escobar Cabezas',
        'nit'         => '1006184587-3',
        'regimen'     => 'No Responsable de IVA',
        'direccion'   => 'Corregimiento de Navarro, Cali, Valle del Cauca',
        'email'       => 'info@gijac.co',
        'telefono'    => '+57 317 178 9584',
        'logo'        => base64_encode(file_get_contents(base_path('public/img/logo_gmb.png'))), // ajusta la ruta real del logo
        'resolucion'  => '18764000001',
        'fecha_res'   => '2026-01-15',
        'rango_desde' => 'FE-1000',
        'rango_hasta' => 'FE-5000',
        'vigencia'    => '12 Meses',
    ];

    $signatureCorta = $factura->x_signature
        ? Str::limit($factura->x_signature, 6, '...') . Str::substr($factura->x_signature, -4)
        : 'N/A';

    $moneda = $factura->currency ?? 'COP';
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura {{ $factura->invoice }}</title>
    <style>
        @page {
            margin: 30px 35px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1f1f1f;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
        }

        .logo-cell {
            width: 70px;
        }

        .logo-cell img {
            width: 60px;
            height: 60px;
        }

        .empresa-cell {
            padding-left: 10px;
        }

        .empresa-nombre {
            font-size: 15px;
            font-weight: bold;
            color: #1d6f4c;
            margin: 0 0 4px 0;
        }

        .empresa-info {
            font-size: 10px;
            line-height: 1.5;
        }

        .factura-cell {
            text-align: right;
            width: 220px;
        }

        .factura-titulo {
            font-size: 14px;
            font-weight: bold;
            color: #333333;
            line-height: 1.3;
        }

        .factura-numero {
            font-size: 17px;
            font-weight: bold;
            color: #d2691e;
            margin: 4px 0;
        }

        .factura-meta {
            font-size: 9px;
            color: #555555;
            line-height: 1.5;
        }

        .section-title {
            background-color: #1d6f4c;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            padding: 6px 10px;
            margin-top: 16px;
        }

        .info-table {
            margin-top: 1px;
            border: 1px solid #dddddd;
            border-top: none;
        }

        .info-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #eeeeee;
            font-size: 10.5px;
        }

        .info-label {
            color: #555555;
            font-weight: bold;
            width: 22%;
        }

        .info-value {
            width: 28%;
        }

        .detalle-table {
            margin-top: 1px;
            border: 1px solid #dddddd;
            border-top: none;
        }

        .detalle-table th {
            background-color: #eef5f0;
            color: #1d6f4c;
            font-size: 9.5px;
            text-transform: uppercase;
            padding: 7px 8px;
            text-align: left;
            border-bottom: 1px solid #dddddd;
        }

        .detalle-table td {
            padding: 8px 8px;
            font-size: 10.5px;
            border-bottom: 1px solid #eeeeee;
        }

        .text-right {
            text-align: right;
        }

        .bottom-table {
            margin-top: 14px;
        }

        .bottom-table td {
            vertical-align: top;
        }

        .transaccion-box {
            background-color: aliceblue;
            border: 1px solid #dddddd;
            padding: 10px 12px;
            font-size: 9.5px;
            line-height: 1.6;
            width: 100%;
        }

        .transaccion-box.warning {
            background-color: rgb(255, 230, 131);
        }

        .transaccion-box .titulo {
            font-weight: bold;
            color: #1d6f4c;
            font-size: 10px;
            margin-bottom: 4px;
            display: block;
        }

        .totales-table {
            width: 60%;
            float: right;
            border: 1px solid #dddddd;
        }

        .totales-table td {
            padding: 7px 10px;
            font-size: 10.5px;
            border-bottom: 1px solid #eeeeee;
        }

        .totales-table .total-row td {
            font-weight: bold;
            color: #1d6f4c;
            font-size: 12px;
            border-bottom: none;
        }

        .cufe-box {
            margin-top: 16px;
            background-color: #fdf8ec;
            border: 1px solid #ecdfb8;
            padding: 8px 10px;
            font-size: 9px;
        }

        .cufe-box .label {
            font-weight: bold;
        }

        .cufe-box .codigo {
            word-wrap: break-word;
            font-family: 'Courier New', monospace;
        }

        .footer-note {
            margin-top: 8px;
            font-size: 8.5px;
            color: #777777;
            font-style: italic;
        }

        .page-number {
            margin-top: 20px;
            text-align: right;
            font-size: 8.5px;
            color: #999999;
        }
    </style>
</head>
<body>

    {{-- ENCABEZADO --}}
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="data:image/png;base64,{{ $empresa['logo'] }}">
            </td>
            <td class="empresa-cell">
                <p class="empresa-nombre">{{ $empresa['nombre'] }}</p>
                <div class="empresa-info">
                    <strong>NIT:</strong> {{ $empresa['nit'] }}<br>
                    <strong>Régimen:</strong> {{ $empresa['regimen'] }}<br>
                    <strong>Dirección:</strong> {{ $empresa['direccion'] }}<br>
                    <strong>Email:</strong> {{ $empresa['email'] }}<br>
                    <strong>Teléfono:</strong> {{ $empresa['telefono'] }}
                </div>
            </td>
            <td class="factura-cell">
                <div class="factura-titulo">FACTURA ELECTRÓNICA<br>DE VENTA</div>
                <div class="factura-numero">No. {{ $factura->invoice }}</div>
                <div class="factura-meta">
                    <strong>Resolución DIAN:</strong> No. {{ $empresa['resolucion'] }} de {{ $empresa['fecha_res'] }}<br>
                    <strong>Rango autorizado:</strong> {{ $empresa['rango_desde'] }} a {{ $empresa['rango_hasta'] }}<br>
                    <strong>Vigencia:</strong> {{ $empresa['vigencia'] }}
                </div>
            </td>
        </tr>
    </table>

    {{-- DATOS DEL CLIENTE --}}
    <div class="section-title" style="border-radius: 0.5rem 0.5rem 0 0;">DATOS DEL ADQUIRENTE / CLIENTE</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Cliente:</td>
            <td class="info-value">{{ trim($factura->name . ' ' . $factura->last_name) }}</td>
            <td class="info-label">Identificación:</td>
            <td class="info-value">{{ $factura->doc_type }} - {{ $factura->doc_number }}</td>
        </tr>
        <tr>
            <td class="info-label">Correo electrónico:</td>
            <td class="info-value">{{ $factura->email }}</td>
            <td class="info-label">Teléfono / Celular:</td>
            <td class="info-value">{{ $factura->cell_phone }}</td>
        </tr>
        <tr>
            <td class="info-label">Dirección:</td>
            <td class="info-value">{{ $factura->x_customer_address }}</td>
            <td class="info-label">País:</td>
            <td class="info-value">{{ $factura->country }}</td>
        </tr>
        <tr>
            <td class="info-label">Tipo de Persona:</td>
            <td class="info-value">{{ $factura->type_person }}</td>
            <td class="info-label">IP del Cliente:</td>
            <td class="info-value">{{ $factura->x_customer_ip }}</td>
        </tr>
    </table>

    {{-- INFORMACION DE CUENTA Y SERVICIO --}}
    <div class="section-title" style="border-radius: 0.5rem 0.5rem 0 0;">INFORMACIÓN DE CUENTA Y SERVICIO</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Código de Usuario:</td>
            <td class="info-value">{{ $factura->cod_usuario }}</td>
            <td class="info-label">Código de Plan:</td>
            <td class="info-value">{{ $factura->cod_plan }}</td>
        </tr>
        <tr>
            <td class="info-label">Tiempo Contratado:</td>
            <td class="info-value">{{ $factura->tiempo }} mes(es)</td>
            <td class="info-label">Fecha Vencimiento:</td>
            <td class="info-value">
                {{ optional($factura->fecha_vencimiento)->format('Y-m-d') }}
            </td>
        </tr>
    </table>

    {{-- DETALLE DE FACTURACION --}}
    <div class="section-title" style="border-radius: 0.5rem 0.5rem 0 0;">DETALLE DE FACTURACIÓN</div>
    <table class="detalle-table">
        <thead>
            <tr>
                <th style="width: 18%;">Código Plan</th>
                <th style="width: 42%;">Descripción del Producto / Servicio</th>
                <th class="text-right" style="width: 13%;">Base Gravable</th>
                <th class="text-right" style="width: 13%;">IVA</th>
                <th class="text-right" style="width: 14%;">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $factura->cod_plan }}</td>
                <td>{{ $factura->description }}</td>
                <td class="text-right">${{ number_format($factura->tax_base, 2, ',', '.') }}</td>
                <td class="text-right">${{ number_format($factura->tax, 2, ',', '.') }}</td>
                <td class="text-right">${{ number_format($factura->value, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- TRANSACCION + TOTALES --}}
    <table class="bottom-table">
        <tr>
            <td style="width: 55%; padding-right: 10px;">
                <div class="transaccion-box" style="border-radius: 0.5rem;">
                    <span class="titulo">DETALLE DE TRANSACCIÓN (PASARELA EPAYCO)</span>
                    <strong>ID Cliente Pasarela (x_cust_id_cliente):</strong> {{ $factura->x_cust_id_cliente }}<br>
                    <strong>Referencia ePayco (x_ref_payco):</strong> {{ $factura->x_ref_payco }}<br>
                    <strong>Banco Operador (bank):</strong> {{ $factura->bank }}<br>
                    <strong>Franquicia Autorizadora (x_franchise):</strong> {{ $factura->x_franchise }}<br>
                    <strong>Estado de Transacción (x_response):</strong> {{ $factura->x_response }}<br>
                    <strong>Firma Digital (x_signature):</strong> {{ $signatureCorta }}
                </div>
            </td>
            <td style="width: 45%;">
                <table class="totales-table" style="border-radius: 0.5rem;">
                    <tr>
                        <td>Subtotal:</td>
                        <td class="text-right">${{ number_format($factura->tax_base, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Impuesto (IVA):</td>
                        <td class="text-right">${{ number_format($factura->tax, 2, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total Recaudado:</td>
                        <td class="text-right">${{ number_format($factura->value, 2, ',', '.') }} {{ $moneda }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="transaccion-box warning" style="margin-top: 1rem; border-radius: 0.5rem;">
        {{-- CUFE --}}
        @if(!empty($factura->cufe))
            <div class="cufe-box">
                <span class="label">CUFE (Código Único de Factura Electrónica):</span><br>
                <span class="codigo">{{ $factura->cufe }}</span>
            </div>
        @endif

        <p class="footer-note" style="color: black;">
            Esta factura representa una representación gráfica de un documento electrónico legal emitido bajo la
            normativa de la DIAN. La firma digital de ePayco garantiza la validez e integridad del recaudo y la
            transacción.
        </p>
    </div>
</body>
</html>
{{-- @dd('jany') --}}
