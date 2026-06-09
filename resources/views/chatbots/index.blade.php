@extends('layouts.index')

@section('css')
    <style>
        /* Flow Builder Styles */
        .flow-builder {
            min-height: 600px;
            background: #F8FAFC;
            border-radius: 12px;
            padding: 2rem;
            position: relative;
        }

        .flow-node {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
        }

        .flow-node:hover {
            box-shadow: var(--card-shadow-hover);
            border-color: var(--primary-color);
        }

        .flow-node.active {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.1);
        }

        .node-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #E5E7EB;
        }

        .node-type {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .node-actions {
            display: flex;
            gap: 0.5rem;
        }

        .node-content {
            margin-bottom: 1rem;
        }

        .node-connections {
            border-top: 1px solid #E5E7EB;
            padding-top: 1rem;
        }

        .connection-item {
            background: #F3F4F6;
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: between;
            align-items: center;
        }
        /* Node Types */
        .node-type-selector {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .node-type-card {
            background: white;
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .node-type-card:hover {
            border-color: var(--primary-color);
            box-shadow: var(--card-shadow);
        }

        .node-type-card.selected {
            border-color: var(--primary-color);
            background: rgba(37, 211, 102, 0.05);
        }

        .node-type-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        /* Flow Diagram */
        .flow-diagram {
            background: #F8FAFC;
            border-radius: 12px;
            padding: 2rem;
            min-height: 400px;
            position: relative;
        }

        .diagram-node {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: var(--card-shadow);
            position: absolute;
            min-width: 200px;
            border: 2px solid transparent;
        }

        .diagram-node.start {
            border-color: var(--success-color);
        }

        .diagram-node.end {
            border-color: var(--danger-color);
        }

        .diagram-connection {
            position: absolute;
            height: 2px;
            background: var(--primary-color);
            z-index: 1;
        }

        .diagram-arrow {
            position: absolute;
            right: -8px;
            top: -4px;
            width: 0;
            height: 0;
            border-left: 8px solid var(--primary-color);
            border-top: 4px solid transparent;
            border-bottom: 4px solid transparent;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h1 class="text-white">
                    <i class="fas fa-robot"></i>
                    Constructor de Chatbots
                </h1>
                <p class="subtitle mb-0">Crea y gestiona chatbots inteligentes para WhatsApp Business</p>
            </div>
            <div class="mt-3 mt-md-0">
                @can('chatbot.crear')
                    <a href="{{ route('chatbots.create') }}" type="button" class="btn btn-new-template">
                        Crear Chatbot
                    </a>
                @endcan
            </div>
        </div>
    </div>
    <div class="container-fluid main-container">
        <!-- Chatbot List View -->
        <div id="chatbotListView">
            <!-- Search Container -->
            <div class="card main-card mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" placeholder="Buscar chatbots..." id="searchInput">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">Todos los estados</option>
                                <option value="active">Activos</option>
                                <option value="inactive">Inactivos</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                <span id="totalChatbots">0</span> chatbots
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chatbots Table -->
            <div class="card main-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-list me-2"></i>
                        Lista de Chatbots
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tablaChatbot">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="30%">Nombre del Chatbot</th>
                                    <th width="15%">Estado</th>
                                    <th width="20%">Fecha de Creación</th>
                                    <th width="15%">Nodos</th>
                                    <th width="15%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chatbot Detail View -->
        <div id="chatbotDetailView" class="d-none">
            <div class="row g-4">
                <div class="col-12">
                    <div class="card main-card">
                        <div class="card-header-custom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">
                                    <i class="fas fa-eye me-2"></i>
                                    <span id="detailTitle">Detalle del Chatbot</span>
                                </h5>
                                <div>
                                    <button type="button" class="btn btn-outline-primary me-2" onclick="testChatbot()">
                                        <i class="fas fa-play me-2"></i>
                                        Probar Flujo
                                    </button>
                                    <button type="button" class="btn btn-warning me-2" id="toggleChatbotBtn" onclick="toggleChatbot()">
                                        <i class="fas fa-power-off me-2"></i>
                                        Activar
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="editChatbot()">
                                        <i class="fas fa-edit me-2"></i>
                                        Editar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="flow-diagram" id="flowDiagram">
                                <!-- Flow diagram will be rendered here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @component('chatbots.modals.test')
    @endcomponent
@endsection

@section('scripts')
    <script src="{{ mix('/js/chatbots/principal.js') }}" ></script>
@endsection
