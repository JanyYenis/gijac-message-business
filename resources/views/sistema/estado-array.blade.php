@php
    $color = $concepto['color'] ?? '';
    $icono = $concepto['icono'] ?? '';
    $nombreConcepto = $concepto['nombre'] ?? '';
@endphp
<div class="text-lg-center">
    <span class="badge badge-light-{{$color}} py-5 px-5">
        <i class="{{$icono}} text-{{$color}}"></i>&nbsp;{{ initcap($nombreConcepto) }}
    </span>
</div>