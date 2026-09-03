@extends('layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ mix('css/plantillas.css') }}">
    <style>
        .main-container {
            min-height: 100vh;
            padding: 2rem 0;
        }

        /* Table Card Styles */
        .table-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
        }

        .table-card .card-header {
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            padding: 1.5rem;
            border-radius: 1rem 1rem 0 0;
        }

        .table-responsive {
            border-radius: 0 0 1rem 1rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background: var(--gray-50);
            border: none;
            font-weight: 600;
            color: var(--gray-700);
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table td {
            border: none;
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-100);
        }

        .table tbody tr:hover {
            background: var(--gray-50);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-approved {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-green);
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-amber);
        }

        .status-rejected {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-red);
        }

        /* Category Badges */
        .category-badge {
            background: rgba(34, 197, 94, 0.1);
            color: var(--primary-green);
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 0.5rem;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-view {
            background: rgba(34, 197, 94, 0.1);
            color: var(--primary-green);
        }

        .btn-view:hover {
            background: var(--primary-green);
            color: white;
            transform: scale(1.1);
        }

        .btn-edit {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-amber);
        }

        .btn-edit:hover {
            background: var(--warning-amber);
            color: white;
            transform: scale(1.1);
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-red);
        }

        .btn-delete:hover {
            background: var(--danger-red);
            color: white;
            transform: scale(1.1);
        }

        /* Phone Preview Modal */
        .phone-preview-modal .modal-dialog {
            max-width: 400px;
        }

        .phone-mockup {
            width: 320px;
            height: 640px;
            background: #1F2937;
            border-radius: 2rem;
            padding: 1rem;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .phone-screen {
            width: 100%;
            height: 100%;
            background: #0D1117;
            border-radius: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .whatsapp-header {
            background: var(--whatsapp-dark);
            padding: 1rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .business-avatar {
            width: 40px;
            height: 40px;
            background: var(--whatsapp-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .business-info h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .business-info small {
            opacity: 0.8;
            font-size: 0.75rem;
        }

        /* Search and Filter Bar */
        .filter-bar {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .search-input {
            border: 2px solid var(--gray-200);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--whatsapp-green);
            box-shadow: 0 0 0 0.2rem rgba(37, 211, 102, 0.25);
        }

        .filter-select {
            border: 2px solid var(--gray-200);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            border-color: var(--whatsapp-green);
            box-shadow: 0 0 0 0.2rem rgba(37, 211, 102, 0.25);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem 0;
            }

            .page-header {
                padding: 1.5rem;
                text-align: center;
            }

            .page-header h1 {
                font-size: 1.5rem;
                justify-content: center;
            }

            .action-buttons {
                justify-content: center;
            }

            .table th,
            .table td {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }

            .phone-mockup {
                width: 280px;
                height: 560px;
            }
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--gray-400);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid main-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h1 class="text-white">
                        <i class="fab fa-whatsapp text-white"></i>{{ __('Plantillas de WhatsApp') }}</h1>
                    <p class="subtitle mb-0">{{ __('Gestiona y previsualiza tus plantillas aprobadas') }}</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-new-template" id="btnSincronizar">
                        <i class="fa fa-sync-alt fs-4"></i>{{ __('Sincronizar Plantillas') }}</button>
                    <button type="button" class="btn btn-new-template" data-bs-toggle="modal" data-bs-target="#modalCrearPlantilla">
                        <i class="fas fa-plus fs-1"></i>{{ __('Nueva Plantilla') }}</button>
                </div>
            </div>
        </div>

        <!-- Templates Table -->
        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <div class="contenedor" id="kt_content_container">
                <div class="d-flex flex-column flex-lg-row">
                    <div class="flex-column flex-lg-row-auto w-100 mb-10 mb-lg-0">
                        <div class="card card-flush">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 fw-bold text-muted">
                                        <i class="fas fa-list me-2"></i>{{ __('Lista de Plantillas') }}</h5>
                                </div>
                            </div>
                            <div class="card-body pt-5" id="kt_chat_contacts_body">
                                <div class="table-responsive">
                                    <table class="table" id="tablaPlantilla">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center all">#</th>
                                                <th width="10%" class="text-center all">{{ __('Nombre') }}</th>
                                                <th width="10%" class="text-center all">{{ __('Categoría') }}</th>
                                                <th width="10%" class="text-center all">{{ __('Lenguaje') }}</th>
                                                <th width="10%" class="text-center all">{{ __('Estado') }}</th>
                                                <th width="10%" class="text-center all">{{ __('Acciones') }}</th>
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
    @component('plantillas.modals.crear')
        @slot('numeroTel', $numeroTel)
        @slot('categorias', $categorias)
        @slot('idiomas', $idiomas)
    @endcomponent
    @component('plantillas.modals.ver')
    @endcomponent
@endsection

@section('scripts')
    <script src="{{ mix('/js/plantillas/principal.js') }}"></script>
@endsection
