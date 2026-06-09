@php
    $paginaInicial = 1;
    $paginaActual  = $paginaActual ?? 1;
    $paginaFinal   = $ultimaPagina ?? 1;

    $paginaInicio = $paginaInicial;
    $paginaMaxima = $paginaFinal;

    $paginaAnterior  = $paginaActual - 1;
    $paginaSiguiente = $paginaActual + 1;
@endphp
<div class="d-flex flex-stack flex-wrap pt-10">
    <div class="fs-6 fw-semibold text-gray-700">
        Total {{count($catidadDatos)}} resultado(s).
    </div>

    <!--begin::Pages-->
    <ul class="pagination">
        <li class="page-item previous">
            <button class="page-link btnPagina" {{$paginaActual <= $paginaInicial ? 'disabled' : ''}} data-pagina="{{$paginaAnterior}}"><i class="previous"></i></button>
        </li>

        @for($i = $paginaInicio ; $i <= $paginaMaxima ; $i++)
            <li class="page-item {{$i == $paginaActual ? 'active' : ''}}">
                <button class="page-link btnPagina {{$i == $paginaActual ? 'btn-primary' : ''}}" {{$i == $paginaActual ? 'disabled' : ''}} data-pagina="{{$i}}">
                    {{$i}}
                </button>
            </li>
        @endfor

        <li class="page-item next">
            <button class="page-link btnPagina" {{$paginaActual >= $paginaFinal ? 'disabled' : ''}} data-pagina="{{$paginaSiguiente}}">
                <i class="next"></i>
            </button>
        </li>
    </ul>
    <!--end::Pages-->
</div>