<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <title>GIJAC MESSAGE BUSINESS</title>
    <meta charset="utf-8">
    <meta name="description" content="Plataforma de procesos de GIJAC MESSAGE BUSINESS">
    <meta name="keywords"
        content="tailwind, tailwindcss, metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js,
        Node.js, Flask, Symfony &amp; Laravel starter kits, admin themes, web design, figma, web development, free templates,
        free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button,
        bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:locale" content="es_ES">
    <meta property="og:type" content="article">
    <meta property="og:title" content="GIJAC MESSAGE BUSINESS - Te ayudamos a crecer">
    <meta property="og:url" content="https://message-business.gijac.com">
    <meta property="og:site_name" content="GIJAC MESSAGE BUSINESS">
    <link rel="shortcut icon" href="{{ asset('img/logo_gmb.png') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css">

    <!--begin::Google tag-->
    <!--end::Google tag-->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
</head>

<style>
    :root {
        --whatsapp-green: #25D366;
        --whatsapp-dark: #128C7E;
        --whatsapp-light: #DCF8C6;
        --text-primary: #1a1a1a;
        --text-secondary: #6b7280;
        --background: #ffffff;
        --surface: #f8fafc;
        --border: #e5e7eb;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Open Sans', sans-serif;
        background: linear-gradient(135deg, var(--whatsapp-green) 0%, var(--whatsapp-dark) 100%);
        min-height: 100vh;
        color: var(--text-primary);
    }

    .hero-section {
        min-height: 100vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-family: 'Work Sans', sans-serif;
        font-weight: 700;
        font-size: 3.5rem;
        color: white;
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }

    .hero-subtitle {
        font-family: 'Work Sans', sans-serif;
        font-weight: 600;
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 1rem;
    }

    .hero-description {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .login-card {
        background: white;
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        z-index: 3;
        width: 33rem !important;
        margin-top: 1rem !important;
    }

    .login-title {
        font-family: 'Work Sans', sans-serif;
        font-weight: 600;
        font-size: 2rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        text-align: center;
    }

    .login-subtitle {
        color: var(--text-secondary);
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-control {
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--surface);
    }

    .form-control:focus {
        border-color: var(--whatsapp-green);
        box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.1);
        background: white;
    }

    .input-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        z-index: 5;
    }

    .input-group .form-control {
        padding-left: 3rem;
    }

    .btn-google {
        background: white;
        border: 2px solid var(--border);
        color: var(--text-primary);
        border-radius: 12px;
        padding: 0.875rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        width: 100%;
        margin-bottom: 1.5rem;
    }

    .btn-google:hover {
        border-color: var(--whatsapp-green);
        background: var(--surface);
        color: var(--text-primary);
    }

    .btn-primary {
        background: var(--whatsapp-green);
        border: none;
        border-radius: 12px;
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: var(--whatsapp-dark);
        transform: translateY(-1px);
    }

    .btn-trial {
        background: transparent;
        border: 2px solid var(--whatsapp-green);
        color: var(--whatsapp-green);
        border-radius: 12px;
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 1rem;
    }

    .btn-trial:hover {
        background: var(--whatsapp-green);
        color: white;
    }

    .divider {
        text-align: center;
        margin: 1.5rem 0;
        position: relative;
    }

    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: var(--border);
    }

    .divider span {
        background: white;
        padding: 0 1rem;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .forgot-password {
        color: var(--whatsapp-green);
        text-decoration: none;
        font-size: 0.875rem;
        transition: color 0.3s ease;
    }

    .forgot-password:hover {
        color: var(--whatsapp-dark);
    }

    .footer {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 2rem 0;
        margin-top: 4rem;
    }

    .footer-links {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-bottom: 1rem;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 0.875rem;
        transition: color 0.3s ease;
    }

    .footer-links a:hover {
        color: white;
    }

    .footer-text {
        text-align: center;
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.875rem;
    }

    .hero-illustration {
        position: absolute;
        left: 4%;
        top: 50%;
        transform: translateY(-50%);
        width: 400px;
        height: 400px;
        opacity: 0.1;
        z-index: 1;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .login-card {
            padding: 2rem;
            margin: 1rem;
        }

        .footer-links {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>

@section('css')
@show

<body>
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;

        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }

            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }

            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->

    <div class="hero-section">
        <div class="container">
            {{-- <div class="flex items-center space-x-3 mb-8">
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-message-circle h-8 w-8 text-white">
                        <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                    </svg>
                </div>
                <h3 class="text-3xl text-white font-bold">GIJAC MESSAGE BUSINESS</h3>
            </div> --}}
            <div class="hero-illustration">
                <i class="fab fa-whatsapp" style="font-size: 400px;"></i>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="hero-title">Conecta con tus clientes como nunca antes</h1>
                        {{-- <h2 class="hero-subtitle">GIJAC WEB</h2> --}}
                        <p class="hero-description">
                            Automatiza tus campañas, responde al instante con chatbots inteligentes y analiza resultados en tiempo real. Todo desde una sola plataforma.
                        </p>
                    </div>
                </div>

                <div class="col-lg-5 offset-lg-1">
                    <!--begin::Body-->
                    @yield('content')
                    <!--end::Body-->
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-links">
                <a href="https://gijac.com" target="_blank">Quienes somos</a>
                <a href="{{ route('precios') }}">Planes</a>
                <a href="{{ route('contactarnos') }}">Contactanos</a>
                <a href="{{ route('terminos-condiciones') }}">Términos de Servicio</a>
                <a href="{{ route('politicas-privacidad') }}">Política de Privacidad</a>
            </div>
            <p class="footer-text">GIJAC WEB © {{ date('Y') }}. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/pin-login/jquery.pinlogin.js') }}"></script>

    @section('js')
    @show
</body>
</html>
