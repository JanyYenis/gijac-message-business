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
                    <i class="bi bi-plug-fill text-white"></i>
                    Conectado
                </span>
                <button class="btn btn-light-wa" id="btnGuardar">
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
                <h3>1</h3>
                <small class="fs-6">Webhook Configurado</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-ico" style="background:var(--blue)">
                    <i class="bi bi-lightning-charge-fill text-white fs-1"></i>
                </div>
                <h3>2,847</h3>
                <small class="fs-6">Automatizaciones Ejecutadas</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-ico" style="background:var(--wa-dark)">
                    <i class="bi bi-chat-dots-fill text-white fs-1"></i>
                </div>
                <h3>18,920</h3>
                <small class="fs-6">Mensajes Procesados</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-ico" style="background:var(--gray)">
                    <i class="bi bi-clock-history text-white fs-1"></i>
                </div>
                <h3>Hace 3 min</h3>
                <small class="fs-6">Última Ejecución</small>
            </div>
        </div>
    </div>

    <!-- MAIN -->
    <div class="row g-4">

        <!-- LEFT COLUMN 65% -->
        <div class="col-lg-8">

            <!-- WEBHOOK CONFIG -->
            <div class="card-mod mb-4">
                <div class="card-header fs-1">
                    <i class="bi bi-gear-fill text-primary fs-1"></i>
                    Configuración del Webhook
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fs-6">Nombre de la Automatización</label>
                            <input type="text" class="form-control" value="Atención al Cliente IA">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-6">Método HTTP</label>
                            <select class="form-select">
                                <option selected>POST</option>
                                <option>GET</option>
                                <option>PUT</option>
                                <option>PATCH</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fs-6">Descripción</label>
                            <textarea class="form-control" rows="2">Procesa los mensajes entrantes de WhatsApp y los reenvía al flujo de n8n.</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fs-6">URL Webhook n8n</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-link-45deg"></i>
                                </span>
                                <input type="text" class="form-control"
                                    value="https://n8n.miempresa.com/webhook/whatsapp-gijac">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fs-6">Token de Seguridad</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-shield-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="tokenInput" value="gjc_sk_8f3a91b27c">
                                <button class="btn btn-outline-secondary" type="button" id="toggleToken">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check form-switch fs-5">
                                <input class="form-check-input" type="checkbox" id="webhookActivo" checked>
                                <label class="form-check-label fs-6 fw-semibold" for="webhookActivo">Webhook Activo</label>
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
                        <button class="btn btn-sm btn-light btn-copy" id="btnCopyJson">
                            <i class="bi bi-clipboard me-1"></i>
                            Copiar Ejemplo
                        </button>
                        <span class="p">{</span>
                        <span class="k">"numero"</span>
                        <span class="p">:</span>
                        <span class="s">"573001234567"</span>
                        <span class="p">,</span>
                        <span class="k">"nombre"</span>
                        <span class="p">:</span>
                        <span class="s">"Juan Pérez"</span>
                        <span class="p">,</span>
                        <span class="k">"mensaje"</span>
                        <span class="p">:</span>
                        <span class="s">"Hola"</span>
                        <span class="p">,</span><br>
                        <span class="k">"mensaje_id"</span>
                        <span class="p">:</span>
                        <span class="s">"MSG001"</span>
                        <span class="p">,</span>
                        <span class="k">"empresa_id"</span>
                        <span class="p">:</span>
                        <span class="s">"1"</span>
                        <span class="p">,</span>
                        <span class="k">"canal"</span>
                        <span class="p">:</span>
                        <span class="s">"whatsapp"</span>
                        <span class="p">,</span><br>
                        <span class="k">"fecha"</span>
                        <span class="p">:</span>
                        <span class="s">"2026-06-11 10:00:00"</span>
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
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ev1" checked>
                                <label class="form-check-label" for="ev1">Nuevo mensaje</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ev2">
                                <label class="form-check-label" for="ev2">Mensaje enviado</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ev3" checked>
                                <label class="form-check-label" for="ev3">Conversación iniciada</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ev4">
                                <label class="form-check-label" for="ev4">Conversación cerrada</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ev5">
                                <label class="form-check-label" for="ev5">Etiqueta agregada</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ev6" checked>
                                <label class="form-check-label" for="ev6">Cliente creado</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ev7">
                                <label class="form-check-label" for="ev7">Campaña entregada</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ev8">
                                <label class="form-check-label" for="ev8">Campaña leída</label>
                            </div>
                        </div>
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
                            <label class="form-label fs-6">Cantidad de reintentos</label>
                            <input type="number" class="form-control" value="3" min="0" max="10">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-6">Tiempo entre intentos (s)</label>
                            <input type="number" class="form-control" value="5" min="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-6">Timeout (s)</label>
                            <input type="number" class="form-control" value="30" min="5">
                        </div>
                    </div>
                </div>
            </div>
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
                        data-desc="Recibe mensajes, clasifica intención y responde automáticamente." data-nodes="8"
                        data-emoji="📩">
                        <div class="tpl-ico">📩</div>
                        <div class="flex-grow-1">
                            <h6>Atención al Cliente</h6>
                            <small>8 nodos</small>
                        </div>
                        <button class="btn btn-sm btn-wa-outline btn-tpl">
                            <i class="bi bi-download"></i>
                        </button>
                    </div>
                    <div class="tpl-item" data-name="Chatbot IA"
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
                    </div>
                    <div class="tpl-item" data-name="Agendamiento"
                        data-desc="Conecta Google Calendar para reservar citas vía WhatsApp." data-nodes="9"
                        data-emoji="📅">
                        <div class="tpl-ico">📅</div>
                        <div class="flex-grow-1">
                            <h6>Agendamiento</h6>
                            <small>9 nodos</small>
                        </div>
                        <button class="btn btn-sm btn-wa-outline btn-tpl">
                            <i class="bi bi-download"></i>
                        </button>
                    </div>
                    <div class="tpl-item" data-name="Seguimiento de Ventas"
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
                    </div>
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
                        <i class="bi bi-play-circle me-1"></i>
                        Probar Webhook
                    </button>
                    <button class="btn btn-wa-outline w-100 mb-3" data-bs-toggle="modal" data-bs-target="#modalProbar">
                        <i class="bi bi-send me-1"></i>
                        Enviar Prueba Personalizada
                    </button>
                    <div class="test-result test-ok" id="resOk">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        Conexión Exitosa
                        <span class="d-block fw-normal small mt-1">
                            Respuesta 200 OK · 312 ms
                        </span>
                    </div>
                    <div class="test-result test-err" id="resErr">
                        <i class="bi bi-x-circle-fill me-1"></i>
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
                <table class="table table-mod align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Evento</th>
                            <th>Estado</th>
                            <th>Duración</th>
                            <th>Respuesta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2026-06-11 10:03</td>
                            <td>Juan Pérez</td>
                            <td>Nuevo mensaje</td>
                            <td>
                                <span class="badge-soft badge-ok">Exitoso</span>
                            </td>
                            <td>312 ms</td>
                            <td>200 OK</td>
                        </tr>
                        <tr>
                            <td>2026-06-11 10:01</td>
                            <td>María Gómez</td>
                            <td>Conversación iniciada</td>
                            <td>
                                <span class="badge-soft badge-ok">Exitoso</span>
                            </td>
                            <td>248 ms</td>
                            <td>200 OK</td>
                        </tr>
                        <tr>
                            <td>2026-06-11 09:58</td>
                            <td>Carlos Ruiz</td>
                            <td>Cliente creado</td>
                            <td>
                                <span class="badge-soft badge-warn">Pendiente</span>
                            </td>
                            <td>—</td>
                            <td>En cola</td>
                        </tr>
                        <tr>
                            <td>2026-06-11 09:55</td>
                            <td>Ana Torres</td>
                            <td>Nuevo mensaje</td>
                            <td>
                                <span class="badge-soft badge-err">Error</span>
                            </td>
                            <td>30 s</td>
                            <td>504 Timeout</td>
                        </tr>
                        <tr>
                            <td>2026-06-11 09:50</td>
                            <td>Luis Mejía</td>
                            <td>Nuevo mensaje</td>
                            <td>
                                <span class="badge-soft badge-ok">Exitoso</span>
                            </td>
                            <td>290 ms</td>
                            <td>200 OK</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- MODAL TEMPLATE -->
    <div class="modal fade" id="modalTpl" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px;border:none;">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="tplTitle">Plantilla</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div style="font-size:3rem" id="tplEmoji">📩</div>
                    </div>
                    <p class="text-muted" id="tplDesc"></p>
                    <div class="bg-light rounded p-3 text-center mb-3">
                        <i class="bi bi-diagram-3 text-primary fs-1" style="font-size:2.5rem"></i>
                        <div class="small text-muted mt-2">Vista previa del flujo</div>
                    </div>
                    <p class="mb-0"><strong>Nodos:</strong> <span id="tplNodes"></span></p>
                </div>
                <div class="modal-footer border-0">
                    <button class="btn btn-wa" id="btnDownloadJson"><i class="bi bi-filetype-json me-1"></i>Descargar
                        JSON</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TEST -->
    <div class="modal fade" id="modalProbar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px;border:none;">
                <div class="modal-header border-0">
                    <h5 class="modal-title"><i class="bi bi-send me-2 text-primary fs-1"></i>Probar Webhook</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2"><label class="form-label">Número</label><input type="text"
                            class="form-control" id="tNum" value="573001234567"></div>
                    <div class="mb-2"><label class="form-label">Nombre</label><input type="text"
                            class="form-control" id="tName" value="Juan Pérez"></div>
                    <div class="mb-3"><label class="form-label">Mensaje</label><input type="text"
                            class="form-control" id="tMsg" value="Hola"></div>
                    <button class="btn btn-wa w-100 mb-3" id="btnEnviarPrueba"><i class="bi bi-send me-1"></i>Enviar
                        Prueba</button>
                    <div id="testOut" style="display:none">
                        <label class="form-label">Request enviado</label>
                        <div class="req-box mb-2" id="reqBox"></div>
                        <label class="form-label">Response recibida</label>
                        <div class="req-box mb-2" id="resBox"></div>
                        <span class="badge bg-success" id="timeBadge"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script src="{{ mix('/js/chatbots/principal.js') }}" ></script> --}}
    <script>
        $(function() {
            // Guardar
            $('#btnGuardar').on('click', function() {
                var t = bootstrap.Toast.getOrCreateInstance(document.getElementById('toastSave'));
                t.show();
            });

            // Toggle token
            $('#toggleToken').on('click', function() {
                var $i = $('#tokenInput'),
                    $ic = $(this).find('i');
                if ($i.attr('type') === 'password') {
                    $i.attr('type', 'text');
                    $ic.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    $i.attr('type', 'password');
                    $ic.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });

            // Copiar JSON
            $('#btnCopyJson').on('click', function() {
                var txt = $('#jsonExample').text().replace('Copiar Ejemplo', '').trim();
                navigator.clipboard.writeText(txt);
                var $b = $(this);
                $b.html('<i class="bi bi-check2 me-1"></i>Copiado');
                setTimeout(function() {
                    $b.html('<i class="bi bi-clipboard me-1"></i>Copiar Ejemplo');
                }, 1500);
            });

            // Probar webhook (simulado)
            $('#btnProbar').on('click', function() {
                var $b = $(this);
                $b.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span>Probando...');
                $('#resOk,#resErr').hide();
                setTimeout(function() {
                    $b.prop('disabled', false).html(
                        '<i class="bi bi-play-circle me-1"></i>Probar Webhook');
                    if (Math.random() > 0.2) {
                        $('#resOk').fadeIn();
                    } else {
                        $('#resErr').fadeIn();
                    }
                }, 1400);
            });

            // Template modal
            $('.tpl-item').on('click', function() {
                $('#tplTitle').text($(this).data('name'));
                $('#tplDesc').text($(this).data('desc'));
                $('#tplNodes').text($(this).data('nodes') + ' nodos');
                $('#tplEmoji').text($(this).data('emoji'));
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTpl')).show();
            });

            $('#btnDownloadJson').on('click', function() {
                var name = $('#tplTitle').text();
                var data = {
                    name: name,
                    nodes: [],
                    connections: {},
                    active: true
                };
                var blob = new Blob([JSON.stringify(data, null, 2)], {
                    type: 'application/json'
                });
                var a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = name.toLowerCase().replace(/ /g, '-') + '-n8n.json';
                a.click();
            });

            // Enviar prueba en modal
            $('#btnEnviarPrueba').on('click', function() {
                var req = {
                    numero: $('#tNum').val(),
                    nombre: $('#tName').val(),
                    mensaje: $('#tMsg').val(),
                    mensaje_id: 'MSG' + Math.floor(Math.random() * 9999),
                    empresa_id: '1',
                    canal: 'whatsapp',
                    fecha: '2026-06-11 10:00:00'
                };
                var $b = $(this);
                $b.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span>Enviando...');
                setTimeout(function() {
                    $('#reqBox').text(JSON.stringify(req, null, 2));
                    $('#resBox').text(JSON.stringify({
                        status: 'ok',
                        received: true,
                        workflow: 'whatsapp-gijac'
                    }, null, 2));
                    $('#timeBadge').text('Tiempo de respuesta: ' + (180 + Math.floor(Math.random() *
                        200)) + ' ms');
                    $('#testOut').fadeIn();
                    $b.prop('disabled', false).html('<i class="bi bi-send me-1"></i>Enviar Prueba');
                }, 1200);
            });
        });
    </script>
@endsection
