@extends('layouts.principal')

@section('content')
    <!-- ===== HERO ===== -->
    <section id="hero" class="hero-section">
        <div class="hero-glow-1"></div>
        <div class="hero-glow-2"></div>
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge-pill reveal" data-reveal="up">
                        <span class="dot-live"></span> Plataforma Oficial de WhatsApp Business API
                    </span>
                    <h1 class="hero-title">
                        <span class="reveal-word">Conecta con tus clientes a través de
                            <span class="gradient-text">WhatsApp Business</span>
                        </span>
                    </h1>
                    <p class="hero-sub reveal" data-reveal="up">
                        {{ __('inicio.seccion.1.descripcion') }}
                    </p>
                    <div class="d-flex flex-wrap gap-3 reveal" data-reveal="up">
                        <a href="{{ route('login') }}" class="btn btn-glow btn-lg magnetic">
                            <i class="bi bi-send-fill me-2"></i> {{ __('inicio.seccion.1.boton.1') }}
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-dark-glass btn-lg magnetic">
                            <i class="bi bi-play-circle me-2"></i> {{ __('inicio.seccion.1.boton.2') }}
                        </a>
                    </div>
                    <div class="hero-trust reveal" data-reveal="up">
                        <span>
                            <i class="bi bi-shield-check"></i> Seguridad Garantizada
                        </span>
                        <span>
                            <i class="bi bi-headset"></i> Soporte 24/7
                        </span>
                        <span>
                            <i class="bi bi-hand-thumbs-up"></i> Fácil de Usar
                        </span>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-visual tilt-3d" data-tilt>
                        <img src="{{ asset('img/hero-dashboard.png') }}"
                            alt="Dashboard 3D de la plataforma GIJAC Message Business"
                            class="hero-dashboard float-slow" />
                        <div class="floating-chip chip-1 float-a">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <div class="floating-chip chip-2 float-b">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="floating-chip chip-3 float-c">
                            <i class="bi bi-robot"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== STATS ===== -->
    <section class="stats-section">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-lg-3 reveal" data-reveal="up">
                    <div class="stat-card glass">
                        <div class="stat-number" data-count="50000" data-suffix="+">0</div>
                        <div class="stat-label">Mensajes enviados</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-reveal="up">
                    <div class="stat-card glass">
                        <div class="stat-number" data-count="2000" data-suffix="+">0</div>
                        <div class="stat-label">Empresas activas</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-reveal="up">
                    <div class="stat-card glass">
                        <div class="stat-number" data-count="99.9" data-suffix="%" data-decimals="1">0</div>
                        <div class="stat-label">Uptime garantizado</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-reveal="up">
                    <div class="stat-card glass">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Soporte técnico</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== MODULOS ===== -->
    <section id="modulos" class="section modules-section">
        <div class="container">
            <div class="text-center mb-5">
                <p class="section-eyebrow reveal" data-reveal="up">MÓDULOS DE LA PLATAFORMA</p>
                <h2 class="section-title reveal" data-reveal="up">
                    Todo lo que necesitas en <span class="gradient-text">un solo lugar</span>
                </h2>
                <p class="section-lead reveal" data-reveal="up">Descubre todas las herramientas que necesitas para
                    gestionar tu comunicación empresarial de manera eficiente.</p>
            </div>

            <div class="row g-4" id="modules-grid">
                <!-- cards injected by JS -->
            </div>

            <div class="text-center mt-5 reveal" data-reveal="up">
                <a href="#" class="btn btn-outline-teal magnetic">
                    <i class="bi bi-grid-3x3-gap me-2"></i>
                    Ver todas las funcionalidades</a>
            </div>
        </div>
    </section>

    <!-- ===== BENEFITS ===== -->
    <section id="beneficios" class="section benefits-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="tilt-3d" data-tilt>
                        <img src="{{ asset('img/benefits-3d.png') }}" alt="Ilustración de seguridad y analítica de la plataforma"
                            class="benefits-img float-slow" />
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2 class="section-title text-start reveal" data-reveal="up">
                        ¿Por qué elegir <span class="gradient-text">nuestra plataforma?</span>
                    </h2>
                    <div class="benefit-list">
                        <div class="benefit-item reveal" data-reveal="right">
                            <div class="benefit-icon">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                            <div>
                                <h5>API Oficial de WhatsApp</h5>
                                <p>Integración directa con la API oficial de WhatsApp Business para máxima confiabilidad
                                    y entrega garantizada.</p>
                            </div>
                        </div>
                        <div class="benefit-item reveal" data-reveal="right">
                            <div class="benefit-icon">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <div>
                                <h5>Seguridad Garantizada</h5>
                                <p>Encriptación end-to-end y cumplimiento total con las políticas de privacidad de
                                    WhatsApp.</p>
                            </div>
                        </div>
                        <div class="benefit-item reveal" data-reveal="right">
                            <div class="benefit-icon">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <div>
                                <h5>Analytics Avanzados</h5>
                                <p>Métricas detalladas de entrega, apertura, clics y conversaciones para optimizar tus
                                    campañas.</p>
                            </div>
                        </div>
                        <div class="benefit-item reveal" data-reveal="right">
                            <div class="benefit-icon">
                                <i class="bi bi-headset"></i>
                            </div>
                            <div>
                                <h5>Soporte 24/7</h5>
                                <p>Equipo de soporte técnico disponible las 24 horas para resolver cualquier consulta o
                                    incidencia.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== IA SECTION ===== -->
    <section id="ia" class="section ia-section">
        <div class="ia-glow"></div>
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-lg-2">
                    <span class="section-eyebrow reveal" data-reveal="up">INTELIGENCIA ARTIFICIAL</span>
                    <h2 class="section-title text-start reveal" data-reveal="up">
                        Potencia tu negocio con <span class="gradient-text">Inteligencia Artificial</span>
                    </h2>
                    <p class="section-lead text-start reveal" data-reveal="up">Automatiza conversaciones, analiza el
                        sentimiento de tus clientes y responde al instante con agentes inteligentes entrenados para tu
                        empresa.</p>
                    <div class="row g-3 mt-2">
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="ia-feature glass">
                                <i class="bi bi-robot"></i>
                                <span>Agentes IA</span>
                            </div>
                        </div>
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="ia-feature glass">
                                <i class="bi bi-lightning-charge-fill"></i>
                                <span>Respuestas automáticas</span>
                            </div>
                        </div>
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="ia-feature glass">
                                <i class="bi bi-chat-square-heart"></i>
                                <span>Análisis de conversaciones</span>
                            </div>
                        </div>
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="ia-feature glass">
                                <i class="bi bi-gear-wide-connected"></i>
                                <span>Automatizaciones</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="chat-mockup glass tilt-3d" data-tilt>
                        <div class="chat-header">
                            <img src="{{ asset('img/logo_gmb.png') }}" alt="" class="chat-avatar" />
                            <div>
                                <strong>Asistente IA · GIJAC</strong>
                                <small>
                                    <span class="dot-live"></span>
                                    En línea
                                </small>
                            </div>
                        </div>
                        <div class="chat-body" id="chat-body"></div>
                        <div class="chat-typing" id="chat-typing">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== APP MÓVIL (3D PHONES) ===== -->
    <section id="app" class="section app-section">
        <div class="app-orb app-orb-1"></div>
        <div class="app-orb app-orb-2"></div>
        <div class="app-orb app-orb-3"></div>
        <div class="app-mesh"></div>

        <div class="container">
            <div class="row align-items-center g-5">
                <!-- LEFT: copy -->
                <div class="col-lg-6">
                    <span class="badge-pill reveal" data-reveal="up">
                        <span class="dot-live"></span> App móvil · iOS &amp; Android
                    </span>
                    <h2 class="app-title">
                        <span class="app-line reveal" data-reveal="up">Lleva el control de tus</span>
                        <span class="app-line reveal" data-reveal="up">conversaciones</span>
                        <span class="app-line reveal" data-reveal="up">
                            <span class="gradient-text">a donde vayas</span>
                        </span>
                    </h2>
                    <p class="app-sub reveal" data-reveal="up">
                        Gestiona campañas, responde a tus clientes y monitorea tus métricas en tiempo real
                        desde la palma de tu mano. Todo el poder de GIJAC, ahora móvil.
                    </p>

                    <div class="row g-3 app-features">
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="app-feature glass tilt-3d" data-tilt>
                                <div class="app-feature-icon">
                                    <i class="bi bi-chat-dots-fill"></i>
                                </div>
                                <div>
                                    <h6>Chats en tiempo real</h6>
                                    <p>Responde al instante desde cualquier lugar.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="app-feature glass tilt-3d" data-tilt>
                                <div class="app-feature-icon">
                                    <i class="bi bi-graph-up-arrow"></i>
                                </div>
                                <div>
                                    <h6>Analíticas al alcance</h6>
                                    <p>Métricas y campañas siempre contigo.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="app-feature glass tilt-3d" data-tilt>
                                <div class="app-feature-icon">
                                    <i class="bi bi-bell-fill"></i>
                                </div>
                                <div>
                                    <h6>Notificaciones inteligentes</h6>
                                    <p>Entérate de todo lo importante al momento.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="app-feature glass tilt-3d" data-tilt>
                                <div class="app-feature-icon">
                                    <i class="bi bi-shield-lock-fill"></i>
                                </div>
                                <div>
                                    <h6>Seguridad garantizada</h6>
                                    <p>Encriptación de extremo a extremo.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="download-card glass reveal" data-reveal="up">
                        <span class="download-label">Descarga la app</span>
                        <div class="store-row">
                            <a href="#" class="store-btn magnetic" aria-label="Descargar en App Store">
                                <i class="bi bi-apple"></i>
                                <span>
                                    <small>Descárgalo en</small>
                                    <strong>App Store</strong>
                                </span>
                            </a>
                            <a href="#" class="store-btn magnetic" aria-label="Disponible en Google Play">
                                <i class="bi bi-google-play"></i>
                                <span>
                                    <small>Disponible en</small>
                                    <strong>Google Play</strong>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: 3D phones (Three.js / WebGL) -->
                <div class="col-lg-6">
                    <div id="phones-3d" class="phones-3d" aria-hidden="true">
                        <div class="phones-fallback">
                            <img src="{{ asset('img/hero-dashboard.png') }}" alt="Vista previa de la app móvil GIJAC" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA FINAL ===== -->
    <section id="cta" class="section cta-section">
        <div class="container">
            <div class="cta-wrap glass">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <h2 class="cta-title reveal" data-reveal="up">
                            ¿Listo para <span class="gradient-text">despegar?</span>
                        </h2>
                        <p class="cta-lead reveal" data-reveal="up">Únete a miles de empresas que ya están utilizando
                            nuestra plataforma para mejorar su comunicación con clientes.</p>
                        <div class="d-flex flex-wrap gap-3 reveal" data-reveal="up">
                            <a href="{{ route('precios') }}" class="btn btn-light-glow btn-lg magnetic">
                                <i class="bi bi-eye me-2"></i>
                                Ver Precios
                            </a>
                            <a href="{{ route('contactarnos') }}" class="btn btn-dark-glass btn-lg magnetic">
                                <i class="bi bi-telephone me-2"></i>
                                Contactar Ventas
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5 text-center">
                        <div class="rocket-wrap">
                            <img src="{{ asset('img/rocket-3d.png') }}" alt="Cohete despegando" class="rocket-img" />
                            <div class="rocket-flame"></div>
                            <div class="rocket-sparks">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    {{-- <script src="{{ mix('js/prueba.js') }}"></script> --}}
@endsection
