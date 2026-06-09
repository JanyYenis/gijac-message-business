@extends('layouts.index')

{{-- @section('tutorial')
    @component('layouts.componentes.header.tutorial')
    @endcomponent
@endsection --}}

@section('css')
    <style>
        .alert-info-custom {
            background: linear-gradient(135deg, #e3f2fd, #f0f8ff);
            border: 1px solid #81c784;
            border-radius: 10px;
            color: #2e7d32;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-info-custom .alert-icon {
            color: #4caf50;
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }

        .btn-excel {
            background: linear-gradient(135deg, #1e7e34, #28a745);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-excel:hover {
            background: linear-gradient(135deg, #155724, #1e7e34);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
            color: white;
        }

        .excel-icon {
            font-size: 1.1rem;
        }

        .checkbox-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin: 1.5rem 0;
            border: 1px solid #e9ecef;
        }

        .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }

        .form-check-input:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .form-check-label {
            font-weight: 500;
            color: #495057;
            cursor: pointer;
        }

        /* Dropzone Styles */
        .dropzone {
            border: 2px dashed #28a745 !important;
            border-radius: 12px !important;
            background: linear-gradient(135deg, #f8fff9, #e8f5e8) !important;
            padding: 2rem !important;
            text-align: center !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            min-height: 200px !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            align-items: center !important;
        }

        .dropzone:hover {
            border-color: #1e7e34 !important;
            background: linear-gradient(135deg, #f0fff0, #d4edda) !important;
            transform: scale(1.02);
        }

        .dropzone.dz-drag-hover {
            border-color: #155724 !important;
            background: linear-gradient(135deg, #e8f5e8, #c3e6cb) !important;
            transform: scale(1.05);
        }

        .dropzone .dz-message {
            margin: 0 !important;
            color: #28a745 !important;
            font-weight: 500 !important;
            font-size: 1.1rem !important;
        }

        .cloud-icon {
            font-size: 3rem;
            color: #28a745;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .dropzone-text {
            color: #28a745;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .dropzone-subtext {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .dz-preview {
            background: white !important;
            border-radius: 8px !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
            margin: 1rem auto !important;
            padding: 1rem !important;
            max-width: 300px !important;
        }

        .dz-filename span {
            color: #28a745 !important;
            font-weight: 500 !important;
        }

        .dz-size {
            color: #6c757d !important;
        }

        .dz-progress {
            background: #e9ecef !important;
            border-radius: 4px !important;
            overflow: hidden !important;
        }

        .dz-upload {
            background: linear-gradient(135deg, #28a745, #20c997) !important;
        }

        .dz-success-mark,
        .dz-error-mark {
            display: none !important;
        }

        .dz-error-message {
            background: #dc3545 !important;
            color: white !important;
            border-radius: 4px !important;
            padding: 0.5rem !important;
            font-size: 0.875rem !important;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h1 class="text-white">
                    <i class="fas fa-users"></i>
                    Mis Contactos
                </h1>
                <p class="subtitle mb-0">Crea y administra la infomación de tus contactos.</p>
            </div>
            <div class="mt-3 mt-md-0">
                @can('clientes.crear')
                    <button type="button" id="tutorialBtnCargar" class="btn btn-new-template" data-bs-toggle="modal" data-bs-target="#cargarContactosModal">
                        <i class="las la-cloud-upload-alt fs-2 text-primary"></i>
                        Cargar Contactos
                    </button>&nbsp;
                    <button type="button" id="tutorialBtnCrear" class="btn btn-new-template" data-bs-toggle="modal" data-bs-target="#modalCrearContactos">
                        <i class="fas fa-user-plus text-primary"></i>
                        Crear Contacto
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="contenedor" id="kt_content_container">
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 mb-10 mb-lg-0">
                    <div class="card card-flush">
                        {{-- <div class="card-header pt-7">
                            <h1 class="text-gijac mulish">Contactos</h1>
                            <div class="d-flex justify-content-end">
                                <button type="button" id="tutorialBtnCargar" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCargarContactos">
                                    <i class="las la-cloud-upload-alt fs-2 text-white"></i>
                                    Cargar Contactos
                                </button>&nbsp;
                                <button type="button" id="tutorialBtnCrear" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearContactos">
                                    <i class="fas fa-user-plus text-white"></i>
                                    Crear Contacto
                                </button>
                            </div>
                        </div> --}}
                        <div class="card-body pt-5" id="kt_chat_contacts_body">
                            <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto tablasScroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_contacts_header" data-kt-scroll-wrappers="#kt_content, #kt_chat_contacts_body" data-kt-scroll-offset="5px" style="max-height: 480px !important;">
                                <div class="table-responsive" id="tutorialTabla">
                                    <table border="1" class="table table-striped table-bordered" id="tablaContactos">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center all">#</th>
                                                <th width="10%" class="text-center all">Nombre</th>
                                                <th width="10%" class="text-center all">Telefono</th>
                                                <th width="10%" class="text-center all">Genero</th>
                                                <th width="10%" class="text-center all">Tratamiento de datos</th>
                                                <th width="10%" class="text-center all">Estado</th>
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
    @can('clientes.crear')
        @component('contactos.modals.crear')
            @slot('etiquetas', $etiquetas)
            @slot('generos', $generos)
        @endcomponent
        @component('contactos.modals.cargar')
        @endcomponent
    @endcan

    @component('contactos.modals.ver')
    @endcomponent

    @can('clientes.editar')
        @component('contactos.modals.modal')
        @endcomponent
    @endcan
@endsection

@section('scripts')
    <script src="{{ mix('/js/contactos/principal.js') }}" ></script>
@endsection
