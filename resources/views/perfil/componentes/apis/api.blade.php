<div class="card mb-5">
    <!--begin::Header-->
    <div class="card-header card-header-stretch">
        <!--begin::Title-->
        <div class="card-title">
            <h3 class="text-gijac">{{ __('Información General') }}</h3>
        </div>
        <!--end::Title-->
        <div class="card-toolbar">
        </div>
    </div>
    <!--end::Header-->

    <!--begin::Body-->
    <div class="card-body m-5">
        <p class="mb-4">{{ __('Usa la API para enviar mensajes y administrar contactos y etiquetas desde tu propio sistema. Todas las solicitudes se envían por HTTPS con tu API Key en el encabezado') }} <code>X-API-KEY</code>.</p>

        <div class="mb-5">
            <div class="fw-bold mb-1">{{ __('URL base') }}</div>
            <code>{{ url('/api') }}</code>
            <div class="text-muted fs-7 mt-1">{{ __('Límite: 60 solicitudes por minuto por llave. Los teléfonos van en formato internacional sin espacios, por ejemplo 573001234567.') }}</div>
        </div>

        <div class="table-responsive mb-5">
            <table class="table table-row-bordered gy-3 fs-7">
                <thead>
                    <tr class="fw-bold bg-lighten">
                        <th>{{ __('Método y ruta') }}</th>
                        <th>{{ __('Descripción') }}</th>
                        <th>{{ __('Permiso') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><code>POST /v1/mensajes/texto</code></td><td>{{ __('Texto libre (solo dentro de 24 h desde la última interacción). Campos: telefono, mensaje.') }}</td><td><code>messages.send</code></td></tr>
                    <tr><td><code>POST /plantillas/enviar-individual</code></td><td>{{ __('Plantilla aprobada. Campos: plantilla (ID), telefono, info.variables.') }}</td><td><code>messages.send</code></td></tr>
                    <tr><td><code>GET /v1/mensajes/{id}</code></td><td>{{ __('Estado de un mensaje enviado (enviado, entregado, leído, fallido).') }}</td><td><code>messages.send</code></td></tr>
                    <tr><td><code>GET /v1/contactos</code></td><td>{{ __('Lista paginada. Parámetros: buscar, por_pagina, page.') }}</td><td><code>contacts.read</code></td></tr>
                    <tr><td><code>GET /v1/contactos/{telefono}</code></td><td>{{ __('Un contacto con sus etiquetas.') }}</td><td><code>contacts.read</code></td></tr>
                    <tr><td><code>POST /v1/contactos</code></td><td>{{ __('Crea un contacto. Campos: telefono, nombre, apellido.') }}</td><td><code>contacts.write</code></td></tr>
                    <tr><td><code>PUT /v1/contactos/{telefono}</code></td><td>{{ __('Edita nombre, apellido o género.') }}</td><td><code>contacts.write</code></td></tr>
                    <tr><td><code>GET /v1/etiquetas</code></td><td>{{ __('Etiquetas de tu empresa.') }}</td><td><code>tags.read</code></td></tr>
                    <tr><td><code>POST /v1/contactos/{telefono}/etiquetas</code></td><td>{{ __('Asigna una etiqueta. Campo: etiqueta (ID).') }}</td><td><code>tags.write</code></td></tr>
                    <tr><td><code>DELETE /v1/contactos/{telefono}/etiquetas/{id}</code></td><td>{{ __('Quita una etiqueta.') }}</td><td><code>tags.write</code></td></tr>
                </tbody>
            </table>
        </div>

        <div class="fw-bold mb-2">{{ __('Ejemplo') }}</div>
<pre class="bg-light rounded p-4 fs-7 mb-0"><code>curl -X POST {{ url('/api/v1/mensajes/texto') }} \
  -H "X-API-KEY: GMB-xxxxxxxx" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"telefono":"573001234567","mensaje":"Hola, ¿en qué te ayudamos?"}'</code></pre>
    </div>
    <!--end::Body-->
</div>

<div class="card mb-5">
    <!--begin::Header-->
    <div class="card-header card-header-stretch">
        <!--begin::Title-->
        <div class="card-title">
            <h3 class="text-gijac">{{ __('API Keys') }}</h3>
        </div>
        <!--end::Title-->
        <div class="card-toolbar">
            <div class="m-5">
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalCrearAPIKey">
                    <i class="fas fa-key fs-2"></i>
                    {{ __('Generar una API key') }}
                </button>
            </div>
        </div>
    </div>
    <!--end::Header-->

    <!--begin::Body-->
    <div class="card-body m-5">
        <!--begin::Table wrapper-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9" id="kt_api_keys_table">
                <!--begin::Thead-->
                <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                    <tr>
                        <th class="text-center all">#</th>
                        <th class="text-center all">{{ __('Etiqueta') }}</th>
                        <th class="text-center all">{{ __('API Keys') }}</th>
                        {{-- <th class="text-center all">Copiar</th> --}}
                        <th class="text-center all">{{ __('Fecha creación') }}</th>
                        <th class="text-center all">{{ __('Estado') }}</th>
                        <th class="text-center all">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <!--end::Thead-->

                <!--begin::Tbody-->
                <tbody class="fs-6 fw-semibold text-gray-600"></tbody>
                <!--end::Tbody-->
            </table>
            <!--end::Table-->
        </div>
        <!--end::Table wrapper-->
    </div>
    <!--end::Body-->
</div>

<div class="card mb-5">
    <div class="card-header card-header-stretch">
        <div class="card-title">
            <h3 class="text-gijac">{{ __('Webhook') }}</h3>
        </div>
    </div>

    <div class="card-body m-5">
        <p class="mb-4">{{ __('Recibe en tu sistema, en tiempo real, los mensajes de tus clientes y el estado de los mensajes que envías.') }}</p>

        <form id="formApiWebhook">
            <div class="mb-5">
                <label class="required fs-5 mb-1">{{ __('URL de destino') }}</label>
                <input type="url" id="webhookUrl" class="form-control" placeholder="https://tu-sistema.com/webhooks/gijac" maxlength="500" required>
                <div class="text-muted fs-7 mt-1">{{ __('Debe usar https y ser accesible desde internet.') }}</div>
            </div>

            <div class="mb-5">
                <label class="fs-5 mb-2">{{ __('Eventos') }}</label>
                @foreach (\App\Models\ApiWebhook::EVENTOS as $evento => $texto)
                    <div class="form-check form-check-custom form-check-solid mb-2">
                        <input class="form-check-input webhook-evento" type="checkbox" value="{{ $evento }}" id="evento_{{ $loop->index }}" checked>
                        <label class="form-check-label" for="evento_{{ $loop->index }}">{{ __($texto) }} <code class="ms-2">{{ $evento }}</code></label>
                    </div>
                @endforeach
            </div>

            <div class="form-check form-switch form-check-custom form-check-solid mb-5">
                <input class="form-check-input" type="checkbox" id="webhookActivo" checked>
                <label class="form-check-label" for="webhookActivo">{{ __('Webhook activo') }}</label>
            </div>

            <div id="webhookEstado" class="text-muted fs-7 mb-5"></div>

            <div class="d-flex flex-wrap gap-3">
                <button type="submit" class="btn btn-success">{{ __('Guardar') }}</button>
                <button type="button" class="btn btn-bg-secondary" id="btnWebhookProbar">{{ __('Enviar prueba') }}</button>
                <button type="button" class="btn btn-light-danger" id="btnWebhookSecreto">{{ __('Regenerar secreto') }}</button>
            </div>
        </form>

        <div class="separator my-6"></div>

        <div class="fw-bold mb-2">{{ __('Cómo verificar que el envío es de GIJAC') }}</div>
        <p class="fs-7 text-muted mb-2">{{ __('Cada solicitud es un POST con JSON y los encabezados X-GMB-Event, X-GMB-Delivery, X-GMB-Timestamp y X-GMB-Signature. Calcula el HMAC-SHA256 de "timestamp.cuerpo" con tu secreto y compáralo con la firma. Responde con HTTP 2xx; si no, se reintenta hasta 5 veces.') }}</p>
<pre class="bg-light rounded p-4 fs-7 mb-0"><code>$cuerpo = file_get_contents('php://input');
$ts     = $_SERVER['HTTP_X_GMB_TIMESTAMP'];
$firma  = 'sha256=' . hash_hmac('sha256', $ts . '.' . $cuerpo, $secreto);

if (!hash_equals($firma, $_SERVER['HTTP_X_GMB_SIGNATURE']) || abs(time() - (int) $ts) &gt; 300) {
    http_response_code(401); exit;
}</code></pre>
    </div>
</div>

<div class="card mb-5 mb-xxl-10">
    <!--begin::Card header-->
    <div class="card-header">
        <!--begin::Heading-->
        <div class="card-title">
            <h3 class="text-gijac">{{ __('Sesiones de inicio de sesión') }}</h3>
        </div>
        <!--end::Heading-->
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body m-5">
        <!--begin::Table wrapper-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9" id="tablaApiKeyLog">
                <!--begin::Thead-->
                <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                    <tr>
                        <th class="text-center all">#</th>
                        <th class="text-center all">{{ __('Ubicación') }}</th>
                        <th class="text-center all">{{ __('Dirección IP') }}</th>
                        <th class="text-center all">{{ __('Dispositivo') }}</th>
                        <th class="text-center all">{{ __('Fecha') }}</th>
                        <th class="text-center all">{{ __('Estado') }}</th>
                    </tr>
                </thead>
                <!--end::Thead-->

                <!--begin::Tbody-->
                <tbody class="fw-6 fw-semibold text-gray-600"></tbody>
                <!--end::Tbody-->
            </table>
            <!--end::Table-->
        </div>
        <!--end::Table wrapper-->
    </div>
    <!--end::Card body-->
</div>
