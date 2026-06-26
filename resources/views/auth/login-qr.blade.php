@extends('layouts.index')

@section('css')
    <style>
        :root {
            --whatsapp-dark: #075E54;
            --whatsapp-green: #25D366;
            --whatsapp-light: #ECE5DD;
            --whatsapp-gray: #E1E8ED;
            --text-dark: #1F2937;
            --text-light: #6B7280;
            --success-color: #10B981;
            --danger-color: #EF4444;
            --warning-color: #F59E0B;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--whatsapp-dark) 0%, #0a4d41 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }

        /* SIDEBAR */
        .sidebar {
            background: white;
            padding: 40px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .user-profile {
            text-align: center;
        }

        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            margin: 0 auto 20px;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        }

        .user-name {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .user-email {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 15px;
        }

        .user-status {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .status-badge {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .status-badge.connected {
            background: var(--whatsapp-green);
        }

        .status-badge.disconnected {
            background: #D1D5DB;
        }

        .divider {
            border-top: 1px solid #E5E7EB;
            margin: 25px 0;
        }

        .info-section {
            text-align: center;
            margin-bottom: 15px;
        }

        .info-label {
            font-size: 12px;
            color: var(--text-light);
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 14px;
            color: var(--text-dark);
        }

        /* MAIN PANEL */
        .main-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.05);
        }

        .qr-card,
        .session-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .qr-container {
            margin: 30px 0;
            padding: 20px;
            background: var(--whatsapp-light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-code {
            width: 250px;
            height: 250px;
            background: white;
            border: 2px solid var(--whatsapp-dark);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 100px;
            color: var(--whatsapp-dark);
        }

        .qr-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .qr-description {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .spinner-animation {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid var(--whatsapp-light);
            border-top-color: var(--whatsapp-green);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .waiting-text {
            font-size: 12px;
            color: var(--text-light);
            margin-top: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* SESSION CARD */
        .session-info {
            background: #F9FAFB;
            border-left: 4px solid var(--whatsapp-green);
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0 30px 0;
            text-align: left;
        }

        .device-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #E5E7EB;
            font-size: 14px;
        }

        .device-item:last-child {
            border-bottom: none;
        }

        .device-label {
            color: var(--text-light);
            font-weight: 500;
        }

        .device-value {
            color: var(--text-dark);
            font-weight: 600;
        }

        .badge-session {
            display: inline-block;
            padding: 6px 12px;
            background: linear-gradient(135deg, var(--whatsapp-green), var(--success-color));
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin: 20px 0;
        }

        /* BUTTONS */
        .btn-qr {
            background: linear-gradient(135deg, var(--whatsapp-green), #1fb464);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 10px;
        }

        .btn-qr:hover {
            background: linear-gradient(135deg, #229954, #1da85a);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
        }

        .btn-logout {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-logout:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }

        .btn-secondary-light {
            background: var(--whatsapp-light);
            border: none;
            color: var(--text-dark);
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 13px;
        }

        .btn-secondary-light:hover {
            background: #D4CDD0;
            color: var(--text-dark);
        }

        /* ALERTS */
        .alert-custom {
            border-radius: 8px;
            border: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success-custom {
            background: #DCFCE7;
            color: #166534;
        }

        .alert-error-custom {
            background: #FEE2E2;
            color: #991B1B;
        }

        /* HIDDEN ELEMENTS */
        .hidden {
            display: none !important;
        }

        .d-flex-center {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
            }

            .sidebar {
                min-height: auto;
                padding: 30px;
                border-bottom: 1px solid #E5E7EB;
            }

            .main-panel {
                min-height: auto;
                padding: 30px;
            }

            .qr-card,
            .session-card {
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                padding: 20px;
            }

            .main-panel {
                padding: 20px;
            }

            .qr-card,
            .session-card {
                padding: 25px;
            }

            .qr-code {
                width: 200px;
                height: 200px;
                font-size: 80px;
            }

            .qr-title {
                font-size: 20px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="login-container">
        <!-- SIDEBAR USUARIO -->
        <div class="sidebar">
            <div class="user-profile">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-name" id="userName">Juan Pérez García</div>
                <div class="user-email" id="userEmail">juan.perez@gijacweb.com</div>

                <div class="user-status">
                    <span class="status-badge disconnected" id="statusBadge"></span>
                    <span id="statusText">Desconectado</span>
                </div>

                <div class="divider"></div>

                <div class="info-section">
                    <div class="info-label">Último intento</div>
                    <div class="info-value" id="lastAttempt">Hace 5 minutos</div>
                </div>

                <div class="info-section">
                    <div class="info-label">Navegador</div>
                    <div class="info-value">Chrome 120</div>
                </div>

                <div class="info-section">
                    <div class="info-label">Versión</div>
                    <div class="info-value">2.24.6</div>
                </div>
            </div>
        </div>

        <!-- PANEL PRINCIPAL -->
        <div class="main-panel">
            <!-- QR CARD -->
            <div class="qr-card" id="qrCard">
                <div class="qr-title">Escanea tu código QR</div>
                <div class="qr-description">
                    Abre la app móvil de GIJAC MESSAGE y escanea este código desde el menú de seguridad para iniciar sesión
                    en esta computadora.
                </div>

                <div class="qr-container">
                    <div class="qr-code" id="qrCode">📱</div>
                </div>

                <div class="waiting-text" id="waitingText">
                    <span class="spinner-animation"></span>
                    Esperando escaneo...
                </div>

                <button class="btn-qr" onclick="simularEscaneoQR()">
                    <i class="fas fa-qrcode"></i> Simular Escaneo QR
                </button>

                <button class="btn-secondary-light" onclick="mostrarMasOpciones()">
                    <i class="fas fa-ellipsis-h"></i> Más opciones
                </button>
            </div>

            <!-- SESSION CARD -->
            <div class="session-card hidden" id="sessionCard">
                <div class="qr-title">
                    <i class="fas fa-check-circle" style="color: var(--whatsapp-green); margin-right: 10px;"></i>
                    Sesión iniciada
                </div>

                <div class="badge-session">
                    <i class="fas fa-circle" style="font-size: 6px; margin-right: 6px;"></i>
                    Sesión Activa
                </div>

                <div class="session-info">
                    <div class="device-item">
                        <span class="device-label"><i class="fas fa-mobile-alt"></i> Dispositivo</span>
                        <span class="device-value" id="deviceName">iPhone 14 Pro</span>
                    </div>
                    <div class="device-item">
                        <span class="device-label"><i class="fas fa-microchip"></i> Sistema</span>
                        <span class="device-value" id="deviceOS">iOS 17.2</span>
                    </div>
                    <div class="device-item">
                        <span class="device-label"><i class="fas fa-link"></i> IP Remota</span>
                        <span class="device-value" id="deviceIP">192.168.1.45</span>
                    </div>
                    <div class="device-item">
                        <span class="device-label"><i class="fas fa-clock"></i> Conectado</span>
                        <span class="device-value" id="connectionTime">Hace 2 minutos</span>
                    </div>
                </div>

                <button class="btn-logout" onclick="cerrarSesion()">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </button>
            </div>
        </div>
    </div>

    <!-- ALERT CONTAINER -->
    <div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;"></div>
@endsection

@section('scripts')
    <script>
        // Estado de la aplicación
        let appState = {
            sessionActive: false,
            lastUpdate: new Date().toLocaleTimeString('es-ES')
        };

        // Mostrar alerta
        function mostrarAlerta(mensaje, tipo = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            const alertId = 'alert-' + Date.now();

            let iconoClase = 'fa-check-circle';
            let claseAlert = 'alert-success-custom';

            if (tipo === 'error') {
                iconoClase = 'fa-exclamation-circle';
                claseAlert = 'alert-error-custom';
            }

            const alertHTML = `
                <div class="alert alert-custom ${claseAlert}" id="${alertId}" role="alert">
                    <i class="fas ${iconoClase}"></i>
                    <span>${mensaje}</span>
                </div>
            `;

            alertContainer.innerHTML += alertHTML;

            // Auto-remove después de 3.5 segundos
            setTimeout(() => {
                const element = document.getElementById(alertId);
                if (element) {
                    element.style.animation = 'fadeOut 0.3s ease-out';
                    setTimeout(() => element.remove(), 300);
                }
            }, 3500);
        }

        // Simular escaneo de QR
        function simularEscaneoQR() {
            console.log("[v0] Iniciando simulación de escaneo QR");

            const qrCard = document.getElementById('qrCard');
            const sessionCard = document.getElementById('sessionCard');

            // Animar QR
            const qrCode = document.getElementById('qrCode');
            qrCode.style.animation = 'pulse 0.5s ease-in-out';

            setTimeout(() => {
                qrCard.classList.add('hidden');
                sessionCard.classList.remove('hidden');

                // Actualizar sidebar
                actualizarSidebar(true);

                // Mostrar alerta
                mostrarAlerta('Sesión iniciada correctamente desde dispositivo móvil', 'success');

                appState.sessionActive = true;
                appState.lastUpdate = new Date().toLocaleTimeString('es-ES');

                console.log("[v0] Sesión activa: ", appState);
            }, 600);
        }

        // Cerrar sesión
        function cerrarSesion() {
            console.log("[v0] Cerrando sesión");

            const qrCard = document.getElementById('qrCard');
            const sessionCard = document.getElementById('sessionCard');

            sessionCard.classList.add('hidden');
            qrCard.classList.remove('hidden');

            // Actualizar sidebar
            actualizarSidebar(false);

            // Mostrar alerta
            mostrarAlerta('Sesión cerrada correctamente', 'success');

            appState.sessionActive = false;
            appState.lastUpdate = new Date().toLocaleTimeString('es-ES');

            console.log("[v0] Sesión cerrada: ", appState);
        }

        // Actualizar sidebar
        function actualizarSidebar(conectado) {
            const statusBadge = document.getElementById('statusBadge');
            const statusText = document.getElementById('statusText');
            const lastAttempt = document.getElementById('lastAttempt');

            if (conectado) {
                statusBadge.classList.remove('disconnected');
                statusBadge.classList.add('connected');
                statusText.textContent = 'Conectado';
                lastAttempt.textContent = 'Ahora mismo';
                statusBadge.style.animation = 'pulse 0.5s ease-in-out';
            } else {
                statusBadge.classList.remove('connected');
                statusBadge.classList.add('disconnected');
                statusText.textContent = 'Desconectado';
                lastAttempt.textContent = 'Hace un momento';
            }
        }

        // Mostrar más opciones
        function mostrarMasOpciones() {
            console.log("[v0] Mostrando más opciones");
            mostrarAlerta('Más opciones disponibles en próximas actualizaciones', 'success');
        }

        // Agregar estilos para animación pulse
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.05); }
            }
            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }
        `;
        document.head.appendChild(style);

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            console.log("[v0] Página de login QR cargada");
            console.log("[v0] Estado inicial: ", appState);
        });
    </script>
@endsection
