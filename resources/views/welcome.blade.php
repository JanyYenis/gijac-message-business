@extends('layouts.principal')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>{{ __('inicio.seccion.1.titulo') }}</h1>
                    <p>{{ __('inicio.seccion.1.descripcion') }}</p>
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-rocket me-2"></i>{{ __('inicio.seccion.1.boton.1') }}
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-play me-2"></i>{{ __('inicio.seccion.1.boton.2') }}
                    </a>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=400&fit=crop&crop=center"
                         alt="GIJAC MESSAGE BUSINESS Dashboard" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section" id="features">
        <div class="container">
            <h2 class="section-title">{{ __('inicio.seccion.2.titulo') }}</h2>
            <p class="section-subtitle">{{ __('inicio.seccion.2.descripcion') }}</p>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <h5>Dashboard</h5>
                            <p>Panel de control completo con métricas en tiempo real, estadísticas de campañas y análisis detallados de rendimiento.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h5>Chat</h5>
                            <p>Sistema de chat centralizado para gestionar todas las conversaciones con tus clientes desde una sola interfaz.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <h5>Campañas</h5>
                            <p>Crea y programa campañas masivas personalizadas con segmentación avanzada y programación automática.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h5>Plantillas</h5>
                            <p>Biblioteca de plantillas pre-diseñadas y personalizables para diferentes tipos de mensajes y campañas.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <h5>Etiquetas</h5>
                            <p>Sistema de etiquetado inteligente para organizar contactos y segmentar audiencias de forma eficiente.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <h5>Configuración</h5>
                            <p>Panel de configuración avanzado para personalizar la plataforma según las necesidades de tu negocio.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-address-book"></i>
                            </div>
                            <h5>Contactos</h5>
                            <p>Gestión completa de contactos con importación masiva, sincronización y organización automática.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <h5>Tickets</h5>
                            <p>Sistema de tickets integrado para gestionar solicitudes de soporte y seguimiento de consultas.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-robot"></i>
                            </div>
                            <h5>Respuestas Automáticas</h5>
                            <p>Chatbot inteligente con respuestas automáticas personalizables para atención 24/7.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="section" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=600&h=400&fit=crop&crop=center"
                         alt="WhatsApp Business Benefits" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6">
                    <h2 class="section-title text-start">¿Por qué elegir nuestra plataforma?</h2>

                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <i class="fas fa-check-circle fa-2x" style="color: var(--primary-color);"></i>
                        </div>
                        <div>
                            <h5>API Oficial de WhatsApp</h5>
                            <p>Integración directa con la API oficial de WhatsApp Business para máxima confiabilidad y entrega garantizada.</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <i class="fas fa-shield-alt fa-2x" style="color: var(--primary-color);"></i>
                        </div>
                        <div>
                            <h5>Seguridad Garantizada</h5>
                            <p>Encriptación end-to-end y cumplimiento total con las políticas de privacidad de WhatsApp.</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <i class="fas fa-chart-line fa-2x" style="color: var(--primary-color);"></i>
                        </div>
                        <div>
                            <h5>Analytics Avanzados</h5>
                            <p>Métricas detalladas de entrega, apertura, clics y conversiones para optimizar tus campañas.</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <i class="fas fa-headset fa-2x" style="color: var(--primary-color);"></i>
                        </div>
                        <div>
                            <h5>Soporte 24/7</h5>
                            <p>Equipo de soporte técnico disponible las 24 horas para resolver cualquier consulta o incidencia.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">¿Listo para comenzar?</h2>
                    <p class="section-subtitle">Únete a miles de empresas que ya están utilizando nuestra plataforma para mejorar su comunicación con clientes.</p>
                    <a href="{{ route('precios') }}" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-eye me-2"></i>Ver Precios
                    </a>
                    <a href="{{ route('contactarnos') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-phone me-2"></i>Contactar Ventas
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    window.chatbaseUserConfig = {
        user_id: '123456789',
        user_hash: '123456789', // this is the hash of the user_id, should be generated on the server
          user_metadata: {
            "name": "Jany Esteban",
            "email": "janytj1207@gmail.com",
            "company": "GIJAC WEB",
            // Add any other relevant user information
          }
    }
    </script>
    <script>
        (function(){
            if (!window.chatbase || window.chatbase("getState") !== "initialized") {
                window.chatbase=(...arguments) => {
                    if (!window.chatbase.q) {
                        window.chatbase.q = []
                    }
                    window.chatbase.q.push(arguments)
                };
                window.chatbase = new Proxy(window.chatbase, {
                    get(target, prop) {
                        if (prop === "q") {
                            return target.q
                        }
                        return (...args) => target(prop, ...args)
                    }
                })
            }
            const onLoad = function() {
                const script = document.createElement("script");
                script.src = "https://www.chatbase.co/embed.min.js";
                script.id = "xWApUWTKG2Mo0xzsLz2_6";
                script.domain = "www.chatbase.co";
                document.body.appendChild(script)};
                if (document.readyState === "complete") {
                    onLoad()
                } else {
                    window.addEventListener("load", onLoad)
                }
            })();
    </script>
    <script src="{{ mix('js/prueba.js') }}"></script>
@endsection
