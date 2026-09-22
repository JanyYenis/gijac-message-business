@extends('layouts.index', ['drawer' => true])

@section('titulo_drawer', __('Filtros'))

@section('contenido_drawer')
    <form id="fromFiltros">
        <div class="row">
            <div class="col-lg-12 col-md-12 mt-2">
                <label class="form-label">{{ __('Fechas') }}</label>
                <input class="form-control" name="fechas" placeholder="{{ __('Fechas') }}" id="inputFechas"/>
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

@section('content')
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
        <div class="row g-4 mb-4">
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
                <div class="card chart-card">
                    <h5 class="chart-title">
                        <i class="fas fa-clock text-warning"></i>
                        {{ __('Horarios de Apertura por Día') }}
                    </h5>

                    <!-- Tabs for Days -->
                    <ul class="nav nav-tabs" id="dayTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="monday-tab" data-bs-toggle="tab" data-bs-target="#monday" type="button" role="tab">{{ __('Lun') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tuesday-tab" data-bs-toggle="tab" data-bs-target="#tuesday" type="button" role="tab">{{ __('Mar') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="wednesday-tab" data-bs-toggle="tab" data-bs-target="#wednesday" type="button" role="tab">{{ __('Mié') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="thursday-tab" data-bs-toggle="tab" data-bs-target="#thursday" type="button" role="tab">{{ __('Jue') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="friday-tab" data-bs-toggle="tab" data-bs-target="#friday" type="button" role="tab">{{ __('Vie') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="saturday-tab" data-bs-toggle="tab" data-bs-target="#saturday" type="button" role="tab">{{ __('Sáb') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sunday-tab" data-bs-toggle="tab" data-bs-target="#sunday" type="button" role="tab">{{ __('Dom') }}</button>
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
@endsection

@section('scripts')
    <script src="{{ mix('/js/home.js') }}"></script>
@endsection
