@extends('layouts.index')

@section('css')
    <style>
        .page-header {
            background: linear-gradient(135deg, var(--primary-green), var(--success-green));
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
        }

        .page-header h1 {
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .badge-service {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            margin: 0.2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .badge-service.active {
            background: rgba(40, 167, 69, 0.1);
            color: var(--primary-green);
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        .badge-service.inactive {
            background: rgba(108, 117, 125, 0.1);
            color: var(--gray-600);
            border: 1px solid rgba(108, 117, 125, 0.2);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-edit {
            background: rgba(253, 126, 20, 0.1);
            color: var(--warning-orange);
        }

        .btn-edit:hover {
            background: var(--warning-orange);
            color: white;
            transform: scale(1.1);
        }

        .btn-delete {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger-red);
        }

        .btn-delete:hover {
            background: var(--danger-red);
            color: white;
            transform: scale(1.1);
        }

        .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .service-item {
            background: var(--gray-100);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .service-item:hover {
            background: #e2e6ea;
        }

        .service-item.active {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        /* Price Preview Card */
        .price-preview {
            background: linear-gradient(135deg, var(--primary-green), var(--success-green));
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            position: sticky;
            top: 2rem;
        }

        .price-amount {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }

        .price-period {
            opacity: 0.9;
            font-size: 1rem;
        }

        .price-contacts {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 0.75rem;
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
                text-align: center;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h1 class="text-white">
                    <i class="fas fa-tags"></i>
                    Planes
                </h1>
                <p class="subtitle mb-0">Crea y administra los planes para tus clientes.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <button type="button" class="btn btn-new-template" data-bs-toggle="modal" data-bs-target="#modalCrearPlanes">
                    Crear Nuevo Plan
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
                            <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto tablasScroll" data-kt-scroll="true"
                                data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                                data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_contacts_header"
                                data-kt-scroll-wrappers="#kt_content, #kt_chat_contacts_body" data-kt-scroll-offset="5px"
                                style="max-height: 410px;">
                                <div class="table-responsive">
                                    <table border="1" class="table table-striped table-bordered" id="tablaPlanes">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center all">#</th>
                                                <th width="10%" class="text-center all">Nombre</th>
                                                <th width="10%" class="text-center all">Precio</th>
                                                <th width="10%" class="text-center all">Categoria</th>
                                                <th width="10%" class="text-center all">Tipo</th>
                                                <th width="10%" class="text-center all">Nº Contactos</th>
                                                <th width="10%" class="text-center none">Servicios Incluidos</th>
                                                <th width="10%" class="text-center none">Fecha Creación</th>
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
    @component('planes.modals.crear')
        @slot('servicios', $servicios)
        @slot('categorias', $categorias)
        @slot('tipos', $tipos)
    @endcomponent
    @component('planes.modals.editar')
    @endcomponent
@endsection

@section('scripts')
    <script src="{{ mix('/js/planes/principal.js') }}"></script>
@endsection
