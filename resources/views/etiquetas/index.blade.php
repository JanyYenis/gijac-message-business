@extends('layouts.index')

@section('content')
    <div class="page-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h1 class="text-white">
                    <i class="fas fa-tags"></i>
                    Mis Etiquetas
                </h1>
                <p class="subtitle mb-0">Crea y administra etiquetas para tus contactos.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <button type="button" class="btn btn-new-template" data-bs-toggle="modal" data-bs-target="#modalCrearEtiqueta">
                    Crear Etiqueta
                </button>
            </div>
        </div>
    </div>

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="contenedor" id="kt_content_container">
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 mb-10 mb-lg-0">
                    <div class="card card-flush">
                        <div class="card-body pt-5" id="kt_chat_contacts_body">
                            <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto tablasScroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_contacts_header" data-kt-scroll-wrappers="#kt_content, #kt_chat_contacts_body" data-kt-scroll-offset="5px" style="max-height: 410px;">
                                <div class="table-responsive">
                                    <table border="1" class="table table-striped table-bordered" id="tablaEtiquetas">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center all">#</th>
                                                <th width="10%" class="text-center all">Nombre</th>
                                                <th width="10%" class="text-center all">Color</th>
                                                <th width="10%" class="text-center all">Descripción</th>
                                                <th width="10%" class="text-center all">Estado</th>
                                                <th width="10%" class="text-center none">Fecha Creación</th>
                                                <th width="10%" class="text-center none">Fecha Modificación</th>
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
    </div>
@endsection

@section('modal')
    @component('etiquetas.modals.crear')
    @endcomponent
    @component('etiquetas.modals.modals')
    @endcomponent
@endsection

@section('scripts')
    <script src="{{ mix('/js/etiquetas/principal.js') }}" ></script>
@endsection
