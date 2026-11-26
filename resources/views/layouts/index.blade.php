<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light"><!--begin::Head-->
<head>
    <title>GIJAC MESSAGE BUSINESS {{ isset($nombre_titulo) ? '- '.$nombre_titulo : '' }}</title>
    <meta charset="utf-8">
    <meta name="description" content="Plataforma para el envio de mensajes/campañas de manera masiva por el canal mas importante que es WhatsApp, asiendo uso de la API de WhatsApp Business.">
    <meta name="keywords"
        content="tailwind, tailwindcss, metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js,
        Node.js, Flask, Symfony &amp; Laravel starter kits, admin themes, web design, figma, web development, free templates,
        free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button,
        bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:locale" content="es_ES">
    <meta property="og:type" content="website">
    <meta property="og:title" content="GIJAC MESSAGE BUSINESS - Te ayudamos a crecer">
    <meta property="og:site_name" content="GIJAC MESSAGE BUSINESS">
    <meta property="og:image" content="{{ asset('img/logo_gmb.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large')">

    <link rel="shortcut icon" href="{{ asset('img/logo_gmb.png') }}">

    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"> <!--end::Fonts-->

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- Drawflow CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/drawflow@0.0.59/dist/drawflow.min.css" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/plugins/custom/jkanban/jkanban.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('css/datatables.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/datatable-gijac.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/multi-select/css/multi-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/pin-login/jquery.pinlogin.css') }}">
    <!-- For Bootstrap 5 -->
    <link rel="stylesheet" href="{{ asset('assets/summernote/summernote-bs5.css') }}" />
    <!-- For <a href="https://www.jqueryscript.net/tags.php?/bootstrap 4/">Bootstrap 4</a> -->
    <link rel="stylesheet" href="{{ asset('assets/summernote/summernote-bs4.css') }}" />
    <!-- For Bootstrap 3 -->
    <link rel="stylesheet" href="{{ asset('assets/summernote/summernote.css') }}" />
    <!-- Without any framework -->
    <link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}" />

    <link rel="stylesheet" href="{{ asset('css/llamada.css') }}" />

    <link href="{{ mix('css/cel.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ mix('css/driver.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ mix('css/acceso-general.css') }}" rel="stylesheet" type="text/css"/>

    <!--begin::Google tag-->
    <script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script>
    <script type="text/javascript" async=""
        src="https://www.googletagmanager.com/gtag/js?id=G-L98VPZFG7E&amp;l=dataLayer&amp;cx=c"></script>
    <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-37564768-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-37564768-1');
    </script>
    <!--end::Google tag-->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>

    <link rel="stylesheet" href="{{ mix('css/graficos.css') }}">
    @section('css')
    @show
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-header="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true"
    data-kt-app-sidebar-stacked="true" data-kt-app-sidebar-secondary-enabled="true"
    data-kt-app-toolbar-enabled="true" data-kt-app-sidebar-secondary-collapse="on"
    class="app-default">
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
    <!--Begin::Google Tag Manager (noscript) -->
    {{-- <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5FS8GGP" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript> --}}
    <!--End::Google Tag Manager (noscript) -->


    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid " id="kt_app_page">

            <!--begin::Header-->
            @component('layouts.componentes.header')
            @endcomponent
            <!--end::Header-->
            <!--begin::Wrapper-->
            <div class="app-wrapper  flex-row flex-row-fluid " id="kt_app_wrapper">
                <!--begin::Sidebar-->
                @component('layouts.componentes.sider')
                @endcomponent
                <!--end::Sidebar-->

                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!--begin::toolbar-->
                        @component('layouts.componentes.toolbar')
                            @slot('drawer', $drawer ?? false)
                            @slot('accesos', $accesos ?? false)
                        @endcomponent
                        <!--end::toolbar-->
                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content flex-column-fluid px-3">
                            <div id="kt_app_content_container" class="app-container container-xxl">
                                @yield('content')
                            </div>
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Content wrapper-->

                    <!--begin::Footer-->
                    @component('layouts.componentes.footer')
                    @endcomponent
                    <!--end::Footer-->
                </div>
                <!--end:::Main-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->

    @if ($accesos)
        @component('layouts.componentes.configuraciones')
        @endcomponent
    @else
        @component('layouts.componentes.guia')
        @endcomponent
    @endif

    <!-- Back to top -->
    <button id="back-to-top" aria-label="{{ __('Volver arriba') }}">
        <i class="bi bi-arrow-up text-white"></i>
    </button>

    <!--begin::Modals-->

    @component('layouts.modals.calls')
    @endcomponent

    <!-- Modal -->
    @section('modal')
    @show

    @component('layouts.componentes.drawer')
    @endcomponent

    @routes
    <!--begin::Javascript-->
    <script>
        window.translations = @json(
            file_exists(app()->langPath(app()->getLocale() . '.json'))
                ? json_decode(file_get_contents(app()->langPath(app()->getLocale() . '.json')), true)
                : []
        );

        function __(text) {
            return window.translations[text] || text;
        }

        window.user = {{ auth()->user()->id }}
        window.numeroTelefono = '{{$numeroTelefono}}';
    </script>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/fslightbox/fslightbox.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/jkanban/jkanban.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/multi-select/js/jquery.quicksearch.js') }}"></script>
    <script src="{{ asset('assets/multi-select/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('assets/pin-login/jquery.pinlogin.js') }}"></script>
    <script src="{{ asset('assets/summernote/summernote-bs5.js') }}"></script>
    <script src="{{ asset('assets/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/summernote/summernote.js') }}"></script>
    <script src="{{ asset('assets/summernote/summernote-lite.js') }}"></script>
    <script src="{{ asset('assets/summernote/lang/summernote-es-ES.js') }}"></script>

    <script src="{{ mix('/js/app.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
    <script src="{{ mix('js/jquery-validator.init.js') }}"></script>

    <script src="{{ mix('/js/sistema/notificaciones.js') }}"></script>
    {{-- <script src="{{ mix('/js/search.js') }}" ></script> --}}

    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
    <!-- Añadir antes de cerrar el body -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.9.3/tagify.min.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
    <!-- Drawflow JS -->
    <script src="https://cdn.jsdelivr.net/npm/drawflow@0.0.59/dist/drawflow.min.js"></script>

    @if (!$accesos)
        <script src="{{ mix('js/guia.js') }}"></script>
    @endif

    @section('scripts')
    @show
</body><!--end::Body-->

</html>
