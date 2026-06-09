@extends('layouts.index', ['titulo' => 'Mis Tickets', 'nombre_titulo' => 'Mis Tickets'])

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="contenedor" id="kt_content_container">
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 mb-10 mb-lg-0 h-100">
                    <div class="card card-flush h-100">
                        <div class="card-header pt-7">
                            <h1 class="text-gijac mulish">Mis Tickets</h1>
                            <div class="d-flex justify-content-end">
                                @canany(['tickets.crear'])
                                    <button type="button" class="btn btn-primary-gijac" data-bs-toggle="modal" data-bs-target="#modalCrearTickets">
                                        Crear Ticket
                                    </button>
                                @endcanany
                            </div>
                        </div>
                        <!--begin::Card body-->
                        <div class="card-body">
                            <!--begin::Layout-->
                            <div class="d-flex flex-column flex-xl-row p-7">
                                <!--begin::Content-->
                                <div class="flex-lg-row-fluid me-xl-15 mb-20 mb-xl-0">
                                    <!--begin::Tickets-->
                                    <div class="mb-0">
                                        <!--begin::Search form-->
                                        <div class="mb-15">
                                            <!--begin::Input wrapper-->
                                            <div class="position-relative">
                                                <i class="fas fa-search fs-1 text-primary position-absolute top-50 translate-middle ms-9"></i>
                                                <input type="text" class="form-control form-control-lg form-control-solid ps-14"
                                                    name="search" value="" placeholder="Buscar">
                                            </div>
                                            <!--end::Input wrapper-->
                                        </div>
                                        <!--end::Search form-->

                                        <!--begin::Tickets List-->
                                        <div class="mb-10">
                                            <div class="seccionListadoTickets"></div>
                                        </div>
                                        <!--end::Tickets List-->
                                    </div>
                                    <!--end::Tickets-->
                                </div>
                                <!--end::Content-->

                                <!--begin::Sidebar-->
                                @component('tickets.sider')
                                @endcomponent
                                <!--end::Sidebar-->
                            </div>
                            <!--end::Layout-->
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @can('tickets.crear')
        @component('tickets.modals.crear')
            @slot('responsables', $responsables)
            @slot('estados', $estados)
            @slot('tipos', $tipos)
            @slot('prioridades', $prioridades)
        @endcomponent
    @endcan
@endsection

@section('scripts')
    <script src="{{ mix('js/tickets/principal.js') }}"></script>
@endsection
