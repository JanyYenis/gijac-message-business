@php
    $color = $concepto?->color ?? '';
    $icono = $concepto?->icono ?? '';
    $nombreConcepto = $concepto?->nombre ?? '';
@endphp
<div class="text-lg-center">
    <span class="badge badge-light-{{$color}} py-5 px-5">
        @if ($icono)
            <i class="{{$icono}} text-{{$color}}"></i>&nbsp;
        @endif
        {{ initcap($nombreConcepto) }}
    </span>
</div>
