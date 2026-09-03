@php
    $ext  = strtolower(pathinfo($asistente?->documento_nombre ?? '', PATHINFO_EXTENSION));
    $icon = match($ext) {
        'pdf'   => 'fa-regular fa-file-pdf',
        'docx'  => 'fa-regular fa-file-word',
        default => 'fa-regular fa-file-lines',
    };
@endphp

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
                            <i class="fas fa-robot text-white fs-1"></i>{{ __('Asistente IA para WhatsApp') }}</h1>
                        <p class="subtitle mb-0">{{ __('Configura un asistente inteligente capaz de responder automáticamente a tus
                            clientes.') }</p>
                    </div>
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="badge-active me-1">
                    @if ($asistente?->activo)
                        <span class="dot"></span>
                    @endif
                    Estado: {{ $asistente?->activo ? 'Activo' : 'Inactivo' }}
                </span>
                <button class="btn btn-white" id="btnSave">
                    <i class="bi bi-check2-circle fs-4 text-primary"></i>{{ __('Guardar Configuración') }}</button>
            </div>
        </div>
    </div>

    <!-- ====== STATS ====== -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon ic-green">
                    <i class="bi bi-chat-dots-fill text-white"></i>
                </div>
                <div class="stat-value fs-4">0</div>
                <div class="stat-label fs-5">{{ __('Conversaciones IA') }}</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon ic-dark">
                    <i class="fa-regular fa-paper-plane text-white"></i>
                </div>
                <div class="stat-value fs-4">0</div>
                <div class="stat-label fs-5">{{ __('Mensajes Respondidos') }}</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon ic-blue">
                    <i class="bi bi-bullseye text-white"></i>
                </div>
                <div class="stat-value fs-4">0%</div>
                <div class="stat-label fs-5">{{ __('Precisión Estimada') }}</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon ic-amber">
                    <i class="bi bi-file-earmark-text-fill text-white"></i>
                </div>
                <div class="stat-value fs-4">{{ $asistente?->documento_nombre ? 1 : 0 }}</div>
                <div class="stat-label fs-5">{{ __('Documentos Cargados') }}</div>
            </div>
        </div>
    </div>

    <form id="formAsistente">
        <!-- ====== MAIN LAYOUT ====== -->
        <div class="row g-4">
            <!-- ===== LEFT COLUMN 65% ===== -->
            <div class="col-12 col-lg-8">
                <!-- IDENTIDAD -->
                <div class="panel">
                    <div class="mb-3">
                        <p class="section-title fs-1">
                            <i class="bi bi-person-badge text-primary fs-1"></i>{{ __('Identidad del Asistente') }}</p>
                        <p class="section-subtitle fs-3">{{ __('Define cómo se presenta tu asistente ante los clientes.') }}</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fs-5 required">{{ __('Nombre del Asistente') }}</label>
                            <input type="text" class="form-control" name="nombre" required id="asstName"
                                value="{{ $asistente?->nombre ?? '' }}" placeholder="{{ __('Ej: Gibot') }}"/>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-5 required">{{ __('Rol del Asistente') }}</label>
                            <input type="text" class="form-control" name="rol" required id="asstRole" value="{{ $asistente?->rol ?? '' }}"
                                placeholder="{{ __('Ej: Asesor Comercial') }}"/>
                        </div>
                        <div class="col-12">
                            <label class="form-label fs-5 required">{{ __('Descripción corta') }}</label>
                            <textarea class="form-control" name="descripcion" rows="2" required
                                placeholder="{{ __('Describe brevemente el propósito del asistente...') }}"
                                >{{ $asistente?->descripcion ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- COMPORTAMIENTO -->
                <div class="panel">
                    <div class="mb-3">
                        <p class="section-title fs-1">
                            <i class="bi bi-chat-square-text text-primary fs-1"></i>{{ __('Comportamiento') }}</p>
                        <p class="section-subtitle fs-3">{{ __('El prompt del sistema define la personalidad y las reglas del asistente.') }}</p>
                    </div>
                    <label class="form-label fs-5 required">{{ __('Prompt del Sistema') }}</label>
                    <textarea class="form-control" id="systemPrompt" name="system_prompt" rows="6" maxlength="2000"
                        placeholder="{{ __('Eres el asistente virtual de GIJAC WEB. Tu objetivo es ayudar a los clientes a conocer nuestros servicios y resolver dudas de manera profesional.') }}"
                        >{{ $asistente?->system_prompt ?? '' }}</textarea>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="char-counter">
                            <span id="charCount">0</span> / 2000 caracteres
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="form-label d-block mb-2 fs-5">{{ __('Ejemplos rápidos') }}</span>
                        <span class="chip"
                            data-prompt="Eres un asistente de atención al cliente de GIJAC WEB. Responde de forma amable, clara y profesional. Resuelve dudas frecuentes y guía al cliente paso a paso.">
                            <i class="bi bi-headset"></i>{{ __('Atención al cliente') }}</span>
                        <span class="chip"
                            data-prompt="Eres un asesor de ventas de GIJAC WEB. Tu objetivo es presentar los servicios, destacar beneficios y motivar al cliente a solicitar una cotización.">
                            <i class="bi bi-cart-check"></i>{{ __('Ventas') }}</span>
                        <span class="chip"
                            data-prompt="Eres un especialista de soporte técnico de GIJAC WEB. Diagnostica problemas, ofrece soluciones paso a paso y escala a un agente humano si es necesario.">
                            <i class="bi bi-tools"></i>{{ __('Soporte técnico') }}</span>
                        <span class="chip"
                            data-prompt="Eres un asistente de agendamiento de GIJAC WEB. Ayuda al cliente a reservar citas, confirmar disponibilidad y enviar recordatorios.">
                            <i class="bi bi-calendar-check"></i>{{ __('Agendamiento') }}</span>
                    </div>
                </div>

                <!-- MODELO IA -->
                <div class="panel">
                    <div class="mb-3">
                        <p class="section-title fs-1">
                            <i class="bi bi-cpu text-primary fs-1"></i>{{ __('Modelo IA') }}</p>
                        <p class="section-subtitle fs-3">{{ __('Selecciona el proveedor y el modelo que impulsará tu asistente.') }}</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <span class="badge bg-light text-dark mb-2 fs-5">
                                <i class="bi bi-hdd-network text-dark me-1 fs-5"></i>{{ __('Ollama (modelos locales, 100% gratis)') }}</span>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fs-5 required">{{ __('Modelo') }}</label>
                            <select class="form-select" id="modelSelect" name="modelo" required
                                data-control="select2" data-placeholder="{{ __('Seleccione el modelo') }}" data-allow-clear="true">
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-1" id="modelMeta">
                        <!-- dynamic -->
                    </div>
                </div>

                <!-- PERSONALIDAD -->
                <div class="panel">
                    <div class="mb-3">
                        <p class="section-title fs-1">
                            <i class="bi bi-sliders text-primary fs-1"></i>{{ __('Personalidad') }}</p>
                        <p class="section-subtitle fs-3">{{ __('Ajusta el tono y estilo de las respuestas del asistente.') }}</p>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="slider-row">
                                <div class="d-flex justify-content-between">
                                    <span class="form-label fs-6 mb-0">{{ __('Creatividad') }}</span>
                                    <span id="vCrea">{{ $asistente?->creatividad ?? 60 }}%</span>
                                </div>
                                <input type="range" min="0" max="100" value="{{ $asistente?->creatividad ?? 60 }}"
                                    name="creatividad" id="slider-creatividad"
                                    oninput="document.getElementById('vCrea').textContent=this.value+'%'">
                            </div>
                            <div class="slider-row">
                                <div class="d-flex justify-content-between">
                                    <span class="form-label fs-6 mb-0">{{ __('Formalidad') }}</span>
                                    <span id="vForm">{{ $asistente?->formalidad ?? 75 }}%</span>
                                </div>
                                <input type="range" min="0" max="100" value="{{ $asistente?->formalidad ?? 75 }}"
                                    name="formalidad" id="slider-formalidad"
                                    oninput="document.getElementById('vForm').textContent=this.value+'%'">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="slider-row">
                                <div class="d-flex justify-content-between">
                                    <span class="form-label fs-6 mb-0">{{ __('Brevedad') }}</span>
                                    <span id="vBrev">{{ $asistente?->brevedad ?? 50 }}%</span>
                                </div>
                                <input type="range" min="0" max="100" value="{{ $asistente?->brevedad ?? 50 }}"
                                    name="brevedad" id="slider-brevedad"
                                    oninput="document.getElementById('vBrev').textContent=this.value+'%'">
                            </div>
                            <div class="slider-row">
                                <div class="d-flex justify-content-between">
                                    <span class="form-label fs-6 mb-0">{{ __('Empatía') }}</span>
                                    <span id="vEmp">{{ $asistente?->empatia ?? 80 }}%</span>
                                </div>
                                <input type="range" min="0" max="100" value="{{ $asistente?->empatia ?? 80 }}"
                                    name="empatia" id="slider-empatia"
                                    oninput="document.getElementById('vEmp').textContent=this.value+'%'">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== RIGHT COLUMN 35% ===== -->
            <div class="col-12 col-lg-4">

                <!-- CONOCIMIENTO -->
                <div class="panel">
                    <div class="mb-3">
                        <p class="section-title fs-1">
                            <i class="bi bi-journal-richtext text-primary fs-1"></i>{{ __('Base de Conocimiento') }}</p>
                        <p class="section-subtitle fs-3">{{ __('Sube documentos para que el asistente responda con tu información.') }}</p>
                    </div>
                    <div class="dropzone" id="dropzone">
                        <i class="bi bi-cloud-arrow-up"></i>
                        <p class="mb-1 mt-2 fw-semibold">{{ __('Arrastra archivos aquí') }}</p>
                        <small>{{ __('PDF, DOCX o TXT (máx. 10 MB)') }}</small>
                    </div>
                    <div id="docList">
                        @if($asistente?->documento_nombre)
                            <div class="doc-item" id="docItemActual">
                                <div class="doc-ico">
                                    <i class="{{ $icon }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">{{ $asistente?->documento_nombre }}</div>
                                    <div class="doc-meta">
                                        {{ number_format($asistente?->documento_size / 1024, 0) }} KB · {{ $asistente?->documento_procesado_en->format('d/m/Y') }}
                                    </div>
                                </div>
                                <span class="res-badge res-ok">{{ __('Procesado') }}</span>
                                <button type="button" class="btn btn-sm btn-link text-danger" id="btnEliminarDoc">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- CAPACIDADES -->
                <div class="panel">
                    <div class="mb-2">
                        <p class="section-title fs-1">
                            <i class="bi bi-toggles text-primary fs-1"></i>{{ __('Capacidades') }}</p>
                    </div>
                    <div class="switch-list" id="capList">
                        @foreach ($capacidadOpciones as $item)
                            <div class="switch-row">
                                <span class="lbl">{{ $item?->nombre }}</span>
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" id="cap{{ $item?->codigo }}"
                                        value="{{ $item?->codigo }}" name="capacidades[]"
                                        {{ $asistente?->capacidades && in_array($item?->codigo, $asistente?->capacidades) ? 'checked' : '' }}>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- HORARIO -->
                <div class="panel">
                    <div class="mb-2">
                        <p class="section-title fs-1">
                            <i class="bi bi-clock-history text-primary fs-1"></i>{{ __('Horario') }}</p>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="scheduleSwitch" checked>
                        <label class="form-check-label fw-semibold" for="scheduleSwitch">{{ __('Respetar horario laboral') }}</label>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fs-6">{{ __('Hora inicio') }}</label>
                            <input type="time" class="form-control" name="hora_inicio" value="{{ $asistente?->hora_inicio ? \Carbon\Carbon::parse($asistente?->hora_inicio)->format('H:i') : '08:00' }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-6">{{ __('Hora fin') }}</label>
                            <input type="time" class="form-control" name="hora_fin" value="{{ $asistente?->hora_fin ? \Carbon\Carbon::parse($asistente?->hora_fin)->format('H:i') : '18:00' }}">
                        </div>
                    </div>
                </div>

                <!-- PALABRAS CLAVE -->
                <div class="panel">
                    <div class="mb-2">
                        <p class="section-title fs-1">
                            <i class="bi bi-key text-primary fs-1"></i>{{ __('Palabras Clave') }}</p>
                        <p class="section-subtitle fs-3">{{ __('Si aparecen, la conversación se transfiere a un agente humano.') }}</p>
                    </div>
                    <textarea class="form-control" rows="4" placeholder="{{ __('cotización, precio, asesor, factura, soporte') }}"
                        name="palabras_clave" id="palabrasClave">{{ $asistente?->palabras_clave ? implode(', ', $asistente?->palabras_clave) : 'cotización, precio, asesor, factura, soporte' }}</textarea>
                </div>

                <!-- MENSAJES ESPECIALES -->
                <div class="panel">
                    <div class="mb-2">
                        <p class="section-title fs-1">
                            <i class="bi bi-envelope-paper text-primary fs-1"></i>{{ __('Mensajes Especiales') }}</p>
                    </div>
                    <label class="form-label fs-6">{{ __('Mensaje de bienvenida') }}</label>
                    <textarea class="form-control mb-3" rows="2" name="mensaje_bienvenida" id="mensajeBienvenida"
                        >{{ $asistente?->mensaje_bienvenida ?? '¡Hola! Soy Gibot, tu asistente de GIJAC WEB. ¿En qué puedo ayudarte hoy?' }}</textarea>
                    <label class="form-label fs-6">{{ __('Mensaje fuera de horario') }}</label>
                    <textarea class="form-control mb-3" rows="2" name="mensaje_fuera_horario"
                        >{{ $asistente?->mensaje_fuera_horario ?? 'Gracias por escribirnos. Nuestro horario es de 8:00 a 18:00. Te responderemos pronto.' }}</textarea>
                    <label class="form-label fs-6">{{ __('Mensaje de transferencia') }}</label>
                    <textarea class="form-control" rows="2" name="mensaje_transferencia"
                        >{{ $asistente?->mensaje_transferencia ?? 'Un momento por favor, te estoy conectando con un asesor humano.' }}</textarea>
                </div>

            </div>
        </div>
    </form>

    <!-- ====== SIMULADOR ====== -->
    <div class="panel mt-2">
        <div class="mb-3">
            <p class="section-title fs-1">
                <i class="bi bi-whatsapp text-primary fs-1"></i>{{ __('Probar Asistente') }}</p>
            <p class="section-subtitle fs-3">{{ __('Simula una conversación para validar el comportamiento del asistente.') }}</p>
        </div>
        <div class="sim-wrap flex-column flex-md-row">
            <div class="sim-info">
                <div class="sim-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <h5 id="simName" class="fs-1 text-white">{{ $asistente?->nombre ?? 'Asistente' }}</h5>
                <div class="role fs-4" id="simRole">{{ $asistente?->rol ?? 'Asistente IA' }}</div>
                <hr style="border-color:rgba(255,255,255,.2)">
                <div class="feat fs-4">
                    <i class="bi bi-check-circle-fill text-white"></i>{{ __('Respuestas automáticas') }}</div>
                <div class="feat fs-4">
                    <i class="bi bi-check-circle-fill text-white"></i>{{ __('Basado en documentos') }}</div>
                <div class="feat fs-4">
                    <i class="bi bi-check-circle-fill text-white"></i>{{ __('Transferencia a humano') }}</div>
                <div class="feat fs-4">
                    <i class="bi bi-check-circle-fill text-white"></i>{{ __('Memoria de conversación') }}</div>
            </div>
            <div class="sim-chat">
                <div class="sim-chat-head fs-4">
                    <i class="bi bi-whatsapp text-white fs-4"></i>{{ __('Vista previa WhatsApp') }}</div>
                <div class="sim-body" id="simBody">
                    <div class="bubble in">{{ $asistente?->mensaje_bienvenida ?? '¡Hola! Soy Gibot, tu asistente de GIJAC WEB. ¿En qué puedo ayudarte hoy?' }}
                        <span class="time">{{ __('09:30') }}</span>
                    </div>
                </div>
                <div class="sim-input">
                    <input type="text" id="simInput" placeholder="{{ __('Escribe un mensaje...') }}" />
                    <button class="sim-send" id="simSend">
                        <i class="fa-regular fa-paper-plane text-white"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== METRICAS ====== -->
    <div class="panel">
        <div class="mb-3">
            <p class="section-title fs-1">
                <i class="fa-solid fa-chart-line text-primary fs-1"></i>{{ __('Últimas Conversaciones') }}</p>
        </div>
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Fecha') }}</th>
                        <th>{{ __('Cliente') }}</th>
                        <th>{{ __('Mensajes') }}</th>
                        <th>{{ __('Duración') }}</th>
                        <th>{{ __('Resultado') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ __('11/06/2026 10:24') }}</td>
                        <td>{{ __('María López') }}</td>
                        <td>8</td>
                        <td>{{ __('3m 12s') }}</td>
                        <td><span class="res-badge res-ok">{{ __('Resuelto') }}</span></td>
                    </tr>
                    <tr>
                        <td>{{ __('11/06/2026 09:58') }}</td>
                        <td>{{ __('Carlos Ruiz') }}</td>
                        <td>{{ __('14') }}</td>
                        <td>{{ __('6m 40s') }}</td>
                        <td><span class="res-badge res-trans">{{ __('Transferido') }}</span></td>
                    </tr>
                    <tr>
                        <td>{{ __('11/06/2026 09:31') }}</td>
                        <td>{{ __('Ana Torres') }}</td>
                        <td>5</td>
                        <td>{{ __('1m 55s') }}</td>
                        <td><span class="res-badge res-ok">{{ __('Resuelto') }}</span></td>
                    </tr>
                    <tr>
                        <td>{{ __('10/06/2026 18:12') }}</td>
                        <td>{{ __('Jorge Méndez') }}</td>
                        <td>3</td>
                        <td>{{ __('0m 48s') }}</td>
                        <td><span class="res-badge res-pend">{{ __('Pendiente') }}</span></td>
                    </tr>
                    <tr>
                        <td>{{ __('10/06/2026 16:45') }}</td>
                        <td>{{ __('Lucía Fernández') }}</td>
                        <td>{{ __('11') }}</td>
                        <td>{{ __('4m 30s') }}</td>
                        <td><span class="res-badge res-ok">{{ __('Resuelto') }}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ====== MODELOS RECOMENDADOS ====== -->
    <h2 class="section-title fs-1">
        <i class="bi bi-stars me-2 text-primary fs-1"></i>{{ __('Modelos Recomendados') }}</h2>
    <div class="row g-3 mb-4 mt-1">
        <div class="col-6 col-lg-3">
            <div class="rec-card">
                <h6 class="fs-4">{{ __('Gemma') }}</h6>
                <div>
                    <span class="tag tag-free fs-7">{{ __('Gratis') }}</span>
                    <span class="tag tag-fast fs-7">{{ __('Rápido') }}</span>
                </div>
                <p class="text-muted fs-7 small mt-2 mb-0">{{ __('Ideal para atención básica.') }}</p>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="rec-card">
                <h6 class="fs-4">{{ __('Qwen') }}</h6>
                <div>
                    <span class="tag tag-free fs-7">{{ __('Gratis') }}</span>
                    <span class="tag tag-precise fs-7">{{ __('Muy preciso') }}</span>
                </div>
                <p class="text-muted fs-7 small mt-2 mb-0">{{ __('Ideal para ventas.') }}</p>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="rec-card">
                <h6 class="fs-4">{{ __('DeepSeek') }}</h6>
                <div>
                    <span class="tag tag-precise fs-7">{{ __('Razonamiento') }}</span>
                </div>
                <p class="text-muted fs-7 small mt-2 mb-0">{{ __('Ideal para soporte.') }}</p>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="rec-card">
                <h6 class="fs-4">{{ __('Mistral') }}</h6>
                <div>
                    <span class="tag tag-balanced fs-7">{{ __('Equilibrado') }}</span>
                </div>
                <p class="text-muted fs-7 small mt-2 mb-0">{{ __('Uso general.') }}</p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ mix('/js/chatbots/ai-assistant/principal.js') }}"></script>
@endsection
