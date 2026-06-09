@extends('layouts.index')

@section('css')
    <style>
        :root {
            --primary-color: #1E6A75;
            --primary-light: #147888;
            --primary-dark: #0d4b55;
            --secondary-color: #0d4b55;
            --secondary-light: #1E6A75;
            --accent-color: #6366F1;
            --success-color: #1E6A75;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
        }
        .main-container {
            min-height: 100vh;
            padding: 2rem 0;
        }

        /* Stepper Styles */
        .stepper {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stepper-item {
            display: flex;
            align-items: center;
            position: relative;
            flex: 1;
            max-width: 200px;
        }

        .stepper-item:not(:last-child)::after {
            /* content: ''; */
            position: absolute;
            top: 50%;
            right: -50%;
            width: 100%;
            height: 2px;
            background: var(--gray-200);
            transform: translateY(-50%);
            z-index: 1;
        }

        .stepper-item.active:not(:last-child)::after {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .stepper-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gray-200);
            color: var(--gray-500);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.75rem;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .stepper-item.active .stepper-number {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .stepper-item.completed .stepper-number {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .stepper-text {
            font-weight: 500;
            color: var(--gray-600);
            font-size: 0.875rem;
        }

        .stepper-item.active .stepper-text {
            color: linear-gradient(135deg, #28a745, #20c997);
            font-weight: 600;
        }

        /* Card Styles */
        .campaign-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: none;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .campaign-card:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header-custom {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border-radius: 1rem 1rem 0 0 !important;
            padding: 1.25rem 1.5rem;
            border: none;
        }

        .card-header-custom h5 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Form Styles */
        .form-control, .form-select {
            border: 2px solid var(--gray-200);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: linear-gradient(135deg, #28a745, #20c997);
            box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-1px);
        }

        .btn-secondary-custom {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: linear-gradient(135deg, #1E6A75, var(--secondary-color));
            transform: translateY(-1px);
            color: white;
        }

        /* Table Styles */
        .contacts-table {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background: var(--gray-50);
            border: none;
            font-weight: 600;
            color: var(--gray-700);
            padding: 1rem;
        }

        .table td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: var(--gray-50);
        }

        /* Step Content */
        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
        }

        /* Variable Tags */
        .variable-tag {
            background: var(--accent-color);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .variable-tag:hover {
            background: #4F46E5;
            transform: scale(1.05);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stepper {
                flex-direction: column;
                gap: 1rem;
            }

            .stepper-item::after {
                display: none;
            }
        }
    </style>

    <style>
        .modal-header-wizard {
            background: linear-gradient(135deg, var(--whatsapp-green) 0%, var(--whatsapp-dark) 100%);
            padding: 30px 20px;
            border-radius: 12px 12px 0 0;
            color: white;
        }

        .modal-header-wizard h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-body-wizard {
            padding: 40px 30px;
            background: white;
        }

        .breadcrumb-wizard {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            position: relative;
        }

        .breadcrumb-wizard::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gray-300);
            z-index: 0;
        }

        .breadcrumb-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            z-index: 1;
            position: relative;
        }

        .breadcrumb-step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 16px;
            background: var(--gray-200);
            color: var(--gray-600);
            border: 2px solid var(--gray-300);
            transition: all 0.3s ease;
        }

        .breadcrumb-step.active .breadcrumb-step-circle {
            background: var(--whatsapp-green);
            color: white;
            border-color: var(--whatsapp-green);
            box-shadow: 0 0 0 4px rgba(37, 211, 102, 0.15);
        }

        .breadcrumb-step.completed .breadcrumb-step-circle {
            background: var(--success-color);
            color: white;
            border-color: var(--success-color);
        }

        .breadcrumb-step.completed .breadcrumb-step-circle::after {
            content: '✓';
            position: absolute;
        }

        .breadcrumb-step-label {
            font-size: 12px;
            color: var(--gray-600);
            font-weight: 500;
            text-align: center;
            max-width: 80px;
        }

        .breadcrumb-step.active .breadcrumb-step-label {
            color: var(--whatsapp-green);
            font-weight: 600;
        }

        .wizard-step {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .wizard-step.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .step-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .step-description {
            color: var(--gray-600);
            margin-bottom: 24px;
            font-size: 0.95rem;
        }

        .template-card {
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .template-card:hover {
            border-color: var(--whatsapp-green);
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.15);
            transform: translateY(-2px);
        }

        .template-card.selected {
            border-color: var(--whatsapp-green);
            background: var(--whatsapp-light);
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.2);
        }

        .template-card.selected::after {
            content: '✓';
            position: absolute;
            top: 15px;
            right: 15px;
            width: 28px;
            height: 28px;
            background: var(--whatsapp-green);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
        }

        .template-name {
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 8px;
            font-size: 1.1rem;
        }

        .template-type {
            display: inline-block;
            background: var(--whatsapp-green);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .template-preview {
            background: var(--gray-50);
            padding: 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--gray-700);
            border-left: 3px solid var(--whatsapp-green);
        }

        .users-table-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
        }

        .users-table {
            margin: 0;
        }

        .users-table thead {
            background: var(--gray-50);
            position: sticky;
            top: 0;
        }

        .users-table th {
            border: none;
            color: var(--gray-700);
            font-weight: 600;
            padding: 12px 16px;
            font-size: 0.9rem;
            background: var(--gray-100);
        }

        .users-table td {
            padding: 12px 16px;
            border: none;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-800);
        }

        .users-table tr:hover {
            background: var(--whatsapp-light);
        }

        .users-table tbody tr:last-child td {
            border-bottom: none;
        }

        .user-checkbox {
            cursor: pointer;
            margin-left: 1rem;
        }

        .user-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--whatsapp-green);
        }

        .user-name {
            font-weight: 500;
            color: var(--gray-900);
        }

        .user-phone {
            color: var(--gray-600);
            font-size: 0.9rem;
        }

        .user-stats {
            color: var(--gray-500);
            font-size: 0.85rem;
        }

        .select-all-container {
            padding: 12px 16px;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .select-all-container input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--whatsapp-green);
        }

        .select-all-container label {
            margin: 0;
            cursor: pointer;
            font-weight: 600;
            color: var(--gray-900);
        }

        .results-table-container {
            max-height: 450px;
            overflow-y: auto;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
        }

        .results-table {
            margin: 0;
        }

        .results-table thead {
            background: var(--gray-50);
            position: sticky;
            top: 0;
        }

        .results-table th {
            border: none;
            color: var(--gray-700);
            font-weight: 600;
            padding: 12px 16px;
            font-size: 0.9rem;
            background: var(--gray-100);
        }

        .results-table td {
            padding: 12px 16px;
            border: none;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-800);
        }

        .results-table tbody tr:last-child td {
            border-bottom: none;
        }

        .probability-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .probability-high {
            background: rgba(16, 185, 129, 0.15);
            color: var(--success-color);
        }

        .probability-medium {
            background: rgba(245, 158, 11, 0.15);
            color: var(--warning-color);
        }

        .probability-low {
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger-color);
        }

        .probability-bar {
            width: 100%;
            height: 6px;
            background: var(--gray-200);
            border-radius: 3px;
            overflow: hidden;
            margin-top: 4px;
        }

        .probability-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--whatsapp-green), var(--whatsapp-dark));
            transition: width 0.3s ease;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-will-open {
            background: rgba(16, 185, 129, 0.15);
            color: var(--success-color);
        }

        .status-wont-open {
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger-color);
        }

        .modal-footer-wizard {
            padding: 20px 30px;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            gap: 12px;
        }

        .btn-custom {
            padding: 10px 28px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-primary-wizard {
            background: linear-gradient(135deg, var(--whatsapp-green) 0%, var(--whatsapp-dark) 100%);
            color: white;
        }

        .btn-primary-wizard:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 211, 102, 0.3);
        }

        .btn-primary-wizard:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-secondary-wizard {
            background: var(--gray-200);
            color: var(--gray-700);
        }

        .btn-secondary-wizard:hover:not(:disabled) {
            background: var(--gray-300);
        }

        .btn-secondary-wizard:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .results-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
            padding: 16px;
            border-radius: 12px;
            border: 1px solid var(--gray-200);
        }

        .stats-label {
            color: var(--gray-600);
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .stats-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--whatsapp-green);
        }

        .selected-info {
            background: var(--whatsapp-light);
            padding: 12px 16px;
            border-radius: 8px;
            border-left: 4px solid var(--whatsapp-green);
            margin-bottom: 20px;
            color: var(--gray-700);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .modal-body-wizard {
                padding: 20px;
            }

            .modal-footer-wizard {
                padding: 16px;
                flex-direction: column;
            }

            .breadcrumb-step-label {
                font-size: 10px;
                max-width: 60px;
            }

            .breadcrumb-step-circle {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }

            .users-table-container,
            .results-table-container {
                max-height: 300px;
            }

            .btn-custom {
                width: 100%;
                padding: 12px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h1 class="text-white">
                    <i class="bi bi-megaphone-fill fs-2 text-white"></i>
                    Mis Campañas
                </h1>
                <p class="subtitle mb-0">Configura y lanza tu campaña de WhatsApp Business</p>
            </div>
            <div class="mt-3 mt-md-0">
                @can('campana.crear')
                    <button type="button" class="btn btn-new-template" id="abrirModalPredictivo">
                        <i class="fas fa-rocket"></i>
                        Análisis Predictivo
                    </button>
                    <button type="button" class="btn btn-new-template" data-bs-toggle="modal" data-bs-target="#modalCrearCampana">
                        <i class="fas fa-plus fs-1"></i>
                        Crear Campaña
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
                        <div class="card-body pt-5" id="kt_chat_contacts_body">
                            <div class="row">
                                <div class="col-lg-2 col-md-2">
                                    <ul class="nav nav-tabs nav-pills flex-row border-0 flex-md-column me-2 mb-3 mb-md-0 fs-6">
                                        <li class="nav-item w-100 me-0 mb-md-2">
                                            <a class="nav-link w-100 active btn btn-flex btn-active-light-success" data-bs-toggle="tab" href="#tabListadoCampanasTabla">
                                                <span class="svg-icon fs-2 me-2">
                                                    <i class="far fa-list-alt"></i>
                                                </span>
                                                <span class="d-flex flex-column align-items-start">
                                                    <span class="fs-4 fw-bold">Listado</span>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item w-100 me-0 mb-md-2">
                                            <a class="nav-link w-100 btn btn-flex btn-active-light-info" id="btnTabListadoTarjetas" data-bs-toggle="tab" href="#tabListadoCampanasTarjeta">
                                                <span class="svg-icon fs-2 me-2">
                                                    <i class="far fa-image"></i>
                                                </span>
                                                <span class="d-flex flex-column align-items-start">
                                                    <span class="fs-4 fw-bold">Tarjetas</span>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-lg-10 col-md-10">
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="tabListadoCampanasTabla" role="tabpanel">
                                            <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto tablasScroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_contacts_header" data-kt-scroll-wrappers="#kt_content, #kt_chat_contacts_body" data-kt-scroll-offset="5px" style="max-height: 410px;">
                                                <div class="table-responsive">
                                                    <table border="1" class="table table-striped table-bordered" id="tablaCampanas">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%" class="text-center all">#</th>
                                                                <th width="10%" class="text-center all">Nombre</th>
                                                                <th width="10%" class="text-center all">Descripción</th>
                                                                <th width="10%" class="text-center all">Enviado por</th>
                                                                <th width="10%" class="text-center all">Estado</th>
                                                                <th width="10%" class="text-center all">Fecha envio</th>
                                                                <th width="10%" class="text-center none">Fecha creación</th>
                                                                <th width="10%" class="text-center none">Plantilla</th>
                                                                <th width="10%" class="text-center none">Tipo</th>
                                                                <th width="10%" class="text-center all">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tabListadoCampanasTarjeta" role="tabpanel">
                                            <div class="seccionListadoCampanas"></div>
                                        </div>
                                    </div>
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
    @can('campana.crear')
        @component('campanas.modals.crear')
            @slot('etiquetas', $etiquetas)
            @slot('plantillas', $plantillas)
            @slot('numeroTel', $numeroTel)
            @slot('categorias', $categorias)
        @endcomponent

        @component('campanas.modals.prediccion')
        @endcomponent
    @endcan

    @component('campanas.modals.detalle')
    @endcomponent

    @can('campana.editar')
        @component('campanas.modals.modal')
        @endcomponent
    @endcan

    @component('campanas.modals.modal-respuesta')
    @endcomponent
    @component('sistema.modales.modal-errores')
    @endcomponent
    @component('campanas.modals.modal-links')
    @endcomponent
@endsection

@section('scripts')
    <script src="{{ mix('/js/campanas/principal.js') }}" ></script>
    <script src="{{ mix('/js/campanas/prediccion.js') }}" ></script>
@endsection
