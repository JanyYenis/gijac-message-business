@if (count($comentarios))
    @foreach ($comentarios as $comentario)
        <div class="mb-9">
            <!--begin::Card-->
            <div class="card card-bordered w-100">
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin::Wrapper-->
                    <div class="w-100 d-flex flex-stack mb-8">
                        <!--begin::Container-->
                        <div class="d-flex align-items-center f">
                            <!--begin::Author-->
                            <div class="symbol symbol-50px me-5">
                                @if ($comentario?->usuario?->foto)
                                    <img src="{{ asset($comentario?->usuario?->foto) }}">
                                @else
                                    <div class="symbol-label fs-1 fw-bold bg-light-success text-success">
                                        {{ $comentario?->usuario?->nombre_completo[0] }}
                                    </div>
                                @endif
                            </div>
                            <!--end::Author-->

                            <!--begin::Info-->
                            <div class="d-flex flex-column fw-semibold fs-5 text-gray-600 text-gray-900">
                                <!--begin::Text-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Username-->
                                    <a href="#" class="text-gray-800 fw-bold text-hover-primary fs-5 me-3">
                                        {{ initCap($comentario?->usuario?->nombre_completo) }}
                                    </a>
                                    <!--end::Username-->

                                    <span class="m-0"></span>
                                </div>
                                <!--end::Text-->

                                <!--begin::Date-->
                                <span class="text-muted fw-semibold fs-6">{{ $comentario?->created_at->format('d/m/Y g:i a') }}</span>
                                <!--end::Date-->
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Container-->

                        <!--begin::Actions-->
                        {{-- <div class="m-0">
                            <button type="button" class="btn btn-color-gray-500 btn-active-color-primary p-0 fw-bold">Responder</button>
                        </div> --}}
                        <!--end::Actions-->
                    </div>
                    <!--end::Wrapper-->

                    <!--begin::Desc-->
                    <p class="fw-normal fs-5 text-gray-700 m-0">
                        {!! $comentario?->descripcion !!}
                    </p>
                    <!--end::Desc-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
    @endforeach
@else
    <div class="text-center">
        <h1>Sin comentarios</h1>
    </div>
@endif

@component("comentarios.paginado")
    @slot("catidadDatos", $comentarios)
    @slot("ultimaPagina", $ultimaPagina)
    @slot("paginaActual", $paginaActual)
@endcomponent
