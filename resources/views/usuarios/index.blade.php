@extends('layouts.index', ['titulo' => 'Usuarios', 'nombre_titulo' => 'Usuarios'])

@section('content')
    <div class="page-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h1 class="text-white">
                    <i class="fas fa-users"></i>
                    Usuarios
                </h1>
                <p class="subtitle mb-0">Crea y administra los usuarios de la plataforma.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <button type="button" class="btn btn-new-template" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario">
                    Crear Usuario
                </button>
            </div>
        </div>
    </div>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="contenedor" id="kt_content_container">
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 mb-10 mb-lg-0 h-100">
                    <div class="card card-flush h-100">
                        {{-- <div class="card-header pt-7">
                            <h1 class="text-gijac mulish">Usuarios</h1>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario">
                                    Crear Usuario
                                </button>
                            </div>
                        </div> --}}
                        <div class="card-body pt-5" id="kt_chat_contacts_body">
                            <div class="scroll-y me-n5 pe-5 h-lg-auto tablasScroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_contacts_header" data-kt-scroll-wrappers="#kt_content, #kt_chat_contacts_body" data-kt-scroll-offset="5px" style="max-height: 410px;">
                                <div class="table-responsive">
                                    <table class="table table-white" id="tablaUsuarios">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center all">#</th>
                                                <th width="10%" class="text-center all">Nombre</th>
                                                <th width="10%" class="text-center all">Identificación</th>
                                                <th width="10%" class="text-center none">Tipo Identificación</th>
                                                <th width="10%" class="text-center none">Genero</th>
                                                <th width="10%" class="text-center all">Telefono</th>
                                                <th width="10%" class="text-center all">Email</th>
                                                <th width="10%" class="text-center all">Ciudad</th>
                                                <th width="10%" class="text-center all space-b">Estado</th>
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
    @component('usuarios.modals.crear')
        @slot('tiposDocumentos', $tiposDocumentos)
        @slot('generos', $generos)
        @slot('paises', $paises)
    @endcomponent
    @component('usuarios.modals.roles-permisos')
    @endcomponent
    @component('usuarios.modals.modals')
    @endcomponent
@endsection

@section('scripts')
    <script src="{{ mix('/js/usuarios/principal.js') }}" ></script>
@endsection
