@extends('layouts.index')

@section('css')
    <style>
        .page-header h1 {
            font-weight: 700;
            font-size: 2.5rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-subtitle {
            opacity: 0.9;
            font-size: 1.1rem;
            margin-top: 0.5rem;
        }

        /* Card Styles */
        .info-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: none;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-2px);
        }

        /* Contact Info Styles */
        .contact-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            font-weight: 700;
            margin-right: 1.5rem;
        }

        .contact-info h3 {
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 0.5rem;
        }

        .contact-detail {
            color: #6B7280;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-export {
            background: linear-gradient(135deg, var(--success-color), #34D399);
            border: none;
            color: white;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-export:hover {
            background: linear-gradient(135deg, #059669, var(--success-color));
            color: white;
        }

        /* Comparison Cards */
        .comparison-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: none;
            text-align: center;
            transition: all 0.3s ease;
        }

        .comparison-card:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-2px);
        }

        .comparison-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }

        .comparison-label {
            color: #6B7280;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .comparison-vs {
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151;
            margin: 0.5rem 0;
        }

        .comparison-average {
            font-size: 1.5rem;
            font-weight: 600;
            color: #6B7280;
        }

        .comparison-difference {
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }

        .comparison-difference.positive { color: var(--success-color); }
        .comparison-difference.negative { color: var(--danger-color); }

        /* Search and Filter */
        .search-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        .search-input {
            border: 2px solid #E5E7EB;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 211, 102, 0.25);
        }

        /* Pagination */
        .pagination {
            margin: 0;
        }

        .page-link {
            border: none;
            color: var(--primary-color);
            font-weight: 500;
            padding: 0.75rem 1rem;
            margin: 0 2px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6B7280;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
                text-align: center;
            }

            .page-header h1 {
                font-size: 2rem;
                justify-content: center;
            }

            .contact-avatar {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
                margin-right: 1rem;
            }

            .comparison-value {
                font-size: 2rem;
            }

            .chart-container {
                height: 300px;
            }
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(251, 191, 36, 0.1));
            color: var(--warning-color);
            border-left: 4px solid var(--warning-color);
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 197, 253, 0.1));
            color: var(--info-color);
            border-left: 4px solid var(--info-color);
        }

        /* Success/Error Icons */
        .status-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .status-success {
            background-color: var(--success-color);
            color: white;
        }

        .status-error {
            background-color: var(--danger-color);
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid main-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h1 class="text-white">
                        <i class="fas fa-user-chart"></i>
                        Comportamiento del Contacto
                    </h1>
                    <p class="page-subtitle mb-0">Análisis detallado de interacciones y patrones de comportamiento</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('contactos.index') }}" type="button" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="info-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="contact-avatar" id="contactAvatar">
                            {{ $contacto?->nombre ? $contacto?->nombre[0] : 'N/A' }}{{ $contacto?->apellido ? $contacto?->apellido[0] : '' }}
                        </div>
                        <div class="contact-info">
                            <h3 id="contactName">{{ $contacto?->nombre_completo }}</h3>
                            <div class="contact-detail">
                                <i class="fas fa-phone text-muted"></i>
                                <span id="contactPhone">+{{ $contacto?->numero_completo }}</span>
                            </div>
                            <div class="contact-detail">
                                <i class="fas fa-calendar text-muted"></i>
                                <span>Registrado el <span id="contactDate">{{ $contacto->created_at }}</span></span>
                            </div>
                            <div class="contact-detail">
                                <i class="fas fa-chart-line text-muted"></i>
                                <span>Última actividad: <span id="lastActivity">2 días</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        @if ($contacto->estado == 1)
                            <span class="status-badge status-active" id="contactStatus">
                                <i class="fas fa-check-circle"></i>
                                Activo
                            </span>
                        @else
                            <span class="status-badge status-finished" id="contactStatus">
                                <i class="fas fa-check-circle"></i>
                                Inactivo
                            </span>
                        @endif
                        <div class="mt-2" id="contactTags">
                            <span class="tag-badge" style="background-color: #dc354520; color: #dc3545; border: 1px solid #dc354540;">
                                <i class="fas fa-tag"></i>
                                Cliente VIP
                            </span>
                            <span class="tag-badge" style="background-color: #28a74520; color: #28a745; border: 1px solid #28a74540;">
                                <i class="fas fa-tag"></i>
                                Marketing
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <button type="button" class="btn btn-edit" onclick="editContact()">
                        <i class="fas fa-edit"></i>
                        Editar Contacto
                    </button>
                </div>
            </div>
        </div>

        <!-- Campaign History Chart -->
        <div class="chart-card">
            <h5 class="chart-title">
                <i class="fas fa-chart-bar text-primary"></i>
                Historial de Aperturas por Campaña
            </h5>
            <div class="chart-container">
                <div id="campaignHistoryChart"></div>
            </div>
        </div>

        <!-- Campaign History Table -->
        <div class="search-container">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control search-input border-start-0" placeholder="Buscar campañas..." id="searchInput">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter">
                        <option value="">Todas las campañas</option>
                        <option value="opened">Solo abiertas</option>
                        <option value="clicked">Con clics</option>
                        <option value="not-opened">No abiertas</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-export w-100" onclick="exportCampaignsToExcel()">
                        <i class="fas fa-file-excel"></i>
                        Exportar
                    </button>
                </div>
            </div>
        </div>

        <div class="chart-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="chart-title mb-0">
                    <i class="fas fa-history text-secondary"></i>
                    Historial de Campañas
                </h5>
                <div class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    <span id="totalCampaigns">0</span> campañas
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" id="campaignsTable">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="35%">Nombre de Campaña</th>
                            <th width="15%">Fecha de Envío</th>
                            <th width="10%">Abierto</th>
                            <th width="10%">Click</th>
                            <th width="15%">Hora de Apertura</th>
                            <th width="10%">Estado</th>
                        </tr>
                    </thead>
                    <tbody id="campaignsTableBody">
                        <!-- Content will be loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div class="empty-state d-none" id="emptyState">
                <i class="fas fa-search"></i>
                <h5>No se encontraron campañas</h5>
                <p class="mb-0">Este contacto no tiene campañas registradas o no coinciden con los filtros</p>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Mostrando <span id="showingFrom">0</span> a <span id="showingTo">0</span> de <span id="totalRecords">0</span> registros
                </div>
                <nav>
                    <ul class="pagination" id="pagination">
                        <!-- Pagination will be generated here -->
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="chart-card">
                    <h5 class="chart-title">
                        <i class="fas fa-chart-pie text-warning"></i>
                        Enlaces Más Clickeados
                    </h5>
                    <div class="chart-container">
                        <div id="linksChart"></div>
                    </div>
                    <div class="text-center mt-3">
                        <div class="row">
                            <div class="col">
                                <h4 class="text-primary mb-0" id="totalClicks">47</h4>
                                <small class="text-muted">Total de Clics</small>
                            </div>
                            <div class="col">
                                <h4 class="text-success mb-0" id="uniqueLinks">8</h4>
                                <small class="text-muted">Enlaces Únicos</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="chart-card">
                    <h5 class="chart-title">
                        <i class="fas fa-clock text-info"></i>
                        Patrones de Apertura por Hora
                    </h5>
                    <div class="chart-container">
                        <div id="hourlyPatternChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparison Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="comparison-card">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="fas fa-envelope-open text-success me-2" style="font-size: 2rem;"></i>
                        <h5 class="mb-0">Tasa de Apertura</h5>
                    </div>
                    <div class="comparison-value text-success" id="contactOpenRate">89.2%</div>
                    <div class="comparison-label">Este Contacto</div>
                    <div class="comparison-vs">vs</div>
                    <div class="comparison-average" id="averageOpenRate">76.4%</div>
                    <div class="comparison-label">Promedio General</div>
                    <div class="comparison-difference positive" id="openRateDifference">
                        <i class="fas fa-arrow-up"></i>
                        <span>+12.8% mejor que la media</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="comparison-card">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="fas fa-mouse-pointer text-warning me-2" style="font-size: 2rem;"></i>
                        <h5 class="mb-0">Tasa de Clics</h5>
                    </div>
                    <div class="comparison-value text-warning" id="contactClickRate">34.7%</div>
                    <div class="comparison-label">Este Contacto</div>
                    <div class="comparison-vs">vs</div>
                    <div class="comparison-average" id="averageClickRate">22.1%</div>
                    <div class="comparison-label">Promedio General</div>
                    <div class="comparison-difference positive" id="clickRateDifference">
                        <i class="fas fa-arrow-up"></i>
                        <span>+12.6% mejor que la media</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @component('contactos.modals.modal')
    @endcomponent
