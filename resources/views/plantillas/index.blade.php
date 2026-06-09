@extends('layouts.index')

@section('css')
    <style>
        .main-container {
            min-height: 100vh;
            padding: 2rem 0;
        }

        /* Table Card Styles */
        .table-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
        }

        .table-card .card-header {
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            padding: 1.5rem;
            border-radius: 1rem 1rem 0 0;
        }

        .table-responsive {
            border-radius: 0 0 1rem 1rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background: var(--gray-50);
            border: none;
            font-weight: 600;
            color: var(--gray-700);
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table td {
            border: none;
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-100);
        }

        .table tbody tr:hover {
            background: var(--gray-50);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-approved {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-green);
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-amber);
        }

        .status-rejected {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-red);
        }

        /* Category Badges */
        .category-badge {
            background: rgba(34, 197, 94, 0.1);
            color: var(--primary-green);
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 0.5rem;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-view {
            background: rgba(34, 197, 94, 0.1);
            color: var(--primary-green);
        }

        .btn-view:hover {
            background: var(--primary-green);
            color: white;
            transform: scale(1.1);
        }

        .btn-edit {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-amber);
        }

        .btn-edit:hover {
            background: var(--warning-amber);
            color: white;
            transform: scale(1.1);
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-red);
        }

        .btn-delete:hover {
            background: var(--danger-red);
            color: white;
            transform: scale(1.1);
        }

        /* Phone Preview Modal */
        .phone-preview-modal .modal-dialog {
            max-width: 400px;
        }

        .phone-mockup {
            width: 320px;
            height: 640px;
            background: #1F2937;
            border-radius: 2rem;
            padding: 1rem;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .phone-screen {
            width: 100%;
            height: 100%;
            background: #0D1117;
            border-radius: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .whatsapp-header {
            background: var(--whatsapp-dark);
            padding: 1rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .business-avatar {
            width: 40px;
            height: 40px;
            background: var(--whatsapp-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .business-info h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .business-info small {
            opacity: 0.8;
            font-size: 0.75rem;
        }

        /* Search and Filter Bar */
        .filter-bar {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .search-input {
            border: 2px solid var(--gray-200);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--whatsapp-green);
            box-shadow: 0 0 0 0.2rem rgba(37, 211, 102, 0.25);
        }

        .filter-select {
            border: 2px solid var(--gray-200);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            border-color: var(--whatsapp-green);
            box-shadow: 0 0 0 0.2rem rgba(37, 211, 102, 0.25);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem 0;
            }

            .page-header {
                padding: 1.5rem;
                text-align: center;
            }

            .page-header h1 {
                font-size: 1.5rem;
                justify-content: center;
            }

            .action-buttons {
                justify-content: center;
            }

            .table th,
            .table td {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }

            .phone-mockup {
                width: 280px;
                height: 560px;
            }
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--gray-400);
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
                        <i class="fab fa-whatsapp text-white"></i>
                        Plantillas de WhatsApp
                    </h1>
                    <p class="subtitle mb-0">Gestiona y previsualiza tus plantillas aprobadas</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-new-template" disabled>
                        <i class="fas fa-plus fs-1"></i>
                        Nueva Plantilla
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0" style="border-color: var(--gray-200);">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control search-input border-start-0"
                            placeholder="Buscar plantillas..." id="searchInput">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select filter-select" data-control="select2" data-hide-search="true"
                        data-placeholder="Categoría" data-allow-clear="true" id="categoryFilter">
                        <option value=""></option>
                        @foreach ($categorias as $item)
                            <option value="{{ $item?->codigo }}">{{ $item?->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select filter-select" data-control="select2" data-hide-search="true"
                        data-placeholder="Estados" data-allow-clear="true" id="statusFilter">
                        <option value=""></option>
                        @foreach ($estados as $item)
                            <option value="{{ $item?->codigo }}">{{ $item?->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Templates Table -->
        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <div class="contenedor" id="kt_content_container">
                <div class="d-flex flex-column flex-lg-row">
                    <div class="flex-column flex-lg-row-auto w-100 mb-10 mb-lg-0">
                        <div class="card card-flush">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 fw-bold text-muted">
                                        <i class="fas fa-list me-2"></i>
                                        Lista de Plantillas
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body pt-5" id="kt_chat_contacts_body">
                                <div class="table-responsive">
                                    <table class="table" id="tablaPlantilla">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center all">#</th>
                                                <th width="10%" class="text-center all">Nombre</th>
                                                <th width="10%" class="text-center all">Categoría</th>
                                                <th width="10%" class="text-center all">Lenguaje</th>
                                                <th width="10%" class="text-center all">Estado</th>
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
        </div>

    </div>
@endsection

@section('modal')
    @component('plantillas.modals.crear')
        @slot('numeroTel', $numeroTel)
    @endcomponent
    @component('plantillas.modals.ver')
    @endcomponent
@endsection

@section('scripts')
    <script src="{{ mix('/js/plantillas/principal.js') }}"></script>
    <script>
        // Template data (in a real app, this would come from an API)
        const templates = {
            'bienvenida_cliente': {
                name: 'bienvenida_cliente',
                header: {
                    type: 'IMAGE',
                    url: 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=400&h=200&fit=crop'
                },
                body: '¡Hola {{1}}! 👋\n\nBienvenido a *Mi Business*. Estamos emocionados de tenerte como parte de nuestra familia.\n\nTu cuenta ha sido activada exitosamente y ya puedes comenzar a disfrutar de todos nuestros servicios.',
                footer: 'Gracias por confiar en nosotros',
                buttons: [
                    { type: 'URL', text: 'Ver mi cuenta', url: 'https://mibusiness.com/cuenta' },
                    { type: 'PHONE_NUMBER', text: 'Llamar soporte', phone: '+1234567890' }
                ]
            },
            'confirmacion_pedido': {
                name: 'confirmacion_pedido',
                body: 'Hola {{1}},\n\nTu pedido #{{2}} ha sido confirmado exitosamente.\n\n📦 *Detalles del pedido:*\n• Total: ${{3}}\n• Fecha estimada de entrega: {{4}}\n• Dirección: {{5}}\n\nTe notificaremos cuando tu pedido esté en camino.',
                footer: 'Equipo de Mi Business',
                buttons: [
                    { type: 'URL', text: 'Rastrear pedido', url: 'https://mibusiness.com/tracking/{{2}}' }
                ]
            },
            'codigo_verificacion': {
                name: 'codigo_verificacion',
                body: 'Tu código de verificación es: *{{1}}*\n\nEste código expira en 10 minutos.\n\n⚠️ No compartas este código con nadie.',
                footer: 'Código de seguridad'
            },
            'promocion_especial': {
                name: 'promocion_especial',
                header: {
                    type: 'IMAGE',
                    url: 'https://images.unsplash.com/photo-1607083206869-4c7672e72a8a?w=400&h=200&fit=crop'
                },
                body: '🎉 *¡OFERTA ESPECIAL!* 🎉\n\nHola {{1}},\n\nTenemos una promoción increíble solo para ti:\n\n✨ *50% de descuento* en todos nuestros productos\n⏰ Válido hasta {{2}}\n🎁 Envío gratis en compras superiores a $100',
                footer: 'Términos y condiciones aplican',
                buttons: [
                    { type: 'URL', text: 'Comprar ahora', url: 'https://mibusiness.com/promocion' },
                    { type: 'PHONE_NUMBER', text: 'Más información', phone: '+1234567890' }
                ]
            }
        };

        function previewTemplate(templateName) {
            const template = templates[templateName];
            if (!template) return;

            let previewHTML = '';

            // Header (image/video)
            if (template.header) {
                if (template.header.type === 'IMAGE') {
                    previewHTML += `<img src="${template.header.url}" alt="Header" class="template-header-media">`;
                }
            }

            // Body
            if (template.body) {
                let bodyText = template.body
                    .replace(/{{1}}/g, 'Juan Pérez')
                    .replace(/{{2}}/g, 'ORD-2024-001')
                    .replace(/{{3}}/g, '299.99')
                    .replace(/{{4}}/g, '15 de Enero, 2024')
                    .replace(/{{5}}/g, 'Av. Siempre Viva 123')
                    .replace(/\*([^*]+)\*/g, '<strong>$1</strong>')
                    .replace(/\n/g, '<br>');

                previewHTML += `<div class="template-body">${bodyText}</div>`;
            }

            // Footer
            if (template.footer) {
                previewHTML += `<div class="template-footer">${template.footer}</div>`;
            }

            // Buttons
            if (template.buttons && template.buttons.length > 0) {
                previewHTML += '<div class="template-buttons">';
                template.buttons.forEach(button => {
                    const buttonClass = button.type === 'PHONE_NUMBER' ? 'call-button' : '';
                    const icon = button.type === 'PHONE_NUMBER' ? '<i class="fas fa-phone me-1"></i>' :
                                button.type === 'URL' ? '<i class="fas fa-external-link-alt me-1"></i>' : '';
                    previewHTML += `<button class="template-button ${buttonClass}">${icon}${button.text}</button>`;
                });
                previewHTML += '</div>';
            }

            // Add timestamp
            previewHTML += '<div class="message-time">12:34 ✓✓</div>';

            document.getElementById('templatePreview').innerHTML = previewHTML;
            document.getElementById('previewModalLabel').innerHTML = `<i class="fab fa-whatsapp me-2"></i>Vista Previa - ${templateName}`;

            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        }
    </script>
@endsection
