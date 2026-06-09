<div class="row g-6 g-xl-9">
    @if (count($campanas))
        @foreach ($campanas as $campana)
            <!--begin::Col-->
            <div class="col-md-6 col-xl-4">
                <!--begin::Card-->
                <div class="card border-hover-success h-100">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-9">
                        <div class="card-title m-0">
                            <span class="fs-5 badge badge-light-{{$campana?->infoEstado?->color}} fw-bold px-4 py-3">{{$campana->infoEstado?->nombre}}</span>
                        </div>
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar d-flex flex-column align-items-end">
                            @component('campanas.columnas.acciones')
                                @slot('model', $campana)
                                @slot('estado_eliminado', $campana?->estado == 0)
                                @slot('estado_enviado', $campana?->estado == 1)
                                @slot('puede_listado', $puede_listado)
                                @slot('puede_crear', $puede_crear)
                                @slot('puede_editar', $puede_editar)
                                @slot('puede_eliminar', $puede_eliminar)
                            @endcomponent
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end:: Card header-->

                    <!--begin:: Card body-->
                    <div class="card-body p-9">
                        @if ($campana->contenido_multimedia)
                            <!--begin::Avatar-->
                            <div class="symbol symbol-150px ms-10 bg-light text-center">
                                <img src="{{ $campana->contenido_multimedia }}" alt="image"
                                    class="p-3">
                            </div>
                            <!--end::Avatar-->
                        @endif
                        <!--begin::Description-->
                        <p class="text-negro fw-normal text-gris fs-4 mt-1 mb-7">
                            {{ $campana?->contenido ?? '' }}
                        <!--end::Description-->

                        <!--begin::Info-->
                        <div class="d-flex flex-column mb-5">
                            <!--begin::Due-->
                            <div class="border border-gray-300 border-1 rounded  mb-3 p-3">
                                <div class="fs-4 text-gris">Fecha creación</div>
                                <div class="fs-3 fw-semibold text-gray-800 fw-bold">{{$campana->created_at}}</div>
                            </div>
                            <!--end::Due-->
                            <!--begin::Due-->
                            <div class="border border-gray-300 border-1 rounded  mb-3 p-3">
                                <div class="fs-4 text-gris">Fecha envio</div>
                                <div class="fs-3 fw-semibold text-gray-800 fw-bold">{{$campana->fecha_envio}}</div>
                            </div>
                            <!--end::Due-->
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end:: Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Col-->
        @endforeach
    @else
        <div class="text-center">
            <h1 class="text-gijac">Sin resultados.</h1>
        </div>
    @endif
</div>

@component("campanas.paginado")
    @slot("catidadDatos", $campanas)
    @slot("ultimaPagina", $ultimaPagina)
    @slot("paginaActual", $paginaActual)
@endcomponent
