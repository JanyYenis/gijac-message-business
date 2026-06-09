@extends('pdfs.index', ['titulo' => 'Factura ' . $factura->invoice])

@section('content')
    <div class="text-start">
        <b class="titulo">Factura #{{ $factura->invoice }}</b>
    </div>

    <div class="pdf-row mt-3">
        <div class="pdf-col" style="width: 50%;">
            <b class="titulo">Fecha:</b><br>
            <label class="fs-5">{{$factura?->created_at->format('d/m/Y') ?? 'N/A'}}</label>
        </div>
        <div class="pdf-col" style="width: 50%;">
            <b class="titulo">Fecha vencimiento:</b><br>
            <label class="fs-5">{{$factura?->fecha_vencimiento ? $factura?->fecha_vencimiento->format('d/m/Y g:i a') : 'N/A'}}</label>
        </div>
    </div>

    <div class="pdf-row mt-3">
        <div class="pdf-col" style="width: 50%;">
            <b class="titulo">Emitido para:</b><br>
            <label class="fs-5">{{$factura?->usuario?->nombre_completo ?? 'N/A'}}</label><br>
            <label class="fs-5">{{$factura?->usuario?->infoDocumento?->nombre_corto ?? 'N/A'}}: {{$factura?->usuario?->identificacion ?? 'N/A'}}</label>
        </div>
        <div class="pdf-col" style="width: 50%;">
            <b class="titulo">Emitido por:</b><br>
            <label class="fs-5">GIJAC WEB</label><br>
            <label class="fs-5">CC: 1006184587</label>
        </div>
    </div>

    <div class="mt-3">
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th class="fs-5" width="5%">#</th>
                    <th class="fs-5" width="10%">Plan</th>
                    <th class="fs-5" width="10%">Detalles</th>
                    <th class="fs-5" width="5%">Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="5%" class="fs-5">1</td>
                    <td width="10%" class="fs-5">{{$factura?->plan?->nombre ?? 'N/A'}}</td>
                    <td width="50%">
                        <ul>
                            <li class="fs-5">{{$factura?->plan?->max_contactos ? formatoMiles($factura?->plan?->max_contactos) : 'Ilimitado' }} Contactos Activos</li>
                            @foreach ($factura?->plan?->serviciosHabilitados as $item)
                                <li class="fs-5">{{$item?->nombre ?? 'N/A'}}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td width="5%" class="fs-5">${{formatoMiles($factura?->tax_base) ?? 0}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="text-end mt-3 fs-5">
        <div class="pdf-row">
            <div class="pdf-col" style="width: 50%;">
                <b class="titulo">Subtotal:</b>
            </div>
            <div class="pdf-col" style="width: 50%;">
                ${{ formatoMiles($factura->tax_base) }}
            </div>
        </div>
        <div class="pdf-row">
            <div class="pdf-col" style="width: 50%;">
                <b class="titulo">IVA:</b>
            </div>
            <div class="pdf-col" style="width: 50%;">
                ${{ formatoMiles($factura->tax) }}
            </div>
        </div>
        <div class="pdf-row">
            <div class="pdf-col" style="width: 50%;">
                <b class="titulo">Subtotal + IVA:</b>
            </div>
            <div class="pdf-col" style="width: 50%;">
                ${{ formatoMiles((int) $factura->tax + (int) $factura->tax_base) }}
            </div>
        </div>
        <div class="pdf-row">
            <div class="pdf-col" style="width: 50%;">
                <b class="titulo">Total:</b>
            </div>
            <div class="pdf-col" style="width: 50%;">
                ${{ formatoMiles($factura->value) }}
            </div>
        </div>
    </div>
@endsection
