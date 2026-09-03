@extends('layouts.principal')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/gmb.css') }}">
    <style>{{ __('.hero-section {
            min-height: auto !important;
        }
        .site-header .nav-link {
            color: #123f45 !important;
            margin: auto !important;
        }
        .site-header .nav-link.active {
            color: #123f45 !important;
        }
        .site-header .brand-name {
            color: var(--teal-dark) !important;
        }

        .site-header .btn-ghost {
            color: var(--teal-dark) !important;
            border: 1px solid rgba(20, 89, 98, .2) !important;
        }
        .btn {
            border: 1px solid rgba(20, 89, 98, .2) !important;
        }') }</style>
@endsection

@section('content')
    <!-- Privacy Policy Content -->
    <section class="section mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <!-- Introducción -->
                    <div class="mb-5">
                        <h2 class="h3 mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-shield-alt me-2"></i>{{ __('Introducción') }}</h2>
                        <p class="mb-3">{{ __('En GIJAC MESSAGE BUSINESS, nos comprometemos a proteger y respetar su privacidad. Esta política de privacidad explica cómo recopilamos, utilizamos, almacenamos y protegemos su información personal cuando utiliza nuestros servicios.') }}</p>
                        <p>{{ __('Al utilizar nuestra plataforma, usted acepta las prácticas descritas en esta política de privacidad. Le recomendamos que lea cuidadosamente este documento antes de utilizar nuestros servicios.') }}</p>
                    </div>

                    <!-- Datos que recopilamos -->
                    <div class="mb-5">
                        <h2 class="h3 mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-database me-2"></i>{{ __('Datos que Recopilamos') }}</h2>
                        <h4 class="h5 mb-3">{{ __('Información Personal') }}</h4>
                        <ul class="mb-3">
                            <li>{{ __('Nombre completo y datos de contacto') }}</li>
                            <li>{{ __('Dirección de correo electrónico') }}</li>
                            <li>{{ __('Número de teléfono') }}</li>
                            <li>{{ __('Información de la empresa y cargo') }}</li>
                            <li>{{ __('Datos de facturación y pago') }}</li>
                        </ul>

                        <h4 class="h5 mb-3">{{ __('Información Técnica') }}</h4>
                        <ul class="mb-3">
                            <li>{{ __('Dirección IP y datos de ubicación') }}</li>
                            <li>{{ __('Información del navegador y dispositivo') }}</li>
                            <li>{{ __('Cookies y tecnologías similares') }}</li>
                            <li>{{ __('Registros de actividad en la plataforma') }}</li>
                            <li>{{ __('Métricas de uso y rendimiento') }}</li>
                        </ul>

                        <h4 class="h5 mb-3">{{ __('Datos de WhatsApp Business') }}</h4>
                        <ul>
                            <li>{{ __('Mensajes enviados y recibidos a través de la API') }}</li>
                            <li>{{ __('Contactos y listas de destinatarios') }}</li>
                            <li>{{ __('Plantillas de mensajes y configuraciones') }}</li>
                            <li>{{ __('Estadísticas de entrega y engagement') }}</li>
                        </ul>
                    </div>

                    <!-- Uso de datos -->
                    <div class="mb-5">
                        <h2 class="h3 mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-cogs me-2"></i>{{ __('Uso de los Datos') }}</h2>
                        <p class="mb-3">{{ __('Utilizamos su información personal para los siguientes propósitos:') }}</p>

                        <h4 class="h5 mb-3">{{ __('Prestación de Servicios') }}</h4>
                        <ul class="mb-3">
                            <li>{{ __('Proporcionar acceso a la plataforma WhatsApp Business') }}</li>
                            <li>{{ __('Procesar y entregar mensajes a través de la API') }}</li>
                            <li>{{ __('Generar análisis y reportes de rendimiento') }}</li>
                            <li>{{ __('Brindar soporte técnico y atención al cliente') }}</li>
                        </ul>

                        <h4 class="h5 mb-3">{{ __('Mejora de Servicios') }}</h4>
                        <ul class="mb-3">
                            <li>{{ __('Analizar patrones de uso para mejorar la plataforma') }}</li>
                            <li>{{ __('Desarrollar nuevas funcionalidades') }}</li>
                            <li>{{ __('Optimizar la experiencia del usuario') }}</li>
                            <li>{{ __('Prevenir fraude y garantizar la seguridad') }}</li>
                        </ul>

                        <h4 class="h5 mb-3">{{ __('Comunicación') }}</h4>
                        <ul>
                            <li>{{ __('Enviar notificaciones importantes del servicio') }}</li>
                            <li>{{ __('Informar sobre actualizaciones y nuevas funciones') }}</li>
                            <li>{{ __('Responder a consultas y solicitudes de soporte') }}</li>
                            <li>{{ __('Enviar comunicaciones de marketing (con su consentimiento)') }}</li>
                        </ul>
                    </div>

                    <!-- Protección de la información -->
                    <div class="mb-5">
                        <h2 class="h3 mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-lock me-2"></i>{{ __('Protección de la Información') }}</h2>
                        <p class="mb-3">{{ __('Implementamos medidas técnicas y organizativas apropiadas para proteger su información personal contra el acceso no autorizado, alteración, divulgación o destrucción.') }}</p>

                        <h4 class="h5 mb-3">{{ __('Medidas de Seguridad Técnicas') }}</h4>
                        <ul class="mb-3">
                            <li>{{ __('Encriptación SSL/TLS para todas las comunicaciones') }}</li>
                            <li>{{ __('Encriptación de datos en reposo') }}</li>
                            <li>{{ __('Autenticación de dos factores (2FA)') }}</li>
                            <li>{{ __('Monitoreo continuo de seguridad') }}</li>
                            <li>{{ __('Copias de seguridad regulares y seguras') }}</li>
                        </ul>

                        <h4 class="h5 mb-3">{{ __('Medidas Organizativas') }}</h4>
                        <ul class="mb-3">
                            <li>{{ __('Acceso limitado a datos personales solo al personal autorizado') }}</li>
                            <li>{{ __('Formación regular en privacidad y seguridad') }}</li>
                            <li>{{ __('Auditorías periódicas de seguridad') }}</li>
                            <li>{{ __('Políticas estrictas de gestión de datos') }}</li>
                        </ul>

                        <h4 class="h5 mb-3">{{ __('Cumplimiento Normativo') }}</h4>
                        <ul>
                            {{-- <li>{{ __('Cumplimiento con el Reglamento General de Protección de Datos (GDPR)') }}</li> --}}
                            <li>{{ __('Adhesión a las políticas de WhatsApp Business API') }}</li>
                            {{-- <li>{{ __('Certificaciones de seguridad ISO 27001') }}</li> --}}
                            {{-- <li>{{ __('Auditorías de terceros independientes') }}</li> --}}
                        </ul>
                    </div>

                    <!-- Derechos del usuario -->
                    <div class="mb-5">
                        <h2 class="h3 mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-user-shield me-2"></i>{{ __('Derechos del Usuario') }}</h2>
                        <p class="mb-3">{{ __('Usted tiene los siguientes derechos con respecto a su información personal:') }}</p>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-eye text-primary me-2"></i>{{ __('Acceso') }}</h5>
                                        <p class="card-text">{{ __('Solicitar una copia de los datos personales que tenemos sobre usted.') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-edit text-primary me-2"></i>{{ __('Rectificación') }}</h5>
                                        <p class="card-text">{{ __('Solicitar la corrección de información personal inexacta o incompleta.') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-trash text-primary me-2"></i>{{ __('Eliminación') }}</h5>
                                        <p class="card-text">{{ __('Solicitar la eliminación de sus datos personales en ciertas circunstancias.') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-ban text-primary me-2"></i>{{ __('Limitación') }}</h5>
                                        <p class="card-text">{{ __('Solicitar la restricción del procesamiento de su información personal.') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-download text-primary me-2"></i>{{ __('Portabilidad') }}</h5>
                                        <p class="card-text">{{ __('Recibir sus datos personales en un formato estructurado y legible.') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-times-circle text-primary me-2"></i>{{ __('Oposición') }}</h5>
                                        <p class="card-text">{{ __('Oponerse al procesamiento de sus datos para fines de marketing directo.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>{{ __('¿Cómo ejercer sus derechos?') }}</strong>{{ __('Para ejercer cualquiera de estos derechos, puede contactarnos a través de nuestro formulario de contacto o enviando un correo a') }}<a href="mailto:privacidad@gijac.co">privacidad@gijac.co</a>
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="mb-5">
                        <h2 class="h3 mb-3" style="color: var(--primary-color);">
                            <i class="fas fa-envelope me-2"></i>{{ __('Contacto') }}</h2>
                        <p class="mb-3">{{ __('Si tiene preguntas, comentarios o inquietudes sobre esta política de privacidad o sobre cómo manejamos su información personal, no dude en contactarnos:') }}</p>

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>{{ __('Datos de Contacto') }}</h5>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-envelope me-2 text-primary"></i>
                                                <strong>{{ __('Email:') }}</strong> privacidad@gijac.co
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-phone me-2 text-primary"></i>
                                                <strong>{{ __('Teléfono:') }}</strong> +57 (317) 178-9584
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                                <strong>{{ __('Dirección:') }}</strong>{{ __('Corregimiento de Navarro, callejón El Recuerdo') }}</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6 d-none">
                                        <h5>{{ __('Delegado de Protección de Datos') }}</h5>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-user me-2 text-primary"></i>
                                                <strong>{{ __('DPO:') }}</strong>{{ __('María González') }}</li>
                                            <li class="mb-2">
                                                <i class="fas fa-envelope me-2 text-primary"></i>
                                                <strong>{{ __('Email:') }}</strong> dpo@gijac.co
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>{{ __('Tiempo de Respuesta:') }}</strong>{{ __('Nos comprometemos a responder a todas las consultas relacionadas con la privacidad dentro de 30 días hábiles.') }}</div>
                    </div>

                    <!-- Cambios en la política -->
                    <div class="mb-5">
                        <div class="card" style="border-left: 5px solid var(--primary-color);">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-sync-alt me-2"></i>{{ __('Cambios en esta Política') }}</h5>
                                <p class="card-text mb-3">{{ __('Podemos actualizar esta política de privacidad de vez en cuando para reflejar cambios en nuestras prácticas de información o en la legislación aplicable.') }}</p>
                                <ul class="mb-0">
                                    <li>{{ __('Le notificaremos sobre cambios significativos por correo electrónico') }}</li>
                                    {{-- <li>{{ __('La fecha de la última actualización aparece en la parte superior de esta página') }}</li> --}}
                                    <li>{{ __('Le recomendamos revisar periódicamente esta política') }}</li>
                                    <li>{{ __('El uso continuado de nuestros servicios constituye la aceptación de los cambios') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
@endsection
