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
                            <i class="fas fa-robot text-white fs-1"></i>
                            Asistente IA para WhatsApp
                        </h1>
                        <p class="subtitle mb-0">Configura un asistente inteligente capaz de responder automáticamente a tus
                            clientes.</p>
                    </div>
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="badge-active me-1">
                    <span class="dot"></span>
                    Estado: Activo
                </span>
                <button class="btn btn-white" id="btnSave">
                    <i class="bi bi-check2-circle fs-4 text-primary"></i>
                    Guardar Configuración
                </button>
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
                <div class="stat-value fs-4">1,248</div>
                <div class="stat-label fs-5">Conversaciones IA</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon ic-dark">
                    <i class="fa-regular fa-paper-plane text-white"></i>
                </div>
                <div class="stat-value fs-4">9,532</div>
                <div class="stat-label fs-5">Mensajes Respondidos</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon ic-blue">
                    <i class="bi bi-bullseye text-white"></i>
                </div>
                <div class="stat-value fs-4">94%</div>
                <div class="stat-label fs-5">Precisión Estimada</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon ic-amber">
                    <i class="bi bi-file-earmark-text-fill text-white"></i>
                </div>
                <div class="stat-value fs-4">12</div>
                <div class="stat-label fs-5">Documentos Cargados</div>
            </div>
        </div>
    </div>

    <!-- ====== MAIN LAYOUT ====== -->
    <div class="row g-4">
        <!-- ===== LEFT COLUMN 65% ===== -->
        <div class="col-12 col-lg-8">

            <!-- IDENTIDAD -->
            <div class="panel">
                <div class="mb-3">
                    <p class="section-title fs-1">
                        <i class="bi bi-person-badge text-primary fs-1"></i>
                        Identidad del Asistente
                    </p>
                    <p class="section-subtitle fs-3">Define cómo se presenta tu asistente ante los clientes.</p>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fs-5 required">Nombre del Asistente</label>
                        <input type="text" class="form-control" required id="asstName" value="Gibot" placeholder="Ej: Gibot" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-5 required">Rol del Asistente</label>
                        <input type="text" class="form-control" required id="asstRole" value="Asesor Comercial"
                            placeholder="Ej: Asesor Comercial" />
                    </div>
                    <div class="col-12">
                        <label class="form-label fs-5 required">Descripción corta</label>
                        <textarea class="form-control" rows="2" required placeholder="Breve descripción del asistente">Asistente virtual de GIJAC WEB enfocado en atención y ventas.</textarea>
                    </div>
                </div>
            </div>

            <!-- COMPORTAMIENTO -->
            <div class="panel">
                <div class="mb-3">
                    <p class="section-title fs-1">
                        <i class="bi bi-chat-square-text text-primary fs-1"></i>
                        Comportamiento
                    </p>
                    <p class="section-subtitle fs-3">El prompt del sistema define la personalidad y las reglas del asistente.</p>
                </div>
                <label class="form-label fs-5 required">Prompt del Sistema</label>
                <textarea class="form-control" id="systemPrompt" rows="6" maxlength="2000"
                    placeholder="Eres el asistente virtual de GIJAC WEB. Tu objetivo es ayudar a los clientes a conocer nuestros servicios y resolver dudas de manera profesional."></textarea>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div class="char-counter">
                        <span id="charCount">0</span> / 2000 caracteres
                    </div>
                </div>
                <div class="mt-3">
                    <span class="form-label d-block mb-2 fs-5">Ejemplos rápidos</span>
                    <span class="chip"
                        data-prompt="Eres un asistente de atención al cliente de GIJAC WEB. Responde de forma amable, clara y profesional. Resuelve dudas frecuentes y guía al cliente paso a paso.">
                        <i class="bi bi-headset"></i>
                        Atención al cliente
                    </span>
                    <span class="chip"
                        data-prompt="Eres un asesor de ventas de GIJAC WEB. Tu objetivo es presentar los servicios, destacar beneficios y motivar al cliente a solicitar una cotización.">
                        <i class="bi bi-cart-check"></i>
                        Ventas
                    </span>
                    <span class="chip"
                        data-prompt="Eres un especialista de soporte técnico de GIJAC WEB. Diagnostica problemas, ofrece soluciones paso a paso y escala a un agente humano si es necesario.">
                        <i class="bi bi-tools"></i>
                        Soporte técnico
                    </span>
                    <span class="chip"
                        data-prompt="Eres un asistente de agendamiento de GIJAC WEB. Ayuda al cliente a reservar citas, confirmar disponibilidad y enviar recordatorios.">
                        <i class="bi bi-calendar-check"></i>
                        Agendamiento
                    </span>
                </div>
            </div>

            <!-- MODELO IA -->
            <div class="panel">
                <div class="mb-3">
                    <p class="section-title fs-1">
                        <i class="bi bi-cpu text-primary fs-1"></i>
                        Modelo IA
                    </p>
                    <p class="section-subtitle fs-3">Selecciona el proveedor y el modelo que impulsará tu asistente.</p>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fs-5 required">Proveedor</label>
                        <select class="form-select" id="provider">
                            <option value="openrouter">OpenRouter</option>
                            <option value="ollama">Ollama</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-5 required">Modelo</label>
                        <select class="form-select" id="modelSelect"></select>
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
                        <i class="bi bi-sliders text-primary fs-1"></i>
                        Personalidad
                    </p>
                    <p class="section-subtitle fs-3">Ajusta el tono y estilo de las respuestas del asistente.</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="slider-row">
                            <div class="d-flex justify-content-between">
                                <span class="form-label fs-6 mb-0">Creatividad</span>
                                <span id="vCrea">60%</span>
                            </div>
                            <input type="range" min="0" max="100" value="60"
                                oninput="document.getElementById('vCrea').textContent=this.value+'%'">
                        </div>
                        <div class="slider-row">
                            <div class="d-flex justify-content-between">
                                <span class="form-label fs-6 mb-0">Formalidad</span>
                                <span id="vForm">75%</span>
                            </div>
                            <input type="range" min="0" max="100" value="75"
                                oninput="document.getElementById('vForm').textContent=this.value+'%'">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="slider-row">
                            <div class="d-flex justify-content-between">
                                <span class="form-label fs-6 mb-0">Brevedad</span>
                                <span id="vBrev">50%</span>
                            </div>
                            <input type="range" min="0" max="100" value="50"
                                oninput="document.getElementById('vBrev').textContent=this.value+'%'">
                        </div>
                        <div class="slider-row">
                            <div class="d-flex justify-content-between">
                                <span class="form-label fs-6 mb-0">Empatía</span>
                                <span id="vEmp">80%</span>
                            </div>
                            <input type="range" min="0" max="100" value="80"
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
                        <i class="bi bi-journal-richtext text-primary fs-1"></i>
                        Base de Conocimiento
                    </p>
                    <p class="section-subtitle fs-3">Sube documentos para que el asistente responda con tu información.</p>
                </div>
                <div class="dropzone" id="dropzone">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <p class="mb-1 mt-2 fw-semibold">Arrastra archivos aquí</p>
                    <small>PDF, DOCX o TXT (máx. 10 MB)</small>
                    <input type="file" id="fileInput" multiple accept=".pdf,.docx,.txt" hidden />
                </div>
                <div id="docList">
                    <div class="doc-item">
                        <div class="doc-ico">
                            <i class="fa-regular fa-file-pdf"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold small">Catalogo-Servicios.pdf</div>
                            <div class="doc-meta">2.4 MB · 10/06/2026</div>
                        </div>
                        <span class="res-badge res-ok">Procesado</span>
                    </div>
                    <div class="doc-item">
                        <div class="doc-ico">
                            <i class="fa-regular fa-file-word"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold small">Preguntas-Frecuentes.docx</div>
                            <div class="doc-meta">820 KB · 09/06/2026</div>
                        </div>
                        <span class="res-badge res-trans">Procesando</span>
                    </div>
                </div>
            </div>

            <!-- CAPACIDADES -->
            <div class="panel">
                <div class="mb-2">
                    <p class="section-title fs-1">
                        <i class="bi bi-toggles text-primary fs-1"></i>
                        Capacidades
                    </p>
                </div>
                <div class="switch-list" id="capList"></div>
            </div>

            <!-- HORARIO -->
            <div class="panel">
                <div class="mb-2">
                    <p class="section-title fs-1">
                        <i class="bi bi-clock-history text-primary fs-1"></i>
                        Horario
                    </p>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="scheduleSwitch" checked>
                    <label class="form-check-label fw-semibold" for="scheduleSwitch">Respetar horario laboral</label>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label fs-6">Hora inicio</label>
                        <input type="time" class="form-control" value="08:00">
                    </div>
                    <div class="col-6">
                        <label class="form-label fs-6">Hora fin</label>
                        <input type="time" class="form-control" value="18:00">
                    </div>
                </div>
            </div>

            <!-- PALABRAS CLAVE -->
            <div class="panel">
                <div class="mb-2">
                    <p class="section-title fs-1">
                        <i class="bi bi-key text-primary fs-1"></i>
                        Palabras Clave
                    </p>
                    <p class="section-subtitle fs-3">Si aparecen, la conversación se transfiere a un agente humano.</p>
                </div>
                <textarea class="form-control" rows="4" placeholder="cotización, precio, asesor, factura, soporte">cotización, precio, asesor, factura, soporte</textarea>
            </div>

            <!-- MENSAJES ESPECIALES -->
            <div class="panel">
                <div class="mb-2">
                    <p class="section-title fs-1">
                        <i class="bi bi-envelope-paper text-primary fs-1"></i>
                        Mensajes Especiales
                    </p>
                </div>
                <label class="form-label fs-6">Mensaje de bienvenida</label>
                <textarea class="form-control mb-3" rows="2">¡Hola! Soy Gibot, tu asistente de GIJAC WEB. ¿En qué puedo ayudarte hoy?</textarea>
                <label class="form-label fs-6">Mensaje fuera de horario</label>
                <textarea class="form-control mb-3" rows="2">Gracias por escribirnos. Nuestro horario es de 8:00 a 18:00. Te responderemos pronto.</textarea>
                <label class="form-label fs-6">Mensaje de transferencia</label>
                <textarea class="form-control" rows="2">Un momento por favor, te estoy conectando con un asesor humano.</textarea>
            </div>

        </div>
    </div>

    <!-- ====== SIMULADOR ====== -->
    <div class="panel mt-2">
        <div class="mb-3">
            <p class="section-title fs-1">
                <i class="bi bi-whatsapp text-primary fs-1"></i>
                Probar Asistente
            </p>
            <p class="section-subtitle fs-3">Simula una conversación para validar el comportamiento del asistente.</p>
        </div>
        <div class="sim-wrap flex-column flex-md-row">
            <div class="sim-info">
                <div class="sim-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <h5 id="simName" class="fs-1 text-white">Gibot</h5>
                <div class="role fs-4" id="simRole">Asesor Comercial</div>
                <hr style="border-color:rgba(255,255,255,.2)">
                <div class="feat fs-4">
                    <i class="bi bi-check-circle-fill"></i>
                    Respuestas automáticas
                </div>
                <div class="feat fs-4">
                    <i class="bi bi-check-circle-fill"></i>
                    Basado en documentos
                </div>
                <div class="feat fs-4">
                    <i class="bi bi-check-circle-fill"></i>
                    Transferencia a humano
                </div>
                <div class="feat fs-4">
                    <i class="bi bi-check-circle-fill"></i>
                    Memoria de conversación
                </div>
            </div>
            <div class="sim-chat">
                <div class="sim-chat-head fs-4">
                    <i class="bi bi-whatsapp text-white fs-4"></i>
                    Vista previa WhatsApp
                </div>
                <div class="sim-body" id="simBody">
                    <div class="bubble in">¡Hola! Soy Gibot, tu asistente de GIJAC WEB. ¿En qué puedo ayudarte hoy?
                        <span class="time">09:30</span>
                    </div>
                </div>
                <div class="sim-input">
                    <input type="text" id="simInput" placeholder="Escribe un mensaje..." />
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
                <i class="fa-solid fa-chart-line text-primary fs-1"></i>
                Últimas Conversaciones
            </p>
        </div>
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Mensajes</th>
                        <th>Duración</th>
                        <th>Resultado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>11/06/2026 10:24</td>
                        <td>María López</td>
                        <td>8</td>
                        <td>3m 12s</td>
                        <td><span class="res-badge res-ok">Resuelto</span></td>
                    </tr>
                    <tr>
                        <td>11/06/2026 09:58</td>
                        <td>Carlos Ruiz</td>
                        <td>14</td>
                        <td>6m 40s</td>
                        <td><span class="res-badge res-trans">Transferido</span></td>
                    </tr>
                    <tr>
                        <td>11/06/2026 09:31</td>
                        <td>Ana Torres</td>
                        <td>5</td>
                        <td>1m 55s</td>
                        <td><span class="res-badge res-ok">Resuelto</span></td>
                    </tr>
                    <tr>
                        <td>10/06/2026 18:12</td>
                        <td>Jorge Méndez</td>
                        <td>3</td>
                        <td>0m 48s</td>
                        <td><span class="res-badge res-pend">Pendiente</span></td>
                    </tr>
                    <tr>
                        <td>10/06/2026 16:45</td>
                        <td>Lucía Fernández</td>
                        <td>11</td>
                        <td>4m 30s</td>
                        <td><span class="res-badge res-ok">Resuelto</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ====== MODELOS RECOMENDADOS ====== -->
    <h2 class="section-title fs-1">
        <i class="bi bi-stars me-2 text-primary fs-1"></i>
        Modelos Recomendados
    </h2>
    <div class="row g-3 mb-4 mt-1">
        <div class="col-6 col-lg-3">
            <div class="rec-card">
                <h6 class="fs-4">Gemma</h6>
                <div>
                    <span class="tag tag-free fs-7">Gratis</span>
                    <span class="tag tag-fast fs-7">Rápido</span>
                </div>
                <p class="text-muted fs-7 small mt-2 mb-0">Ideal para atención básica.</p>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="rec-card">
                <h6 class="fs-4">Qwen</h6>
                <div>
                    <span class="tag tag-free fs-7">Gratis</span>
                    <span class="tag tag-precise fs-7">Muy preciso</span>
                </div>
                <p class="text-muted fs-7 small mt-2 mb-0">Ideal para ventas.</p>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="rec-card">
                <h6 class="fs-4">DeepSeek</h6>
                <div>
                    <span class="tag tag-precise fs-7">Razonamiento</span>
                </div>
                <p class="text-muted fs-7 small mt-2 mb-0">Ideal para soporte.</p>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="rec-card">
                <h6 class="fs-4">Mistral</h6>
                <div>
                    <span class="tag tag-balanced fs-7">Equilibrado</span>
                </div>
                <p class="text-muted fs-7 small mt-2 mb-0">Uso general.</p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script src="{{ mix('/js/chatbots/nodos/principal.js') }}"></script> --}}
    <script>
        $(function() {
            /* ===== MODEL DATA ===== */
            const models = {
                openrouter: [{
                        id: 'google/gemma',
                        name: 'google/gemma',
                        meta: [
                            ['Costo', 'Gratis'],
                            ['Velocidad', 'Rápida'],
                            ['Contexto', '8K']
                        ]
                    },
                    {
                        id: 'mistralai/devstral-small',
                        name: 'mistralai/devstral-small',
                        meta: [
                            ['Costo', '$0.10/1M'],
                            ['Velocidad', 'Media'],
                            ['Contexto', '32K']
                        ]
                    },
                    {
                        id: 'qwen/qwen3',
                        name: 'qwen/qwen3',
                        meta: [
                            ['Costo', 'Gratis'],
                            ['Velocidad', 'Media'],
                            ['Contexto', '128K']
                        ]
                    },
                    {
                        id: 'deepseek/deepseek-chat',
                        name: 'deepseek/deepseek-chat',
                        meta: [
                            ['Costo', '$0.14/1M'],
                            ['Velocidad', 'Media'],
                            ['Contexto', '64K']
                        ]
                    }
                ],
                ollama: [{
                        id: 'llama3',
                        name: 'llama3',
                        meta: [
                            ['RAM', '8 GB'],
                            ['Velocidad', 'Rápida']
                        ]
                    },
                    {
                        id: 'qwen3',
                        name: 'qwen3',
                        meta: [
                            ['RAM', '6 GB'],
                            ['Velocidad', 'Media']
                        ]
                    },
                    {
                        id: 'gemma3',
                        name: 'gemma3',
                        meta: [
                            ['RAM', '4 GB'],
                            ['Velocidad', 'Muy rápida']
                        ]
                    },
                    {
                        id: 'mistral',
                        name: 'mistral',
                        meta: [
                            ['RAM', '8 GB'],
                            ['Velocidad', 'Rápida']
                        ]
                    },
                    {
                        id: 'phi4',
                        name: 'phi4',
                        meta: [
                            ['RAM', '5 GB'],
                            ['Velocidad', 'Rápida']
                        ]
                    }
                ]
            };

            function renderModels() {
                const prov = $('#provider').val();
                const $sel = $('#modelSelect').empty();
                models[prov].forEach(m => $sel.append(`<option value="${m.id}">${m.name}</option>`));
                renderMeta();
            }

            function renderMeta() {
                const prov = $('#provider').val();
                const id = $('#modelSelect').val();
                const model = models[prov].find(m => m.id === id);
                const $meta = $('#modelMeta').empty();
                if (!model) return;
                model.meta.forEach(([lbl, val]) => {
                    $meta.append(
                        `<div class="col-4"><div class="meta-pill"><div class="lbl">${lbl}</div><div class="val">${val}</div></div></div>`
                        );
                });
            }
            $('#provider').on('change', renderModels);
            $('#modelSelect').on('change', renderMeta);
            renderModels();

            /* ===== PROMPT CHIPS + COUNTER ===== */
            const $prompt = $('#systemPrompt');

            function updateCount() {
                $('#charCount').text($prompt.val().length);
            }
            $prompt.on('input', updateCount);
            $('.chip').on('click', function() {
                $prompt.val($(this).data('prompt'));
                updateCount();
            });
            $prompt.val(
                "Eres el asistente virtual de GIJAC WEB. Tu objetivo es ayudar a los clientes a conocer nuestros servicios y resolver dudas de manera profesional."
                );
            updateCount();

            /* ===== CAPABILITIES ===== */
            const caps = [
                ['Responder automáticamente', true],
                ['Enviar botones', true],
                ['Enviar listas', false],
                ['Transferir a agente humano', true],
                ['Usar documentos cargados', true],
                ['Guardar contexto', true],
                ['Recordar conversación', false]
            ];
            const $cap = $('#capList');
            caps.forEach((c, i) => {
                $cap.append(`
          <div class="switch-row">
            <span class="lbl">${c[0]}</span>
            <div class="form-check form-switch m-0">
              <input class="form-check-input" type="checkbox" id="cap${i}" ${c[1]?'checked':''}>
            </div>
          </div>`);
            });

            /* ===== DROPZONE ===== */
            const $dz = $('#dropzone'),
                $fi = $('#fileInput'),
                $dl = $('#docList');
            $dz.on('click', () => $fi.click());
            $dz.on('dragover', e => {
                e.preventDefault();
                $dz.addClass('drag');
            });
            $dz.on('dragleave drop', () => $dz.removeClass('drag'));
            $dz.on('drop', e => {
                e.preventDefault();
                addFiles(e.originalEvent.dataTransfer.files);
            });
            $fi.on('change', function() {
                addFiles(this.files);
            });

            function iconFor(name) {
                if (name.endsWith('.pdf')) return 'bi-filetype-pdf';
                if (name.endsWith('.docx')) return 'bi-filetype-docx';
                return 'bi-filetype-txt';
            }

            function addFiles(files) {
                Array.from(files).forEach(f => {
                    const size = f.size > 1048576 ? (f.size / 1048576).toFixed(1) + ' MB' : Math.round(f
                        .size / 1024) + ' KB';
                    const date = new Date().toLocaleDateString('es-ES');
                    $dl.append(`
            <div class="doc-item">
              <div class="doc-ico"><i class="bi ${iconFor(f.name)}"></i></div>
              <div class="flex-grow-1">
                <div class="fw-semibold small">${f.name}</div>
                <div class="doc-meta">${size} · ${date}</div>
              </div>
              <span class="res-badge res-trans">Procesando</span>
            </div>`);
                });
            }

            /* ===== IDENTITY -> SIMULATOR SYNC ===== */
            $('#asstName').on('input', function() {
                $('#simName').text($(this).val() || 'Asistente');
            });
            $('#asstRole').on('input', function() {
                $('#simRole').text($(this).val() || 'Asistente IA');
            });

            /* ===== SIMULATOR ===== */
            const $body = $('#simBody'),
                $input = $('#simInput');

            function time() {
                return new Date().toLocaleTimeString('es-ES', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            function addBubble(text, dir) {
                $body.append(`<div class="bubble ${dir}">${text}<span class="time">${time()}</span></div>`);
                $body.scrollTop($body[0].scrollHeight);
            }

            function botReply(userText) {
                const t = userText.toLowerCase();
                const keywords = ['cotización', 'cotizacion', 'precio', 'asesor', 'factura', 'soporte'];
                if (keywords.some(k => t.includes(k))) {
                    return 'Un momento por favor, te estoy conectando con un asesor humano. 🧑‍💼';
                }
                if (t.includes('hola') || t.includes('buenas')) {
                    return '¡Hola! Con gusto te ayudo. ¿Sobre qué servicio te gustaría saber más?';
                }
                if (t.includes('horario')) {
                    return 'Nuestro horario de atención es de 8:00 a 18:00, de lunes a viernes.';
                }
                if (t.includes('servicio') || t.includes('ofrecen')) {
                    return 'En GIJAC WEB ofrecemos desarrollo web, chatbots y automatización de WhatsApp. ¿Cuál te interesa?';
                }
                return 'Entiendo tu consulta. Según nuestra base de conocimiento, puedo ayudarte con eso. ¿Deseas más detalles?';
            }

            function send() {
                const text = $input.val().trim();
                if (!text) return;
                addBubble(text, 'out');
                $input.val('');
                const $typing = $('<div class="bubble in typing">escribiendo...</div>');
                $body.append($typing);
                $body.scrollTop($body[0].scrollHeight);
                setTimeout(() => {
                    $typing.remove();
                    addBubble(botReply(text), 'in');
                }, 900);
            }
            $('#simSend').on('click', send);
            $input.on('keypress', e => {
                if (e.which === 13) send();
            });

            /* ===== SAVE TOAST ===== */
            $('#btnSave').on('click', function() {
                new bootstrap.Toast(document.getElementById('saveToast')).show();
            });
        });
    </script>
@endsection
