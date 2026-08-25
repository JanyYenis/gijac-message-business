@php
    $automatizacion = $automatizacion ?? null;
@endphp
@extends('layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ mix('/css/gmb.css') }}">
    <style>
        .flow-preview-container {
            width: 100%;
            height: 420px;
            background: #f8f9fa;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }

        #drawflowPreview {
            width: 100%;
            height: 100%;
        }

        #drawflowPreview {
            background-color: #f8f9fa;
            background-image:
                radial-gradient(#d5d5d5 1px, transparent 1px);
            background-size: 20px 20px;
        }

        #drawflowPreview .input {
            width: 12px;
            height: 12px;
            background: #ffffff;
            border: 2px solid #20c997;
        }

        #drawflowPreview .output {
            width: 12px;
            height: 12px;
            background: #ffffff;
            border: 2px solid #20c997;
        }

        #drawflowPreview .connection .main-path {
            stroke: #20c997;
            stroke-width: 3px;
            fill: none;
        }

        .n8n-preview-node {
            width: 180px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.12);
            border: 1px solid #e5e7eb;
        }

        .n8n-preview-node-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px;
        }

        .n8n-preview-icon {
            font-size: 20px;
        }

        .n8n-preview-title {
            font-size: 13px;
            font-weight: 600;
            color: #212529;
        }

        .n8n-preview-type {
            padding: 7px 10px;
            font-size: 10px;
            color: #6c757d;
            background: #f8f9fa;
            border-top: 1px solid #eee;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .n8n-preview-node {
            width: 190px;
            min-height: 80px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.12);
            border: 1px solid #e5e7eb;
        }

        #drawflowPreview .connection .main-path {
            stroke: #198754 !important;
            stroke-width: 3px !important;
            fill: none !important;
        }

        #drawflowPreview .input,
        #drawflowPreview .output {
            width: 12px !important;
            height: 12px !important;
            border: 2px solid #198754 !important;
            background: #fff !important;
        }

        #drawflowPreview .drawflow-node {
            min-width: 190px;
        }
    </style>
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
                            <i class="bi bi-diagram-3-fill text-white fs-1"></i>
                            Automatizaciones con n8n
                        </h1>
                        <p class="subtitle mb-0">
                            Conecta tu instancia de n8n para crear flujos avanzados, integrar sistemas externos y
                            automatizar
                            procesos empresariales.
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="badge-status me-2">
                    @if ($automatizacion?->webhook_activo)
                        <i class="bi bi-plug-fill text-white"></i>
                    @endif
                    {{ $automatizacion?->webhook_activo ? 'Conectado' : 'Desconectado' }}
                </span>
                <button type="button" class="btn btn-light-wa" id="btnGuardar">
                    <i class="bi bi-save text-primary fs-1"></i>
                    Guardar
                </button>
            </div>
        </div>
    </div>

    <!-- STATS -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-ico" style="background:var(--wa-green)">
                    <i class="bi bi-link-45deg text-white fs-1"></i>
                </div>
                <h3>{{ $automatizacion ? 1 : 0 }}</h3>
                <small class="fs-6">Webhook Configurado</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-ico" style="background:var(--blue)">
                    <i class="bi bi-lightning-charge-fill text-white fs-1"></i>
                </div>
                <h3>{{ $total_ejecuciones ?? 0 }}</h3>
                <small class="fs-6">Automatizaciones Ejecutadas</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-ico" style="background:var(--wa-dark)">
                    <i class="bi bi-chat-dots-fill text-white fs-1"></i>
                </div>
                <h3>0</h3>
                <small class="fs-6">Mensajes Procesados</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-ico" style="background:var(--gray)">
                    <i class="bi bi-clock-history text-white fs-1"></i>
                </div>
                <h3>{{ $ultima_ejecucion?->fecha_ejecucion ? $ultima_ejecucion?->fecha_ejecucion->formatLocalized('%d de %B del %Y a las %H:%M') : 'N/A' }}</h3>
                <small class="fs-6">Última Ejecución</small>
            </div>
        </div>
    </div>

    <!-- MAIN -->
    <div class="row g-4">

        <!-- LEFT COLUMN 65% -->
        <div class="col-lg-8">

            <form id="formN8n">
                <!-- WEBHOOK CONFIG -->
                <div class="card-mod mb-4">
                    <div class="card-header fs-1">
                        <i class="bi bi-gear-fill text-primary fs-1"></i>
                        Configuración del Webhook
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required fs-6">Nombre de la Automatización</label>
                                <input type="text" class="form-control" value="{{ $automatizacion?->nombre ?? '' }}"
                                    name="nombre" required placeholder="Atención al Cliente IA">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-6">Método HTTP</label>
                                <select class="form-select" data-control="select2"
                                    data-placeholder="Seleccion el metodo HTTPS" data-allow-clear="true" required
                                    data-hide-search="true" required name="metodo_http">
                                    @foreach ($metodos as $item)
                                        <option value="{{ $item?->codigo }}"
                                            {{ $item?->codigo == $automatizacion?->metodo_http ? 'selected' : '' }}>
                                            {{ $item?->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label required fs-6">Descripción</label>
                                <textarea class="form-control" rows="2" required name="descripcion"
                                    placeholder="Procesa los mensajes entrantes de WhatsApp y los reenvía al flujo de n8n.">{{ $automatizacion?->descripcion ?? '' }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label required fs-6">URL Webhook n8n</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-link-45deg"></i>
                                    </span>
                                    <input type="text" class="form-control" required name="url_webhook"
                                        value="{{ $automatizacion?->url_webhook ?? '' }}"
                                        placeholder="https://n8n.miempresa.com/webhook/whatsapp-gijac">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fs-6">Token de Seguridad</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-shield-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="tokenInput"
                                        value="{{ $automatizacion?->token_seguridad ?? '' }}"
                                        placeholder="gjc_sk_8f3a91b27c" name="token_seguridad">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleToken">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" id="webhookActivo"
                                        {{ $automatizacion && !$automatizacion?->webhook_activo ? '' : 'checked' }}>
                                    <label class="form-check-label fs-6 fw-semibold" for="webhookActivo">Webhook
                                        Activo</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DATA SENT -->
                <div class="card-mod mb-4">
                    <div class="card-header fs-1">
                        <i class="bi bi-braces text-primary fs-1"></i>
                        Datos Enviados a n8n
                    </div>
                    <div class="card-body">
                        <p class="text-muted fs-6 small mb-2">
                            Estos son los datos que GIJAC MESSAGE BUSINESS enviará en cada evento:
                        </p>
                        <div class="json-viewer" id="jsonExample">
                            <button type="button" class="btn btn-sm btn-light btn-copy" id="btnCopyJson">
                                <i class="bi bi-clipboard me-1"></i>
                                Copiar Ejemplo
                            </button>
                            <span class="p">{</span><br>&nbsp;
                            <span class="k">"event"</span>
                            <span class="p">:</span>
                            <span class="s">"message.received"</span>
                            <span class="p">,</span><br>&nbsp;
                            <span class="k">"automation_id"</span>
                            <span class="p">:</span>
                            <span class="s">"GmB-123..."</span>
                            <span class="p">,</span><br>&nbsp;
                            <span class="k">"company_id"</span>
                            <span class="p">:</span>
                            <span class="s">"GmB-123..."</span>
                            <span class="p">,</span><br>&nbsp;
                            <span class="k">"contact"</span>
                            <span class="p">:</span>
                            <span class="p">{</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="k">"id"</span>
                            <span class="p">:</span>
                            <span class="s">"GmB-123..."</span>
                            <span class="p">,</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="k">"name"</span>
                            <span class="p">:</span>
                            <span class="s">"Juan Pérez"</span>
                            <span class="p">,</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="k">"phone"</span>
                            <span class="p">:</span>
                            <span class="s">"573001234567"</span><br>&nbsp;
                            <span class="p">}</span>
                            <span class="p">,</span><br>&nbsp;
                            <span class="k">"message"</span>
                            <span class="p">:</span>
                            <span class="p">{</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="k">"id"</span>
                            <span class="p">:</span>
                            <span class="s">"wamid.xxx"</span>
                            <span class="p">,</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="k">"type"</span>
                            <span class="p">:</span>
                            <span class="s">"text"</span>
                            <span class="p">,</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="k">"text"</span>
                            <span class="p">:</span>
                            <span class="s">"Hola, quiero saber el precio del producto X"</span>
                            <span class="p">,</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="k">"timestamp"</span>
                            <span class="p">:</span>
                            <span class="s">"{{ date('Y-m-d H:i:s') }}"</span><br>&nbsp;
                            <span class="p">}</span><br>&nbsp;
                            <span class="k">"channel"</span>
                            <span class="p">:</span>
                            <span class="p">{</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="k">"type"</span>
                            <span class="p">:</span>
                            <span class="s">"whatsapp"</span>
                            <span class="p">,</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="k">"phone_number_id"</span>
                            <span class="p">:</span>
                            <span class="s">"123456789"</span><br>&nbsp;
                            <span class="p">}</span><br>
                            <span class="p">}</span>
                        </div>
                    </div>
                </div>

                <!-- EVENTS -->
                <div class="card-mod mb-4">
                    <div class="card-header fs-1">
                        <i class="bi bi-bell-fill fs-1 text-warning"></i>
                        Eventos Disponibles
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach ($eventos as $item)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input evento-checkbox" type="checkbox" name="eventos[]"
                                            id="ev{{ $item?->codigo }}" value="{{ $item->codigo }}" checked>
                                        <label class="form-check-label"
                                            for="ev{{ $item?->codigo }}">{{ $item?->nombre }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- RETRIES -->
                <div class="card-mod">
                    <div class="card-header fs-1">
                        <i class="bi bi-arrow-repeat text-secondary"></i>
                        Reintentos
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label required fs-6">Cantidad de reintentos</label>
                                <input type="number" class="form-control" required name="cantidad_reintentos"
                                    placeholder="3"
                                    value="{{ $automatizacion?->cantidad_reintentos ? $automatizacion?->cantidad_reintentos : 3 }}"
                                    min="0" max="10">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required fs-6">Tiempo entre intentos (s)</label>
                                <input type="number" class="form-control" required name="tiempo_entre_intentos"
                                    placeholder="5"
                                    value="{{ $automatizacion?->tiempo_entre_intentos ? $automatizacion?->tiempo_entre_intentos : 5 }}"
                                    min="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required fs-6">Timeout (s)</label>
                                <input type="number" class="form-control" required name="timeout_segundos"
                                    placeholder="30"
                                    value="{{ $automatizacion?->timeout_segundos ? $automatizacion?->timeout_segundos : 30 }}"
                                    min="5">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" id="btnGuardarTodo" class="d-none"></button>
            </form>
        </div>

        <!-- RIGHT COLUMN 35% -->
        <div class="col-lg-4">

            <!-- TEMPLATES -->
            <div class="card-mod mb-4">
                <div class="card-header fs-1">
                    <i class="bi bi-collection-fill text-primary fs-1"></i>
                    Plantillas n8n
                </div>
                <div class="card-body">
                    <div class="tpl-item" data-name="Atención al Cliente"
                        data-desc="Recibe mensajes, clasifica intención y responde automáticamente." data-nodes="5"
                        data-emoji="📩" data-json="{{ asset('estructuras/Atencion_al_Cliente.json') }}">
                        <div class="tpl-ico">📩</div>
                        <div class="flex-grow-1">
                            <h6>Atención al Cliente</h6>
                            <small>5 nodos</small>
                        </div>
                        <button class="btn btn-sm btn-wa-outline btn-tpl">
                            <i class="bi bi-download"></i>
                        </button>
                    </div>
                    {{-- <div class="tpl-item" data-name="Chatbot IA"
                        data-desc="Integra OpenRouter/Ollama para respuestas inteligentes." data-nodes="11"
                        data-emoji="🧠">
                        <div class="tpl-ico">🧠</div>
                        <div class="flex-grow-1">
                            <h6>Chatbot IA</h6>
                            <small>11 nodos</small>
                        </div>
                        <button class="btn btn-sm btn-wa-outline btn-tpl">
                            <i class="bi bi-download"></i>
                        </button>
                    </div> --}}
                    <div class="tpl-item" data-name="Agendamiento"
                        data-desc="Conecta Google Sheer para reservar citas vía WhatsApp." data-nodes="6" data-emoji="📅"
                        data-json="{{ asset('estructuras/Agendamiento.json') }}">
                        <div class="tpl-ico">📅</div>
                        <div class="flex-grow-1">
                            <h6>Agendamiento</h6>
                            <small>6 nodos</small>
                        </div>
                        <button class="btn btn-sm btn-wa-outline btn-tpl">
                            <i class="bi bi-download"></i>
                        </button>
                    </div>
                    {{-- <div class="tpl-item" data-name="Seguimiento de Ventas"
                        data-desc="Registra leads en tu CRM y dispara recordatorios." data-nodes="10" data-emoji="🛒">
                        <div class="tpl-ico">🛒</div>
                        <div class="flex-grow-1">
                            <h6>Seguimiento de Ventas</h6>
                            <small>10 nodos</small>
                        </div>
                        <button class="btn btn-sm btn-wa-outline btn-tpl">
                            <i class="bi bi-download"></i>
                        </button>
                    </div>
                    <div class="tpl-item" data-name="Estado de Pedidos"
                        data-desc="Consulta tu sistema y notifica el estado del pedido." data-nodes="7" data-emoji="📦">
                        <div class="tpl-ico">📦</div>
                        <div class="flex-grow-1">
                            <h6>Estado de Pedidos</h6>
                            <small>7 nodos</small>
                        </div>
                        <button class="btn btn-sm btn-wa-outline btn-tpl">
                            <i class="bi bi-download"></i>
                        </button>
                    </div> --}}
                </div>
            </div>

            <!-- TEST CONNECTION -->
            <div class="card-mod mb-4">
                <div class="card-header fs-1">
                    <i class="bi bi-wifi text-primary fs-1"></i>
                    Prueba de Conexión
                </div>
                <div class="card-body">
                    <button class="btn btn-wa w-100 mb-3" id="btnProbar">
                        <i class="bi bi-play-circle text-white me-1"></i>
                        Probar Webhook
                    </button>
                    <button class="btn btn-wa-outline w-100 mb-3" data-bs-toggle="modal" data-bs-target="#modalProbar">
                        <i class="fa-solid fa-paper-plane me-1"></i>
                        Enviar Prueba Personalizada
                    </button>
                    <div class="test-result test-ok" id="resOk">
                        <i class="bi bi-check-circle-fill text-primary me-1"></i>
                        Conexión Exitosa
                        <span class="d-block fw-normal small mt-1">
                            Respuesta 200 OK · 312 ms
                        </span>
                    </div>
                    <div class="test-result test-err" id="resErr">
                        <i class="bi bi-x-circle-fill text-danger me-1"></i>
                        Error de Conexión
                        <span class="d-block fw-normal small mt-1">
                            Tiempo de espera agotado.
                        </span>
                    </div>
                </div>
            </div>

            <!-- DOCS -->
            <div class="card-mod">
                <div class="card-header fs-1">
                    <i class="bi bi-journal-text text-secondary"></i>
                    Documentación Rápida
                </div>
                <div class="card-body">
                    <div class="step">
                        <div class="step-num">1</div>
                        <div>Crear workflow en n8n</div>
                    </div>
                    <div class="step-arrow">
                        <i class="bi bi-arrow-down text-primary"></i>
                    </div>
                    <div class="step">
                        <div class="step-num">2</div>
                        <div>Agregar nodo Webhook</div>
                    </div>
                    <div class="step-arrow">
                        <i class="bi bi-arrow-down text-primary"></i>
                    </div>
                    <div class="step">
                        <div class="step-num">3</div>
                        <div>Copiar URL</div>
                    </div>
                    <div class="step-arrow">
                        <i class="bi bi-arrow-down text-primary"></i>
                    </div>
                    <div class="step">
                        <div class="step-num">4</div>
                        <div>Pegar en GIJAC MESSAGE BUSINESS</div>
                    </div>
                    <div class="step-arrow">
                        <i class="bi bi-arrow-down text-primary"></i>
                    </div>
                    <div class="step">
                        <div class="step-num">5</div>
                        <div>Guardar</div>
                    </div>
                    <div class="step-arrow">
                        <i class="bi bi-arrow-down text-primary"></i>
                    </div>
                    <div class="step">
                        <div class="step-num">6</div>
                        <div>Activar</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MONITOR -->
    <div class="card-mod mt-4">
        <div class="card-header fs-1">
            <i class="bi bi-activity text-primary fs-1"></i>
            Monitor de Ejecuciones
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="tablaEjecicionN8n">
                    <thead>
                        <tr>
                            <th  width="5%" class="text-center all">#</th>
                            <th  width="10%" class="text-center all">Fecha</th>
                            <th  width="10%" class="text-center all">Cliente</th>
                            <th  width="10%" class="text-center all">Evento</th>
                            <th  width="10%" class="text-center all">Estado</th>
                            <th  width="10%" class="text-center all">Duración</th>
                            <th  width="10%" class="text-center all">Respuesta</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @component('chatbots.webhook-n8n.modals.template')
    @endcomponent
    @component('chatbots.webhook-n8n.modals.prueba')
    @endcomponent
@endsection

@section('scripts')
    <script>
        window.automatizacion = '{{ $automatizacion?->id }}'
    </script>
    <script src="{{ mix('/js/chatbots/n8n/principal.js') }}"></script>
    <script src="{{ mix('/js/chatbots/n8n/crear.js') }}"></script>
@endsection
