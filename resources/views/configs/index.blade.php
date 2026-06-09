@extends('layouts.index')

@section('content')
    <div class="page-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h1 class="text-white">
                    <i class="fa-brands fa-facebook"></i>
                    Configuraciones
                </h1>
                <p class="subtitle mb-0">Conectate con tu aplicación de META</p>
            </div>
            <div class="mt-3 mt-md-0">
                @if (($demo && !$existeConfig) || $plan)
                <button type="button" class="btn btn-new-template" data-bs-toggle="modal" data-bs-target="#modalCrearConfig">
                    <i class="fas fa-plus me-2"></i>
                    Crear Configuración
                </button>
                @endif
            </div>
        </div>
    </div>

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="contenedor" id="kt_content_container">
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 mb-10 mb-lg-0">
                    <div class="card card-flush">
                        <div class="card-body pt-5" id="kt_chat_contacts_body">
                            <div class="table-responsive">
                                <table border="1" class="table table-striped table-bordered" id="tablaConfig">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center all">#</th>
                                            <th width="10%" class="text-center all">Version</th>
                                            <th width="10%" class="text-center all">Waba Id</th>
                                            <th width="10%" class="text-center all">App Id</th>
                                            <th width="10%" class="text-center none">Phone Number Id</th>
                                            <th width="10%" class="text-center none">Token</th>
                                            <th width="10%" class="text-center all">Numero</th>
                                            <th width="10%" class="text-center all">URL Webhook</th>
                                            <th width="10%" class="text-center all">Estado</th>
                                            <th width="10%" class="text-center none">Identificador de verificación</th>
                                            <th width="10%" class="text-center all">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @component('configs.modals.crear')
    @endcomponent
    @component('configs.modals.modals')
    @endcomponent
@endsection

@section('scripts')
    <script src="{{ mix('/js/configs/principal.js') }}" ></script>
@endsection
