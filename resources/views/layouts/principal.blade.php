<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light"><!--begin::Head-->
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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('img/logo_gmb.png') }}">
    <!-- CSRF Token -->

    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/gmb.css') }}">
</head>

@section('css')
@show

<body>
    <!-- Header/Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fab fa-whatsapp me-2"></i>GIJAC MESSAGE BUSINESS
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{request()->is('/') ? 'active' : ''}}" href="{{ url('/') }}">{{ __('menu-inicio.inicio') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{request()->is('precios') ? 'active' : ''}}" href="{{ route('precios') }}">{{ __('menu-inicio.precio') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{request()->is('preguntas-frecuentes') ? 'active' : ''}}" href="{{ url('preguntas-frecuentes') }}">{{ __('menu-inicio.preguntas.frecuentes') }}</a>
                    </li>
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item me-2">
                                <a class="btn btn-primary btn-sm" href="{{ route('home') }}">{{ __('menu-inicio.dashboard') }}</a>
                            </li>
                        @else
                            <li class="nav-item me-2">
                                <a class="btn btn-primary btn-sm" href="{{ route('login') }}">{{ __('menu-inicio.login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('register') }}">{{ __('menu-inicio.probar.gratis') }}</a>
                            </li>
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5>
                        <i class="fab fa-whatsapp me-2"></i>GIJAC MESSAGE BUSINESS
                    </h5>
                    <p>{{ __('pie-inicio.label.1') }}</p>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/share/1AgqGKJ5Dj/"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>{{ __('pie-inicio.enlaces.rapidos') }}</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/') }}">{{ __('pie-inicio.inicio') }}</a></li>
                        {{-- <li class="mb-2"><a href="{{ route('precios') }}">{{ __('pie-inicio.precio') }}</a></li>
                        <li class="mb-2"><a href="{{ url('preguntas-frecuentes') }}">{{ __('pie-inicio.preguntas.frecuentes') }}</a></li> --}}
                        <li class="mb-2"><a href="{{ route('contactarnos') }}">{{ __('pie-inicio.contacto') }}</a></li>
                        <li class="mb-2"><a href="{{ route('politicas-privacidad') }}">{{ __('pie-inicio.politicas.privacidad') }}</a></li>
                        <li class="mb-2"><a href="{{ route('terminos-condiciones') }}">{{ __('pie-inicio.terminos.condiciones') }}</a></li>
                        <li class="mb-2"><a href="{{ route('eliminacion-datos') }}">{{ __('pie-inicio.eliminar.datos') }}</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>{{ __('pie-inicio.funcionalidades') }}</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">{{ __('pie-inicio.dashboard') }}</a></li>
                        <li class="mb-2"><a href="#">{{ __('pie-inicio.campanas') }}</a></li>
                        <li class="mb-2"><a href="#">{{ __('pie-inicio.plantillas') }}</a></li>
                        <li class="mb-2"><a href="#">API</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>{{ __('pie-inicio.contacto') }}</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            soporte@gijac.co
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2"></i>
                            +57 (317) 178-9584
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Corregimiento de Navarro, callejón El Recuerdo.
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} GIJAC MESSAGE BUSINESS. {{ __('pie-inicio.derechos.reservados') }}</p>
            </div>
        </div>
    </footer>

    @routes
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
    <script src="{{ mix('/js/app.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
    <script src="{{ mix('js/jquery-validator.init.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/chatbot-n8n.css') }}">
    <script type="module">
        import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';

        createChat({
            webhookUrl: 'https://n8n.gijac.com/webhook/12f759d2-871b-4021-9e9b-04d443443aa9/chat',
            // webhookUrl: 'http://localhost:5678/webhook/141808e4-72e6-4927-889c-3a8abaec79cc/chat',
            // webhookUrl: 'https://jany1207.app.n8n.cloud/webhook/d2081356-62b0-4cac-970e-58d070efc7fe/chat',
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
