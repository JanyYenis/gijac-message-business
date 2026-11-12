@extends('layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ mix('css/config.css') }}">
@endsection

@section('content')
    <main class="dash-content">
        <!-- PAGE HEADER -->
        <div class="page-header">
            <h1 class="text-white">{{ __('Conexión WhatsApp Business API') }}</h1>
            <p class="text-white">
                {{ __('Conecta y administra tu número oficial de WhatsApp desde GIJAC Message Business.') }}
            </p>
        </div>

        <!-- ============================================================
            HERO DE ONBOARDING (composición 3D)
            ============================================================ -->
        <section class="wa-hero" id="waHero" aria-labelledby="heroTitle">
            <div class="h-glow h-glow-1" aria-hidden="true"></div>
            <div class="h-glow h-glow-2" aria-hidden="true"></div>
            <span class="h-particle" style="width: 5px; height: 5px; top: 18%; left: 8%" aria-hidden="true"></span>
            <span class="h-particle" style="width: 3px; height: 3px; top: 70%; left: 30%; animation-delay: 1.2s;"
                aria-hidden="true"></span>
            <span class="h-particle" style="width: 4px; height: 4px; top: 24%; left: 88%; animation-delay: 0.6s;"
                aria-hidden="true"></span>
            <span class="h-particle" style="width: 3px; height: 3px; top: 80%; left: 70%; animation-delay: 1.8s;"
                aria-hidden="true"></span>

            <div class="wa-hero-inner">
                <div>
                    <span class="hero-badge">
                        <span class="dot-live"></span>
                        {{ __('WHATSAPP BUSINESS PLATFORM') }}
                    </span>
                    <h2 id="heroTitle">
                        {{ __('Conecta tu') }} <span class="gradient-text">{{ __('WhatsApp Business') }}</span>
                    </h2>
                    <p class="hero-txt">
                        {{ __('Integra tu número oficial de WhatsApp y comienza a gestionar conversaciones, campañas y automatizaciones desde GIJAC Message Business.') }}
                    </p>
                </div>

                <!-- Composición 3D: GIJAC → META → WhatsApp -->
                <div>
                    <div class="flow-perspective">
                        <div class="flow-stage" id="flowStage">
                            <div class="fnode-wrap z1">
                                <div class="fnode float-a">
                                    <span class="fnode-chip chip-gijac">
                                        <img src="{{ asset('img/logo_gmb.png') }}" alt="GIJAC Message Business"
                                            width="100%">
                                    </span>
                                    <strong>{{ __('GIJAC') }}</strong><small>{{ __('MESSAGE BUSINESS') }}</small>
                                </div>
                            </div>
                            <div class="fconn" aria-hidden="true">
                                <i class="d1"></i>
                                <i class="d2"></i>
                            </div>
                            <div class="fnode-wrap z2">
                                <div class="fnode fnode-meta float-b">
                                    <span class="fnode-chip chip-meta">
                                        <img src="{{ asset('img/meta.png') }}" alt="META" width="100%">
                                    </span>
                                    <strong>{{ __('META') }}</strong>
                                    <small>{{ __('TECHNOLOGY PROVIDER') }}</small>
                                </div>
                            </div>
                            <div class="fconn" aria-hidden="true">
                                <i class="d1"></i>
                                <i class="d2"></i>
                            </div>
                            <div class="fnode-wrap z3">
                                <div class="fnode float-c">
                                    <span class="fnode-chip chip-wa">
                                        <img src="{{ asset('img/whatsapp-business.png') }}" alt="WhatsApp" width="100%">
                                    </span>
                                    <strong>{{ __('WhatsApp') }}</strong><small>{{ __('BUSINESS') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="hero-trustline">
                            <i class="bi bi-shield-lock-fill"></i>
                            {{ __('Conexión encriptada · Infraestructura oficial') }}
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
            ESTADO DE CONEXIÓN
            ============================================================ -->
        <div class="section-head">
            <h3 class="fs-2">{{ __('Estado de conexión') }}</h3>
            <p class="fs-5">
                {{ __('Situación actual de tu vinculación con WhatsApp Business Platform.') }}
            </p>
        </div>

        <div class="gi-card tilt-3d" id="stateCard">
            <div class="gi-card-pad">
                <!-- ===== ESTADO: NO CONECTADO ===== -->
                <div id="stateDisconnected" class="fade-swap">
                    <span class="conn-badge off">
                        <span class="dot-off"></span>
                        {{ __('NO CONECTADO') }}
                    </span>
                    <h4 class="state-title fs-3">
                        {{ __('Tu cuenta de WhatsApp Business todavía no está vinculada.') }}
                    </h4>
                    <p class="state-desc fs-7">
                        {{ __('Conecta tu cuenta de Meta Business para comenzar a utilizar WhatsApp Business desde GIJAC.') }}
                    </p>
                    <button type="button" class="btn btn-meta btn-lg" id="btnConnectMeta">
                        <i class="bi bi-meta fs-3 text-white"></i>
                        {{ __('Conectar con Meta') }}
                    </button>

                    <div class="req-label fs-5">{{ __('Antes de comenzar') }}</div>
                    <div class="req-grid">
                        <div class="req-mini">
                            <span class="req-ico">
                                <i class="bi bi-check-lg text-primary"></i>
                            </span>
                            <span>
                                <strong class="fs-4">{{ __('Business Manager de Meta') }}</strong>
                                <small class="fs-7">{{ __('Con acceso de administrador') }}</small>
                            </span>
                        </div>
                        <div class="req-mini">
                            <span class="req-ico">
                                <i class="bi bi-check-lg text-primary"></i>
                            </span>
                            <span>
                                <strong class="fs-4">{{ __('Cuenta de WhatsApp Business') }}</strong>
                                <small class="fs-7">{{ __('WABA activa en tu portafolio') }}</small>
                            </span>
                        </div>
                        <div class="req-mini">
                            <span class="req-ico">
                                <i class="bi bi-check-lg text-primary"></i>
                            </span>
                            <span>
                                <strong class="fs-4">{{ __('Número disponible para WhatsApp') }}</strong>
                                <small class="fs-7">{{ __('Sin vincular a otra plataforma') }}</small>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- ===== ESTADO: CONECTADO ===== -->
                <div id="stateConnected" class="d-none">
                    <span class="conn-badge on">
                        <span class="dot-live"></span>
                        {{ __('CONECTADO') }}
                    </span>
                    <h4 class="state-title fs-3">
                        {{ __('WhatsApp Business conectado correctamente') }}
                    </h4>
                    <p class="state-desc fs-7">
                        {{ __('Tu cuenta está activa y lista para utilizar WhatsApp desde GIJAC Message Business.') }}
                    </p>

                    <!-- Timeline META → WHATSAPP → GIJAC -->
                    <div class="conn-chain" aria-label="Cadena de conexión">
                        <div class="chain-step">
                            <span class="chain-dot">
                                <i class="bi bi-meta fs-3 text-primary"></i>
                                <span class="mini-live"></span>
                            </span>
                            <strong class="fs-4">{{ __('META BUSINESS') }}</strong>
                            <small class="fs-7">{{ __('Portafolio verificado') }}</small>
                        </div>
                        <div class="chain-step">
                            <span class="chain-dot">
                                <i class="bi bi-whatsapp fs-3 text-primary"></i>
                                <span class="mini-live"></span>
                            </span>
                            <strong class="fs-4">{{ __('WHATSAPP BUSINESS') }}</strong>
                            <small class="fs-7">{{ __('Número oficial vinculado') }}</small>
                        </div>
                        <div class="chain-step">
                            <span class="chain-dot">
                                <i class="bi bi-hexagon-fill fs-3 text-primary"></i>
                                <span class="mini-live"></span>
                            </span>
                            <strong class="fs-4">{{ __('GIJAC MESSAGE BUSINESS') }}</strong>
                            <small class="fs-7">{{ __('Plataforma operativa') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================
            DASHBOARD CONECTADO (3 columnas · visible solo conectado)
            ============================================================ -->
        <div id="dashConnected" class="d-none">
            <div class="row g-4 mt-1">
                <!-- CARD 1 · CUENTA WHATSAPP -->
                <div class="col-lg-4">
                    <div class="gi-card gi-card-pad h-100 tilt-3d">
                        <h4 class="fs-4">
                            <i class="bi bi-person-badge"></i>
                            {{ __('Cuenta WhatsApp Business') }}
                        </h4>
                        <div class="id-row">
                            <span class="id-label fs-6">
                                <i class="bi bi-hash"></i>
                                {{ __('WABA ID') }}
                            </span>
                            <span class="id-value fs-6">
                                <code class="fs-6" data-field="waba_id"></code>
                                <button class="copy-btn" data-copy="" data-bs-toggle="tooltip"
                                    data-bs-title="{{ __('Copiar ID') }}" aria-label="{{ __('Copiar WABA ID') }}">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </span>
                        </div>
                        <div class="id-row">
                            <span class="id-label fs-6">
                                <i class="bi bi-phone"></i>
                                {{ __('Phone Number ID') }}
                            </span>
                            <span class="id-value fs-6">
                                <code class="fs-6" data-field="phone_number_id"></code>
                                <button class="copy-btn" data-copy="" data-bs-toggle="tooltip"
                                    data-bs-title="{{ __('Copiar ID') }}"
                                    aria-label="{{ __('Copiar Phone Number ID') }}">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </span>
                        </div>
                        <div class="id-row">
                            <span class="id-label fs-6">
                                <i class="bi bi-telephone"></i>
                                {{ __('Número vinculado') }}
                            </span>
                            <span class="id-value fs-6">
                                <code class="fs-6" data-field="phone_number"></code>
                                <button class="copy-btn" data-copy="" data-bs-toggle="tooltip"
                                    data-bs-title="{{ __('Copiar ID') }}" aria-label="{{ __('Copiar número') }}">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </span>
                        </div>
                        <div class="id-row">
                            <span class="id-label fs-6">
                                <i class="bi bi-building"></i>
                                {{ __('Business ID') }}
                            </span>
                            <span class="id-value fs-6">
                                <code class="fs-6" data-field="business_id"></code>
                                <button class="copy-btn" data-copy="" data-bs-toggle="tooltip"
                                    data-bs-title="{{ __('Copiar ID') }}" aria-label="{{ __('Copiar Business ID') }}">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- CARD 2 · ESTADO TÉCNICO -->
                <div class="col-lg-4">
                    <div class="gi-card gi-card-pad h-100 tilt-3d">
                        <h4 class="fs-4">
                            <i class="bi bi-cpu"></i>
                            {{ __('Estado técnico') }}
                        </h4>
                        <div class="tech-row" data-check="webhook">
                            <div class="tech-info">
                                <strong class="fs-6">{{ __('Webhook') }}</strong>
                                <small class="fs-7">{{ __('Eventos en tiempo real') }}</small>
                                <div class="mini-bar">
                                    <div class="mini-fill" data-fill="100"></div>
                                </div>
                            </div>
                            <span class="mini-badge mb-ok fs-7">
                                <span class="q-dot"></span>
                                {{ __('Suscrito') }}
                            </span>
                        </div>
                        <div class="tech-row" data-check="numero">
                            <div class="tech-info">
                                <strong class="fs-6">{{ __('Número') }}</strong>
                                <small class="fs-7">{{ __('Registro en Cloud API') }}</small>
                                <div class="mini-bar">
                                    <div class="mini-fill" data-fill="100"></div>
                                </div>
                            </div>
                            <span class="mini-badge mb-ok fs-7">
                                <span class="q-dot"></span>
                                {{ __('Registrado') }}
                            </span>
                        </div>
                        <div class="tech-row" data-check="salud">
                            <div class="tech-info">
                                <strong class="fs-6">{{ __('API') }}</strong>
                                <small class="fs-7">{{ __('Envío y recepción') }}</small>
                                <div class="mini-bar">
                                    <div class="mini-fill" data-fill="100"></div>
                                </div>
                            </div>
                            <span class="mini-badge mb-ok fs-7">
                                <span class="q-dot"></span>
                                {{ __('Operativa') }}
                            </span>
                        </div>
                        <div class="tech-row" data-check="token">
                            <div class="tech-info">
                                <strong class="fs-6">{{ __('Token') }}</strong>
                                <small class="fs-7" data-field="token_text">{{ __('Expira en 58 días') }}</small>
                                <div class="mini-bar">
                                    <div class="mini-fill" data-fill="82"></div>
                                </div>
                            </div>
                            <span class="mini-badge mb-ok fs-7">
                                <span class="q-dot"></span>
                                {{ __('Activo') }}
                            </span>
                        </div>
                        <div class="tech-row">
                            <div class="tech-info">
                                <strong class="fs-6">{{ __('API Version') }}</strong>
                                <small class="fs-7">{{ __('Graph API') }}</small>
                            </div>
                            <span class="mini-badge mb-ok fs-7 api-pill" data-field="api_version">v0.0</span>
                        </div>
                    </div>
                </div>

                <!-- CARD 3 · CALIDAD Y CAPACIDAD -->
                <div class="col-lg-4">
                    <div class="gi-card gi-card-pad h-100 tilt-3d">
                        <h4 class="fs-4">
                            <i class="bi bi-heart-pulse"></i>
                            {{ __('Calidad del número') }}
                        </h4>
                        <div class="q-hero">
                            <span class="q-big">
                                <span class="q-dot"></span>
                                <span data-field="quality">
                                    {{ __('Excelente') }}
                                </span>
                            </span>
                        </div>
                        <div class="limit-caption">{{ __('Límite de mensajes') }}</div>
                        <div class="limit-value">
                            <span data-field="limit">10K</span>
                            <small class="fs-7">{{ __('mensajes / día') }}</small>
                        </div>
                        <div class="tier-meter" role="img" aria-label="{{ __('Nivel de capacidad: 10K de 100K') }}">
                            <span class="tier-seg"></span>
                            <span class="tier-seg active"></span>
                            <span class="tier-seg"></span>
                        </div>
                        <div class="tier-labels fs-5">
                            <span>1K</span>
                            <span class="on">10K</span>
                            <span>100K</span>
                        </div>
                        <p class="text-center mb-0 mt-3 fs-6" style="color: #8aa0a2">
                            {{ __('Los límites aumentan automáticamente al mantener una buena calidad.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================
            PAGO Y VERIFICACIONES · NÚMEROS (visible solo conectado)
            ============================================================ -->
        <div id="dashExtra" class="d-none">
            <div class="section-head">
                <h3 class="fs-2">{{ __('Pago y verificaciones') }}</h3>
                <p class="fs-5">{{ __('Lo que Meta necesita para que envíes mensajes con tu número de producción.') }}</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="gi-card gi-card-pad h-100">
                        <h4 class="fs-4"><i class="bi bi-credit-card"></i> {{ __('Método de pago') }}</h4>
                        <span class="mini-badge mb-warn fs-7" id="payBadge"><span class="q-dot"></span> {{ __('Verificando…') }}</span>
                        <p class="mt-3 mb-2 fs-6" id="payText" style="color: #6b8083"></p>
                        <p class="mb-3 fs-7" style="color: #8aa0a2">{{ __('El número de prueba no necesita tarjeta. Solo se pide para enviar con un número de producción. Meta no acepta tarjetas prepago, virtuales ni de regalo.') }}</p>
                        <a class="btn btn-glow btn-sm d-none" id="payLink" target="_blank" rel="noopener">
                            <i class="bi bi-box-arrow-up-right"></i> {{ __('Agregar tarjeta en Meta') }}
                        </a>
                        <details class="mt-3 fs-6">
                            <summary>{{ __('¿Cómo se agrega?') }}</summary>
                            <ol class="mt-2 mb-0 fs-7" style="color: #6b8083">
                                <li>{{ __('Abre el botón de arriba (o Meta Business Suite > Facturación y pagos).') }}</li>
                                <li>{{ __('En "Métodos de pago", agrega una tarjeta de crédito o débito.') }}</li>
                                <li>{{ __('En la pestaña "Cuentas de WhatsApp Business", elige tu cuenta y pulsa "Agregar método de pago".') }}</li>
                                <li>{{ __('Vuelve aquí y pulsa "Actualizar configuración".') }}</li>
                            </ol>
                        </details>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="gi-card gi-card-pad h-100">
                        <h4 class="fs-4">
                            <i class="bi bi-patch-check"></i>
                            {{ __('Verificación del negocio') }}
                        </h4>
                        <span class="mini-badge mb-warn fs-7" id="verBadge">
                            <span class="q-dot"></span>
                            {{ __('Verificando…') }}
                        </span>
                        <p class="mt-3 mb-2 fs-6" id="verText" style="color: #6b8083"></p>
                        <p class="mb-3 fs-7" style="color: #8aa0a2">
                            {{ __('Verificar tu negocio sube el límite de contactos nuevos por día y permite hasta 20 números. Meta puede tardar hasta 14 días hábiles.') }}
                        </p>
                        <a class="btn btn-glow btn-sm d-none" id="verLink" target="_blank" rel="noopener">
                            <i class="bi bi-box-arrow-up-right text-white"></i>
                            {{ __('Verificar mi negocio en Meta') }}
                        </a>
                        <details class="mt-3 fs-6">
                            <summary>{{ __('¿Qué necesito?') }}</summary>
                            <ul class="mt-2 mb-0 fs-7" style="color: #6b8083">
                                <li>{{ __('Nombre legal, dirección y teléfono de tu empresa, exactamente como en tus documentos.') }}</li>
                                <li>{{ __('Un sitio web con HTTPS que cargue correctamente.') }}</li>
                                <li>{{ __('Si Meta no encuentra tu empresa, te pedirá un documento oficial (por ejemplo, el certificado de existencia y representación legal).') }}</li>
                            </ul>
                        </details>
                    </div>
                </div>
            </div>

            <div class="gi-card gi-card-pad mt-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                    <h4 class="fs-4 mb-0">
                        <i class="bi bi-telephone"></i>
                        {{ __('Números de WhatsApp') }}</h4>
                    <div class="d-flex align-items-center gap-2">
                        <span class="mini-badge mb-ok fs-7" id="cupoBadge">—</span>
                        <button type="button" class="btn btn-glow btn-sm" id="btnAddNumber">
                            <i class="bi bi-plus-lg text-white"></i>
                            {{ __('Agregar número') }}
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="logs-table fs-5" aria-label="{{ __('Números de WhatsApp de tu cuenta') }}">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Número') }}</th>
                                <th scope="col">{{ __('Nombre visible') }}</th>
                                <th scope="col">{{ __('Estado') }}</th>
                                <th scope="col">{{ __('Calidad y límite') }}</th>
                                <th scope="col">{{ __('Tipo') }}</th>
                                <th scope="col">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody id="numbersBody">
                            <tr><td colspan="6" class="text-center" style="color: #8aa0a2">{{ __('Cargando…') }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ============================================================
            ACCIONES · ADMINISTRAR CONEXIÓN
            ============================================================ -->
        <div class="section-head">
            <h3 class="fs-2">{{ __('Administrar conexión') }}</h3>
            <p class="fs-5">{{ __('Acciones disponibles sobre tu número de WhatsApp Business.') }}</p>
        </div>
        <div class="action-grid">
            <button class="action-card" id="btnTest" data-bs-toggle="modal" data-bs-target="#testModal">
                <i class="ac-ico bi bi-send"></i>
                <span class="fs-5">
                    {{ __('Probar conexión') }}
                    <small class="fs-7">{{ __('Envía un mensaje de verificación') }}</small>
                </span>
            </button>
            <a class="action-card" href="{{ route('plantillas.index') }}" id="btnTemplates">
                <i class="ac-ico bi bi-layout-text-window-reverse"></i>
                <span class="fs-5">
                    {{ __('Ver plantillas') }}
                    <small class="fs-7">{{ __('Gestiona tus plantillas aprobadas') }}</small>
                </span>
            </a>
            <button class="action-card" id="btnSync">
                <i class="ac-ico bi bi-arrow-clockwise"></i>
                <span class="fs-5">
                    {{ __('Actualizar configuración') }}
                    <small class="fs-7">{{ __('Sincroniza datos de la cuenta') }}</small>
                </span>
            </button>
            <button class="action-card danger" id="btnDisconnect" data-bs-toggle="modal"
                data-bs-target="#disconnectModal">
                <i class="ac-ico bi bi-ban"></i>
                <span class="fs-5">
                    {{ __('Desconectar') }}
                    <small class="fs-7">{{ __('Desvincula tu número actual') }}</small>
                </span>
            </button>
        </div>

        <!-- ============================================================
            ACTIVIDAD / LOGS
            ============================================================ -->
        <div class="section-head d-none">
            <h3 class="fs-2">{{ __('Actividad reciente') }}</h3>
            <p class="fs-5">{{ __('Últimos eventos relacionados con tu conexión de WhatsApp.') }}</p>
        </div>
        <div class="gi-card d-none" style="overflow: hidden">
            <div class="table-responsive">
                <table class="logs-table fs-5" aria-label="{{ __('Actividad reciente de la conexión') }}">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('Evento') }}</th>
                            <th scope="col">{{ __('Cuenta') }}</th>
                            <th scope="col">{{ __('Fecha') }}</th>
                            <th scope="col">{{ __('Estado') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-label="Evento">
                                <span class="ev-cell">
                                    <i class="bi bi-plug"></i>
                                    {{ __('Webhook conectado') }}
                                </span>
                            </td>
                            <td data-label="Cuenta">
                                <span class="mono">{{ __('WABA 123456') }}</span>
                            </td>
                            <td data-label="Fecha">{{ __('Hoy, 10:32') }}</td>
                            <td data-label="Estado">
                                <span class="log-badge lb-ok">
                                    <i class="bi bi-check-lg"></i>
                                    {{ __('Correcto') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Evento">
                                <span class="ev-cell">
                                    <i class="bi bi-telephone"></i>
                                    {{ __('Número registrado') }}
                                </span>
                            </td>
                            <td data-label="Cuenta">
                                <span class="mono">+57 300 123 4567</span>
                            </td>
                            <td data-label="Fecha">{{ __('Hoy, 10:31') }}</td>
                            <td data-label="Estado">
                                <span class="log-badge lb-ok">
                                    <i class="bi bi-check-lg"></i>
                                    {{ __('Correcto') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Evento">
                                <span class="ev-cell">
                                    <i class="bi bi-link-45deg"></i>
                                    {{ __('Cuenta vinculada') }}
                                </span>
                            </td>
                            <td data-label="Cuenta">
                                <span class="mono">{{ __('Meta Business') }}</span>
                            </td>
                            <td data-label="Fecha">{{ __('Hoy, 10:30') }}</td>
                            <td data-label="Estado">
                                <span class="log-badge lb-ok">
                                    <i class="bi bi-check-lg"></i>
                                    {{ __('Correcto') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Evento">
                                <span class="ev-cell">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    {{ __('Error de sincronización') }}
                                </span>
                            </td>
                            <td data-label="Cuenta">
                                <span class="mono">{{ __('WABA 123456') }}</span>
                            </td>
                            <td data-label="Fecha">{{ __('Ayer, 18:42') }}</td>
                            <td data-label="Estado">
                                <span class="log-badge lb-warn">
                                    <i class="bi bi-exclamation-lg"></i>
                                    {{ __('Revisar') }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Toasts + toggle demo -->
    <div class="toast-stack" id="toastStack" aria-live="polite"></div>
    <button class="demo-toggle" id="demoToggle" title="Herramienta de demostración">
        <i class="bi bi-toggles"></i>
        {{ __('Demo · Cambiar estado') }}
    </button>
@endsection

@section('modal')
    <!-- ============================================================
        MODAL · PROBAR CONEXIÓN
        ============================================================ -->
    <div class="modal fade" id="testModal" tabindex="-1" aria-labelledby="testModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="p-4">
                    <div id="testFormWrap">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0" id="testModalTitle">
                                <i class="bi bi-send me-2"
                                    style="color: var(--teal-light)"></i>{{ __('Probar conexión') }}
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <form id="testForm">
                            <div class="mb-3">
                                <label for="testTo" class="form-label">{{ __('Número de WhatsApp') }}</label>
                                <input type="tel" class="form-control" id="testTo" value="" placeholder="+57 300 000 0000" />
                            </div>
                            <div class="mb-4">
                                <label for="testMsg" class="form-label">{{ __('Mensaje') }}</label>
                                <textarea class="form-control" id="testMsg" rows="3"
                                    >{{ __('Hola, este es un mensaje de prueba enviado desde GIJAC Message Business.') }}</textarea>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">
                                    {{ __('Cancelar') }}
                                </button>
                                <button type="submit" class="btn btn-glow" id="btnSubmitTest">
                                    <i class="bi bi-send me-1"></i> {{ __('Enviar mensaje de prueba') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- Vista de éxito (se muestra tras el envío simulado) -->
                    <div id="testSuccess" class="d-none text-center py-3">
                        <div class="confirm-ico ci-ok">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <h4 class="mb-2">{{ __('Mensaje enviado') }}</h4>
                        <p class="text-secondary mb-4" style="font-size: 0.9rem">
                            {{ __('La conexión funciona correctamente. Revisa el WhatsApp de destino.') }}
                        </p>
                        <button type="button" class="btn btn-glow" data-bs-dismiss="modal">
                            {{ __('Listo') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================
        MODAL · CONFIRMAR DESCONEXIÓN
        ============================================================ -->
    <div class="modal fade" id="disconnectModal" tabindex="-1" aria-labelledby="discTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 440px">
            <div class="modal-content">
                <div class="p-4 text-center">
                    <div class="confirm-ico ci-warn">
                        <i class="bi bi-exclamation-triangle text-white fs-1"></i>
                    </div>
                    <h4 id="discTitle" class="fs-2">
                        {{ __('¿Desconectar WhatsApp Business?') }}
                    </h4>
                    <p class="text-dark fs-6">
                        {{ __('Al desconectar esta cuenta dejarás de gestionar este número desde GIJAC Message Business.') }}
                    </p>
                    <div class="d-flex justify-content-center gap-2 mt-4">
                        <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">
                            {{ __('Cancelar') }}
                        </button>
                        <button type="button" class="btn btn-danger-soft" id="btnConfirmDisconnect"
                            style="color: #fff; background: #a10000;">
                            <i class="bi bi-plug-x me-1"></i> {{ __('Desconectar cuenta') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================
        MODAL · AGREGAR NÚMERO (asistente de 4 pasos)
        ============================================================ -->
    <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="agTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px">
            <div class="modal-content">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h4 class="mb-0" id="agTitle">
                            <i class="bi bi-people me-2" style="color: var(--teal-light)"></i>
                            {{ __('Agentes del número') }}
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <p class="mb-1 fs-7 mono" id="agNumero" style="color: #8aa0a2"></p>
                    <p class="mb-3 fs-7" id="agAyuda" style="color: #6b8083"></p>
                    <div id="agLista" style="max-height: 320px; overflow-y: auto"></div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                        <button type="button" class="btn btn-glow btn-sm" id="agGuardar">{{ __('Guardar') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="numberModal" tabindex="-1" aria-labelledby="nwTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px">
            <div class="modal-content">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h4 class="mb-0" id="nwTitle">
                            <i class="bi bi-telephone-plus me-2" style="color: var(--teal-light)"></i>
                            {{ __('Agregar número') }}
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <p class="mb-3 fs-7" id="nwLabel" style="color: #8aa0a2"></p>
                    <div class="alert alert-danger py-2 fs-7 d-none" id="nwError" role="alert"></div>

                    <!-- Paso 1 · datos del número -->
                    <form id="nwStep1" class="nw-step">
                        <p class="fs-7" style="color: #6b8083">
                            {{ __('El número debe poder recibir SMS o llamadas y no estar activo en la app WhatsApp (personal o Business). Si lo está, elimina esa cuenta antes de continuar.') }}
                        </p>
                        <div class="row g-2 mb-3">
                            {{-- <div class="col-4">
                                <label for="nwCc" class="form-label">{{ __('País') }}</label>
                                <input type="text" class="form-control" id="nwCc" value="57" inputmode="numeric" maxlength="3"
                                    required/>
                            </div> --}}
                            <div class="col-12">
                                <label for="nwNum" class="form-label">{{ __('Número') }}</label>
                                <input type="tel" class="form-control" id="nwNum" placeholder="3001234567" inputmode="numeric"
                                    required/>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="nwName" class="form-label">{{ __('Nombre que verán tus clientes') }}</label>
                            <input type="text" class="form-control" id="nwName" maxlength="100" required />
                            <small class="fs-7" style="color: #8aa0a2">{{ __('Debe corresponder a tu negocio. Meta lo revisa y puede tardar en aprobarlo.') }}</small>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                            <button type="submit" class="btn btn-glow" id="nwBtn1">{{ __('Continuar') }}</button>
                        </div>
                    </form>

                    <!-- Paso 2 · enviar código -->
                    <div id="nwStep2" class="nw-step d-none">
                        <p class="fs-7" style="color: #6b8083">{{ __('Elige cómo quieres recibir el código de verificación.') }}</p>
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nwMethod" id="nwSms" value="SMS" checked />
                                <label class="form-check-label" for="nwSms">{{ __('Mensaje de texto (SMS)') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="nwMethod" id="nwVoice" value="VOICE" />
                                <label class="form-check-label" for="nwVoice">{{ __('Llamada de voz') }}</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                            <button type="button" class="btn btn-glow" id="nwBtn2">{{ __('Enviar código') }}</button>
                        </div>
                    </div>

                    <!-- Paso 3 · código -->
                    <form id="nwStep3" class="nw-step d-none">
                        <div class="mb-4">
                            <label for="nwCode" class="form-label">{{ __('Código de 6 dígitos') }}</label>
                            <input type="text" class="form-control" id="nwCode" inputmode="numeric" maxlength="7" autocomplete="one-time-code" required />
                            <small class="fs-7" style="color: #8aa0a2">{{ __('Si no llega, puedes volver y pedirlo de nuevo (Meta limita los intentos).') }}</small>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <button type="button" class="btn btn-ghost" id="nwBack3">{{ __('Pedir otro código') }}</button>
                            <button type="submit" class="btn btn-glow" id="nwBtn3">{{ __('Verificar') }}</button>
                        </div>
                    </form>

                    <!-- Paso 4 · PIN y registro -->
                    <form id="nwStep4" class="nw-step d-none">
                        <p class="fs-7" style="color: #6b8083">
                            {{ __('Crea un PIN de 6 dígitos (verificación en dos pasos). Guárdalo: lo necesitarás si algún día tienes que volver a registrar el número.') }}
                        </p>
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <label for="nwPin" class="form-label">{{ __('PIN') }}</label>
                                <input type="password" class="form-control" id="nwPin" inputmode="numeric" maxlength="6" autocomplete="new-password" required />
                            </div>
                            <div class="col-6">
                                <label for="nwPin2" class="form-label">{{ __('Repite el PIN') }}</label>
                                <input type="password" class="form-control" id="nwPin2" inputmode="numeric" maxlength="6" autocomplete="new-password" required />
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                            <button type="submit" class="btn btn-glow" id="nwBtn4">{{ __('Registrar número') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            "use strict";

            /* ==========================================================
            1. ESTADO MOCK · cambia entre los dos escenarios
            ========================================================== */
            const BOOT = @json($boot);
            let connected = BOOT.connected;
            const signup = {
                code: null,
                session: null,
                sent: false,
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': BOOT.csrf,
                    'Accept': 'application/json'
                }
            });

            function errMsg(xhr) {
                return (xhr.responseJSON && xhr.responseJSON.message) || 'Error inesperado';
            }

            const QUALITY = {
                GREEN: 'Excelente',
                YELLOW: 'Media',
                RED: 'Baja',
                UNKNOWN: 'Sin datos'
            };

            function fillAccount(a) {
                ['waba_id', 'phone_number_id', 'phone_number', 'business_id'].forEach(function(f) {
                    if (a[f] == null) return;
                    const $c = $('code[data-field="' + f + '"]').text(a[f]);
                    $c.closest('.id-value').find('.copy-btn').attr('data-copy', a[f]).data('copy', a[f]);
                });
                if (a.quality) $('[data-field="quality"]').text(QUALITY[a.quality] || a.quality);
                if (a.limit) $('[data-field="limit"]').text(a.limit);
                if ('token_days' in a) {
                    const sinVencimiento = a.token_days == null;
                    $('[data-field="token_text"]').text(sinVencimiento ? 'No vence' : 'Expira en ' + a.token_days + ' días');
                    const pct = sinVencimiento ? 100 : Math.min(100, Math.round(a.token_days / 60 * 100));
                    $('[data-field="token_text"]').closest('.tech-row').find('.mini-fill')
                        .attr('data-fill', pct).data('fill', pct);
                }
                $('[data-field="api_version"]').text(BOOT.graph_version);
            }
            if (BOOT.account) fillAccount(BOOT.account);
            if (!BOOT.demo) $('#demoToggle').remove();

            /* ==========================================================
            2. HELPERS · toasts, loading, fade
            ========================================================== */
            function toast(type, title, msg) {
                const cfg = {
                    success: ["bi-check-circle-fill", "#16a34a"],
                    info: ["bi-info-circle-fill", "#2C8F99"],
                    warning: ["bi-exclamation-triangle-fill", "#d99a05"],
                    error: ["bi-x-circle-fill", "#d9534f"],
                } [type];
                const $t = $(
                    '<div class="gi-toast" style="--tt:' +
                    cfg[1] +
                    '"><i class="bi ' +
                    cfg[0] +
                    '"></i><div><strong>' +
                    title +
                    "</strong><span>" +
                    msg +
                    "</span></div></div>",
                );
                $("#toastStack").append($t);
                setTimeout(function() {
                    $t.addClass("out");
                    setTimeout(function() {
                        $t.remove();
                    }, 320);
                }, 4000);
            }

            function setLoading($btn, on, label) {
                if (on) {
                    $btn
                        .data("orig", $btn.html())
                        .prop("disabled", true)
                        .html(
                            '<span class="spinner-border spinner-border-sm me-2"></span>' +
                            (label || ""),
                        );
                } else {
                    $btn.prop("disabled", false).html($btn.data("orig"));
                }
            }

            function refade($el) {
                $el.removeClass("fade-swap");
                void $el[0].offsetWidth;
                $el.addClass("fade-swap");
            }

            /* ==========================================================
            3. RENDER DE ESTADO (conectado / no conectado)
            ========================================================== */
            function renderState() {
                $("#stateDisconnected, #stateConnected").addClass("d-none");
                $("#dashConnected, #dashExtra").addClass("d-none");

                if (connected) {
                    $("#stateConnected").removeClass("d-none");
                    $("#dashConnected, #dashExtra").removeClass("d-none");
                    refade($("#stateConnected"));
                    /* Animar barras tras el pintado */
                    setTimeout(function() {
                        $("#dashConnected .mini-fill").each(function() {
                            $(this).css("width", $(this).data("fill") + "%");
                        });
                    }, 80);
                } else {
                    $("#stateDisconnected").removeClass("d-none");
                    refade($("#stateDisconnected"));
                    $("#dashConnected .mini-fill").css("width", 0);
                }
            }
            renderState();

            /* ==========================================================
               4. ESTADO TÉCNICO REAL · consulta el checklist contra Meta
               ========================================================== */
            const CHECK_TXT = {
                webhook: { ok: 'Suscrito', pendiente: 'Pendiente', error: 'Sin suscribir' },
                numero: { ok: 'Registrado', pendiente: 'Pendiente', error: 'Sin registrar' },
                salud: { ok: 'Operativa', pendiente: 'Limitada', error: 'Bloqueada' },
                token: { ok: 'Activo', pendiente: 'Por vencer', error: 'Inválido' }
            };
            const CHECK_CLS = { ok: 'mb-ok', pendiente: 'mb-warn', error: 'mb-bad' };
            const CHECK_FILL = { ok: 100, pendiente: 50, error: 15 };

            function pintarFila(clave, estado, detalle) {
                const $row = $('#dashConnected .tech-row[data-check="' + clave + '"]');
                if (!$row.length || !CHECK_CLS[estado]) return;
                $row.find('.mini-badge')
                    .removeClass('mb-ok mb-warn mb-bad').addClass(CHECK_CLS[estado])
                    .html('<span class="q-dot"></span> ' + CHECK_TXT[clave][estado]);
                const pct = estado === 'ok' && clave === 'token' ? ($row.find('.mini-fill').data('fill') || 100) : CHECK_FILL[estado];
                $row.find('.mini-fill').removeClass('warn bad')
                    .addClass(estado === 'pendiente' ? 'warn' : (estado === 'error' ? 'bad' : ''))
                    .data('fill', pct).css('width', pct + '%');
                if (detalle && estado !== 'ok') $row.find('.tech-info small').text(detalle);
            }

            function esc(s) { return $('<div>').text(s == null ? '' : s).html(); }

            const VER_TXT = {
                verified: ['mb-ok', 'Verificado', 'Tu negocio está verificado en Meta.'],
                pending: ['mb-warn', 'En revisión', 'Meta está revisando tu verificación.'],
                pending_submission: ['mb-warn', 'En proceso', 'Tienes la verificación iniciada pero sin enviar.'],
                pending_need_more_info: ['mb-warn', 'Faltan datos', 'Meta necesita más información o documentos.'],
                rejected: ['mb-bad', 'Rechazada', 'Meta rechazó la verificación. Revisa los motivos en Meta e inténtalo de nuevo.'],
                failed: ['mb-bad', 'Fallida', 'La verificación falló. Revisa los datos de tu empresa en Meta.'],
                expired: ['mb-bad', 'Vencida', 'La verificación venció. Debes repetirla.'],
                revoked: ['mb-bad', 'Revocada', 'Meta revocó la verificación.'],
                ineligible: ['mb-warn', 'No elegible', 'Meta no permite verificar este negocio desde aquí. Revisa tus notificaciones en Meta.'],
                not_verified: ['mb-warn', 'Sin verificar', 'Tu negocio aún no está verificado.'],
                unknown: ['mb-warn', 'Sin consultar', 'No se pudo consultar la verificación en Meta. Pulsa "Actualizar configuración".']
            };
            const NOMBRE_TXT = {
                APPROVED: ['mb-ok', 'Aprobado'], AVAILABLE_WITHOUT_REVIEW: ['mb-ok', 'Aprobado'],
                PENDING_REVIEW: ['mb-warn', 'En revisión'], DECLINED: ['mb-bad', 'Rechazado'], EXPIRED: ['mb-bad', 'Vencido']
            };

            function badge(cls, texto) {
                return '<span class="mini-badge ' + cls + ' fs-7"><span class="q-dot"></span> ' + esc(texto) + '</span>';
            }

            function accionesNumero(n) {
                const btn = function(accion, texto) {
                    return '<button type="button" class="btn btn-ghost btn-sm num-act" data-act="' + accion +
                        '" data-id="' + esc(n.id) + '" data-num="' + esc(n.numero) + '">' + texto + '</button>';
                };
                if (n.estado !== 'CONNECTED') {
                    if (n.es_prueba || n.verificado) return btn('registrar', 'Registrar'); // la prueba no lleva código SMS
                    return btn('verificar', 'Verificar');
                }
                if (!n.en_app) return btn('vincular', 'Agregar a la app');
                const agentes = btn('agentes', '<i class="bi bi-people"></i> Agentes' + (n.agentes ? ' (' + n.agentes + ')' : ''));
                const bot = '<button type="button" class="btn btn-ghost btn-sm num-act" data-act="bot" data-on="' + (n.bot ? 1 : 0) +
                    '" data-id="' + esc(n.id) + '" data-num="' + esc(n.numero) + '"><i class="bi bi-robot"></i> Bot: ' + (n.bot ? 'activo' : 'apagado') + '</button>';
                if (!n.activo) return agentes + ' ' + bot + ' ' + btn('principal', 'Usar como principal');
                return agentes + ' ' + bot;
            }

            function pintarResumen(res) {
                const r = res.resumen;
                if (!r) {
                    $('#payBadge, #verBadge').attr('class', 'mini-badge mb-warn fs-7').html('<span class="q-dot"></span> No disponible');
                    $('#payText, #verText').text('Reconecta tu cuenta para poder consultar este estado en Meta.');
                    $('#numbersBody').html('<tr><td colspan="6" class="text-center" style="color:#8aa0a2">Reconecta tu cuenta para ver tus números.</td></tr>');
                    return;
                }
                const items = res.items || [];
                const pago = items.find(function(i) { return i.clave === 'pago'; });

                // Pago
                if (pago) {
                    $('#payBadge').attr('class', 'mini-badge ' + CHECK_CLS[pago.estado] + ' fs-7')
                        .html('<span class="q-dot"></span> ' + (r.con_pago ? 'Configurado' : (pago.estado === 'ok' ? 'Sin problemas' : (pago.estado === 'error' ? 'Error de pago' : 'Sin confirmar'))));
                    $('#payText').text(pago.detalle);
                    $('#payLink').toggleClass('d-none', r.con_pago).attr('href', r.enlaces.pago);
                }

                // Verificación del negocio
                const v = VER_TXT[r.verificacion] || VER_TXT.not_verified;
                $('#verBadge').attr('class', 'mini-badge ' + v[0] + ' fs-7').html('<span class="q-dot"></span> ' + v[1]);
                $('#verText').text(v[2]);
                $('#verLink').toggleClass('d-none', r.verificacion === 'verified').attr('href', r.enlaces.verificacion);

                // Números
                const lleno = r.cupo.usados >= r.cupo.maximo;
                $('#btnAddNumber').prop('disabled', lleno).attr('title', lleno
                    ? 'Llegaste al máximo de números que Meta permite por ahora. Verifica tu negocio para ampliarlo.' : '');
                $('#cupoBadge').text(r.cupo.usados + ' de ' + r.cupo.maximo + ' números')
                    .attr('class', 'mini-badge ' + (r.cupo.usados >= r.cupo.maximo ? 'mb-warn' : 'mb-ok') + ' fs-7');
                const filas = (r.numeros || []).map(function(n) {
                    const conectado = n.estado === 'CONNECTED';
                    const est = conectado ? badge('mb-ok', 'Conectado')
                        : badge('mb-warn', (n.es_prueba || n.verificado) ? n.estado : 'Sin verificar');
                    const nom = n.nombre_estado && NOMBRE_TXT[n.nombre_estado]
                        ? ' ' + badge(NOMBRE_TXT[n.nombre_estado][0], NOMBRE_TXT[n.nombre_estado][1]) : '';
                    const tipo = n.es_prueba
                        ? '<span class="log-badge lb-warn">' + esc('Prueba') + '</span>'
                        : '<span class="log-badge lb-ok">' + esc('Producción') + '</span>';
                    return '<tr>' +
                        '<td data-label="Número"><span class="mono">' + esc(n.numero) + '</span>' + (n.activo ? ' ' + badge('mb-ok', 'Activo') : '') + '</td>' +
                        '<td data-label="Nombre visible">' + esc(n.nombre || '—') + nom + '</td>' +
                        '<td data-label="Estado">' + est + '</td>' +
                        '<td data-label="Calidad y límite">' + esc((QUALITY[n.calidad] || n.calidad) + (n.limite ? ' · ' + n.limite : '')) + '</td>' +
                        '<td data-label="Tipo">' + tipo + '</td>' +
                        '<td data-label="Acciones">' + accionesNumero(n) + '</td></tr>';
                });
                $('#numbersBody').html(filas.length ? filas.join('')
                    : '<tr><td colspan="6" class="text-center" style="color:#8aa0a2">No se pudieron leer los números desde Meta.</td></tr>');
            }

            function cargarChecklist() {
                if (BOOT.demo || !connected || !BOOT.routes.checklist) return;
                $.get(BOOT.routes.checklist).done(function(res) {
                    const items = res.items || [];
                    items.forEach(function(it) { pintarFila(it.clave, it.estado, it.detalle); });
                    pintarResumen(res);
                    // Si el token no sirve, el resto no se puede verificar: no se muestra en verde
                    const tok = items.find(function(i) { return i.clave === 'token'; });
                    if (tok && tok.estado === 'error') {
                        ['webhook', 'numero', 'salud'].forEach(function(c) {
                            pintarFila(c, 'pendiente', 'No se puede verificar sin una autorización válida.');
                        });
                    }
                });
            }
            cargarChecklist();

            /* ==========================================================
               5. PARALLAX del hero (composición 3D)
               ========================================================== */
            const $stage = $("#flowStage");
            $("#waHero")
                .on("mousemove", function(e) {
                    if (window.innerWidth < 992) return;
                    const r = this.getBoundingClientRect();
                    const x = (e.clientX - r.left) / r.width - 0.5;
                    const y = (e.clientY - r.top) / r.height - 0.5;
                    $stage.css(
                        "transform",
                        "rotateY(" +
                        (x * 7).toFixed(2) +
                        "deg) rotateX(" +
                        (-y * 7).toFixed(2) +
                        "deg)",
                    );
                })
                .on("mouseleave", function() {
                    $stage.css("transform", "");
                });

            /* ==========================================================
               6. COPIAR ID · tooltip + toast + feedback
               ========================================================== */
            /* Inicializar tooltips de Bootstrap */
            document
                .querySelectorAll('[data-bs-toggle="tooltip"]')
                .forEach(function(el) {
                    new bootstrap.Tooltip(el);
                });

            $(document).on("click", ".copy-btn", function() {
                const $btn = $(this),
                    text = $btn.data("copy");
                const tip = bootstrap.Tooltip.getInstance($btn[0]);
                if (tip) tip.hide();

                function feedback() {
                    $btn.addClass("copied").find("i").attr("class", "bi bi-check-lg");
                    setTimeout(function() {
                        $btn
                            .removeClass("copied")
                            .find("i")
                            .attr("class", "bi bi-clipboard");
                    }, 1500);
                }
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(text).then(feedback);
                } else {
                    const ta = document.createElement("textarea");
                    ta.value = text;
                    document.body.appendChild(ta);
                    ta.select();
                    try {
                        document.execCommand("copy");
                    } catch (e) {}
                    document.body.removeChild(ta);
                    feedback();
                }
                toast("success", "ID copiado correctamente", text);
            });

            /* ==========================================================
               7. ACCIONES SIMULADAS (mock · sin backend)
               ========================================================== */
            /* ===== Embedded Signup ===== */
            if (!BOOT.demo) {
                window.fbAsyncInit = function() {
                    FB.init({
                        appId: BOOT.fb_app_id,
                        autoLogAppEvents: true,
                        xfbml: true,
                        version: BOOT.graph_version
                    });
                };
                $('<script>', {
                    async: true,
                    defer: true,
                    crossorigin: 'anonymous',
                    src: 'https://connect.facebook.net/en_US/sdk.js'
                }).appendTo('head');
            }

            function sendSignup() {
                if (signup.sent || !signup.code) return;
                const s = signup.session;
                if (s && !String(s.event).startsWith('FINISH')) return;
                signup.sent = true;
                clearTimeout(signup.timer);

                $.ajax({
                    url: BOOT.routes.exchange,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        code: signup.code,
                        event: s ? s.event : null,
                        waba_id: s && s.data ? s.data.waba_id || null : null,
                        phone_number_id: s && s.data ? s.data.phone_number_id || null : null,
                        business_id: s && s.data ? s.data.business_id || null : null,
                        pin: signup.session?.data?.pin || null // Para números nuevos
                    })
                }).done(function(res) {
                    BOOT.account = res.account;
                    fillAccount(res.account);
                    connected = true;
                    renderState();
                    cargarChecklist();
                    toast('success', 'WhatsApp conectado', res.message);
                    setTimeout(function() {
                        $('#dashConnected')[0].scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }, 350);
                }).fail(function(xhr) {
                    toast('error', 'No se pudo vincular', errMsg(xhr));
                    signup.sent = false; // Permite reintentar
                }).always(function() {
                    setLoading($('#btnConnectMeta'), false);
                });
            }

            window.addEventListener('message', function(event) {
                if (event.origin!== 'https://www.facebook.com' && event.origin!== 'https://web.facebook.com') return;
                let data;
                try {
                    data = JSON.parse(event.data);
                } catch (e) {
                    return;
                } // mensajes internos del SDK
                if (data.type !== 'WA_EMBEDDED_SIGNUP') return;

                if (data.event === 'CANCEL' || data.event === 'ERROR') {
                    setLoading($('#btnConnectMeta'), false);
                    toast('warning', 'Flujo no completado',
                        data.data && data.data.error_message ? data.data.error_message :
                        'Cancelaste el proceso.');
                    return;
                }
                signup.session = data;
                sendSignup();
            });

            $('#btnConnectMeta').on('click', function() {
                if (BOOT.demo) {
                    toast('warning', 'Modo demo',
                        'Falta configurar client_id y config_id en config/facebook.php.');
                    return;
                }
                signup.code = null;
                signup.session = null;
                signup.sent = false;
                setLoading($(this), true, 'Conectando…');

                FB.login(function(response) {
                    if (response.authResponse) {
                        signup.code = response.authResponse.code;
                        sendSignup();
                    } else {
                        setLoading($('#btnConnectMeta'), false);
                        toast('info', 'Cancelado', 'No se completó el inicio de sesión con Meta.');
                    }
                }, {
                    config_id: BOOT.fb_config_id,
                    response_type: 'code',
                    override_default_response_type: true,
                    extras: {
                        featureType: 'whatsapp_business_app_onboarding', // OBLIGATORIO para Tech Provider
                        sessionInfoVersion: '3',
                        setup: {},
                        features: [{ name: 'marketing_messages_lite' }],
                    }
                });
            });

            /* Desconectar */
            $("#btnConfirmDisconnect").on("click", function() {
                const $b = $(this);
                setLoading($b, true, "Desconectando…");
                $.ajax({
                        url: BOOT.routes.disconnect,
                        method: 'DELETE'
                    })
                    .done(function(res) {
                        bootstrap.Modal.getInstance(document.getElementById("disconnectModal")).hide();
                        connected = false;
                        renderState();
                        toast("info", "Cuenta desconectada", res.message);
                    })
                    .fail(function(xhr) {
                        toast("error", "Error", errMsg(xhr));
                    })
                    .always(function() {
                        setLoading($b, false);
                    });
            });

            /* Probar conexión */
            $("#testForm").on("submit", function(e) {
                e.preventDefault();
                const $b = $("#btnSubmitTest");
                setLoading($b, true, "Enviando…");
                $.post(BOOT.routes.test, {
                        to: $("#testTo").val(),
                        message: $("#testMsg").val().trim()
                    })
                    .done(function(res) {
                        $("#testFormWrap").addClass("d-none");
                        $("#testSuccess").removeClass("d-none").addClass("fade-swap");
                        toast("success", "Mensaje de prueba enviado", res.message);
                    })
                    .fail(function(xhr) {
                        toast("error", "No se pudo enviar", errMsg(xhr));
                    })
                    .always(function() {
                        setLoading($b, false);
                    });
            });

            /* Actualizar configuración */
            $("#btnSync").on("click", function() {
                const $b = $(this);
                setLoading($b, true, "");
                $.get(BOOT.routes.status)
                    .done(function(res) {
                        fillAccount({
                            quality: res.account.quality_rating,
                            limit: res.account.messaging_limit
                        });
                        cargarChecklist();
                        toast("success", "Configuración actualizada", "Datos sincronizados con Meta.");
                    })
                    .fail(function(xhr) {
                        toast("error", "Error", errMsg(xhr));
                    })
                    .always(function() {
                        setLoading($b, false);
                    });
            });

            /* ==========================================================
               7b. AGREGAR NÚMERO · asistente y acciones por número
               ========================================================== */
            const nw = { id: null };
            const numUrl = function(path) { return BOOT.routes.numbers + path; };

            function nwStep(n) {
                $('#numberModal .nw-step').addClass('d-none');
                $('#nwStep' + n).removeClass('d-none');
                $('#nwError').addClass('d-none').text('');
            }

            function nwFail(xhr) {
                $('#nwError').removeClass('d-none').text(errMsg(xhr));
            }

            function nwOpen(step, id, label) {
                nw.id = id || null;
                $('#nwLabel').text(label || '');
                $('#nwStep1')[0].reset();
                $('#nwCode, #nwPin, #nwPin2').val('');
                nwStep(step);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('numberModal')).show();
            }

            $('#btnAddNumber').on('click', function() { nwOpen(1); });

            $('#nwStep1').on('submit', function(e) {
                e.preventDefault();
                const $b = $('#nwBtn1');
                setLoading($b, true, 'Creando…');
                let inputTelefono = generalidades.darTelefonoInput(`#nwStep1 #nwNum`);
                let tel = inputTelefono?.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
                let codigo = inputTelefono?.getSelectedCountryData()?.dialCode ?? '';
                tel = tel.replace(/\((\w+)\)/g, "$1").replace(/-/g, "").replace(/\s/g, "");

                $.post(numUrl(''), {
                    cc: codigo,
                    phone_number: tel,
                    verified_name: $('#nwName').val().trim()
                }).done(function(res) {
                    nw.id = res.phone_number_id;
                    nwStep(2);
                }).fail(nwFail).always(function() { setLoading($b, false); });
            });

            $('#nwBtn2').on('click', function() {
                const $b = $(this);
                setLoading($b, true, 'Enviando…');
                $.post(numUrl('/' + nw.id + '/code'), { method: $('input[name="nwMethod"]:checked').val() })
                    .done(function(res) { toast('success', 'Código enviado', res.message); nwStep(3); })
                    .fail(nwFail).always(function() { setLoading($b, false); });
            });

            $('#nwBack3').on('click', function() { nwStep(2); });

            $('#nwStep3').on('submit', function(e) {
                e.preventDefault();
                const $b = $('#nwBtn3');
                setLoading($b, true, 'Verificando…');
                $.post(numUrl('/' + nw.id + '/verify'), { code: $('#nwCode').val() })
                    .done(function() { nwStep(4); })
                    .fail(nwFail).always(function() { setLoading($b, false); });
            });

            $('#nwStep4').on('submit', function(e) {
                e.preventDefault();
                const pin = $('#nwPin').val();
                if (!/^\d{6}$/.test(pin)) { $('#nwError').removeClass('d-none').text('El PIN debe tener 6 dígitos.'); return; }
                if (pin !== $('#nwPin2').val()) { $('#nwError').removeClass('d-none').text('Los PIN no coinciden.'); return; }
                const $b = $('#nwBtn4');
                setLoading($b, true, 'Registrando…');
                $.post(numUrl('/' + nw.id + '/register'), { pin: pin })
                    .done(function(res) {
                        bootstrap.Modal.getInstance(document.getElementById('numberModal')).hide();
                        toast('success', 'Número agregado', res.message);
                        cargarChecklist();
                    })
                    .fail(nwFail).always(function() { setLoading($b, false); });
            });

            /* Asignación de agentes a un número */
            let agId = null;
            function abrirAgentes(id, numero) {
                agId = id;
                $('#agNumero').text(numero);
                $('#agLista').html('<p class="text-center fs-7" style="color:#8aa0a2">Cargando…</p>');
                bootstrap.Modal.getOrCreateInstance(document.getElementById('assignModal')).show();
                $.get(numUrl('/' + id + '/agentes')).done(function(res) {
                    $('#agAyuda').text(res.principal
                        ? 'Este es tu número principal: lo usan todos los usuarios que no tengan otro asignado.'
                        : 'Los usuarios marcados usarán este número en la app móvil.');
                    const filas = (res.usuarios || []).map(function(u) {
                        return '<label class="d-flex align-items-center gap-2 py-2 border-bottom">' +
                            '<input type="checkbox" class="form-check-input mt-0 ag-chk" value="' + esc(u.id) + '"' + (u.asignado ? ' checked' : '') + '>' +
                            '<span class="flex-grow-1">' + esc(u.nombre) + '<br><small style="color:#8aa0a2">' + esc(u.email || '') + '</small></span>' +
                            (u.otro ? '<small style="color:#b7791f">Hoy usa ' + esc(u.otro) + '</small>' : '') + '</label>';
                    });
                    $('#agLista').html(filas.join('') || '<p class="text-center fs-7" style="color:#8aa0a2">No hay usuarios en tu empresa.</p>');
                }).fail(function(xhr) {
                    $('#agLista').html('<p class="text-center fs-7" style="color:#c0392b">' + esc(errMsg(xhr)) + '</p>');
                });
            }

            $('#agGuardar').on('click', function() {
                const $b = $(this);
                const sel = $('.ag-chk:checked').map(function() { return this.value; }).get();
                setLoading($b, true, '');
                $.post(numUrl('/' + agId + '/agentes'), { agentes: sel })
                    .done(function(res) {
                        bootstrap.Modal.getInstance(document.getElementById('assignModal')).hide();
                        toast('success', 'Listo', res.message);
                        cargarChecklist();
                    })
                    .fail(function(xhr) { toast('error', 'No se pudo guardar', errMsg(xhr)); })
                    .always(function() { setLoading($b, false); });
            });

            $(document).on('click', '.num-act', function() {
                const $b = $(this), id = $b.data('id'), numero = $b.data('num'), act = $b.data('act');
                if (act === 'verificar') return nwOpen(2, id, numero);
                if (act === 'registrar') return nwOpen(4, id, numero);
                if (act === 'agentes') return abrirAgentes(id, numero);
                if (act === 'bot') {
                    setLoading($b, true, '');
                    return $.post(numUrl('/' + id + '/bot'), { activo: $b.data('on') ? 0 : 1 })
                        .done(function(res) { toast('success', 'Listo', res.message); cargarChecklist(); })
                        .fail(function(xhr) { toast('error', 'No se pudo cambiar', errMsg(xhr)); setLoading($b, false); });
                }

                if (act === 'principal' && !window.confirm('Los chats y las campañas usarán este número. Los envíos que estén en curso saldrán desde el nuevo número. ¿Continuar?')) return;

                setLoading($b, true, '');
                $.post(numUrl('/' + id + '/' + (act === 'vincular' ? 'link' : 'principal')))
                    .done(function(res) {
                        if (res.account) fillAccount(res.account);
                        toast('success', 'Listo', res.message);
                        cargarChecklist();
                    })
                    .fail(function(xhr) { toast('error', 'No se pudo completar', errMsg(xhr)); setLoading($b, false); });
            });

            /* Ver plantillas (placeholder de navegación) */
            $("#btnTemplates").on("click", function(e) {
                e.preventDefault();
                toast(
                    "info",
                    "Plantillas",
                    "Aquí se abrirá /admin/whatsapp/templates.",
                );
            });

            /* ==========================================================
               8. TOGGLE DEMO · alternar escenarios
               ========================================================== */
            $("#demoToggle").on("click", function() {
                connected = !connected;
                renderState();
                toast(
                    "info",
                    "Demo",
                    "Estado cambiado a: " + (connected ? "CONECTADO" : "NO CONECTADO"),
                );
            });

            /* ==========================================================
               9. SIDEBAR MÓVIL
               ========================================================== */
            function closeSidebar() {
                $("#dashSidebar").removeClass("open");
                $("#sidebarBackdrop").removeClass("show");
            }
            $("#sidebarToggle").on("click", function() {
                $("#dashSidebar").toggleClass("open");
                $("#sidebarBackdrop").toggleClass("show");
            });
            $("#sidebarBackdrop").on("click", closeSidebar);
        });

        $(document).on('shown.bs.modal', '#numberModal', function () {
            generalidades.initTelefonoInput(`#nwStep1 #nwNum`);
        });
    </script>
@endsection
