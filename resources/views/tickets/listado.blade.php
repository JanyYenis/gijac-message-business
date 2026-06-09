@if (count($tickets))
    @foreach ($tickets as $ticket)
        <!--begin::Ticket-->
        <div class="d-flex mb-10">
            <!--begin::Symbol-->
            <i class="ki-outline ki-add-files fs-2x me-5 ms-n1 mt-2 text-warning"></i>
            <!--end::Symbol-->

            <!--begin::Section-->
            <div class="d-flex flex-column">
                <!--begin::Content-->
                <div class="d-flex align-items-center mb-2">
                    <!--begin::Title-->
                    <a href="{{ route('tickets.edit', ['ticket' => $ticket->id]) }}"
                        class="text-gray-900 text-hover-primary fs-4 me-3 fw-semibold">{{$ticket?->titulo ?? 'N/A'}}</a>
                    <!--end::Title-->

                    <!--begin::Label-->
                    <span class="badge badge-{{ $ticket?->infoEstado?->color ?? 'light' }} my-1">{{ $ticket->infoEstado->nombre }}</span>
                    <!--end::Label-->
                    <!--begin::Label-->
                    <span class="badge badge-{{ $ticket?->infoPrioridad?->color ?? 'light' }} my-1 ms-1">{{ $ticket?->infoPrioridad?->nombre ?? 'N/A' }}</span>
                    <!--end::Label-->
                </div>
                <!--end::Content-->

                <!--begin::Text-->
                <span class="text-muted fw-semibold fs-6">{!! acortarCadena($ticket?->descripcion) !!}</span>
                <!--end::Text-->
            </div>
            <!--end::Section-->
        </div>
        <!--end::Ticket-->
    @endforeach
@else
    <div class="text-center m-5">
        <h1>No cuenta con tickets disponibles.</h1>
    </div>
@endif

@component("tickets.paginado")
    @slot("catidadDatos", $tickets)
    @slot("ultimaPagina", $ultimaPagina)
    @slot("paginaActual", $paginaActual)
@endcomponent
