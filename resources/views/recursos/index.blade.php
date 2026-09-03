@extends('layouts.principal')

@section('css')
    <link href="{{ mix('css/recursos.css') }}" rel="stylesheet">
@endsection

@section('content')
    <!-- ============ HERO ============ -->
    <header class="hero" id="inicio">
        <canvas id="heroCanvas"></canvas>
        <div class="grid-lines"></div>
        <div class="glow-blob gb1"></div>
        <div class="glow-blob gb2"></div>
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 text-white">
                    <span class="eyebrow"
                        style="color:#bff2f7;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.18)">
                        <i class="fa-solid fa-graduation-cap"></i>{{ __('Centro de Recursos') }}</span>
                    <h1 class="mt-3">{{ __('Aprende a dominar') }}<span class="gradient-text">{{ __('GIJAC MESSAGE BUSINESS') }}</span></h1>
                    <p class="lead">{{ __('Documentación, cursos, videotutoriales, guías rápidas y plantillas listas para
                        usar.
                        Todo lo que necesitas para llevar tu comunicación por WhatsApp al siguiente nivel.') }</p>

                    <div class="searchbox">
                        <div class="field">
                            <i class="fa-solid fa-magnifying-glass text-white-50"></i>
                            <input type="text" id="heroSearch" placeholder="{{ __('Busca campañas, chatbots, IA, contactos…') }}"
                                autocomplete="off">
                            <button class="btn-gj"><i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                        <div class="search-results" id="searchResults" style="display:none"></div>
                        <div class="search-tags">
                            <span>{{ __('Populares:') }}</span>
                            <span class="tag-chip">{{ __('Campañas') }}</span>
                            <span class="tag-chip">{{ __('Chatbots') }}</span>
                            <span class="tag-chip">{{ __('Plantillas') }}</span>
                            <span class="tag-chip">{{ __('Contactos') }}</span>
                            <span class="tag-chip">{{ __('Automatizaciones') }}</span>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-4 mt-5">
                        <div>
                            <div class="h3 fw-bold mb-0" data-count="120" data-suffix="+">0</div><small
                                class="text-white-50">{{ __('Artículos') }}</small>
                        </div>
                        <div>
                            <div class="h3 fw-bold mb-0" data-count="48" data-suffix="">0</div><small
                                class="text-white-50">{{ __('Videotutoriales') }}</small>
                        </div>
                        <div>
                            <div class="h3 fw-bold mb-0" data-count="12" data-suffix="">0</div><small
                                class="text-white-50">{{ __('Cursos') }}</small>
                        </div>
                        <div>
                            <div class="h3 fw-bold mb-0" data-count="9800" data-suffix="+">0</div><small
                                class="text-white-50">{{ __('Usuarios formados') }}</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="scene">
                        <div class="deck" id="heroDeck">
                            <div class="panel-3d panel-main" style="width:340px">
                                <div class="d-flex justify-content-between align-items-center mb-3 text-white">
                                    <span class="fw-semibold small"><i class="fa-solid fa-chart-line me-2"
                                            style="color:#7fe3ec"></i>{{ __('Panel de aprendizaje') }}</span>
                                    <span class="chip new">{{ __('En vivo') }}</span>
                                </div>
                                <div class="ph w80"></div>
                                <div class="ph w60"></div>
                                <div class="ph w40"></div>
                                <div class="mini-metric">
                                    <div><b>92%</b><span>{{ __('Progreso') }}</span></div>
                                    <div><b>{{ __('4.9') }}</b><span>{{ __('Valoración') }}</span></div>
                                    <div><b>{{ __('32m') }}</b><span>{{ __('Restante') }}</span></div>
                                </div>
                            </div>
                            <div class="float-card fc1"><i class="fa-solid fa-circle-play me-2"></i>{{ __('Nuevo video') }}</div>
                            <div class="float-card fc2"><i class="fa-solid fa-book me-2"></i>{{ __('Docs actualizados') }}</div>
                            <div class="float-card fc3"><i class="fa-solid fa-robot me-2"></i>{{ __('IA · Asistentes') }}</div>
                            <div class="bubble-3d"><i class="fa-brands fa-whatsapp me-2"></i>{{ __('¡Campaña enviada!') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ============ ACCESO RÁPIDO ============ -->
    <section class="section pb-0">
        <div class="container">
            <div class="row g-4">
                <div class="col-6 col-lg-3 reveal"><a href="#guias" class="card-gj quick-card tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-rocket"></i></div>
                        <h5 class="mt-3">{{ __('Primeros pasos') }}</h5>
                        <p>{{ __('Configura tu cuenta en minutos.') }}</p>
                    </a></div>
                <div class="col-6 col-lg-3 reveal" data-delay="80"><a href="#documentacion"
                        class="card-gj quick-card tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-book"></i></div>
                        <h5 class="mt-3">{{ __('Documentación') }}</h5>
                        <p>{{ __('Guías técnicas y funcionales.') }}</p>
                    </a></div>
                <div class="col-6 col-lg-3 reveal" data-delay="160"><a href="#videos"
                        class="card-gj quick-card tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-circle-play"></i>
                        </div>
                        <h5 class="mt-3">{{ __('Videotutoriales') }}</h5>
                        <p>{{ __('Aprende viendo, paso a paso.') }}</p>
                    </a></div>
                <div class="col-6 col-lg-3 reveal" data-delay="240"><a href="#soporte"
                        class="card-gj quick-card tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-headset"></i></div>
                        <h5 class="mt-3">{{ __('Soporte') }}</h5>
                        <p>{{ __('Habla con nuestro equipo.') }}</p>
                    </a></div>
            </div>
        </div>
    </section>

    <!-- ============ CONTINÚA APRENDIENDO ============ -->
    <section class="section">
        <div class="container">
            <span class="eyebrow"><i class="fa-solid fa-clock-rotate-left"></i>{{ __('Retoma donde quedaste') }}</span>
            <h2 class="section-title mt-3">{{ __('Continúa aprendiendo') }}</h2>
            <p class="section-sub">{{ __('Tu progreso se guarda automáticamente en cada curso y ruta de aprendizaje.') }}</p>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="card-gj tilt">
                        <span class="chip">{{ __('Curso') }}</span>
                        <h5 class="mt-3">{{ __('Primeros pasos con GIJAC') }}</h5>
                        <p class="mb-3">{{ __('Lección 7 de 12 · Conectar tu número') }}</p>
                        <div class="progress-gj mb-2"><span data-value="{{ __('58') }}"></span></div>
                        <small class="text-secondary">{{ __('58% completado') }}</small>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="80">
                    <div class="card-gj tilt">
                        <span class="chip warn">{{ __('En curso') }}</span>
                        <h5 class="mt-3">{{ __('Automatiza tu atención con Chatbots') }}</h5>
                        <p class="mb-3">{{ __('Lección 3 de 10 · Palabras clave') }}</p>
                        <div class="progress-gj mb-2"><span data-value="{{ __('31') }}"></span></div>
                        <small class="text-secondary">{{ __('31% completado') }}</small>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="160">
                    <div class="card-gj tilt">
                        <span class="chip dark">{{ __('Ruta') }}</span>
                        <h5 class="mt-3">{{ __('Experto en Campañas') }}</h5>
                        <p class="mb-3">{{ __('2 de 4 cursos completados') }}</p>
                        <div class="progress-gj mb-2"><span data-value="{{ __('50') }}"></span></div>
                        <small class="text-secondary">{{ __('50% completado') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ CATEGORÍAS ============ -->
    <section class="section pt-0">
        <div class="container">
            <span class="eyebrow"><i class="fa-solid fa-layer-group"></i>{{ __('Explora por tema') }}</span>
            <h2 class="section-title mt-3">{{ __('Categorías') }}</h2>
            <p class="section-sub">{{ __('Encuentra rápidamente el recurso que necesitas según el módulo de la plataforma.') }}</p>
            <div class="row g-4">
                <div class="col-6 col-lg-3 reveal">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-bullhorn"></i></div>
                        <h5 class="mt-3">{{ __('Campañas') }}</h5>
                        <p>{{ __('12 artículos · 6 videos') }}</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="60">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-address-book"></i>
                        </div>
                        <h5 class="mt-3">{{ __('Contactos') }}</h5>
                        <p>{{ __('9 artículos · 4 videos') }}</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="120">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-robot"></i></div>
                        <h5 class="mt-3">{{ __('Chatbots') }}</h5>
                        <p>{{ __('11 artículos · 7 videos') }}</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="180">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-brain"></i></div>
                        <h5 class="mt-3">{{ __('Inteligencia Artificial') }}</h5>
                        <p>{{ __('8 artículos · 5 videos') }}</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i
                                class="fa-solid fa-diagram-project"></i></div>
                        <h5 class="mt-3">{{ __('Automatizaciones') }}</h5>
                        <p>{{ __('7 artículos · 3 videos') }}</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="60">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-file-lines"></i>
                        </div>
                        <h5 class="mt-3">{{ __('Plantillas') }}</h5>
                        <p>{{ __('10 artículos · 5 videos') }}</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="120">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <h5 class="mt-3">{{ __('Métricas') }}</h5>
                        <p>{{ __('8 artículos · 4 videos') }}</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="180">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-gear"></i></div>
                        <h5 class="mt-3">{{ __('Administración') }}</h5>
                        <p>{{ __('9 artículos · 3 videos') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ CURSOS ============ -->
    <section class="section pt-0" id="cursos">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
                <div>
                    <span class="eyebrow"><i class="fa-solid fa-graduation-cap"></i>{{ __('Academia GIJAC') }}</span>
                    <h2 class="section-title mt-3 mb-1">{{ __('Cursos') }}</h2>
                    <p class="section-sub mb-0">{{ __('Rutas de aprendizaje guiadas, con certificado al finalizar.') }}</p>
                </div>
                <a href="#cursos" class="btn-outline-gj" data-toast="Catálogo completo de cursos">{{ __('Ver todos') }}<i
                        class="fa-solid fa-arrow-right-long ms-1"></i></a>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="card-gj course-card tilt">
                        <div class="thumb"><i class="fa-solid fa-rocket fs-2 text-white"></i><span
                                class="thumb-label">{{ __('Principiante') }}</span></div>
                        <h5>{{ __('Primeros pasos con GIJAC Message Business') }}</h5>
                        <p class="mb-3">{{ __('12 lecciones · 45 min') }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="chip"><i class="fa-solid fa-star me-1"></i>{{ __('4.9') }}</span>
                            <button class="btn-gj" data-toast="Curso iniciado: Primeros pasos">{{ __('Empezar') }}</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal" data-delay="80">
                    <div class="card-gj course-card tilt">
                        <div class="thumb"><i class="fa-solid fa-bullhorn fs-2 text-white"></i><span
                                class="thumb-label">{{ __('Principiante') }}</span></div>
                        <h5>{{ __('Crea tu primera campaña') }}</h5>
                        <p class="mb-3">{{ __('8 lecciones · 32 min') }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="chip"><i class="fa-solid fa-star me-1"></i>{{ __('4.8') }}</span>
                            <button class="btn-gj"
                                data-toast="Curso iniciado: Crea tu primera campaña">{{ __('Empezar') }}</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal" data-delay="160">
                    <div class="card-gj course-card tilt">
                        <div class="thumb"><i class="fa-solid fa-robot fs-2 text-white"></i><span
                                class="thumb-label">{{ __('Intermedio') }}</span></div>
                        <h5>{{ __('Automatiza tu atención con Chatbots') }}</h5>
                        <p class="mb-3">{{ __('10 lecciones · 55 min') }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="chip"><i class="fa-solid fa-star me-1"></i>{{ __('4.9') }}</span>
                            <button class="btn-gj" data-toast="Curso iniciado: Chatbots">{{ __('Empezar') }}</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal" data-delay="240">
                    <div class="card-gj course-card tilt">
                        <div class="thumb"><i class="fa-solid fa-brain fs-2 text-white"></i><span
                                class="thumb-label">{{ __('Avanzado') }}</span></div>
                        <h5>{{ __('Asistentes IA para atención al cliente') }}</h5>
                        <p class="mb-3">{{ __('9 lecciones · 50 min') }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="chip"><i class="fa-solid fa-star me-1"></i>{{ __('5.0') }}</span>
                            <button class="btn-gj" data-toast="Curso iniciado: Asistentes IA">{{ __('Empezar') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ VIDEOS ============ -->
    <section class="section videos" id="videos">
        <div class="container">
            <span class="eyebrow"><i class="fa-solid fa-circle-play"></i>{{ __('Videoteca') }}</span>
            <h2 class="section-title mt-3 text-white">{{ __('Videotutoriales') }}</h2>
            <p class="section-sub">{{ __('Tutoriales cortos y directos para resolver tareas concretas dentro de la plataforma.') }}</p>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="video-card tilt" data-title="{{ __('¿Cómo crear una campaña?') }}"
                        data-desc="Aprende a seleccionar contactos, elegir una plantilla aprobada, programar el envío y revisar los resultados de tu campaña de WhatsApp."
                        data-cat="Campañas" data-dur="04:32">
                        <div class="video-thumb">
                            <div class="overlay"></div><span class="play-btn"><i class="fa-solid fa-play"
                                    style="color:#145962"></i></span><span class="dur">{{ __('04:32') }}</span>
                        </div>
                        <div class="video-body"><span class="chip">{{ __('Campañas') }}</span>
                            <h6>{{ __('¿Cómo crear una campaña?') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="80">
                    <div class="video-card tilt" data-title="{{ __('Configura tu chatbot en 6 minutos') }}"
                        data-desc="Define palabras clave, horarios de atención, mensajes especiales y transferencia a un agente humano."
                        data-cat="Chatbots" data-dur="06:14">
                        <div class="video-thumb">
                            <div class="overlay"></div><span class="play-btn"><i class="fa-solid fa-play"
                                    style="color:#145962"></i></span><span class="dur">{{ __('06:14') }}</span>
                        </div>
                        <div class="video-body"><span class="chip">{{ __('Chatbots') }}</span>
                            <h6>{{ __('Configura tu chatbot en 6 minutos') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="160">
                    <div class="video-card tilt" data-title="{{ __('Entrena tu asistente IA') }}"
                        data-desc="Carga tu base de conocimiento, ajusta el tono y define los límites de respuesta de tu asistente inteligente."
                        data-cat="Inteligencia Artificial" data-dur="08:05">
                        <div class="video-thumb">
                            <div class="overlay"></div><span class="play-btn"><i class="fa-solid fa-play"
                                    style="color:#145962"></i></span><span class="dur">{{ __('08:05') }}</span>
                        </div>
                        <div class="video-body"><span class="chip">{{ __('IA') }}</span>
                            <h6>{{ __('Entrena tu asistente IA') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="video-card tilt" data-title="{{ __('Importar contactos desde CSV') }}"
                        data-desc="Prepara tu archivo, mapea columnas, evita duplicados y crea segmentos automáticos con etiquetas."
                        data-cat="Contactos" data-dur="05:20">
                        <div class="video-thumb">
                            <div class="overlay"></div><span class="play-btn"><i class="fa-solid fa-play"
                                    style="color:#145962"></i></span><span class="dur">{{ __('05:20') }}</span>
                        </div>
                        <div class="video-body"><span class="chip">{{ __('Contactos') }}</span>
                            <h6>{{ __('Importar contactos desde CSV') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="80">
                    <div class="video-card tilt" data-title="{{ __('Plantillas aprobadas por WhatsApp') }}"
                        data-desc="Cómo redactar, categorizar y enviar a aprobación tus plantillas para evitar rechazos."
                        data-cat="Plantillas" data-dur="07:41">
                        <div class="video-thumb">
                            <div class="overlay"></div><span class="play-btn"><i class="fa-solid fa-play"
                                    style="color:#145962"></i></span><span class="dur">{{ __('07:41') }}</span>
                        </div>
                        <div class="video-body"><span class="chip">{{ __('Plantillas') }}</span>
                            <h6>{{ __('Plantillas aprobadas por WhatsApp') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="160">
                    <div class="video-card tilt" data-title="{{ __('Conecta GIJAC con n8n') }}"
                        data-desc="Crea webhooks entrantes y salientes para integrar tu CRM y automatizar procesos de negocio."
                        data-cat="Automatizaciones" data-dur="09:12">
                        <div class="video-thumb">
                            <div class="overlay"></div><span class="play-btn"><i class="fa-solid fa-play"
                                    style="color:#145962"></i></span><span class="dur">{{ __('09:12') }}</span>
                        </div>
                        <div class="video-body"><span class="chip">{{ __('Automatizaciones') }}</span>
                            <h6>{{ __('Conecta GIJAC con n8n') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ DOCUMENTACIÓN ============ -->
    <section class="section" id="documentacion">
        <div class="container">
            <span class="eyebrow"><i class="fa-solid fa-book"></i>{{ __('Base de conocimiento') }}</span>
            <h2 class="section-title mt-3">{{ __('Documentación') }}</h2>
            <p class="section-sub">{{ __('Navega por módulos y consulta los artículos oficiales de la plataforma.') }}</p>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="docs-side">
                        <div class="doc-cat" data-key="intro"><button><span><i
                                        class="fa-solid fa-flag me-2"></i>{{ __('Introducción') }}</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">{{ __('Qué es GIJAC') }}</a></li>
                                <li><a href="#documentacion">{{ __('Estructura de la cuenta') }}</a></li>
                                <li><a href="#documentacion">{{ __('Conectar WhatsApp') }}</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="campanas"><button><span><i
                                        class="fa-solid fa-bullhorn me-2"></i>{{ __('Campañas') }}</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">{{ __('Crear una campaña') }}</a></li>
                                <li><a href="#documentacion">{{ __('Programar envíos') }}</a></li>
                                <li><a href="#documentacion">{{ __('Resultados') }}</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="contactos"><button><span><i
                                        class="fa-solid fa-address-book me-2"></i>{{ __('Contactos') }}</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">{{ __('Importar CSV') }}</a></li>
                                <li><a href="#documentacion">{{ __('Listas y etiquetas') }}</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="chatbots"><button><span><i
                                        class="fa-solid fa-robot me-2"></i>{{ __('Chatbots') }}</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">{{ __('Crear un chatbot') }}</a></li>
                                <li><a href="#documentacion">{{ __('Palabras clave') }}</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="ia"><button><span><i
                                        class="fa-solid fa-brain me-2"></i>{{ __('Inteligencia Artificial') }}</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">{{ __('Crear un asistente') }}</a></li>
                                <li><a href="#documentacion">{{ __('Base de conocimiento') }}</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="automatizaciones"><button><span><i
                                        class="fa-solid fa-diagram-project me-2"></i>{{ __('Automatizaciones') }}</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">{{ __('Conectar n8n') }}</a></li>
                                <li><a href="#documentacion">{{ __('Webhooks') }}</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="plantillas"><button><span><i
                                        class="fa-solid fa-file-lines me-2"></i>{{ __('Plantillas') }}</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">{{ __('Crear plantilla') }}</a></li>
                                <li><a href="#documentacion">{{ __('Variables dinámicas') }}</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="metricas"><button><span><i
                                        class="fa-solid fa-chart-pie me-2"></i>{{ __('Métricas') }}</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">{{ __('Panel de métricas') }}</a></li>
                                <li><a href="#documentacion">{{ __('Exportar reportes') }}</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="admin"><button><span><i
                                        class="fa-solid fa-gear me-2"></i>{{ __('Administración') }}</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">{{ __('Usuarios y equipos') }}</a></li>
                                <li><a href="#documentacion">{{ __('Facturación') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="doc-panel" id="docPanel"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ GUÍAS RÁPIDAS ============ -->
    <section class="section pt-0" id="guias">
        <div class="container">
            <span class="eyebrow"><i class="fa-solid fa-bolt"></i>{{ __('Paso a paso') }}</span>
            <h2 class="section-title mt-3">{{ __('Guías rápidas') }}</h2>
            <p class="section-sub">{{ __('Soluciones concretas que puedes aplicar hoy mismo, en menos de 10 minutos.') }}</p>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-bolt"></i></div>
                        <h5 class="mt-3">{{ __('Lanza tu primera campaña en 5 minutos') }}</h5>
                        <p class="mb-3">{{ __('Del contacto al envío, sin rodeos.') }}</p>
                        <span class="chip">{{ __('⏱ 5 min') }}</span> <span class="chip dark">{{ __('Principiante') }}</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="80">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-robot"></i></div>
                        <h5 class="mt-3">{{ __('Configura tu primer chatbot') }}</h5>
                        <p class="mb-3">{{ __('Horarios, saludo y palabras clave.') }}</p>
                        <span class="chip">{{ __('⏱ 8 min') }}</span> <span class="chip dark">{{ __('Principiante') }}</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="160">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i
                                class="fa-solid fa-diagram-project"></i></div>
                        <h5 class="mt-3">{{ __('Conecta una automatización') }}</h5>
                        <p class="mb-3">{{ __('Integra n8n con webhooks seguros.') }}</p>
                        <span class="chip">{{ __('⏱ 10 min') }}</span> <span class="chip warn">{{ __('Intermedio') }}</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-users"></i></div>
                        <h5 class="mt-3">{{ __('Segmenta tu base de contactos') }}</h5>
                        <p class="mb-3">{{ __('Etiquetas, listas y campos propios.') }}</p>
                        <span class="chip">{{ __('⏱ 6 min') }}</span> <span class="chip dark">{{ __('Principiante') }}</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="80">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i
                                class="fa-solid fa-file-circle-check"></i></div>
                        <h5 class="mt-3">{{ __('Evita el rechazo de plantillas') }}</h5>
                        <p class="mb-3">{{ __('Buenas prácticas de aprobación.') }}</p>
                        <span class="chip">{{ __('⏱ 7 min') }}</span> <span class="chip warn">{{ __('Intermedio') }}</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="160">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h5 class="mt-3">{{ __('Interpreta tus métricas') }}</h5>
                        <p class="mb-3">{{ __('Entregados, leídos y respuestas.') }}</p>
                        <span class="chip">{{ __('⏱ 9 min') }}</span> <span class="chip warn">{{ __('Intermedio') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ DESCARGABLES ============ -->
    <section class="section pt-0" id="recursos">
        <div class="container">
            <span class="eyebrow"><i class="fa-solid fa-download"></i>{{ __('Kit de trabajo') }}</span>
            <h2 class="section-title mt-3">{{ __('Recursos descargables') }}</h2>
            <p class="section-sub">{{ __('Plantillas, checklists y material listo para usar con tu equipo.') }}</p>
            <div class="row g-4">
                <div class="col-6 col-lg-3 reveal">
                    <div class="card-gj res-card tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-file-pdf"></i></div>
                        <h5>{{ __('Guía de buenas prácticas') }}</h5>
                        <p class="mb-3">{{ __('PDF · 2.4 MB') }}</p>
                        <button class="btn-outline-gj w-100"
                            data-toast="Descargando: Guía de buenas prácticas">{{ __('Descargar') }}</button>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="60">
                    <div class="card-gj res-card tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-file-csv"></i></div>
                        <h5>{{ __('Plantilla de contactos') }}</h5>
                        <p class="mb-3">{{ __('CSV · 12 KB') }}</p>
                        <button class="btn-outline-gj w-100"
                            data-toast="Descargando: Plantilla de contactos">{{ __('Descargar') }}</button>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="120">
                    <div class="card-gj res-card tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-list-check"></i>
                        </div>
                        <h5>{{ __('Checklist de campaña') }}</h5>
                        <p class="mb-3">{{ __('PDF · 640 KB') }}</p>
                        <button class="btn-outline-gj w-100"
                            data-toast="Descargando: Checklist de campaña">{{ __('Descargar') }}</button>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="180">
                    <div class="card-gj res-card tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-comments"></i></div>
                        <h5>{{ __('Guiones de chatbot') }}</h5>
                        <p class="mb-3">{{ __('DOCX · 380 KB') }}</p>
                        <button class="btn-outline-gj w-100"
                            data-toast="Descargando: Guiones de chatbot">{{ __('Descargar') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="section pt-0" id="faq">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-5">
                    <span class="eyebrow"><i class="fa-solid fa-circle-question"></i>{{ __('Dudas frecuentes') }}</span>
                    <h2 class="section-title mt-3">{{ __('Preguntas frecuentes') }}</h2>
                    <p class="section-sub">{{ __('Las respuestas que más consulta nuestra comunidad. ¿No encuentras la tuya?
                        Escríbenos desde el centro de soporte.') }</p>
                    <a href="#soporte" class="btn-gj">{{ __('Contactar soporte') }}</a>
                </div>
                <div class="col-lg-7">
                    <div class="accordion" id="faqAcc">
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button" data-bs-toggle="collapse"
                                    data-bs-target="#f1">{{ __('¿Cómo conecto mi número de WhatsApp?') }}</button></h2>
                            <div id="f1" class="accordion-collapse collapse show" data-bs-parent="#faqAcc">
                                <div class="accordion-body">{{ __('Desde') }}<b>{{ __('Administración → Canales') }}</b>{{ __('selecciona “Conectar
                                    WhatsApp”, verifica tu número de empresa y completa la validación de Meta. El
                                    proceso suele tardar entre 5 y 20 minutos.') }</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#f2">{{ __('¿Por qué mi plantilla fue
                                    rechazada?') }</button></h2>
                            <div id="f2" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">{{ __('Las causas más comunes son categorías incorrectas,
                                    variables sin ejemplo o contenido promocional en plantillas de servicio. Revisa la
                                    guía “Evita el rechazo de plantillas”.') }</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#f3">{{ __('¿Cuántos contactos puedo
                                    importar?') }</button></h2>
                            <div id="f3" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">{{ __('Depende de tu plan. En todos los planes puedes importar por
                                    lotes de hasta 50.000 registros por archivo CSV, con deduplicación automática por
                                    número.') }</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#f4">{{ __('¿El asistente IA responde
                                    solo?') }</button></h2>
                            <div id="f4" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">{{ __('Sí, dentro de los límites que definas. Puedes configurar
                                    tono, temas permitidos y una regla de transferencia a un agente humano cuando no
                                    logre resolver la conversación.') }</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#f5">{{ __('¿Puedo integrar mi CRM?') }}</button>
                            </h2>
                            <div id="f5" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">{{ __('Sí. Usa webhooks entrantes y salientes o conecta n8n para
                                    sincronizar contactos, etiquetas y eventos de conversación en tiempo real.') }</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#f6">{{ __('¿Los cursos entregan
                                    certificado?') }</button></h2>
                            <div id="f6" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">{{ __('Al completar el 100% de las lecciones y aprobar la
                                    evaluación final recibirás un certificado digital verificable con tu nombre y la
                                    fecha de finalización.') }</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ NOVEDADES ============ -->
    <section class="section pt-0" id="novedades">
        <div class="container">
            <span class="eyebrow"><i class="fa-solid fa-bell"></i>{{ __('Changelog') }}</span>
            <h2 class="section-title mt-3">{{ __('Novedades') }}</h2>
            <p class="section-sub">{{ __('Lo último que llegó a la plataforma y al Centro de Recursos.') }}</p>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="news-card tilt"><span class="bar"></span>
                        <span class="chip new">{{ __('Nuevo') }}</span>
                        <h5 class="mt-3 fw-bold">{{ __('Calendario de campañas') }}</h5>
                        <p class="text-secondary mb-3">{{ __('Programa y arrastra tus campañas en vistas de mes, semana y día.') }}</p>
                        <small class="text-secondary"><i class="fa-regular fa-calendar me-1"></i>{{ __('20 de agosto, 2026') }}</small>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="80">
                    <div class="news-card tilt"><span class="bar"></span>
                        <span class="chip">{{ __('Mejora') }}</span>
                        <h5 class="mt-3 fw-bold">{{ __('Asistentes IA más precisos') }}</h5>
                        <p class="text-secondary mb-3">{{ __('Nueva base de conocimiento con búsqueda semántica y citas de
                            origen.') }</p>
                        <small class="text-secondary"><i class="fa-regular fa-calendar me-1"></i>{{ __('12 de agosto, 2026') }}</small>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="160">
                    <div class="news-card tilt"><span class="bar"></span>
                        <span class="chip warn">{{ __('Beta') }}</span>
                        <h5 class="mt-3 fw-bold">{{ __('Flujos visuales de chatbot') }}</h5>
                        <p class="text-secondary mb-3">{{ __('Diseña la atención con nodos arrastrables e integraciones de IA.') }}</p>
                        <small class="text-secondary"><i class="fa-regular fa-calendar me-1"></i>{{ __('2 de agosto, 2026') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ SOPORTE ============ -->
    <section class="section pt-0" id="soporte">
        <div class="container">
            <div class="support">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <span class="eyebrow"
                            style="color:#bff2f7;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.18)">
                            <i class="fa-solid fa-headset"></i>{{ __('Centro de soporte') }}</span>
                        <h2 class="mt-3 fw-bold">{{ __('¿Necesitas ayuda de una persona real?') }}</h2>
                        <p>{{ __('Nuestro equipo responde en menos de 2 horas hábiles. Chatea con nosotros, abre un ticket o
                            agenda una sesión guiada con un especialista GIJAC.') }</p>
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a type="button" href="https://wa.me/573171789584" class="btn-gj" data-toast="Abriendo chat con soporte">
                                <i class="fa-brands fa-whatsapp me-2"></i>{{ __('Chatear por WhatsApp') }}</a>
                            <a type="button" href="{{ route('tickets.index') }}" class="btn-ghost-r" data-toast="Formulario de ticket abierto">
                                <i class="fa-regular fa-envelope me-2"></i>{{ __('Abrir un ticket') }}</a>
                            <a type="button" href="{{ route('contactarnos') }}" class="btn-ghost-r" data-toast="Agenda de sesión guiada">
                                <i class="fa-regular fa-calendar-check me-2"></i>{{ __('Agendar sesión') }}</a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="support-3d">
                            <div class="support-orb"><i class="fa-solid fa-life-ring"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="gj-toasts" id="toasts"></div>

    <!-- ============ MODAL VIDEO ============ -->
    <div class="modal fade" id="videoModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:26px;border:0;overflow:hidden">
                <div class="modal-body p-0">
                    <div class="video-thumb" style="aspect-ratio:16/9">
                        <div class="overlay"></div>
                        <span class="play-btn"><i class="fa-solid fa-play" style="color:#145962"></i></span>
                    </div>
                    <div class="p-4">
                        <div class="d-flex gap-2 mb-2"><span class="chip" id="videoCat">{{ __('Categoría') }}</span><span
                                class="chip dark" id="videoDur">{{ __('00:00') }}</span></div>
                        <h4 class="fw-bold" id="videoTitle">{{ __('Título') }}</h4>
                        <p class="text-secondary mb-4" id="videoDesc">{{ __('Descripción') }}</p>
                        <div class="d-flex gap-2">
                            <button class="btn-gj" data-toast="Reproduciendo video">{{ __('Reproducir') }}</button>
                            <button class="btn-outline-gj" data-bs-dismiss="modal">{{ __('Cerrar') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ mix('js/recursos/particles.js') }}"></script>
    <script src="{{ mix('js/recursos/app.js') }}"></script>
@endsection
