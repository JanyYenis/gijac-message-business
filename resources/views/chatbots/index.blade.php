@extends('layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ mix('/css/gmb.css') }}">
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h1 class="text-white">
                    <i class="fas fa-robot"></i>
                    Centro de Chatbots
                </h1>
                <p class="subtitle mb-0">Administra y configura los diferentes tipos de automatización
                    disponibles para tu negocio.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="badge-available">
                    <i class="bi bi-check2-circle me-1 text-white"></i>3 tipos disponibles
                </span>
            </div>
        </div>
    </div>
    <!-- ====== ESTADÍSTICAS RÁPIDAS ====== -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-robot fs-1"></i>
                </div>
                <div>
                    <p class="stat-title fs-3">Chatbots Activos</p>
                    <p class="stat-value fs-3">5</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-cpu fs-1"></i>
                </div>
                <div>
                    <p class="stat-title fs-3">Asistentes IA</p>
                    <p class="stat-value fs-3">2</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-diagram-3 fs-1"></i>
                </div>
                <div>
                    <p class="stat-title fs-3">Integraciones n8n</p>
                    <p class="stat-value fs-3">1</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-chat-dots fs-1"></i>
                </div>
                <div>
                    <p class="stat-title fs-3">Conversaciones Automatizadas</p>
                    <p class="stat-value fs-3">12,540</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== SECCIÓN PRINCIPAL ====== -->
    <div class="mb-3">
        <h2 class="section-title fs-1">Tipos de Automatización</h2>
        <p class="section-subtitle fs-3">Selecciona y configura el chatbot que mejor se adapte a tu negocio.</p>
    </div>

    <div class="row g-4 mb-5">
        <!-- TARJETA 1 -->
        <div class="col-12 col-lg-4">
            <div class="bot-card">
                <div class="bot-icon">🌳</div>
                <h3 class="fs-1">Chatbot por Nodos</h3>
                <p class="subtitle fs-3">Automatización visual basada en flujos.</p>
                <p class="desc fs-6">
                    Crea experiencias conversacionales mediante nodos conectados entre sí.
                </p>
                <ul class="feature-list">
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Mensajes de texto</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Botones</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Listas</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Condiciones</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Archivos</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Imágenes</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Videos</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Formularios futuros</li>
                </ul>
                <div class="status-row">
                    <span class="status-label fs-6">
                        Estado: <span class="status-value text-success fs-6">Activo</span>
                    </span>
                    <span class="badge-soft-green fs-6">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        Configurado
                    </span>
                </div>
                <div class="card-actions">
                    <a href="{{ route('chatbots.nodos.index') }}" class="btn btn-wa fs-4">
                        <i class="bi bi-sliders mb-1 text-white fs-4"></i>
                        Administrar Flujo
                    </a>
                    {{-- <button class="btn btn-wa-outline fs-4">
                        <i class="bi bi-chat-left-text fs-4"></i>
                        Ver Conversaciones
                    </button> --}}
                </div>
            </div>
        </div>

        <!-- TARJETA 2 -->
        <div class="col-12 col-lg-4">
            <div class="bot-card">
                <div class="bot-icon">🧠</div>
                <h3 class="fs-1">Chatbot con Inteligencia Artificial</h3>
                <p class="subtitle fs-3">Asistente entrenado con IA.</p>
                <p class="desc fs-6">
                    Configura un chatbot impulsado por OpenRouter u Ollama.
                </p>
                <ul class="feature-list">
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Prompt personalizado</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Carga de PDF</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Memoria contextual</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Respuestas inteligentes</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Botones automáticos</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Listas automáticas</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Escalamiento a agente</li>
                </ul>
                <div class="status-row">
                    <span class="status-label fs-6">
                        Estado:
                        <span class="status-value text-warning fs-6">
                            No configurado
                        </span>
                    </span>
                    <span class="badge-soft-yellow fs-6">
                        <i class="bi bi-exclamation-circle-fill me-1"></i>
                        Pendiente
                    </span>
                </div>
                <div class="card-actions">
                    <a href="{{ route('chatbots.asistente.index') }}" class="btn btn-wa fs-4">
                        <i class="bi bi-gear text-white fs-4"></i>
                        Configurar IA
                    </a>
                    {{-- <a href="asistente-ia.html" class="btn btn-wa-outline fs-4">
                        <i class="bi bi-eye fs-4"></i>
                        Ver Configuración
                    </a> --}}
                </div>
            </div>
        </div>

        <!-- TARJETA 3 -->
        <div class="col-12 col-lg-4">
            <div class="bot-card">
                <div class="bot-icon">🔗</div>
                <h3 class="fs-1">Webhook n8n</h3>
                <p class="subtitle fs-3">Automatizaciones externas avanzadas.</p>
                <p class="desc fs-6">
                    Conecta tu instancia de n8n mediante un webhook para ejecutar procesos personalizados.
                </p>
                <ul class="feature-list">
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Automatizaciones ilimitadas</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Integración con CRM</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>IA externa</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Correos</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>APIs</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Bases de datos</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>Flujos avanzados</li>
                </ul>
                <div class="status-row">
                    <span class="status-label fs-6">
                        Estado:
                        <span class="status-value text-success fs-6">
                            Configurado
                        </span>
                    </span>
                    <span class="badge-soft-green fs-6">
                        <i class="bi bi-plug-fill me-1"></i>
                        Conectado
                    </span>
                </div>
                <div class="card-actions">
                    <a href="{{ route('chatbots.n8n.index') }}" class="btn btn-wa fs-4">
                        <i class="bi bi-gear text-white fs-4"></i>
                        Configurar Webhook
                    </a>
                    {{-- <a href="webhook-n8n.html" class="btn btn-wa-outline fs-4">
                        <i class="bi bi-download fs-4"></i>
                        Descargar Plantilla n8n
                    </a> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- ====== SECCIÓN INFERIOR: TIMELINE ====== -->
    <div class="timeline-card">
        <div class="mb-4">
            <h2 class="section-title fs-1">¿Cómo funciona el flujo?</h2>
            <p class="section-subtitle fs-3">Recorrido general de un mensaje dentro del sistema.</p>
        </div>
        <div class="flow">
            <div class="flow-step">
                <div class="flow-icon">
                    <i class="bi bi-whatsapp fs-2"></i>
                </div>
                <p class="fs-6">Cliente escribe por WhatsApp</p>
            </div>
            <div class="flow-arrow">
                <i class="bi bi-arrow-right-short fs-2"></i>
            </div>
            <div class="flow-step">
                <div class="flow-icon">
                    <i class="bi bi-inbox fs-2"></i>
                </div>
                <p class="fs-6">Sistema recibe mensaje</p>
            </div>
            <div class="flow-arrow">
                <i class="bi bi-arrow-right-short fs-2"></i>
            </div>
            <div class="flow-step">
                <div class="flow-icon">
                    <i class="bi bi-ui-checks fs-2"></i>
                </div>
                <p class="fs-6">Seleccionar chatbot activo</p>
            </div>
            <div class="flow-arrow">
                <i class="bi bi-arrow-right-short fs-2"></i>
            </div>
            <div class="flow-step">
                <div class="flow-icon">
                    <i class="bi bi-lightning-charge fs-2"></i>
                </div>
                <p class="fs-6">Ejecutar flujo</p>
            </div>
            <div class="flow-arrow">
                <i class="bi bi-arrow-right-short fs-2"></i>
            </div>
            <div class="flow-step">
                <div class="flow-icon">
                    <i class="fas fa-reply fs-2"></i>
                </div>
                <p class="fs-6">Responder al cliente</p>
            </div>
            <div class="flow-arrow">
                <i class="bi bi-arrow-right-short fs-2"></i>
            </div>
            <div class="flow-step">
                <div class="flow-icon">
                    <i class="fas fa-database fs-2"></i>
                </div>
                <p class="fs-6">Guardar conversación</p>
            </div>
        </div>
    </div>
@endsection

@section('modal')
@endsection

@section('scripts')
    <script src="{{ mix('/js/chatbots/principal.js') }}" ></script>
@endsection
