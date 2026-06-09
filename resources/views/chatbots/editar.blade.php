@extends('layouts.index')

@section('css')
    <style>
        :root {
            --primary-color: #25D366;
            --secondary-color: #128C7E;
        }

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

        /* Status Badges */
        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-active {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-inactive {
            background-color: rgba(107, 114, 128, 0.1);
            color: #6B7280;
            border: 1px solid rgba(107, 114, 128, 0.2);
        }

        /* WhatsApp Preview */
        .whatsapp-preview {
            background: var(--whatsapp-bg);
            border-radius: 12px;
            height: 600px;
            overflow-y: auto;
            padding: 1rem;
            position: sticky;
            top: 2rem;
        }

        .whatsapp-header {
            background: var(--accent-color);
            color: white;
            padding: 1rem;
            border-radius: 12px 12px 0 0;
            margin: -1rem -1rem 1rem -1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .whatsapp-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .whatsapp-message {
            max-width: 80%;
            margin-bottom: 1rem;
            animation: fadeInUp 0.3s ease;
        }

        .whatsapp-message.sent {
            margin-left: auto;
            background: var(--whatsapp-sent);
            border-radius: 18px 18px 4px 18px;
        }

        .whatsapp-message.received {
            margin-right: auto;
            background: var(--whatsapp-received);
            border-radius: 18px 18px 18px 4px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .message-content {
            padding: 0.75rem 1rem;
            word-wrap: break-word;
        }

        .message-buttons {
            padding: 0.5rem;
            border-top: 1px solid #E5E7EB;
        }

        .message-button {
            display: block;
            width: 100%;
            background: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            padding: 0.5rem;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .message-button:hover {
            background: var(--primary-color);
            color: white;
        }

        .message-time {
            font-size: 0.75rem;
            color: #6B7280;
            text-align: right;
            margin-top: 0.25rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .flow-builder {
                padding: 1rem;
            }

            .whatsapp-preview {
                height: 400px;
                position: static;
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6B7280;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Loading */
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
            to { transform: rotate(360deg); }
        }

        /* Button Styles */
        .btn-create {
            background: linear-gradient(135deg, var(--primary-color), var(--success-color));
            border: none;
            color: white;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-create:hover {
            background: linear-gradient(135deg, #1e7e34, #17a2b8);
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-hover);
            color: white;
        }

        /* Card Styles */
        .main-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            border: none;
            overflow: hidden;
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
                <p class="subtitle mb-0">Diseña el flujo de conversación de tu chatbot</p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('chatbots.index') }}" type="button" class="btn btn-new-template">
                    Volver
                </a>
            </div>
        </div>
    </div>
    <div class="container-fluid main-container">
        <!-- Chatbot Builder View -->
        <div id="chatbotBuilderView">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card main-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">
                                    <i class="fas fa-project-diagram me-2"></i>
                                    <span id="builderTitle">Constructor de Flujo</span>
                                </h5>
                                <div>
                                    <button type="button" class="btn btn-outline btn-outline-dashed btn-outline-secondary btn-active-light-secondary me-2" data-bs-toggle="modal" data-bs-target="#addNodeModal">
                                        <i class="fas fa-plus fs-2 me-2"></i>
                                        Agregar Nodo
                                    </button>
                                    <button type="button" class="btn btn-primary saveChatbot">
                                        <i class="fas fa-save me-2"></i>
                                        Guardar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="flow-builder" id="flowBuilder">
                                <!-- Flow nodes will be added here -->
                                <div class="empty-state" id="builderEmptyState">
                                    <i class="fas fa-project-diagram"></i>
                                    <h5>Flujo vacío</h5>
                                    <p class="mb-3">Comienza agregando tu primer nodo de conversación</p>
                                    <button type="button" class="btn btn-create" data-bs-toggle="modal" data-bs-target="#addNodeModal">
                                        <i class="fas fa-plus me-2"></i>
                                        Agregar Primer Nodo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card main-card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fab fa-whatsapp me-2"></i>
                                Vista Previa
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="whatsapp-preview" id="whatsappPreview">
                                <div class="whatsapp-header">
                                    <div class="whatsapp-avatar">
                                        <i class="fas fa-robot"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">Mi Chatbot</div>
                                        <small class="opacity-75">En línea</small>
                                    </div>
                                </div>
                                <div id="previewMessages">
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-comment-dots fa-2x mb-2"></i>
                                        <p>La vista previa aparecerá aquí</p>
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
    @component('chatbots.modals.crear')
    @endcomponent
    @component('chatbots.modals.editar')
    @endcomponent
@endsection

@section('scripts')
    <script>window.id_chatbot = '{{ $chatbot->id }}'</script>
    <script>window.name_chatbot = '{{ $chatbot->name }}'</script>
    <script src="{{ mix('/js/chatbots/editar.js') }}" ></script>
@endsection
