@extends('layouts.principal')

@section('meta')
    <meta name="description"
        content="{{ __('Conoce los planes y precios de GIJAC Message Business. Elige la solución ideal para gestionar WhatsApp Business, campañas, contactos, automatizaciones, chatbots e inteligencia artificial para tu empresa.') }}">

    <meta name="keywords"
        content="precios WhatsApp Business, planes WhatsApp Business, precios automatización WhatsApp, planes chatbot, WhatsApp API, campañas WhatsApp, inteligencia artificial, automatización empresarial, GIJAC Message Business">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="og:locale" content="es_ES">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Planes y precios | GIJAC MESSAGE BUSINESS">
    <meta property="og:description"
        content="{{ __('Conoce los planes de GIJAC Message Business y elige la solución ideal para gestionar WhatsApp Business, campañas, contactos, automatizaciones, chatbots e inteligencia artificial.') }}">
    <meta property="og:url" content="https://message-business.gijac.com/precios">
    <meta property="og:image" content="https://message-business.gijac.com/img/logo_gmb.png">
    <meta property="og:site_name" content="GIJAC MESSAGE BUSINESS">
    <meta property="article:author" content="GIJAC WEB">
    <meta property="og:image:width" content="400">
    <meta property="og:image:height" content="300">

    @verbatim
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "WebPage",
                "@id": "https://message-business.gijac.com/precios#webpage",
                "name": "Planes y precios | GIJAC MESSAGE BUSINESS",
                "url": "https://message-business.gijac.com/precios",
                "description": "{{ __('Conoce los planes y precios de GIJAC Message Business y elige la solución ideal para gestionar WhatsApp Business, campañas, contactos, automatizaciones, chatbots e inteligencia artificial.') }}",
                "isPartOf": {
                    "@type": "WebSite",
                    "name": "GIJAC MESSAGE BUSINESS",
                    "url": "https://message-business.gijac.com"
                },
                "publisher": {
                    "@type": "Organization",
                    "name": "GIJAC MESSAGE BUSINESS",
                    "url": "https://message-business.gijac.com",
                    "logo": {
                        "@type": "ImageObject",
                        "url": "https://message-business.gijac.com/img/logo_gmb.png"
                    }
                }
            }
        </script>
    @endverbatim
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/gmb.css') }}">
    <style>
        .hero-section {
            min-height: auto !important;
        }
        .site-header .nav-link {
            color: #fff !important;
            margin: auto !important;
        }
        .site-header .nav-link.active {
            color: #fff !important;
        }
        .btn {
            border: 1px solid rgba(20, 89, 98, .2) !important;
        }
    </style>
@endsection

@section('content')
    <section id="hero" class="hero-section">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <h1 class="display-6 fw-bold mb-3">{{ __('Planes simples y transparentes') }}</h1>
                    <p class="lead mb-0">{{ __('Escala cuando lo necesites, sin sorpresas.') }}</p>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <img src="{{ asset('img/precios.png') }}"
                        alt="Personas trabajando y planificando" class="img-fluid rounded shadow-sm">
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                @foreach ($planes as $index => $plan)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 card-hover {{ $index == 1 ? 'pricing-popular pricing-card featured' : '' }}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold">{{ __($plan?->nombre) }}</h5>
                                <p class="text-muted">{{ __('Para equipos que recién comienzan.') }}</p>
                                <h2 class="fw-bold mb-3">${{formatoMiles($plan?->valor ?? 0)}}<span class="fs-6 text-muted">/{{ __($plan?->infoTipo?->nombre) }}</span></h2>
                                <ul class="list-unstyled mb-4">
                                    <li>• {{ $plan?->max_contactos ? formatoMiles($plan?->max_contactos) : __('Ilimitado') }} {{ __('Contactos Activos') }}</li>
                                    <li>• {{ $plan?->max_usuarios ? formatoMiles($plan?->max_usuarios) : __('Ilimitado') }} {{ __('Usuarios Activos') }}</li>
                                    @foreach ($plan->serviciosHabilitados as $item)
                                        <li>• {{ __($item?->nombre) }}</li>
                                    @endforeach
                                </ul>
                                <a href="{{ route('facturas.pago', ['plan' => $plan->id]) }}" class="btn {{ $index == 1 ? 'btn-primary' : 'btn-outline-primary' }} mt-auto">{{ __('Obtener') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card mt-5" style="border-left: 5px solid var(--primary-color);">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-info-circle fa-2x me-3" style="color: var(--primary-color);"></i>
                        <h4 class="mb-0">{{ __('Información Importante') }}</h4>
                    </div>
                    <p class="mb-3">
                        <strong>{{ __('El precio de los envíos de WhatsApp corre por cuenta del cliente.') }}</strong>
                    </p>
                    <p class="mb-3">
                        {{ __('Los costos mostrados corresponden únicamente al uso de la plataforma. Los envíos de mensajes se facturan directamente por WhatsApp Business API según sus tarifas oficiales.') }}
                    </p>
                    <p class="mb-3">
                        {{ __('Para mas informacion lo invitamos a consultar los precios que utiliza') }}
                        <a href="https://developers.facebook.com/docs/whatsapp/pricing/?translation"
                            target="_blank" class="link-primary">{{ __('WhatsApp Business API') }}</a>.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
