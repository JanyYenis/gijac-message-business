<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página No Encontrada | GIJAC MESSAGE</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* WhatsApp Colors */
            --whatsapp-green: #25D366;
            --whatsapp-dark: #128C7E;
            --whatsapp-light: #DCF8C6;
            --whatsapp-bg: #eae6df;

            /* Gray Scale */
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;

            /* Status Colors */
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;

            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-50) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            overflow-x: hidden;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(37, 211, 102, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(18, 140, 126, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(220, 248, 198, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        /* Floating elements */
        .floating-element {
            position: fixed;
            opacity: 0.08;
            font-size: 150px;
            pointer-events: none;
            z-index: 0;
            animation: float 8s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            top: -50px;
            left: 5%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            top: 20%;
            right: 10%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(3) {
            bottom: 10%;
            left: 15%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-30px) rotate(10deg);
            }
        }

        /* Main Container */
        .error-container {
            position: relative;
            z-index: 10;
            max-width: 600px;
            width: 100%;
        }

        /* Card styling */
        .error-card {
            background: white;
            border-radius: 24px;
            padding: 3rem 2rem;
            box-shadow: var(--shadow-lg);
            text-align: center;
            animation: slideUp 0.6s ease-out;
            border: 1px solid var(--gray-200);
            position: relative;
            overflow: hidden;
        }

        .error-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--whatsapp-green), var(--whatsapp-dark));
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Error Code */
        .error-code {
            font-size: 120px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1rem;
            animation: codeScale 0.8s ease-out;
        }

        @keyframes codeScale {
            from {
                opacity: 0;
                transform: scale(0.5);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Icon */
        .error-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--whatsapp-green) 0%, var(--whatsapp-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 3rem;
            color: white;
            box-shadow: 0 10px 25px rgba(37, 211, 102, 0.3);
            animation: iconBounce 0.6s ease-out;
        }

        @keyframes iconBounce {
            0% {
                transform: scale(0) rotate(-45deg);
            }
            100% {
                transform: scale(1) rotate(0deg);
            }
        }

        /* Title */
        .error-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        /* Description */
        .error-description {
            font-size: 1rem;
            color: var(--gray-600);
            line-height: 1.6;
            margin-bottom: 2.5rem;
            padding: 0 1rem;
        }

        /* Status message */
        .error-status {
            display: inline-block;
            background: linear-gradient(135deg, rgba(37, 211, 102, 0.1), rgba(18, 140, 126, 0.1));
            border: 1px solid var(--whatsapp-green);
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            margin-bottom: 2rem;
            color: var(--whatsapp-dark);
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Buttons Container */
        .error-actions {
            display: flex;
            gap: 1rem;
            flex-direction: column;
            justify-content: center;
        }

        .btn-primary-action {
            background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark));
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
            text-decoration: none;
        }

        .btn-primary-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-primary-action:active {
            transform: translateY(0);
        }

        .btn-secondary-action {
            background: var(--gray-100);
            border: 2px solid var(--gray-300);
            color: var(--gray-700);
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .btn-secondary-action:hover {
            background: var(--gray-200);
            border-color: var(--gray-400);
            color: var(--gray-800);
            transform: translateY(-2px);
            text-decoration: none;
        }

        .btn-secondary-action:active {
            transform: translateY(0);
        }

        /* Additional Info */
        .error-info {
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid var(--gray-200);
            font-size: 0.85rem;
            color: var(--gray-500);
        }

        .error-info-title {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.75rem;
        }

        .error-info-text {
            line-height: 1.6;
            margin-bottom: 0.5rem;
        }

        /* Quick Links */
        .quick-links {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 2rem;
        }

        .quick-link {
            padding: 1rem;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            text-decoration: none;
            color: var(--gray-700);
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .quick-link:hover {
            background: var(--gray-50);
            border-color: var(--whatsapp-green);
            color: var(--whatsapp-green);
            transform: translateY(-2px);
        }

        .quick-link i {
            font-size: 1.1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .error-card {
                padding: 2rem 1.5rem;
                border-radius: 16px;
            }

            .error-code {
                font-size: 80px;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .error-icon {
                width: 80px;
                height: 80px;
                font-size: 2.5rem;
            }

            .error-actions {
                flex-direction: column;
            }

            .btn-primary-action,
            .btn-secondary-action {
                width: 100%;
            }

            .quick-links {
                grid-template-columns: 1fr;
            }

            .floating-element {
                font-size: 100px;
            }
        }

        @media (max-width: 480px) {
            .error-card {
                padding: 1.5rem 1rem;
                border-radius: 12px;
            }

            .error-code {
                font-size: 60px;
                margin-bottom: 0.75rem;
            }

            .error-title {
                font-size: 1.25rem;
                margin-bottom: 0.75rem;
            }

            .error-description {
                font-size: 0.9rem;
                margin-bottom: 1.5rem;
            }

            .error-icon {
                width: 70px;
                height: 70px;
                font-size: 2rem;
                margin-bottom: 1.5rem;
            }

            .floating-element {
                font-size: 80px;
                opacity: 0.05;
            }

            .error-actions {
                gap: 0.75rem;
            }

            .btn-primary-action,
            .btn-secondary-action {
                padding: 0.875rem 1.5rem;
                font-size: 0.9rem;
            }
        }

        /* Accessibility */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        /* Focus states */
        .btn-primary-action:focus,
        .btn-secondary-action:focus,
        .quick-link:focus {
            outline: 2px solid var(--whatsapp-green);
            outline-offset: 2px;
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Background Elements -->
    <div class="floating-element">
        <i class="fas fa-comment-dots"></i>
    </div>
    <div class="floating-element">
        <i class="fas fa-envelope"></i>
    </div>
    <div class="floating-element">
        <i class="fas fa-paper-plane"></i>
    </div>

    <!-- Main Error Container -->
    <div class="error-container">
        <div class="error-card">
            <!-- Error Icon -->
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>

            <!-- Error Code -->
            <div class="error-code">404</div>

            <!-- Error Title -->
            <h1 class="error-title">Página No Encontrada</h1>

            <!-- Status Message -->
            <div class="error-status">
                <i class="fas fa-info-circle"></i> Parece que esta página no existe o fue movida
            </div>

            <!-- Error Description -->
            <p class="error-description">
                Lo sentimos, no pudimos encontrar la página que estás buscando.
                Verifica la URL e intenta de nuevo o regresa al inicio.
            </p>

            <!-- Action Buttons -->
            <div class="error-actions">
                <a href="{{ route('home') }}" class="btn-primary-action">
                    <i class="fas fa-home"></i>
                    Volver al Inicio
                </a>
                <a href="javascript:history.back()" class="btn-secondary-action">
                    <i class="fas fa-arrow-left"></i>
                    Página Anterior
                </a>
            </div>

            <!-- Additional Info -->
            <div class="error-info">
                <div class="error-info-title">
                    <i class="fas fa-lightbulb"></i> ¿Qué puedes hacer?
                </div>
                <div class="error-info-text">
                    • Verifica que la URL sea correcta
                </div>
                <div class="error-info-text">
                    • Intenta nuevamente con la página principal
                </div>
                <div class="error-info-text">
                    • Contacta al soporte si el problema persiste
                </div>
            </div>

            <!-- Quick Links -->
            <div class="quick-links">
                <a href="{{ route('home') }}" class="quick-link" title="Ir a inicio">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
                <a href="{{ route('chats.index') }}" class="quick-link" title="Ir a mensajería">
                    <i class="fas fa-comments"></i>
                    <span>Mensajería</span>
                </a>
                <a href="{{ route('clasificacion-ia.index') }}" class="quick-link" title="Ir a configuración">
                    <i class="fas fa-cog"></i>
                    <span>Configuración IA</span>
                </a>
                <a href="{{ route('tickets.index') }}" class="quick-link" title="Contactar soporte">
                    <i class="fas fa-headset"></i>
                    <span>Soporte</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // Log 404 error
        console.log('[v0] Página 404 cargada - Usuario intentó acceder a una página no existente');

        // Track error in analytics (example)
        function trackError() {
            const errorData = {
                timestamp: new Date().toISOString(),
                url: window.location.href,
                referrer: document.referrer,
                userAgent: navigator.userAgent
            };
            console.log('[v0] Error tracking:', errorData);
        }

        // Initialize on document ready
        $(document).ready(function() {
            trackError();

            // Add keyboard shortcuts
            $(document).on('keydown', function(e) {
                // Home key to go to index
                if (e.key === 'Home') {
                    window.location.href = 'index.html';
                }

                // Backspace to go back
                if (e.key === 'Backspace' && e.target === document.body) {
                    history.back();
                }
            });

            // Add hover effects
            $('.quick-link').on('mouseenter', function() {
                console.log('[v0] Usuario está explorando:', $(this).attr('title'));
            });

            // Button interactions
            $('.btn-primary-action, .btn-secondary-action').on('click', function() {
                console.log('[v0] Usuario hizo clic en:', $(this).text().trim());
            });
        });

        // Handle network errors
        window.addEventListener('error', function(event) {
            console.error('[v0] Error de red detectado:', event.error);
        });

        // Simulate potential redirect after time (optional)
        let redirectTimer = null;

        function setupAutoRedirect() {
            redirectTimer = setTimeout(() => {
                console.log('[v0] Auto-redirección a página de inicio después de inactividad');
                // Uncomment to enable auto-redirect
                // window.location.href = 'index.html';
            }, 5 * 60 * 1000); // 5 minutes
        }

        // Reset redirect timer on user interaction
        document.addEventListener('click', function() {
            if (redirectTimer) {
                clearTimeout(redirectTimer);
                setupAutoRedirect();
            }
        });

        // setupAutoRedirect();
    </script>
</body>
</html>
