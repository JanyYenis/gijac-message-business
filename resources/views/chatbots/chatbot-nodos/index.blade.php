@extends('layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ mix('/css/gmb.css') }}">
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <a href="{{ route('chatbots.index') }}" class="btn btn-light-soft btn-sm">
                        <i class="bi bi-arrow-left text-white"></i>
                    </a>
                    <div class="ms-3">
                        <h1 class="text-white">
                            <i class="bi bi-diagram-3 text-white fs-1"></i>
                            Constructor de Chatbots
                        </h1>
                        <p class="subtitle mb-0">Diseña flujos conversacionales para WhatsApp conectando nodos visualmente.</p>
                    </div>
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                <button class="btn btn-light-soft d-none" id="btnImportTop">
                    <i class="bi bi-upload text-white"></i>
                    Importar Flujo
                </button>
                {{-- <button class="btn btn-white" data-bs-toggle="modal" data-bs-target="#modalNuevoFlujo">
                    <i class="bi bi-plus-lg text-primary"></i>
                    Nuevo Flujo
                </button> --}}
            </div>
        </div>
    </div>

    <!-- ====== ESTADÍSTICAS ====== -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-diagram-2 fs-1"></i>
                </div>
                <div>
                    <p class="stat-title fs-4">Flujos Activos</p>
                    <p class="stat-value fs-5">{{ $total_flujos ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-bounding-box-circles fs-1"></i>
                </div>
                <div>
                    <p class="stat-title fs-4">Total de Nodos</p>
                    <p class="stat-value fs-5" id="statNodos">0</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-chat-dots fs-1"></i>
                </div>
                <div>
                    <p class="stat-title fs-4">Conversaciones Atendidas</p>
                    <p class="stat-value fs-5">0</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="las la-rocket fs-2x"></i>
                </div>
                <div>
                    <p class="stat-title fs-4">Última Publicación</p>
                    <p class="stat-value fs-5">{{ $ultimo_flujo?->fecha_publicado->formatLocalized('%d de %B del %Y a las %H:%M') ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== CONSTRUCTOR (3 columnas) ====== -->
    <div class="builder-card mb-4">
        <div class="builder-grid">
            <!-- ===== COLUMNA IZQUIERDA: Biblioteca ===== -->
            <div class="col-panel">
                <div class="panel-head">
                    <h6 class="fs-4">
                        <i class="bi bi-grid-1x2 text-dark me-1"></i>
                        Biblioteca de Nodos
                    </h6>
                </div>
                <div class="panel-body">
                    <div class="node-search input-group input-group-sm">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="nodeSearch" class="form-control" placeholder="Buscar nodo..." />
                    </div>

                    <!-- MENSAJES -->
                    <button class="cat-toggle" data-target="cat-mensajes">
                        <span>
                            <i class="bi bi-chat-square-text text-dark me-1"></i>
                            Mensajes
                        </span>
                        <i class="bi bi-chevron-down chev"></i>
                    </button>
                    <div class="cat-items" id="cat-mensajes">
                        <div class="drag-node" draggable="true" data-node="text">
                            <span class="dn-icon dn-text">
                                <i class="bi bi-chat-left-text"></i>
                            </span>
                            Nodo Texto
                        </div>
                        <div class="drag-node" draggable="true" data-node="image">
                            <span class="dn-icon dn-image">
                                <i class="bi bi-image"></i>
                            </span>
                            Nodo Imagen
                        </div>
                        <div class="drag-node" draggable="true" data-node="video">
                            <span class="dn-icon dn-video">
                                <i class="bi bi-camera-video"></i>
                            </span>
                            Nodo Video
                        </div>
                        <div class="drag-node" draggable="true" data-node="doc">
                            <span class="dn-icon dn-doc">
                                <i class="bi bi-file-earmark-text"></i>
                            </span>
                            Nodo Documento
                        </div>
                        {{-- <div class="drag-node" draggable="true" data-node="audio">
                            <span class="dn-icon dn-audio">
                                <i class="bi bi-mic"></i>
                            </span>
                            Nodo Audio
                        </div> --}}
                    </div>

                    <!-- INTERACCIÓN -->
                    <button class="cat-toggle" data-target="cat-interaccion">
                        <span>
                            <i class="bi bi-ui-radios text-dark me-1"></i>
                            Interacción
                        </span>
                        <i class="bi bi-chevron-down chev"></i>
                    </button>
                    <div class="cat-items" id="cat-interaccion">
                        <div class="drag-node" draggable="true" data-node="buttons"><span
                                class="dn-icon dn-buttons">
                                <i class="bi bi-ui-checks-grid"></i>
                            </span>
                            Nodo Botones
                        </div>
                        <div class="drag-node" draggable="true" data-node="list">
                            <span class="dn-icon dn-list">
                                <i class="bi bi-list-ul"></i>
                            </span>
                            Nodo Lista
                        </div>
                        {{-- <div class="drag-node" draggable="true" data-node="question">
                            <span class="dn-icon dn-question">
                                <i class="bi bi-question-circle"></i>
                            </span>
                            Nodo Pregunta
                        </div>
                        <div class="drag-node" draggable="true" data-node="capture">
                            <span class="dn-icon dn-capture">
                                <i class="bi bi-input-cursor-text"></i>
                            </span>
                            Capturar Respuesta
                        </div> --}}
                    </div>

                    <!-- LÓGICA -->
                    {{-- <button class="cat-toggle" data-target="cat-logica">
                        <span>
                            <i class="bi bi-diagram-3 text-dark me-1"></i>
                            Lógica
                        </span>
                        <i class="bi bi-chevron-down chev"></i>
                    </button>
                    <div class="cat-items" id="cat-logica">
                        <div class="drag-node" draggable="true" data-node="condition">
                            <span class="dn-icon dn-condition">
                                <i class="bi bi-signpost-split"></i>
                            </span>
                            Nodo Condición
                        </div>
                        <div class="drag-node" draggable="true" data-node="variable">
                            <span class="dn-icon dn-variable">
                                <i class="bi bi-braces"></i>
                            </span>
                            Nodo Variable
                        </div>
                        <div class="drag-node" draggable="true" data-node="tag">
                            <span class="dn-icon dn-tag">
                                <i class="bi bi-tag"></i>
                            </span>
                            Nodo Etiqueta
                        </div>
                        <div class="drag-node" draggable="true" data-node="goto">
                            <span class="dn-icon dn-goto">
                                <i class="bi bi-arrow-return-right"></i>
                            </span>
                            Nodo Ir a Nodo
                        </div>
                    </div> --}}

                    <!-- ACCIONES -->
                    <button class="cat-toggle" data-target="cat-acciones">
                        <span>
                            <i class="bi bi-lightning-charge text-dark me-1"></i>
                            Acciones
                        </span>
                        <i class="bi bi-chevron-down chev"></i>
                    </button>
                    <div class="cat-items" id="cat-acciones">
                        {{-- <div class="drag-node" draggable="true" data-node="webhook">
                            <span class="dn-icon dn-webhook">
                                <i class="bi bi-hdd-network"></i>
                            </span>
                            Nodo Webhook
                        </div>
                        <div class="drag-node" draggable="true" data-node="api">
                            <span class="dn-icon dn-api">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </span>
                            Nodo API
                        </div>
                        <div class="drag-node" draggable="true" data-node="agent">
                            <span class="dn-icon dn-agent">
                                <i class="bi bi-person-badge"></i>
                            </span>
                            Asignar Agente
                        </div> --}}
                        <div class="drag-node" draggable="true" data-node="end">
                            <span class="dn-icon dn-end">
                                <i class="bi bi-flag"></i>
                            </span>
                            Finalizar Conversación
                        </div>
                    </div>

                    <!-- IA -->
                    {{-- <button class="cat-toggle" data-target="cat-ia">
                        <span>
                            <i class="bi bi-cpu text-dark me-1"></i>
                            Inteligencia Artificial
                        </span>
                        <i class="bi bi-chevron-down chev"></i>
                    </button>
                    <div class="cat-items" id="cat-ia">
                        <div class="drag-node" draggable="true" data-node="ai">
                            <span class="dn-icon dn-ai">
                                <i class="fas fa-robot"></i>
                            </span>
                            Nodo IA
                        </div>
                        <div class="drag-node" draggable="true" data-node="pdf">
                            <span class="dn-icon dn-pdf">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </span>
                            Consulta PDF
                        </div>
                        <div class="drag-node" draggable="true" data-node="generate">
                            <span class="dn-icon dn-generate">
                                <i class="bi bi-stars"></i>
                            </span>
                            Generar Respuesta
                        </div>
                    </div> --}}
                </div>
            </div>

            <!-- ===== COLUMNA CENTRAL: Canvas ===== -->
            <div class="col-panel" style="border-right:1px solid var(--border-soft);">
                <div class="canvas-toolbar">
                    <button class="btn btn-sm btn-wa-save btn-toolbar" id="btnPublicar"
                        style="background:var(--wa-green); color:#fff; border-color:var(--wa-green);">
                        <i class="bi bi-save text-white me-1"></i>
                        Guardar / Publicar
                    </button>
                    {{-- <button class="btn btn-sm btn-wa-save btn-toolbar" id="btnGuardar"
                        style="background:var(--wa-green); color:#fff; border-color:var(--wa-green);">
                        <i class="bi bi-save text-white me-1"></i>
                        Guardar
                    </button>
                    <button class="btn btn-sm btn-toolbar" id="btnPublicar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-rocket-takeoff me-1" viewBox="0 0 16 16">
                            <path d="M9.752 6.193c.599.6 1.73.437 2.528-.362s.96-1.932.362-2.531c-.599-.6-1.73-.438-2.528.361-.798.8-.96 1.933-.362 2.532"/>
                            <path d="M15.811 3.312c-.363 1.534-1.334 3.626-3.64 6.218l-.24 2.408a2.56 2.56 0 0 1-.732 1.526L8.817 15.85a.51.51 0 0 1-.867-.434l.27-1.899c.04-.28-.013-.593-.131-.956a9 9 0 0 0-.249-.657l-.082-.202c-.815-.197-1.578-.662-2.191-1.277-.614-.615-1.079-1.379-1.275-2.195l-.203-.083a10 10 0 0 0-.655-.248c-.363-.119-.675-.172-.955-.132l-1.896.27A.51.51 0 0 1 .15 7.17l2.382-2.386c.41-.41.947-.67 1.524-.734h.006l2.4-.238C9.005 1.55 11.087.582 12.623.208c.89-.217 1.59-.232 2.08-.188.244.023.435.06.57.093q.1.026.16.045c.184.06.279.13.351.295l.029.073a3.5 3.5 0 0 1 .157.721c.055.485.051 1.178-.159 2.065m-4.828 7.475.04-.04-.107 1.081a1.54 1.54 0 0 1-.44.913l-1.298 1.3.054-.38c.072-.506-.034-.993-.172-1.418a9 9 0 0 0-.164-.45c.738-.065 1.462-.38 2.087-1.006M5.205 5c-.625.626-.94 1.351-1.004 2.09a9 9 0 0 0-.45-.164c-.424-.138-.91-.244-1.416-.172l-.38.054 1.3-1.3c.245-.246.566-.401.91-.44l1.08-.107zm9.406-3.961c-.38-.034-.967-.027-1.746.163-1.558.38-3.917 1.496-6.937 4.521-.62.62-.799 1.34-.687 2.051.107.676.483 1.362 1.048 1.928.564.565 1.25.941 1.924 1.049.71.112 1.429-.067 2.048-.688 3.079-3.083 4.192-5.444 4.556-6.987.183-.771.18-1.345.138-1.713a3 3 0 0 0-.045-.283 3 3 0 0 0-.3-.041Z"/>
                            <path d="M7.009 12.139a7.6 7.6 0 0 1-1.804-1.352A7.6 7.6 0 0 1 3.794 8.86c-1.102.992-1.965 5.054-1.839 5.18.125.126 3.936-.896 5.054-1.902Z"/>
                        </svg>
                        Publicar
                    </button> --}}
                    <button class="btn btn-sm btn-toolbar d-none" data-bs-toggle="modal" data-bs-target="#modalProbar">
                        <i class="bi bi-play-circle text-dark me-1"></i>
                        Probar Flujo
                    </button>
                    <button class="btn btn-sm btn-toolbar d-none" id="btnDuplicar">
                        <i class="bi bi-files text-dark me-1"></i>
                        Duplicar
                    </button>
                    <button class="btn btn-sm btn-toolbar" id="btnExportar">
                        <i class="fas fa-file me-1"></i>
                        Exportar JSON
                    </button>
                    <button class="btn btn-sm btn-toolbar" id="btnHistorial">
                        <i class="bi bi-clock-history text-dark me-1"></i>
                        Historial
                    </button>
                    <div class="toolbar-spacer"></div>
                    <div class="btn-group btn-group-sm zoom-group" role="group">
                        <button class="btn btn-toolbar" id="btnZoomOut">
                            <i class="bi bi-zoom-out"></i>
                        </button>
                        <button class="btn btn-toolbar" id="btnZoomReset">100%</button>
                        <button class="btn btn-toolbar" id="btnZoomIn">
                            <i class="bi bi-zoom-in"></i>
                        </button>
                    </div>
                </div>
                <div id="drawflow"></div>
            </div>

            <!-- ===== COLUMNA DERECHA: Propiedades ===== -->
            <div class="col-panel right">
                <div class="panel-head">
                    <h6 class="fs-4">
                        <i class="bi bi-sliders text-dark me-1"></i>
                        Propiedades
                    </h6>
                    <span class="badge bg-light text-dark" id="propBadge">—</span>
                </div>
                <div class="panel-body" id="propsPanel">
                    <div class="props-empty">
                        <i class="bi bi-hand-index-thumb"></i>
                        Selecciona un nodo para editar su configuración
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== SECCIÓN INFERIOR: Tabla versiones ====== -->
    <div class="table-card">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h2 class="section-title fs-1" style="font-weight:700; margin:0;">
                    Versiones del Flujo
                </h2>
                <p class="fs-4" style="color:var(--text-muted); margin:2px 0 0;">
                    Historial de cambios y publicaciones del flujo actual.
                </p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table" id="tablaVersiones">
                <thead>
                    <tr>
                        <th width="5%" class="text-center all">Versión</th>
                        <th width="10%" class="text-center all">Fecha</th>
                        <th width="10%" class="text-center all">Usuario</th>
                        <th width="10%" class="text-center all">Estado</th>
                        <th width="10%" class="text-center all">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('modal')
    @component('chatbots.chatbot-nodos.modals.ver')
    @endcomponent
@endsection

@section('scripts')
    <script src="{{ mix('/js/chatbots/nodos/principal.js') }}" ></script>
@endsection
