@extends('layouts.index')

@section('css')
@endsection

@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="text-white">
                    <i class="fas fa-chart-line"></i>
                    <span>{{ $campana?->nombre }}</span>
                </h1>
                <div class="mt-3">
                    <div class="row g-3">
                        <div class="col-auto fs-2">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <span id="campaignDate">{{ $campana?->fecha_envio->translatedFormat('j \d\e F \d\e\l Y') }}</span>
                        </div>
                        <div class="col-auto">
                            @component('sistema.estado')
                                @slot('concepto', $campana?->infoEstado)
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3 align-items-center">
                    <div class="col-md-12">
                        <select class="form-select campaign-selector" data-control="select2"
                            data-placeholder="Campañas" data-allow-clear="true" id="campaignSelector">
                            <option value=""></option>
                            @foreach ($campanas as $item)
                                <option value="{{ $item->id }}" {{ $campana?->id == $item?->id ? 'selected' : '' }}>{{ $item?->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="col-md-4">
                        <a href="{{ route('campanas.exportar', ['campana' => $campana->id]) }}" type="button" class="btn btn-new-template">
                            <i class="fas fa-file-excel"></i>
                            Generar Excel
                        </a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid main-container">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="phone-preview">
                    <h6 class="fw-bold mb-3 text-center">
                        <i class="bi bi-phone-fill fs-2 me-2" style="color: var(--primary-color);"></i>
                        Vista Previa
                    </h6>
                    <div class="phone-mockup">
                        <div class="phone-screen">
                            <div class="phone-header">
                                <div class="contact-avatar">JD</div>
                                <div class="contact-info">
                                    <h6 class="text-white">Juan Pérez</h6>
                                    <small>en línea</small>
                                </div>
                            </div>
                            <div class="chat-area conversation conversation-container">
                                <div class="message-area" style="background: transparent;">
                                    <div class="template-message" id="templatePreview">
                                        <!-- Template content will be inserted here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Vista previa aproximada del mensaje
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8">
                <!-- Metrics Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="card metric-card">
                            <div class="metric-icon contacts">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="metric-value" id="totalContacts">{{ formatoMiles(count($campana?->enviosActivos)) }}</h3>
                            <p class="metric-label">Total Contactos</p>
                            <div class="metric-change positive">
                                <i class="fas fa-arrow-up"></i>
                                <span>100% enviados</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card metric-card">
                            <div class="metric-icon opened">
                                <i class="fas fa-envelope-open"></i>
                            </div>
                            <h3 class="metric-value" id="totalOpened">{{ formatoMiles(count($campana?->mensajesAbiertos)) }}</h3>
                            <p class="metric-label">Mensajes Abiertos</p>
                            @php
                                if (count($campana?->enviosActivos) && count($campana?->mensajesAbiertos)) {
                                    $class = round((count($campana?->mensajesAbiertos) / count($campana?->enviosActivos)) * 100) >= 50 ? 'positive' : 'negative';
                                    $icono = round((count($campana?->mensajesAbiertos) / count($campana?->enviosActivos)) * 100) >= 50 ? 'fa-arrow-up' : 'fa-arrow-down';
                                } else {
                                    $class = 'negative';
                                    $icono = 'fa-arrow-down';
                                }
                            @endphp
                            <div class="metric-change {{ $class }}">
                                <i class="fas {{ $icono }}"></i>
                                <span>{{ count($campana?->enviosActivos) && count($campana?->mensajesAbiertos) ?  round((count($campana?->mensajesAbiertos) / count($campana?->enviosActivos)) * 100) : 0 }}% tasa apertura</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card metric-card">
                            <div class="metric-icon clicks">
                                <i class="fas fa-mouse-pointer"></i>
                            </div>
                            <h3 class="metric-value" id="totalClicks">{{ formatoMiles(count($campana?->clicksAbiertos)) }}</h3>
                            <p class="metric-label">Clics en Links</p>
                            @php
                                if (count($campana?->enviosActivos) && count($campana?->clicksAbiertos)) {
                                    $class = round((count($campana?->clicksAbiertos) / count($campana?->enviosActivos)) * 100) >= 50 ? 'positive' : 'negative';
                                    $icono = round((count($campana?->clicksAbiertos) / count($campana?->enviosActivos)) * 100) >= 50 ? 'fa-arrow-up' : 'fa-arrow-down';
                                } else {
                                    $class = 'negative';
                                    $icono = 'fa-arrow-down';
                                }
                            @endphp
                            <div class="metric-change {{ $class }}">
                                <i class="fas {{ $icono }}"></i>
                                <span>{{ count($campana?->enviosActivos) && count($campana?->clicksAbiertos) ? round((count($campana?->clicksAbiertos) / count($campana?->enviosActivos)) * 100) : 0 }}% CTR</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card metric-card">
                            <div class="metric-icon effectiveness">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <h3 class="metric-value" id="effectiveness">{{ round((count($campana?->mensajesAbiertos) / count($campana?->enviosActivos)) * 100) }}<span style="font-size: 1.5rem;">%</span></h3>
                            <p class="metric-label">Efectividad</p>
                            @php
                                if (count($campana?->enviosActivos) && count($campana?->mensajesAbiertos)) {
                                    $class = round((count($campana?->mensajesAbiertos) / count($campana?->enviosActivos)) * 100) >= 50 ? 'positive' : 'negative';
                                    $icono = round((count($campana?->mensajesAbiertos) / count($campana?->enviosActivos)) * 100) >= 50 ? 'fa-arrow-up' : 'fa-arrow-down';
                                    $label = round((count($campana?->mensajesAbiertos) / count($campana?->enviosActivos)) * 100) >= 50 ? 'Excelente resultado' : 'Bajo resultado';
                                } else {
                                    $class = 'negative';
                                    $icono = 'fa-arrow-down';
                                    $label = 'Bajo resultado';
                                }
                            @endphp
                            <div class="metric-change {{ $class }}">
                                <i class="fas {{ $icono }}"></i>
                                <span>{{ $label }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row g-4 mb-4">
                    <div class="col-lg-6">
                        <div class="card chart-card">
                            <h5 class="chart-title">
                                <i class="fas fa-chart-pie text-primary"></i>
                                Distribución de Apertura
                            </h5>
                            <div class="chart-container">
                                <div id="openingChart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card chart-card">
                            <h5 class="chart-title">
                                <i class="fas fa-chart-bar text-success"></i>
                                Clics vs Aperturas
                            </h5>
                            <div class="chart-container">
                                <div id="clicksChart"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hourly Activity Chart -->
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <div class="card chart-card">
                            <h5 class="chart-title">
                                <i class="fas fa-clock text-warning"></i>
                                Actividad por Horas del Día
                            </h5>
                            <div class="chart-container">
                                <div id="hourlyChart"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card table-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="chart-title mb-0">
                            <i class="fas fa-users text-info"></i>
                            Detalle de Contactos
                        </h5>
                    </div>

                    <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto tablasScroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_contacts_header"
                        data-kt-scroll-wrappers="#kt_content, #kt_chat_contacts_body" data-kt-scroll-offset="5px"
                        style="max-height: 410px;">
                        <div class="table-responsive">
                            <table border="1" class="table table-striped table-bordered" id="tablaDetalleCampanas">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center all">#</th>
                                        <th width="10%" class="text-center all">Contacto</th>
                                        <th width="10%" class="text-center all">Etiqueta</th>
                                        <th width="10%" class="text-center all">Abierto</th>
                                        <th width="10%" class="text-center all">Click (Links)</th>
                                        <th width="10%" class="text-center all">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
@endsection

@section('scripts')
    <script src="{{ mix('/js/campanas/detalle.js') }}" ></script>
    <script src="{{ mix('/js/campanas/ver.js') }}" ></script>
    <script>
        window.campana_id = '{{ $campana->id }}';
        window.listadoDetalleCampana('{{ $campana->id }}');
        window.verPlantilla('{{ $campana->id_plantilla }}');
    </script>
@endsection
