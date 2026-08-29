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
                        <i class="fa-solid fa-graduation-cap"></i> Centro de Recursos
                    </span>
                    <h1 class="mt-3">Aprende a dominar <span class="gradient-text">GIJAC MESSAGE BUSINESS</span></h1>
                    <p class="lead">Documentación, cursos, videotutoriales, guías rápidas y plantillas listas para
                        usar.
                        Todo lo que necesitas para llevar tu comunicación por WhatsApp al siguiente nivel.</p>

                    <div class="searchbox">
                        <div class="field">
                            <i class="fa-solid fa-magnifying-glass text-white-50"></i>
                            <input type="text" id="heroSearch" placeholder="Busca campañas, chatbots, IA, contactos…"
                                autocomplete="off">
                            <button class="btn-gj"><i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                        <div class="search-results" id="searchResults" style="display:none"></div>
                        <div class="search-tags">
                            <span>Populares:</span>
                            <span class="tag-chip">Campañas</span>
                            <span class="tag-chip">Chatbots</span>
                            <span class="tag-chip">Plantillas</span>
                            <span class="tag-chip">Contactos</span>
                            <span class="tag-chip">Automatizaciones</span>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-4 mt-5">
                        <div>
                            <div class="h3 fw-bold mb-0" data-count="120" data-suffix="+">0</div><small
                                class="text-white-50">Artículos</small>
                        </div>
                        <div>
                            <div class="h3 fw-bold mb-0" data-count="48" data-suffix="">0</div><small
                                class="text-white-50">Videotutoriales</small>
                        </div>
                        <div>
                            <div class="h3 fw-bold mb-0" data-count="12" data-suffix="">0</div><small
                                class="text-white-50">Cursos</small>
                        </div>
                        <div>
                            <div class="h3 fw-bold mb-0" data-count="9800" data-suffix="+">0</div><small
                                class="text-white-50">Usuarios formados</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="scene">
                        <div class="deck" id="heroDeck">
                            <div class="panel-3d panel-main" style="width:340px">
                                <div class="d-flex justify-content-between align-items-center mb-3 text-white">
                                    <span class="fw-semibold small"><i class="fa-solid fa-chart-line me-2"
                                            style="color:#7fe3ec"></i>Panel de aprendizaje</span>
                                    <span class="chip new">En vivo</span>
                                </div>
                                <div class="ph w80"></div>
                                <div class="ph w60"></div>
                                <div class="ph w40"></div>
                                <div class="mini-metric">
                                    <div><b>92%</b><span>Progreso</span></div>
                                    <div><b>4.9</b><span>Valoración</span></div>
                                    <div><b>32m</b><span>Restante</span></div>
                                </div>
                            </div>
                            <div class="float-card fc1"><i class="fa-solid fa-circle-play me-2"></i>Nuevo video</div>
                            <div class="float-card fc2"><i class="fa-solid fa-book me-2"></i>Docs actualizados</div>
                            <div class="float-card fc3"><i class="fa-solid fa-robot me-2"></i>IA · Asistentes</div>
                            <div class="bubble-3d"><i class="fa-brands fa-whatsapp me-2"></i>¡Campaña enviada!</div>
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
                        <h5 class="mt-3">Primeros pasos</h5>
                        <p>Configura tu cuenta en minutos.</p>
                    </a></div>
                <div class="col-6 col-lg-3 reveal" data-delay="80"><a href="#documentacion"
                        class="card-gj quick-card tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-book"></i></div>
                        <h5 class="mt-3">Documentación</h5>
                        <p>Guías técnicas y funcionales.</p>
                    </a></div>
                <div class="col-6 col-lg-3 reveal" data-delay="160"><a href="#videos"
                        class="card-gj quick-card tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-circle-play"></i>
                        </div>
                        <h5 class="mt-3">Videotutoriales</h5>
                        <p>Aprende viendo, paso a paso.</p>
                    </a></div>
                <div class="col-6 col-lg-3 reveal" data-delay="240"><a href="#soporte"
                        class="card-gj quick-card tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-headset"></i></div>
                        <h5 class="mt-3">Soporte</h5>
                        <p>Habla con nuestro equipo.</p>
                    </a></div>
            </div>
        </div>
    </section>

    <!-- ============ CONTINÚA APRENDIENDO ============ -->
    <section class="section">
        <div class="container">
            <span class="eyebrow"><i class="fa-solid fa-clock-rotate-left"></i> Retoma donde quedaste</span>
            <h2 class="section-title mt-3">Continúa aprendiendo</h2>
            <p class="section-sub">Tu progreso se guarda automáticamente en cada curso y ruta de aprendizaje.</p>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="card-gj tilt">
                        <span class="chip">Curso</span>
                        <h5 class="mt-3">Primeros pasos con GIJAC</h5>
                        <p class="mb-3">Lección 7 de 12 · Conectar tu número</p>
                        <div class="progress-gj mb-2"><span data-value="58"></span></div>
                        <small class="text-secondary">58% completado</small>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="80">
                    <div class="card-gj tilt">
                        <span class="chip warn">En curso</span>
                        <h5 class="mt-3">Automatiza tu atención con Chatbots</h5>
                        <p class="mb-3">Lección 3 de 10 · Palabras clave</p>
                        <div class="progress-gj mb-2"><span data-value="31"></span></div>
                        <small class="text-secondary">31% completado</small>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="160">
                    <div class="card-gj tilt">
                        <span class="chip dark">Ruta</span>
                        <h5 class="mt-3">Experto en Campañas</h5>
                        <p class="mb-3">2 de 4 cursos completados</p>
                        <div class="progress-gj mb-2"><span data-value="50"></span></div>
                        <small class="text-secondary">50% completado</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ CATEGORÍAS ============ -->
    <section class="section pt-0">
        <div class="container">
            <span class="eyebrow"><i class="fa-solid fa-layer-group"></i> Explora por tema</span>
            <h2 class="section-title mt-3">Categorías</h2>
            <p class="section-sub">Encuentra rápidamente el recurso que necesitas según el módulo de la plataforma.</p>
            <div class="row g-4">
                <div class="col-6 col-lg-3 reveal">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-bullhorn"></i></div>
                        <h5 class="mt-3">Campañas</h5>
                        <p>12 artículos · 6 videos</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="60">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-address-book"></i>
                        </div>
                        <h5 class="mt-3">Contactos</h5>
                        <p>9 artículos · 4 videos</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="120">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-robot"></i></div>
                        <h5 class="mt-3">Chatbots</h5>
                        <p>11 artículos · 7 videos</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="180">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-brain"></i></div>
                        <h5 class="mt-3">Inteligencia Artificial</h5>
                        <p>8 artículos · 5 videos</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i
                                class="fa-solid fa-diagram-project"></i></div>
                        <h5 class="mt-3">Automatizaciones</h5>
                        <p>7 artículos · 3 videos</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="60">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-file-lines"></i>
                        </div>
                        <h5 class="mt-3">Plantillas</h5>
                        <p>10 artículos · 5 videos</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="120">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <h5 class="mt-3">Métricas</h5>
                        <p>8 artículos · 4 videos</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="180">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-gear"></i></div>
                        <h5 class="mt-3">Administración</h5>
                        <p>9 artículos · 3 videos</p>
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
                    <span class="eyebrow"><i class="fa-solid fa-graduation-cap"></i> Academia GIJAC</span>
                    <h2 class="section-title mt-3 mb-1">Cursos</h2>
                    <p class="section-sub mb-0">Rutas de aprendizaje guiadas, con certificado al finalizar.</p>
                </div>
                <a href="#cursos" class="btn-outline-gj" data-toast="Catálogo completo de cursos">Ver todos <i
                        class="fa-solid fa-arrow-right-long ms-1"></i></a>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="card-gj course-card tilt">
                        <div class="thumb"><i class="fa-solid fa-rocket fs-2 text-white"></i><span
                                class="thumb-label">Principiante</span></div>
                        <h5>Primeros pasos con GIJAC Message Business</h5>
                        <p class="mb-3">12 lecciones · 45 min</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="chip"><i class="fa-solid fa-star me-1"></i>4.9</span>
                            <button class="btn-gj" data-toast="Curso iniciado: Primeros pasos">Empezar</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal" data-delay="80">
                    <div class="card-gj course-card tilt">
                        <div class="thumb"><i class="fa-solid fa-bullhorn fs-2 text-white"></i><span
                                class="thumb-label">Principiante</span></div>
                        <h5>Crea tu primera campaña</h5>
                        <p class="mb-3">8 lecciones · 32 min</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="chip"><i class="fa-solid fa-star me-1"></i>4.8</span>
                            <button class="btn-gj"
                                data-toast="Curso iniciado: Crea tu primera campaña">Empezar</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal" data-delay="160">
                    <div class="card-gj course-card tilt">
                        <div class="thumb"><i class="fa-solid fa-robot fs-2 text-white"></i><span
                                class="thumb-label">Intermedio</span></div>
                        <h5>Automatiza tu atención con Chatbots</h5>
                        <p class="mb-3">10 lecciones · 55 min</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="chip"><i class="fa-solid fa-star me-1"></i>4.9</span>
                            <button class="btn-gj" data-toast="Curso iniciado: Chatbots">Empezar</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal" data-delay="240">
                    <div class="card-gj course-card tilt">
                        <div class="thumb"><i class="fa-solid fa-brain fs-2 text-white"></i><span
                                class="thumb-label">Avanzado</span></div>
                        <h5>Asistentes IA para atención al cliente</h5>
                        <p class="mb-3">9 lecciones · 50 min</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="chip"><i class="fa-solid fa-star me-1"></i>5.0</span>
                            <button class="btn-gj" data-toast="Curso iniciado: Asistentes IA">Empezar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ VIDEOS ============ -->
    <section class="section videos" id="videos">
        <div class="container">
            <span class="eyebrow"><i class="fa-solid fa-circle-play"></i> Videoteca</span>
            <h2 class="section-title mt-3 text-white">Videotutoriales</h2>
            <p class="section-sub">Tutoriales cortos y directos para resolver tareas concretas dentro de la plataforma.
            </p>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="video-card tilt" data-title="¿Cómo crear una campaña?"
                        data-desc="Aprende a seleccionar contactos, elegir una plantilla aprobada, programar el envío y revisar los resultados de tu campaña de WhatsApp."
                        data-cat="Campañas" data-dur="04:32">
                        <div class="video-thumb">
                            <div class="overlay"></div><span class="play-btn"><i class="fa-solid fa-play"
                                    style="color:#145962"></i></span><span class="dur">04:32</span>
                        </div>
                        <div class="video-body"><span class="chip">Campañas</span>
                            <h6>¿Cómo crear una campaña?</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="80">
                    <div class="video-card tilt" data-title="Configura tu chatbot en 6 minutos"
                        data-desc="Define palabras clave, horarios de atención, mensajes especiales y transferencia a un agente humano."
                        data-cat="Chatbots" data-dur="06:14">
                        <div class="video-thumb">
                            <div class="overlay"></div><span class="play-btn"><i class="fa-solid fa-play"
                                    style="color:#145962"></i></span><span class="dur">06:14</span>
                        </div>
                        <div class="video-body"><span class="chip">Chatbots</span>
                            <h6>Configura tu chatbot en 6 minutos</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="160">
                    <div class="video-card tilt" data-title="Entrena tu asistente IA"
                        data-desc="Carga tu base de conocimiento, ajusta el tono y define los límites de respuesta de tu asistente inteligente."
                        data-cat="Inteligencia Artificial" data-dur="08:05">
                        <div class="video-thumb">
                            <div class="overlay"></div><span class="play-btn"><i class="fa-solid fa-play"
                                    style="color:#145962"></i></span><span class="dur">08:05</span>
                        </div>
                        <div class="video-body"><span class="chip">IA</span>
                            <h6>Entrena tu asistente IA</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="video-card tilt" data-title="Importar contactos desde CSV"
                        data-desc="Prepara tu archivo, mapea columnas, evita duplicados y crea segmentos automáticos con etiquetas."
                        data-cat="Contactos" data-dur="05:20">
                        <div class="video-thumb">
                            <div class="overlay"></div><span class="play-btn"><i class="fa-solid fa-play"
                                    style="color:#145962"></i></span><span class="dur">05:20</span>
                        </div>
                        <div class="video-body"><span class="chip">Contactos</span>
                            <h6>Importar contactos desde CSV</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="80">
                    <div class="video-card tilt" data-title="Plantillas aprobadas por WhatsApp"
                        data-desc="Cómo redactar, categorizar y enviar a aprobación tus plantillas para evitar rechazos."
                        data-cat="Plantillas" data-dur="07:41">
                        <div class="video-thumb">
                            <div class="overlay"></div><span class="play-btn"><i class="fa-solid fa-play"
                                    style="color:#145962"></i></span><span class="dur">07:41</span>
                        </div>
                        <div class="video-body"><span class="chip">Plantillas</span>
                            <h6>Plantillas aprobadas por WhatsApp</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="160">
                    <div class="video-card tilt" data-title="Conecta GIJAC con n8n"
                        data-desc="Crea webhooks entrantes y salientes para integrar tu CRM y automatizar procesos de negocio."
                        data-cat="Automatizaciones" data-dur="09:12">
                        <div class="video-thumb">
                            <div class="overlay"></div><span class="play-btn"><i class="fa-solid fa-play"
                                    style="color:#145962"></i></span><span class="dur">09:12</span>
                        </div>
                        <div class="video-body"><span class="chip">Automatizaciones</span>
                            <h6>Conecta GIJAC con n8n</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ DOCUMENTACIÓN ============ -->
    <section class="section" id="documentacion">
        <div class="container">
            <span class="eyebrow"><i class="fa-solid fa-book"></i> Base de conocimiento</span>
            <h2 class="section-title mt-3">Documentación</h2>
            <p class="section-sub">Navega por módulos y consulta los artículos oficiales de la plataforma.</p>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="docs-side">
                        <div class="doc-cat" data-key="intro"><button><span><i
                                        class="fa-solid fa-flag me-2"></i>Introducción</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">Qué es GIJAC</a></li>
                                <li><a href="#documentacion">Estructura de la cuenta</a></li>
                                <li><a href="#documentacion">Conectar WhatsApp</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="campanas"><button><span><i
                                        class="fa-solid fa-bullhorn me-2"></i>Campañas</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">Crear una campaña</a></li>
                                <li><a href="#documentacion">Programar envíos</a></li>
                                <li><a href="#documentacion">Resultados</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="contactos"><button><span><i
                                        class="fa-solid fa-address-book me-2"></i>Contactos</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">Importar CSV</a></li>
                                <li><a href="#documentacion">Listas y etiquetas</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="chatbots"><button><span><i
                                        class="fa-solid fa-robot me-2"></i>Chatbots</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">Crear un chatbot</a></li>
                                <li><a href="#documentacion">Palabras clave</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="ia"><button><span><i
                                        class="fa-solid fa-brain me-2"></i>Inteligencia Artificial</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">Crear un asistente</a></li>
                                <li><a href="#documentacion">Base de conocimiento</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="automatizaciones"><button><span><i
                                        class="fa-solid fa-diagram-project me-2"></i>Automatizaciones</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">Conectar n8n</a></li>
                                <li><a href="#documentacion">Webhooks</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="plantillas"><button><span><i
                                        class="fa-solid fa-file-lines me-2"></i>Plantillas</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">Crear plantilla</a></li>
                                <li><a href="#documentacion">Variables dinámicas</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="metricas"><button><span><i
                                        class="fa-solid fa-chart-pie me-2"></i>Métricas</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">Panel de métricas</a></li>
                                <li><a href="#documentacion">Exportar reportes</a></li>
                            </ul>
                        </div>
                        <div class="doc-cat" data-key="admin"><button><span><i
                                        class="fa-solid fa-gear me-2"></i>Administración</span><i
                                    class="fa-solid fa-chevron-right caret"></i></button>
                            <ul class="doc-sub">
                                <li><a href="#documentacion">Usuarios y equipos</a></li>
                                <li><a href="#documentacion">Facturación</a></li>
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
            <span class="eyebrow"><i class="fa-solid fa-bolt"></i> Paso a paso</span>
            <h2 class="section-title mt-3">Guías rápidas</h2>
            <p class="section-sub">Soluciones concretas que puedes aplicar hoy mismo, en menos de 10 minutos.</p>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-bolt"></i></div>
                        <h5 class="mt-3">Lanza tu primera campaña en 5 minutos</h5>
                        <p class="mb-3">Del contacto al envío, sin rodeos.</p>
                        <span class="chip">⏱ 5 min</span> <span class="chip dark">Principiante</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="80">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-robot"></i></div>
                        <h5 class="mt-3">Configura tu primer chatbot</h5>
                        <p class="mb-3">Horarios, saludo y palabras clave.</p>
                        <span class="chip">⏱ 8 min</span> <span class="chip dark">Principiante</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="160">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i
                                class="fa-solid fa-diagram-project"></i></div>
                        <h5 class="mt-3">Conecta una automatización</h5>
                        <p class="mb-3">Integra n8n con webhooks seguros.</p>
                        <span class="chip">⏱ 10 min</span> <span class="chip warn">Intermedio</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-users"></i></div>
                        <h5 class="mt-3">Segmenta tu base de contactos</h5>
                        <p class="mb-3">Etiquetas, listas y campos propios.</p>
                        <span class="chip">⏱ 6 min</span> <span class="chip dark">Principiante</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="80">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i
                                class="fa-solid fa-file-circle-check"></i></div>
                        <h5 class="mt-3">Evita el rechazo de plantillas</h5>
                        <p class="mb-3">Buenas prácticas de aprobación.</p>
                        <span class="chip">⏱ 7 min</span> <span class="chip warn">Intermedio</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="160">
                    <div class="card-gj tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h5 class="mt-3">Interpreta tus métricas</h5>
                        <p class="mb-3">Entregados, leídos y respuestas.</p>
                        <span class="chip">⏱ 9 min</span> <span class="chip warn">Intermedio</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ DESCARGABLES ============ -->
    <section class="section pt-0" id="recursos">
        <div class="container">
            <span class="eyebrow"><i class="fa-solid fa-download"></i> Kit de trabajo</span>
            <h2 class="section-title mt-3">Recursos descargables</h2>
            <p class="section-sub">Plantillas, checklists y material listo para usar con tu equipo.</p>
            <div class="row g-4">
                <div class="col-6 col-lg-3 reveal">
                    <div class="card-gj res-card tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-file-pdf"></i></div>
                        <h5>Guía de buenas prácticas</h5>
                        <p class="mb-3">PDF · 2.4 MB</p>
                        <button class="btn-outline-gj w-100"
                            data-toast="Descargando: Guía de buenas prácticas">Descargar</button>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="60">
                    <div class="card-gj res-card tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-file-csv"></i></div>
                        <h5>Plantilla de contactos</h5>
                        <p class="mb-3">CSV · 12 KB</p>
                        <button class="btn-outline-gj w-100"
                            data-toast="Descargando: Plantilla de contactos">Descargar</button>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="120">
                    <div class="card-gj res-card tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-list-check"></i>
                        </div>
                        <h5>Checklist de campaña</h5>
                        <p class="mb-3">PDF · 640 KB</p>
                        <button class="btn-outline-gj w-100"
                            data-toast="Descargando: Checklist de campaña">Descargar</button>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-delay="180">
                    <div class="card-gj res-card tilt">
                        <div class="ico" style="background:var(--grad)"><i class="fa-solid fa-comments"></i></div>
                        <h5>Guiones de chatbot</h5>
                        <p class="mb-3">DOCX · 380 KB</p>
                        <button class="btn-outline-gj w-100"
                            data-toast="Descargando: Guiones de chatbot">Descargar</button>
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
                    <span class="eyebrow"><i class="fa-solid fa-circle-question"></i> Dudas frecuentes</span>
                    <h2 class="section-title mt-3">Preguntas frecuentes</h2>
                    <p class="section-sub">Las respuestas que más consulta nuestra comunidad. ¿No encuentras la tuya?
                        Escríbenos desde el centro de soporte.</p>
                    <a href="#soporte" class="btn-gj">Contactar soporte</a>
                </div>
                <div class="col-lg-7">
                    <div class="accordion" id="faqAcc">
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button" data-bs-toggle="collapse"
                                    data-bs-target="#f1">¿Cómo conecto mi número de WhatsApp?</button></h2>
                            <div id="f1" class="accordion-collapse collapse show" data-bs-parent="#faqAcc">
                                <div class="accordion-body">Desde <b>Administración → Canales</b> selecciona “Conectar
                                    WhatsApp”, verifica tu número de empresa y completa la validación de Meta. El
                                    proceso suele tardar entre 5 y 20 minutos.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#f2">¿Por qué mi plantilla fue
                                    rechazada?</button></h2>
                            <div id="f2" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">Las causas más comunes son categorías incorrectas,
                                    variables sin ejemplo o contenido promocional en plantillas de servicio. Revisa la
                                    guía “Evita el rechazo de plantillas”.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#f3">¿Cuántos contactos puedo
                                    importar?</button></h2>
                            <div id="f3" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">Depende de tu plan. En todos los planes puedes importar por
                                    lotes de hasta 50.000 registros por archivo CSV, con deduplicación automática por
                                    número.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#f4">¿El asistente IA responde
                                    solo?</button></h2>
                            <div id="f4" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">Sí, dentro de los límites que definas. Puedes configurar
                                    tono, temas permitidos y una regla de transferencia a un agente humano cuando no
                                    logre resolver la conversación.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#f5">¿Puedo integrar mi CRM?</button>
                            </h2>
                            <div id="f5" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">Sí. Usa webhooks entrantes y salientes o conecta n8n para
                                    sincronizar contactos, etiquetas y eventos de conversación en tiempo real.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#f6">¿Los cursos entregan
                                    certificado?</button></h2>
                            <div id="f6" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">Al completar el 100% de las lecciones y aprobar la
                                    evaluación final recibirás un certificado digital verificable con tu nombre y la
                                    fecha de finalización.</div>
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
            <span class="eyebrow"><i class="fa-solid fa-bell"></i> Changelog</span>
            <h2 class="section-title mt-3">Novedades</h2>
            <p class="section-sub">Lo último que llegó a la plataforma y al Centro de Recursos.</p>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="news-card tilt"><span class="bar"></span>
                        <span class="chip new">Nuevo</span>
                        <h5 class="mt-3 fw-bold">Calendario de campañas</h5>
                        <p class="text-secondary mb-3">Programa y arrastra tus campañas en vistas de mes, semana y día.
                        </p>
                        <small class="text-secondary"><i class="fa-regular fa-calendar me-1"></i>20 de agosto,
                            2026</small>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="80">
                    <div class="news-card tilt"><span class="bar"></span>
                        <span class="chip">Mejora</span>
                        <h5 class="mt-3 fw-bold">Asistentes IA más precisos</h5>
                        <p class="text-secondary mb-3">Nueva base de conocimiento con búsqueda semántica y citas de
                            origen.</p>
                        <small class="text-secondary"><i class="fa-regular fa-calendar me-1"></i>12 de agosto,
                            2026</small>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" data-delay="160">
                    <div class="news-card tilt"><span class="bar"></span>
                        <span class="chip warn">Beta</span>
                        <h5 class="mt-3 fw-bold">Flujos visuales de chatbot</h5>
                        <p class="text-secondary mb-3">Diseña la atención con nodos arrastrables e integraciones de IA.
                        </p>
                        <small class="text-secondary"><i class="fa-regular fa-calendar me-1"></i>2 de agosto,
                            2026</small>
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
                            <i class="fa-solid fa-headset"></i> Centro de soporte</span>
                        <h2 class="mt-3 fw-bold">¿Necesitas ayuda de una persona real?</h2>
                        <p>Nuestro equipo responde en menos de 2 horas hábiles. Chatea con nosotros, abre un ticket o
                            agenda una sesión guiada con un especialista GIJAC.</p>
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a type="button" href="https://wa.me/573171789584" class="btn-gj" data-toast="Abriendo chat con soporte">
                                <i class="fa-brands fa-whatsapp me-2"></i>
                                Chatear por WhatsApp
                            </a>
                            <a type="button" href="{{ route('tickets.index') }}" class="btn-ghost-r" data-toast="Formulario de ticket abierto">
                                <i class="fa-regular fa-envelope me-2"></i>
                                Abrir un ticket
                            </a>
                            <a type="button" href="{{ route('contactarnos') }}" class="btn-ghost-r" data-toast="Agenda de sesión guiada">
                                <i class="fa-regular fa-calendar-check me-2"></i>
                                Agendar sesión
                            </a>
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
                        <div class="d-flex gap-2 mb-2"><span class="chip" id="videoCat">Categoría</span><span
                                class="chip dark" id="videoDur">00:00</span></div>
                        <h4 class="fw-bold" id="videoTitle">Título</h4>
                        <p class="text-secondary mb-4" id="videoDesc">Descripción</p>
                        <div class="d-flex gap-2">
                            <button class="btn-gj" data-toast="Reproduciendo video">Reproducir</button>
                            <button class="btn-outline-gj" data-bs-dismiss="modal">Cerrar</button>
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
