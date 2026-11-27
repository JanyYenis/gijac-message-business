@extends('layouts.index', ['drawer' => true])

@section('titulo_drawer', __('Filtros'))

@if ($accesos)
    @section('tutorial')
        @component('layouts.componentes.tutorial')
        @endcomponent
    @endsection
@endif

@section('contenido_drawer')
    <form id="fromFiltros">
        <div class="row">
            <div class="col-lg-12 col-md-12 mt-2">
                <label class="form-label">{{ __('Fechas') }}</label>
                <input class="form-control" name="fechas" placeholder="{{ __('Fechas') }}" id="inputFechas" />
            </div>
            <div class="col-lg-12 col-md-12 mt-2">
                <label class="form-label">{{ __('Etiquetas') }}</label>
                <select id="selectEtiquetas" name="etiquetas" class="form-select" multiple
                    data-placeholder="{{ __('Seleccione etiquetas') }}" data-allow-clear="true">
                    <option value=""></option>
                </select>
            </div>
            <div class="col-lg-12 col-md-12 mt-2">
                <label class="form-label">{{ __('Contactos') }}</label>
                <select id="selectContactos" name="contactos" class="form-select" multiple
                    data-placeholder="{{ __('Seleccione el o los contactos') }}" data-allow-clear="true">
                    <option value=""></option>
                </select>
            </div>
            <div class="col-lg-12 col-md-12 mt-2">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <button type="button" class="btn btn-secondary" id="btnLimpiar">{{ __('Limpiar') }}</button>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <button type="submit" class="btn btn-success">{{ __('Filtrar') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@if (!$accesos)
    @section('css')
        <link rel="stylesheet" href="{{ mix('css/acceso.css') }}">
    @endsection
@endif

@section('content')
    @if ($accesos)
        <div class="page-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h1 class="text-white">
                        <i class="fab fa-whatsapp"></i>
                        {{ __('Dashboard Analytics') }}
                    </h1>
                    <p class="subtitle mb-0">{{ __('Monitorea el rendimiento de tus campañas de WhatsApp Business') }}</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <span id="currentDate"></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="container-fluid main-container">
            <!-- Metric Cards -->
            <div class="row g-4 mb-4" id="driverMetrics">
                <div class="col-lg-3 col-md-6">
                    <div class="card metric-card">
                        <div class="metric-icon contacts">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="metric-value" id="totalContacts">0</h3>
                        <p class="metric-label">{{ __('Contactos Activos') }}</p>
                        {{-- <div class="metric-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+12.5% vs mes anterior</span>
                        </div> --}}
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card metric-card">
                        <div class="metric-icon campaigns">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h3 class="metric-value" id="totalCampaigns">0</h3>
                        <p class="metric-label">{{ __('Campañas Enviadas') }}</p>
                        {{-- <div class="metric-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+8.3% vs mes anterior</span>
                        </div> --}}
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card metric-card">
                        <div class="metric-icon messages">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <h3 class="metric-value fs-2" id="diaEfectivo">N/A</h3>
                        <p class="metric-label">{{ __('Tu día más efectivo') }}</p>
                        {{-- <div class="metric-change negative">
                            <i class="fas fa-arrow-down"></i>
                            <span>-3.2% vs mes anterior</span>
                        </div> --}}
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card metric-card">
                        <div class="metric-icon effectiveness">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="metric-value" id="effectiveness">0<span style="font-size: 1.5rem;">%</span></h3>
                        <p class="metric-label">{{ __('Efectividad') }}</p>
                        {{-- <div class="metric-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+2.1% vs mes anterior</span>
                        </div> --}}
                    </div>
                </div>
            </div>

            <!-- Charts Row 1 -->
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card chart-card">
                        <h5 class="chart-title">
                            <i class="fas fa-chart-bar text-primary"></i>
                            {{ __('Campañas Enviadas por Mes') }}
                        </h5>
                        <div class="chart-container">
                            <div id="campaignsChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card chart-card">
                        <h5 class="chart-title">
                            <i class="fas fa-chart-pie text-success"></i>
                            {{ __('Distribución por Etiquetas') }}
                        </h5>
                        <div class="chart-container">
                            <div id="tagsChart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 2 -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="card chart-card" id="driverHorarios">
                        <h5 class="chart-title">
                            <i class="fas fa-clock text-warning"></i>
                            {{ __('Horarios de Apertura por Día') }}
                        </h5>

                        <!-- Tabs for Days -->
                        <ul class="nav nav-tabs" id="dayTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="monday-tab" data-bs-toggle="tab"
                                    data-bs-target="#monday" type="button" role="tab">{{ __('Lun') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tuesday-tab" data-bs-toggle="tab" data-bs-target="#tuesday"
                                    type="button" role="tab">{{ __('Mar') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="wednesday-tab" data-bs-toggle="tab"
                                    data-bs-target="#wednesday" type="button"
                                    role="tab">{{ __('Mié') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="thursday-tab" data-bs-toggle="tab"
                                    data-bs-target="#thursday" type="button"
                                    role="tab">{{ __('Jue') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="friday-tab" data-bs-toggle="tab" data-bs-target="#friday"
                                    type="button" role="tab">{{ __('Vie') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="saturday-tab" data-bs-toggle="tab"
                                    data-bs-target="#saturday" type="button"
                                    role="tab">{{ __('Sáb') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="sunday-tab" data-bs-toggle="tab" data-bs-target="#sunday"
                                    type="button" role="tab">{{ __('Dom') }}</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="dayTabsContent">
                            <div class="tab-pane fade show active" id="monday" role="tabpanel">
                                <div class="chart-container">
                                    <div id="diaLunes"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tuesday" role="tabpanel">
                                <div class="chart-container">
                                    <div id="diaMartes"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="wednesday" role="tabpanel">
                                <div class="chart-container">
                                    <div id="diaMiercoles"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="thursday" role="tabpanel">
                                <div class="chart-container">
                                    <div id="diaJueves"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="friday" role="tabpanel">
                                <div class="chart-container">
                                    <div id="diaViernes"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="saturday" role="tabpanel">
                                <div class="chart-container">
                                    <div id="diaSabado"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="sunday" role="tabpanel">
                                <div class="chart-container">
                                    <div id="diaDomingo"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card chart-card">
                        <h5 class="chart-title">
                            <i class="fas fa-exchange-alt text-info"></i>
                            {{ __('Embudo de efectividad') }}
                        </h5>
                        <div class="chart-container">
                            <div id="chartEmbudo"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- ================= Componente: «Tu ruta de inicio» =================
            Markup idéntico al que genera el componente Blade
            (resources/views/components/onboarding.blade.php). -->
        <section class="gi-onb" id="onb" data-onboarding aria-labelledby="onb-title">
            <header class="onb-hero">
                <span class="onb-hero-chip">
                    <i class="bi bi-signpost-2-fill text-white" aria-hidden="true"></i>
                    Tu ruta de inicio
                </span>
                <h1 class="onb-hero-title" id="onb-title">
                    ¡Bienvenido a GIJAC MESSAGE BUSINESS!
                </h1>
                <p class="onb-hero-sub">
                    Completa estos sencillos pasos y prepara tu empresa para aprovechar
                    todas nuestras herramientas de comunicación y automatización
                </p>
            </header>

            <div class="onb-summary-slot" data-onb="summary">
                <div class="onb-progress-card" data-onb-progress="">
                    <div class="onb-progress-head">
                        <span class="onb-progress-text">
                            <i class="bi bi-check2-square me-2" aria-hidden="true"></i>
                            <strong>{{ $completados ?? 0 }} de {{ $total }} pasos completados</strong>
                        </span>
                        <span class="onb-progress-percent">{{ $porcentaje ?? 0}}%</span>
                    </div>
                    <div class="progress onb-progress" role="progressbar" aria-label="Progreso de la configuración inicial" aria-valuemin="0" aria-valuemax="{{ $total }}" aria-valuenow="{{ $porcentaje }}">
                        <div class="progress-bar onb-progress-bar" style="width: {{ $porcentaje ?? 0 }}%"></div>
                    </div>
                </div>
            </div>

            <div class="onb-timeline-slot" data-onb="timeline">
                <ol class="onb-timeline" aria-label="Pasos de configuración inicial">
                    <li class="onb-step {{ $pasos[0] ? 'is-completed' : 'is-pending is-next' }}">
                        <div class="onb-marker" aria-hidden="true">
                            <span class="onb-marker-number">1</span>
                        </div>
                        <div class="onb-card">
                            <div class="onb-card-head">
                                <span class="onb-step-icon" aria-hidden="true">
                                    <i class="bi bi-buildings text-gijac fs-3"></i>
                                </span>
                                <div class="onb-card-titles">
                                    <h2 class="onb-step-title">PASO 1. Registra tu empresa</h2>
                                    @if ($pasos[0])
                                        <span class="onb-status onb-status-done">
                                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                                            Completado
                                        </span>
                                    @else
                                        <span class="onb-status onb-status-pending">
                                            <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                                            Pendiente
                                        </span>
                                    @endif
                                </div>
                                @if (!$pasos[0])
                                    <span class="onb-next-chip">
                                        <i class="bi bi-lightning-charge-fill text-white" aria-hidden="true"></i>
                                        Siguiente paso
                                    </span>
                                @endif
                            </div>
                            <p class="onb-step-desc">
                                Comienza configurando la información de tu empresa. Esto permitirá personalizar tu experiencia y administrar los servicios de GIJAC MESSAGE BUSINESS
                            </p>
                            <div class="onb-actions">
                                @if ($pasos[0])
                                    <a class="btn onb-btn-outline" href="{{ route('negocios.index') }}">
                                        <i class="bi bi-gear me-1" aria-hidden="true"></i>
                                        Revisar configuración
                                    </a>
                                    <span class="onb-done-note">
                                        <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>
                                        Configuración completada
                                    </span>
                                @else
                                    <a class="btn onb-btn-primary" href="{{ route('negocios.index') }}">
                                        Registrar mi empresa
                                        <i class="bi bi-arrow-right ms-2 text-white" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </li>
                    <li class="onb-step {{ $pasos[1] ? 'is-completed' : 'is-pending is-next' }}">
                        <div class="onb-marker" aria-hidden="true">
                            <span class="onb-marker-number">2</span>
                        </div>
                        <div class="onb-card">
                            <div class="onb-card-head">
                                <span class="onb-step-icon" aria-hidden="true">
                                    <i class="bi bi-facebook text-gijac fs-3"></i>
                                </span>
                                <div class="onb-card-titles">
                                    <h2 class="onb-step-title">PASO 2. Conecta tu cuenta de Facebook</h2>
                                     @if ($pasos[1])
                                        <span class="onb-status onb-status-done">
                                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                                            Completado
                                        </span>
                                    @else
                                        <span class="onb-status onb-status-pending">
                                            <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                                            Pendiente
                                        </span>
                                    @endif
                                </div>
                                @if (!$pasos[1])
                                    <span class="onb-next-chip">
                                        <i class="bi bi-lightning-charge-fill text-white" aria-hidden="true"></i>
                                        Siguiente paso
                                    </span>
                                @endif
                            </div>
                            <p class="onb-step-desc">
                                Conecta tu cuenta de Facebook y configura tu integración con Meta para comenzar a utilizar las funcionalidades de WhatsApp Business
                            </p>
                            <div class="onb-actions">
                                @if ($pasos[1])
                                    <a class="btn onb-btn-outline" href="{{ route('configs.index') }}">
                                        <i class="bi bi-gear me-1" aria-hidden="true"></i>
                                        Ver estado de conexión
                                    </a>
                                    <span class="onb-done-note">
                                        <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>
                                        Configuración completada
                                    </span>
                                @else
                                    <a class="btn onb-btn-primary" href="{{ route('configs.index') }}">
                                        Conectar con Facebook
                                        <i class="bi bi-arrow-right ms-2 text-white" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </li>
                    <li class="onb-step {{ $pasos[2] ? 'is-completed' : 'is-pending is-next' }}">
                        <div class="onb-marker" aria-hidden="true">
                            <span class="onb-marker-number">3</span>
                        </div>
                        <div class="onb-card">
                            <div class="onb-card-head">
                                <span class="onb-step-icon" aria-hidden="true">
                                    <i class="bi bi-person-plus text-gijac fs-3"></i>
                                </span>
                                <div class="onb-card-titles">
                                    <h2 class="onb-step-title">PASO 3. Crea tu primer contacto</h2>
                                     @if ($pasos[2])
                                        <span class="onb-status onb-status-done">
                                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                                            Completado
                                        </span>
                                    @else
                                        <span class="onb-status onb-status-pending">
                                            <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                                            Pendiente
                                        </span>
                                    @endif
                                </div>
                                @if (!$pasos[2])
                                    <span class="onb-next-chip">
                                        <i class="bi bi-lightning-charge-fill text-white" aria-hidden="true"></i>
                                        Siguiente paso
                                    </span>
                                @endif
                            </div>
                            <p class="onb-step-desc">
                                Registra tu primer contacto y comienza a construir tu base de datos para gestionar conversaciones y enviar campañas
                            </p>
                            <div class="onb-actions">
                                @if ($pasos[2])
                                    <a class="btn onb-btn-outline" href="{{ route('contactos.index') }}">
                                        <i class="bi bi-gear me-1" aria-hidden="true"></i>
                                        Ver contactos
                                    </a>
                                    <span class="onb-done-note">
                                        <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>
                                        Configuración completada
                                    </span>
                                @else
                                    <a class="btn onb-btn-primary" href="{{ route('contactos.index') }}">
                                        Crear mi primer contacto
                                        <i class="bi bi-arrow-right ms-2 text-white" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </li>
                    <li class="onb-step {{ $pasos[3] ? 'is-completed' : 'is-pending is-next' }}">
                        <div class="onb-marker" aria-hidden="true">
                            <span class="onb-marker-number">4</span>
                        </div>
                        <div class="onb-card">
                            <div class="onb-card-head">
                                <span class="onb-step-icon" aria-hidden="true">
                                    <i class="bi bi-megaphone text-gijac fs-3"></i>
                                </span>
                                <div class="onb-card-titles">
                                    <h2 class="onb-step-title">PASO 4. Crear plantilla</h2>
                                     @if ($pasos[3])
                                        <span class="onb-status onb-status-done">
                                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                                            Completado
                                        </span>
                                    @else
                                        <span class="onb-status onb-status-pending">
                                            <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                                            Pendiente
                                        </span>
                                    @endif
                                </div>
                                @if (!$pasos[3])
                                    <span class="onb-next-chip">
                                        <i class="bi bi-lightning-charge-fill text-white" aria-hidden="true"></i>
                                        Siguiente paso
                                    </span>
                                @endif
                            </div>
                            <p class="onb-step-desc">
                                Experimenta el potencial de GIJAC MESSAGE BUSINESS creando una plantilla de prueba
                            </p>
                            <div class="onb-actions">
                                @if ($pasos[3])
                                    <a class="btn onb-btn-outline" href="{{ route('plantillas.index') }}">
                                        <i class="bi bi-gear me-1" aria-hidden="true"></i>
                                        Ver plantillas
                                    </a>
                                    <span class="onb-done-note">
                                        <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>
                                        Configuración completada
                                    </span>
                                @else
                                    <a class="btn onb-btn-primary" href="{{ route('plantillas.index') }}">
                                        Crear mi primera plantillas
                                        <i class="bi bi-arrow-right ms-2 text-white" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </li>
                    <li class="onb-step {{ $pasos[4] ? 'is-completed' : 'is-pending is-next' }}">
                        <div class="onb-marker" aria-hidden="true">
                            <span class="onb-marker-number">5</span>
                        </div>
                        <div class="onb-card">
                            <div class="onb-card-head">
                                <span class="onb-step-icon" aria-hidden="true">
                                    <i class="bi bi-megaphone text-gijac fs-3"></i>
                                </span>
                                <div class="onb-card-titles">
                                    <h2 class="onb-step-title">PASO 5. Envía una campaña de prueba</h2>
                                     @if ($pasos[4])
                                        <span class="onb-status onb-status-done">
                                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                                            Completado
                                        </span>
                                    @else
                                        <span class="onb-status onb-status-pending">
                                            <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                                            Pendiente
                                        </span>
                                    @endif
                                </div>
                                @if (!$pasos[4])
                                    <span class="onb-next-chip">
                                        <i class="bi bi-lightning-charge-fill text-white" aria-hidden="true"></i>
                                        Siguiente paso
                                    </span>
                                @endif
                            </div>
                            <p class="onb-step-desc">
                                Experimenta el potencial de GIJAC MESSAGE BUSINESS enviando tu primera campaña de WhatsApp
                            </p>
                            <div class="onb-actions">
                                @if ($pasos[4])
                                    <a class="btn onb-btn-outline" href="{{ route('campanas.index') }}">
                                        <i class="bi bi-gear me-1" aria-hidden="true"></i>
                                        Ver campañas
                                    </a>
                                    <span class="onb-done-note">
                                        <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>
                                        Configuración completada
                                    </span>
                                @else
                                    <a class="btn onb-btn-primary" href="{{ route('campanas.index') }}">
                                        Crear mi primera campaña
                                        <i class="bi bi-arrow-right ms-2 text-white" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </li>
                    <li class="onb-step {{ $pasos[5] ? 'is-completed' : 'is-pending is-next' }}">
                        <div class="onb-marker" aria-hidden="true">
                            <span class="onb-marker-number">6</span>
                        </div>
                        <div class="onb-card">
                            <div class="onb-card-head">
                                <span class="onb-step-icon" aria-hidden="true">
                                    <i class="bi bi-gem text-gijac fs-3"></i>
                                </span>
                                <div class="onb-card-titles">
                                    <h2 class="onb-step-title">PASO 6. Adquiere un plan</h2>
                                     @if ($pasos[5])
                                        <span class="onb-status onb-status-done">
                                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                                            Completado
                                        </span>
                                    @else
                                        <span class="onb-status onb-status-pending">
                                            <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                                            Pendiente
                                        </span>
                                    @endif
                                </div>
                                @if (!$pasos[5])
                                    <span class="onb-next-chip">
                                        <i class="bi bi-lightning-charge-fill text-white" aria-hidden="true"></i>
                                        Siguiente paso
                                    </span>
                                @endif
                            </div>
                            <p class="onb-step-desc">
                                Selecciona el plan que mejor se adapte a las necesidades de tu empresa y desbloquea todas las funcionalidades de GIJAC MESSAGE BUSINESS
                            </p>
                            <div class="onb-actions">
                                @if ($pasos[5])
                                    <a class="btn onb-btn-outline" href="{{ route('perfil') }}">
                                        <i class="bi bi-gear me-1" aria-hidden="true"></i>
                                        Ver plan comprado
                                    </a>
                                    <span class="onb-done-note">
                                        <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>
                                        Configuración completada
                                    </span>
                                @else
                                    <a class="btn onb-btn-primary" href="{{ route('precios') }}">
                                        Conocer nuestros planes
                                        <i class="bi bi-arrow-right ms-2 text-white" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </li>
                </ol>
            </div>
        </section>
    @endif
@endsection

@section('scripts')
    @if ($accesos)
        <script src="{{ mix('/js/home.js') }}"></script>
    @endif
@endsection
