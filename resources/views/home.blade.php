@extends('layouts.index', ['drawer' => true])

@section('titulo_drawer', 'Filtros')

@section('contenido_drawer')
    <form id="fromFiltros">
        <div class="row">
            <div class="col-lg-12 col-md-12 mt-2">
                <label class="form-label">Fechas</label>
                <input class="form-control" name="fechas" placeholder="Fechas" id="inputFechas"/>
            </div>
            <div class="col-lg-12 col-md-12 mt-2">
                <label class="form-label">Etiquetas</label>
                <select id="selectEtiquetas" name="etiquetas" class="form-select" multiple
                    data-placeholder="Seleccione etiquetas" data-allow-clear="true">
                    <option value=""></option>
                </select>
            </div>
            <div class="col-lg-12 col-md-12 mt-2">
                <label class="form-label">Contactos</label>
                <select id="selectContactos" name="contactos" class="form-select" multiple
                    data-placeholder="Seleccione el o los contactos" data-allow-clear="true">
                    <option value=""></option>
                </select>
            </div>
            <div class="col-lg-12 col-md-12 mt-2">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <button type="reset" class="btn btn-secondary" id="btnLimpiar">Limpiar</button>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <button type="submit" class="btn btn-success">Filtrar</button>
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
                    Dashboard Analytics
                </h1>
                <p class="subtitle mb-0">Monitorea el rendimiento de tus campañas de WhatsApp Business</p>
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
                    <p class="metric-label">Contactos Activos</p>
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
                    <p class="metric-label">Campañas Enviadas</p>
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
                    <h3 class="metric-value" id="diaEfectivo">0</h3>
                    <p class="metric-label">Tu día más efectivo</p>
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
                    <p class="metric-label">Efectividad</p>
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
                        Campañas Enviadas por Mes
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
                        Distribución por Etiquetas
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
                        Horarios de Apertura por Día
                    </h5>

                    <!-- Tabs for Days -->
                    <ul class="nav nav-tabs" id="dayTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="monday-tab" data-bs-toggle="tab" data-bs-target="#monday" type="button" role="tab">Lun</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tuesday-tab" data-bs-toggle="tab" data-bs-target="#tuesday" type="button" role="tab">Mar</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="wednesday-tab" data-bs-toggle="tab" data-bs-target="#wednesday" type="button" role="tab">Mié</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="thursday-tab" data-bs-toggle="tab" data-bs-target="#thursday" type="button" role="tab">Jue</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="friday-tab" data-bs-toggle="tab" data-bs-target="#friday" type="button" role="tab">Vie</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="saturday-tab" data-bs-toggle="tab" data-bs-target="#saturday" type="button" role="tab">Sáb</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sunday-tab" data-bs-toggle="tab" data-bs-target="#sunday" type="button" role="tab">Dom</button>
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
                        Embudo de efectividad
                    </h5>
                    <div class="chart-container">
                        <div id="chartEmbudo"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Campaigns Table -->
        {{-- <div class="card table-card">
            <h5 class="chart-title">
                <i class="fas fa-history text-secondary"></i>
                Campañas
            </h5>
            <div class="table-responsive">
                <table class="table" id="tablaCampanas">
                    <thead>
                        <tr>
                            <th>Nombre de Campaña</th>
                            <th>Estado</th>
                            <th>Fecha de Envío</th>
                            <th>Mensajes Enviados</th>
                            <th>Efectividad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="fw-semibold">Black Friday 2024</div>
                                <small class="text-muted">Promoción especial</small>
                            </td>
                            <td><span class="status-badge status-active">Activo</span></td>
                            <td>15 Nov 2024</td>
                            <td>8,547</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-success me-2">96.2%</span>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 96.2%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="fw-semibold">Recordatorio de Pago</div>
                                <small class="text-muted">Notificación automática</small>
                            </td>
                            <td><span class="status-badge status-finished">Finalizado</span></td>
                            <td>12 Nov 2024</td>
                            <td>3,421</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-success me-2">94.8%</span>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 94.8%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="fw-semibold">Bienvenida Nuevos Clientes</div>
                                <small class="text-muted">Mensaje de bienvenida</small>
                            </td>
                            <td><span class="status-badge status-paused">Pausado</span></td>
                            <td>10 Nov 2024</td>
                            <td>1,234</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-warning me-2">87.3%</span>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-warning" style="width: 87.3%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="fw-semibold">Encuesta de Satisfacción</div>
                                <small class="text-muted">Feedback de clientes</small>
                            </td>
                            <td><span class="status-badge status-finished">Finalizado</span></td>
                            <td>08 Nov 2024</td>
                            <td>5,678</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-success me-2">92.1%</span>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 92.1%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="fw-semibold">Oferta Cyber Monday</div>
                                <small class="text-muted">Descuentos especiales</small>
                            </td>
                            <td><span class="status-badge status-active">Activo</span></td>
                            <td>05 Nov 2024</td>
                            <td>12,890</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-success me-2">98.5%</span>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 98.5%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> --}}
    </div>
@endsection

@section('scripts')
    <script src="{{ mix('/js/home.js') }}"></script>
@endsection
