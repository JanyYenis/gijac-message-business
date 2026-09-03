<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light"><!--begin::Head-->

<head>
    <title>{{ __('GIJAC MESSAGE BUSINESS') }}</title>
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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('img/logo_gmb.png') }}">
    <!-- CSRF Token -->

    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chatbot-n8n.css') }}">
</head>

@section('css')
@show

<body>

    <!-- ===== PARTICLE BACKGROUND (global aurora) ===== -->
    <canvas id="particle-canvas"></canvas>

    <!-- ===== HEADER ===== -->
    <header id="main-header" class="site-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <img src="{{ asset('img/logo_gmb.png') }}" alt="Logo GIJAC Message Business" class="brand-logo" />
                    <span class="brand-name">{{ __('GIJAC MESSAGE BUSINESS') }}</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent"
                    aria-controls="navContent" aria-expanded="false" aria-label="Abrir menú">
                    <i class="bi bi-list"></i>
                </button>

                <div class="collapse navbar-collapse" id="navContent">
                    <ul class="navbar-nav mx-auto gap-lg-2">
                        <li class="nav-item">
                            <a class="nav-link {{request()->is('/') ? 'active' : ''}}" href="{{ url('/') }}">{{ __('menu-inicio.inicio') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}#modulos">{{ __('Módulos') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{request()->is('recursos') ? 'active' : ''}}" href="{{ route('recursos.index') }}">Recursos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}#app">{{ __('App') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{request()->is('precios') ? 'active' : ''}}" href="{{ route('precios') }}">{{ __('menu-inicio.precio') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{request()->is('preguntas-frecuentes') ? 'active' : ''}}" href="{{ url('preguntas-frecuentes') }}">{{ __('menu-inicio.preguntas.frecuentes') }}</a>
                        </li>
                    </ul>
                    <div class="d-flex gap-2 align-items-center header-cta">
                        @if (Route::has('login'))
                            @auth
                                <a class="btn btn-ghost" href="{{ route('home') }}">{{ __('menu-inicio.dashboard') }}</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-ghost">{{ __('menu-inicio.login') }}</a>
                                <a href="{{ route('register') }}" class="btn btn-glow magnetic">{{ __('menu-inicio.probar.gratis') }}</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>
    </header>

    @yield('content')

    <!-- ===== FOOTER ===== -->
    <footer class="site-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <a class="d-flex align-items-center gap-2 mb-3 footer-brand" href="#hero">
                        <img src="{{ asset('img/logo_gmb.png') }}" alt="Logo GIJAC" class="brand-logo" />
                        <span class="brand-name">{{ __('GIJAC MESSAGE BUSINESS') }}</span>
                    </a>
                    <p class="footer-text">{{ __('pie-inicio.label.1') }}</p>
                    <div class="social-row">
                        <a href="https://www.facebook.com/share/1AgqGKJ5Dj/" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" aria-label="Twitter">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="#" aria-label="LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a href="#" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <h6>{{ __('pie-inicio.enlaces.rapidos') }}</h6>
                    <ul class="footer-links">
                        <li>
                            <a href="{{ url('/') }}">{{ __('pie-inicio.inicio') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('recursos.index') }}">{{ __('Recursos') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('contactarnos') }}">{{ __('pie-inicio.contacto') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('politicas-privacidad') }} ">{{ __('pie-inicio.politicas.privacidad') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('terminos-condiciones') }} ">{{ __('pie-inicio.terminos.condiciones') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('eliminacion-datos') }} ">{{ __('pie-inicio.eliminar.datos') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="col-6 col-lg-3">
                    <h6>{{ __('pie-inicio.funcionalidades') }}</h6>
                    <ul class="footer-links">
                        <li>
                            <a href="{{ url('/') }}#modulos">{{ __('pie-inicio.dashboard') }}</a>
                        </li>
                        <li>
                            <a href="{{ url('/') }}#modulos">{{ __('pie-inicio.campanas') }}</a>
                        </li>
                        <li>
                            <a href="{{ url('/') }}#modulos">{{ __('pie-inicio.plantillas') }}</a>
                        </li>
                        <li>
                            <a href="{{ url('/') }}#ia">{{ __('Inteligencia Artificial') }}</a>
                        </li>
                        <li>
                            <a href="{{ url('/') }}#">{{ __('API') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6>{{ __('pie-inicio.contacto') }}</h6>
                    <ul class="footer-contact">
                        <li>
                            <i class="bi bi-envelope"></i> soporte@gijac.co
                        </li>
                        <li>
                            <i class="bi bi-telephone"></i> +57 (317) 178-9584
                        </li>
                        <li>
                            <i class="bi bi-geo-alt"></i>{{ __('Cali, Colombia.') }}</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© {{ date('Y') }} GIJAC MESSAGE BUSINESS. {{ __('pie-inicio.derechos.reservados') }}</p>
            </div>
        </div>
    </footer>

    <!-- Back to top -->
    <button id="back-to-top" aria-label="Volver arriba">
        <i class="bi bi-arrow-up"></i>
    </button>

    @routes
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Three.js (WebGL) for 3D phones -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ mix('js/main.js') }}"></script>
    <script src="{{ mix('js/phones-3d.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ mix('/js/app.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
    <script src="{{ mix('js/jquery-validator.init.js') }}"></script>

    <script type="module">
        import {
            createChat
        } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';

        createChat({
            webhookUrl: 'https://n8n.gijac.com/webhook/2cc503de-973b-4f46-b516-d5d9708bd4ec/chat',
            webhookConfig: {
                method: 'POST',
                headers: {}
            },
            target: '#n8n-chat',
            mode: 'window',
            chatInputKey: 'chatInput',
            chatSessionKey: 'sessionId',
            metadata: {},
            showWelcomeScreen: false,
            defaultLanguage: 'es',
            initialMessages: [
                '¡Hola! 👋',
                'Me llamo Gibot. ¿En qué puedo ayudarle?'
            ],
            i18n: {
                es: {
                    title: '¡Hola! 👋',
                    subtitle: "Inicia un chat. Estamos disponibles las 24 horas, los 7 días de la semana.",
                    footer: '',
                    getStarted: 'Nueva conversación',
                    inputPlaceholder: 'Escribe tu pregunta...',
                },
            },
        });
    </script>
    @section('scripts')
    @show
</body>

</html>
