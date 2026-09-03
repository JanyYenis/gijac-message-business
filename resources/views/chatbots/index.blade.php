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
                    <i class="fas fa-robot"></i>{{ __('Centro de Chatbots') }}</h1>
                <p class="subtitle mb-0">{{ __('Administra y configura los diferentes tipos de automatización
                    disponibles para tu negocio.') }</p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="badge-available me-1">
                    <i class="bi bi-check2-circle me-1 text-white"></i>{{ __('3 tipos disponibles') }}</span>
                <a type="button" class="btn btn-light-wa" href="{{ route('chatbots.configuracion.index') }}">
                    <i class="bi bi-gear text-primary fs-1"></i>{{ __('Configuración') }}</a>
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
                    <p class="stat-title fs-3">{{ __('Chatbots Activos') }}</p>
                    <p class="stat-value fs-3">{{ $chatbot_nodo ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-cpu fs-1"></i>
                </div>
                <div>
                    <p class="stat-title fs-3">{{ __('Asistentes IA') }}</p>
                    <p class="stat-value fs-3">{{ $chatbot_ia ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-diagram-3 fs-1"></i>
                </div>
                <div>
                    <p class="stat-title fs-3">{{ __('Integraciones n8n') }}</p>
                    <p class="stat-value fs-3">{{ $chatbot_n8n ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-chat-dots fs-1"></i>
                </div>
                <div>
                    <p class="stat-title fs-3">{{ __('Conversaciones Automatizadas') }}</p>
                    <p class="stat-value fs-3">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== SECCIÓN PRINCIPAL ====== -->
    <div class="mb-3">
        <h2 class="section-title fs-1">{{ __('Tipos de Automatización') }}</h2>
        <p class="section-subtitle fs-3">{{ __('Selecciona y configura el chatbot que mejor se adapte a tu negocio.') }}</p>
    </div>

    <div class="row g-4 mb-5">
        <!-- TARJETA 1 -->
        <div class="col-12 col-lg-4">
            <div class="bot-card">
                <div class="bot-icon">🌳</div>
                <h3 class="fs-1">{{ __('Chatbot por Nodos') }}</h3>
                <p class="subtitle fs-3">{{ __('Automatización visual basada en flujos.') }}</p>
                <p class="desc fs-6">{{ __('Crea experiencias conversacionales mediante nodos conectados entre sí.') }}</p>
                <ul class="feature-list">
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Mensajes de texto') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Botones') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Listas') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Condiciones') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Archivos') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Imágenes') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Videos') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Formularios futuros') }}</li>
                </ul>
                <div class="status-row">
                    <span class="status-label fs-6">
                        Estado:
                        @if ($chatbot_nodo > 0)
                            <span class="status-value text-success fs-6">{{ __('Activo') }}</span>
                        @else
                            <span class="status-value text-warning fs-6">{{ __('No configurado') }}</span>
                        @endif
                    </span>
                    @if ($chatbot_nodo > 0)
                        <span class="badge-soft-green fs-6">
                            <i class="bi bi-check-circle-fill me-1"></i>{{ __('Configurado') }}</span>
                    @else
                        <span class="badge-soft-yellow fs-6">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>{{ __('Pendiente') }}</span>
                    @endif
                </div>
                <div class="card-actions">
                    <a href="{{ route('chatbots.nodos.index') }}" class="btn btn-wa fs-4">
                        <i class="bi bi-sliders mb-1 text-white fs-4"></i>{{ __('Administrar Flujo') }}</a>
                    {{-- <button class="btn btn-wa-outline fs-4">
                        <i class="bi bi-chat-left-text fs-4"></i>{{ __('Ver Conversaciones') }}</button> --}}
                </div>
            </div>
        </div>

        <!-- TARJETA 2 -->
        <div class="col-12 col-lg-4">
            <div class="bot-card">
                <div class="bot-icon">🧠</div>
                <h3 class="fs-1">{{ __('Chatbot con Inteligencia Artificial') }}</h3>
                <p class="subtitle fs-3">{{ __('Asistente entrenado con IA.') }}</p>
                <p class="desc fs-6">{{ __('Configura un chatbot impulsado por OpenRouter u Ollama.') }}</p>
                <ul class="feature-list">
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Prompt personalizado') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Carga de PDF') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Memoria contextual') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Respuestas inteligentes') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Botones automáticos') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Listas automáticas') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Escalamiento a agente') }}</li>
                </ul>
                <div class="status-row">
                    <span class="status-label fs-6">
                        Estado:
                        @if ($chatbot_ia > 0)
                            <span class="status-value text-success fs-6">{{ __('Activo') }}</span>
                        @else
                            <span class="status-value text-warning fs-6">{{ __('No configurado') }}</span>
                        @endif
                    </span>
                    @if ($chatbot_ia > 0)
                        <span class="badge-soft-green fs-6">
                            <i class="bi bi-check-circle-fill me-1"></i>{{ __('Configurado') }}</span>
                    @else
                        <span class="badge-soft-yellow fs-6">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>{{ __('Pendiente') }}</span>
                    @endif
                </div>
                <div class="card-actions">
                    <a href="{{ route('chatbots.asistente.index') }}" class="btn btn-wa fs-4">
                        <i class="bi bi-gear text-white fs-4"></i>{{ __('Configurar IA') }}</a>
                    {{-- <a href="asistente-ia.html" class="btn btn-wa-outline fs-4">
                        <i class="bi bi-eye fs-4"></i>{{ __('Ver Configuración') }}</a> --}}
                </div>
            </div>
        </div>

        <!-- TARJETA 3 -->
        <div class="col-12 col-lg-4">
            <div class="bot-card">
                <div class="bot-icon">🔗</div>
                <h3 class="fs-1">{{ __('Webhook n8n') }}</h3>
                <p class="subtitle fs-3">{{ __('Automatizaciones externas avanzadas.') }}</p>
                <p class="desc fs-6">{{ __('Conecta tu instancia de n8n mediante un webhook para ejecutar procesos personalizados.') }}</p>
                <ul class="feature-list">
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Automatizaciones ilimitadas') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Integración con CRM') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('IA externa') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Correos') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('APIs') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Bases de datos') }}</li>
                    <li class="fs-7"><i class="bi bi-check-lg"></i>{{ __('Flujos avanzados') }}</li>
                </ul>
                <div class="status-row">
                    <span class="status-label fs-6">
                        Estado:
                        @if ($chatbot_n8n > 0)
                            <span class="status-value text-success fs-6">{{ __('Configurado') }}</span>
                        @else
                            <span class="status-value text-warning fs-6">{{ __('No configurado') }}</span>
                        @endif
                    </span>
                    @if ($chatbot_n8n > 0)
                        <span class="badge-soft-green fs-6">
                            <i class="bi bi-check-circle-fill me-1"></i>{{ __('Configurado') }}</span>
                    @else
                        <span class="badge-soft-yellow fs-6">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>{{ __('Pendiente') }}</span>
                    @endif
                </div>
                <div class="card-actions">
                    <a href="{{ route('chatbots.n8n.index') }}" class="btn btn-wa fs-4">
                        <i class="bi bi-gear text-white fs-4"></i>{{ __('Configurar Webhook') }}</a>
                    {{-- <a href="webhook-n8n.html" class="btn btn-wa-outline fs-4">
                        <i class="bi bi-download fs-4"></i>{{ __('Descargar Plantilla n8n') }}</a> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- ====== SECCIÓN INFERIOR: TIMELINE ====== -->
    <div class="timeline-card">
        <div class="mb-4">
            <h2 class="section-title fs-1">{{ __('¿Cómo funciona el flujo?') }}</h2>
            <p class="section-subtitle fs-3">{{ __('Recorrido general de un mensaje dentro del sistema.') }}</p>
        </div>
        <div class="flow">
            <div class="flow-step">
                <div class="flow-icon">
                    <i class="bi bi-whatsapp fs-2"></i>
                </div>
                <p class="fs-6">{{ __('Cliente escribe por WhatsApp') }}</p>
            </div>
            <div class="flow-arrow">
                <i class="bi bi-arrow-right-short fs-2"></i>
            </div>
            <div class="flow-step">
                <div class="flow-icon">
                    <i class="bi bi-inbox fs-2"></i>
                </div>
                <p class="fs-6">{{ __('Sistema recibe mensaje') }}</p>
            </div>
            <div class="flow-arrow">
                <i class="bi bi-arrow-right-short fs-2"></i>
            </div>
            <div class="flow-step">
                <div class="flow-icon">
                    <i class="bi bi-ui-checks fs-2"></i>
                </div>
                <p class="fs-6">{{ __('Seleccionar chatbot activo') }}</p>
            </div>
            <div class="flow-arrow">
                <i class="bi bi-arrow-right-short fs-2"></i>
            </div>
            <div class="flow-step">
                <div class="flow-icon">
                    <i class="bi bi-lightning-charge fs-2"></i>
                </div>
                <p class="fs-6">{{ __('Ejecutar flujo') }}</p>
            </div>
            <div class="flow-arrow">
                <i class="bi bi-arrow-right-short fs-2"></i>
            </div>
            <div class="flow-step">
                <div class="flow-icon">
                    <i class="fas fa-reply fs-2"></i>
                </div>
                <p class="fs-6">{{ __('Responder al cliente') }}</p>
            </div>
            <div class="flow-arrow">
                <i class="bi bi-arrow-right-short fs-2"></i>
            </div>
            <div class="flow-step">
                <div class="flow-icon">
                    <i class="fas fa-database fs-2"></i>
                </div>
                <p class="fs-6">{{ __('Guardar conversación') }}</p>
            </div>
        </div>
    </div>
@endsection

@section('modal')
@endsection

@section('scripts')
    <script src="{{ mix('/js/chatbots/principal.js') }}" ></script>
@endsection