@endsection

@section('scripts')
    {{-- <script src="{{ mix('/js/contactos/principal.js') }}" ></script> --}}
    <script>
        // Global variables
        let campaigns = [];
        let filteredCampaigns = [];
        let currentPage = 1;
        const itemsPerPage = 10;

        // Initialize
        $(document).ready(function() {
            loadContactData();
            loadCampaignData();
            setupEventListeners();
            initializeCharts();
            renderCampaignsTable();
        });

        // Load contact data
        function loadContactData() {
            const contact = {
                name: 'Ana García Martínez',
                phone: '+34 612 345 678',
                registrationDate: '15 Mar 2024',
                lastActivity: '2 días',
                status: 'active',
                tags: [
                    { name: 'Cliente VIP', color: '#dc3545' },
                    { name: 'Marketing', color: '#28a745' }
                ],
                openRate: 89.2,
                clickRate: 34.7,
                totalClicks: 47,
                uniqueLinks: 8
            };

            // Update contact info
            $('#contactOpenRate').text(contact.openRate + '%');
            $('#contactClickRate').text(contact.clickRate + '%');
            $('#totalClicks').text(contact.totalClicks);
            $('#uniqueLinks').text(contact.uniqueLinks);
        }

        // Load campaign data
        function loadCampaignData() {
            campaigns = [
                {
                    id: 1,
                    name: 'Black Friday 2024',
                    date: '15 Nov 2024',
                    opened: true,
                    clicked: true,
                    openTime: '09:23',
                    status: 'delivered'
                },
                {
                    id: 2,
                    name: 'Cyber Monday Especial',
                    date: '28 Nov 2024',
                    opened: true,
                    clicked: false,
                    openTime: '14:15',
                    status: 'delivered'
                },
                {
                    id: 3,
                    name: 'Recordatorio de Pago',
                    date: '12 Nov 2024',
                    opened: true,
                    clicked: true,
                    openTime: '11:45',
                    status: 'delivered'
                },
                {
                    id: 4,
                    name: 'Bienvenida Nuevos Clientes',
                    date: '10 Nov 2024',
                    opened: false,
                    clicked: false,
                    openTime: null,
                    status: 'delivered'
                },
                {
                    id: 5,
                    name: 'Encuesta de Satisfacción',
                    date: '08 Nov 2024',
                    opened: true,
                    clicked: true,
                    openTime: '16:30',
                    status: 'delivered'
                },
                {
                    id: 6,
                    name: 'Oferta Especial Verano',
                    date: '05 Nov 2024',
                    opened: true,
                    clicked: false,
                    openTime: '10:12',
                    status: 'delivered'
                },
                {
                    id: 7,
                    name: 'Newsletter Semanal',
                    date: '01 Nov 2024',
                    opened: true,
                    clicked: true,
                    openTime: '08:45',
                    status: 'delivered'
                },
                {
                    id: 8,
                    name: 'Promoción Halloween',
                    date: '31 Oct 2024',
                    opened: false,
                    clicked: false,
                    openTime: null,
                    status: 'delivered'
                },
                {
                    id: 9,
                    name: 'Actualización de Productos',
                    date: '28 Oct 2024',
                    opened: true,
                    clicked: false,
                    openTime: '13:20',
                    status: 'delivered'
                },
                {
                    id: 10,
                    name: 'Invitación Evento VIP',
                    date: '25 Oct 2024',
                    opened: true,
                    clicked: true,
                    openTime: '15:55',
                    status: 'delivered'
                }
            ];

            filteredCampaigns = [...campaigns];
        }

        // Setup event listeners
        function setupEventListeners() {
            $('#searchInput').on('input', function() {
                filterCampaigns();
            });

            $('#statusFilter').on('change', function() {
                filterCampaigns();
            });
        }

        // Filter campaigns
        function filterCampaigns() {
            const searchTerm = $('#searchInput').val().toLowerCase();
            const statusFilter = $('#statusFilter').val();

            filteredCampaigns = campaigns.filter(campaign => {
                const matchesSearch = campaign.name.toLowerCase().includes(searchTerm);

                let matchesStatus = true;
                switch(statusFilter) {
                    case 'opened':
                        matchesStatus = campaign.opened;
                        break;
                    case 'clicked':
                        matchesStatus = campaign.clicked;
                        break;
                    case 'not-opened':
                        matchesStatus = !campaign.opened;
                        break;
                }

                return matchesSearch && matchesStatus;
            });

            currentPage = 1;
            renderCampaignsTable();
        }

        // Render campaigns table
        function renderCampaignsTable() {
            const tbody = $('#campaignsTableBody');
            const emptyState = $('#emptyState');

            if (filteredCampaigns.length === 0) {
                tbody.empty();
                emptyState.removeClass('d-none');
                updatePaginationInfo(0, 0, 0);
                $('#pagination').empty();
                $('#totalCampaigns').text('0');
                return;
            }

            emptyState.addClass('d-none');

            // Calculate pagination
            const totalItems = filteredCampaigns.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
            const pageItems = filteredCampaigns.slice(startIndex, endIndex);

            // Render table rows
            tbody.empty();
            pageItems.forEach((campaign, index) => {
                const row = `
                    <tr>
                        <td>${startIndex + index + 1}</td>
                        <td>
                            <div class="fw-semibold">${campaign.name}</div>
                        </td>
                        <td>
                            <span class="text-muted">${campaign.date}</span>
                        </td>
                        <td>
                            <div class="status-icon ${campaign.opened ? 'status-success' : 'status-error'}">
                                ${campaign.opened ? '✓' : '✗'}
                            </div>
                        </td>
                        <td>
                            <span class="badge ${campaign.clicked ? 'bg-primary text-white' : 'bg-secondary'}">
                                ${campaign.clicked ? 'Sí' : 'No'}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted">
                                ${campaign.openTime || '-'}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-primary text-white">
                                Entregado
                            </span>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });

            // Update pagination info
            updatePaginationInfo(startIndex + 1, endIndex, totalItems);
            renderPagination(totalPages);
            $('#totalCampaigns').text(totalItems);
        }

        // Update pagination info
        function updatePaginationInfo(from, to, total) {
            $('#showingFrom').text(from);
            $('#showingTo').text(to);
            $('#totalRecords').text(total);
        }

        // Render pagination
        function renderPagination(totalPages) {
            const pagination = $('#pagination');
            pagination.empty();

            if (totalPages <= 1) return;

            // Previous button
            const prevDisabled = currentPage === 1 ? 'disabled' : '';
            pagination.append(`
                <li class="page-item ${prevDisabled}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            `);

            // Page numbers
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);

            for (let i = startPage; i <= endPage; i++) {
                const active = i === currentPage ? 'active' : '';
                pagination.append(`
                    <li class="page-item ${active}">
                        <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                    </li>
                `);
            }

            // Next button
            const nextDisabled = currentPage === totalPages ? 'disabled' : '';
            pagination.append(`
                <li class="page-item ${nextDisabled}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            `);
        }

        // Change page
        function changePage(page) {
            const totalPages = Math.ceil(filteredCampaigns.length / itemsPerPage);
            if (page < 1 || page > totalPages) return;

            currentPage = page;
            renderCampaignsTable();
        }

        // Initialize charts
        function initializeCharts() {
            // Campaign History Chart (Bar)
            const campaignHistoryOptions = {
                series: [{
                    name: 'Abiertos',
                    data: [1, 1, 1, 0, 1, 1, 1, 0, 1, 1]
                }, {
                    name: 'Con Clics',
                    data: [1, 0, 1, 0, 1, 0, 1, 0, 0, 1]
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#10B981', '#F59E0B'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 4
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: campaigns.slice(0, 10).map(c => c.name.substring(0, 15) + '...'),
                    labels: {
                        rotate: -45
                    }
                },
                yaxis: {
                    title: {
                        text: 'Interacciones'
                    },
                    max: 1,
                    tickAmount: 1,
                    labels: {
                        formatter: function (val) {
                            return val === 1 ? 'Sí' : 'No'
                        }
                    }
                },
                legend: {
                    position: 'top'
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val === 1 ? 'Sí' : 'No'
                        }
                    }
                }
            };

            const campaignHistoryChart = new ApexCharts(document.querySelector("#campaignHistoryChart"), campaignHistoryOptions);
            campaignHistoryChart.render();

            // Links Chart (Pie)
            const linksOptions = {
                series: [15, 12, 8, 7, 5],
                chart: {
                    type: 'pie',
                    height: 350
                },
                labels: ['Página Principal', 'Productos', 'Ofertas', 'Blog', 'Contacto'],
                colors: ['#25D366', '#128C7E', '#075E54', '#10B981', '#F59E0B'],
                legend: {
                    position: 'bottom'
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex] + ' clics'
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " clics"
                        }
                    }
                }
            };

            const linksChart = new ApexCharts(document.querySelector("#linksChart"), linksOptions);
            linksChart.render();

            // Hourly Pattern Chart (Area)
            const hourlyData = [];
            for (let i = 0; i < 24; i++) {
                const hour = i.toString().padStart(2, '0') + ':00';
                let value = 0;
                if (i >= 8 && i <= 18) {
                    value = Math.floor(Math.random() * 5) + 1;
                }
                hourlyData.push(value);
            }

            const hourlyPatternOptions = {
                series: [{
                    name: 'Aperturas',
                    data: hourlyData
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#3B82F6'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: Array.from({length: 24}, (_, i) => i.toString().padStart(2, '0') + ':00'),
                    title: {
                        text: 'Hora del Día'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Número de Aperturas'
                    }
                },
                tooltip: {
                    x: {
                        format: 'HH:mm'
                    },
                    y: {
                        formatter: function (val) {
                            return val + " aperturas"
                        }
                    }
                }
            };

            const hourlyPatternChart = new ApexCharts(document.querySelector("#hourlyPatternChart"), hourlyPatternOptions);
            hourlyPatternChart.render();
        }

        // Export campaigns to Excel
        function exportCampaignsToExcel() {
            if (filteredCampaigns.length === 0) {
                Swal.fire({
                    title: 'Sin datos',
                    text: 'No hay campañas para exportar con los filtros actuales',
                    icon: 'warning',
                    confirmButtonColor: '#F59E0B'
                });
                return;
            }

            Swal.fire({
                title: 'Exportando campañas...',
                html: `Preparando historial de <strong>${filteredCampaigns.length}</strong> campañas`,
                icon: 'info',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            }).then(() => {
                Swal.fire({
                    title: '¡Exportación Completa!',
                    text: 'El historial de campañas se ha exportado correctamente',
                    icon: 'success',
                    confirmButtonText: 'Descargar',
                    confirmButtonColor: '#10B981'
                });
            });
        }

        // Edit contact
        function editContact() {
            Swal.fire({
                title: 'Editar Contacto',
                html: `
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" value="Ana García Martínez">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" value="+34 612 345 678">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select">
                                <option value="active" selected>Activo</option>
                                <option value="inactive">Inactivo</option>
                            </select>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar Cambios',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3B82F6',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '¡Actualizado!',
                        text: 'Los datos del contacto han sido actualizados correctamente',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }

        // Go back
        function goBack() {
            window.history.back();
        }

        // Show alert if no campaigns
        if (campaigns.length === 0) {
            const alertHtml = `
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Este contacto no tiene campañas registradas. Los gráficos y análisis estarán disponibles cuando se envíen campañas.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            $('.main-container').prepend(alertHtml);
        }
    </script>
@endsection
