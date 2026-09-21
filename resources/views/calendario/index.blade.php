@extends('layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ mix('css/calendario.css') }}">
@endsection

@section('content')
    <main>

        <!-- KPI -->
        <div class="kpi-grid">
            <div class="kpi">
                <div class="kpi-inner">
                    <div>
                        <div class="kpi-label">{{ __('Campañas Programadas') }}</div>
                        <div class="kpi-value" data-count="{{ $campanas_programadas ?? 0 }}">0</div>
                        {{-- <div class="kpi-delta">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                            {{ __('+12% vs semana pasada') }}
                        </div> --}}
                    </div>
                    <div class="kpi-ico">
                        <i class="fa-solid fa-calendar-plus"></i>
                    </div>
                </div>
                <svg class="kpi-spark" viewBox="0 0 200 26" preserveAspectRatio="none">
                    <path d="M0,20 L30,15 L60,18 L90,10 L120,14 L150,6 L180,10 L200,4" fill="none" stroke="#1E6F78"
                        stroke-width="2" />
                </svg>
            </div>
            <div class="kpi">
                <div class="kpi-inner">
                    <div>
                        <div class="kpi-label">{{ __('Campañas Enviadas') }}</div>
                        <div class="kpi-value" data-count="{{ $campanas_enviadas ?? 0 }}">0</div>
                        {{-- <div class="kpi-delta">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                            {{ __('+8% este mes') }}
                        </div> --}}
                    </div>
                    <div class="kpi-ico ok">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                </div>
                <svg class="kpi-spark" viewBox="0 0 200 26" preserveAspectRatio="none">
                    <path d="M0,22 L30,18 L60,20 L90,12 L120,16 L150,8 L180,6 L200,3" fill="none" stroke="#22C55E"
                        stroke-width="2" />
                </svg>
            </div>
            <div class="kpi">
                <div class="kpi-inner">
                    <div>
                        <div class="kpi-label">{{ __('Mensajes Enviados') }}</div>
                        <div class="kpi-value" data-count="{{ $mensajes_enviados ?? 0 }}">0</div>
                        {{-- <div class="kpi-delta down">
                            <i class="fa-solid fa-arrow-trend-down"></i>
                            {{ __('-3% hoy') }}
                        </div> --}}
                    </div>
                    <div class="kpi-ico warn">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                </div>
                <svg class="kpi-spark" viewBox="0 0 200 26" preserveAspectRatio="none">
                    <path d="M0,10 L30,14 L60,8 L90,16 L120,10 L150,14 L180,18 L200,20" fill="none" stroke="#F59E0B"
                        stroke-width="2" />
                </svg>
            </div>
            <div class="kpi">
                <div class="kpi-inner">
                    <div>
                        <div class="kpi-label">{{ __('Efectividad') }}</div>
                        <div class="kpi-value"><span data-count="{{ $cantidad_efectividad ?? 0 }}">0</span>%</div>
                        {{-- <div class="kpi-delta">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                            {{ __('+1.2% este mes') }}
                        </div> --}}
                    </div>
                    <div class="kpi-ico dark">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
                <svg class="kpi-spark" viewBox="0 0 200 26" preserveAspectRatio="none">
                    <path d="M0,18 L30,14 L60,16 L90,10 L120,12 L150,6 L180,8 L200,4" fill="none" stroke="#145962"
                        stroke-width="2" />
                </svg>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="filter">
                <select name="etiqueta_id" id="selectEtiqueta" class="form-control" data-control="select2"
                    data-placeholder="{{ __('Seleccione la etiqueta') }}" data-allow-clear="true">
                    <option value=""></option>
                    @foreach ($etiquetas as $item)
                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter">
                <select name="estado" id="selectEstado" class="form-control" data-control="select2"
                    data-placeholder="{{ __('Seleccione el estado') }}" data-allow-clear="true">
                    <option value=""></option>
                     @foreach ($estados as $item)
                        <option value="{{ $item->codigo }}">{{ $item->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter">
                <select name="tipo" id="selectTipos" class="form-control" data-control="select2"
                    data-placeholder="{{ __('Seleccione el tipo') }}" data-allow-clear="true">
                    <option value=""></option>
                     @foreach ($tipos as $item)
                        <option value="{{ $item->codigo }}">{{ $item->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter">
                <select name="categoria" id="selectCategorias" class="form-control" data-control="select2"
                    data-placeholder="{{ __('Seleccione la categoria') }}" data-allow-clear="true">
                    <option value=""></option>
                     @foreach ($categorias as $item)
                        <option value="{{ $item->codigo }}">{{ $item->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter">
                <select name="responsable_id" id="selectResponsable" class="form-control" data-control="select2"
                    data-placeholder="{{ __('Seleccione el responsable') }}" data-allow-clear="true">
                    <option value=""></option>
                    @foreach ($usuarios as $item)
                        <option value="{{ $item->uuid }}">{{ $item->nombre_completo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter">
                <input type="date" placeholder="{{ __('Seleccione la fecha') }}" id="kt_datepicker_1"/>
            </div>
            <button class="btn-clear btnAplicar">
                <i class="fa-solid fa-filter"></i>
                {{ __('Aplicar') }}
            </button>
        </div>

        <!-- Work -->
        <div class="work">
            <div class="cal-card">
                <div class="cal-toolbar">
                    <div class="cal-title"><i class="fa-solid fa-calendar-days" style="color:var(--primary)"></i><span
                            id="calTitle">—</span></div>
                    <div class="cal-nav">
                        <button id="prev" title="Anterior"><i class="fa-solid fa-chevron-left"></i></button>
                        <button id="today" class="today">{{ __('Hoy') }}</button>
                        <button id="next" title="Siguiente"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                    <div class="cal-views">
                        <button data-view="dayGridMonth" class="active">{{ __('Mes') }}</button>
                        <button data-view="timeGridWeek">{{ __('Semana') }}</button>
                        <button data-view="timeGridDay">{{ __('Día') }}</button>
                        <button data-view="listWeek">{{ __('Lista') }}</button>
                    </div>
                </div>
                <div id="calendar"></div>
            </div>

            <!-- Right sidebar -->
            <aside class="side stagger">
                <div class="panel">
                    <h4>
                        <span>
                            <i class="fa-solid fa-bolt" style="color:var(--warning)"></i>
                            {{ __('Hoy') }}
                        </span>
                        <span class="cnt" id="todayCount">0</span>
                    </h4>
                    <div id="todayAgenda"></div>
                </div>
                <div class="panel">
                    <h4>
                        <span>
                            <i class="fa-solid fa-forward" style="color:var(--primary)"></i>
                            {{ __('Próximas') }}
                        </span>
                        <span class="cnt" id="upcomingCount">0</span>
                    </h4>
                    <div id="upcomingAgenda"></div>
                </div>
                <div class="panel">
                    <h4>
                        <span>
                            <i class="fa-solid fa-palette" style="color:var(--accent)"></i>
                            {{ __('Estados') }}
                        </span>
                    </h4>
                    <div class="legend">
                        @foreach ($estados as $item)
                            <div class="lg">
                                <span class="badge badge-{{ $item->color }}">&nbsp;&nbsp;</span>
                                {{ __($item?->nombre) }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <!-- Tooltip -->
    <div class="cal-tt" id="tooltip">
        <span class="tt-badge" id="ttBadge">{{ __('Programada') }}</span>
        <div class="tt-name" id="ttName">—</div>
        <div class="tt-co" id="ttCo">—</div>
        <div class="tt-row">
            <span>{{ __('Fecha') }}</span>
            <span id="ttDate">—</span>
        </div>
        <div class="tt-row">
            <span>{{ __('Mensajes') }}</span>
            <span id="ttMsgs">—</span>
        </div>
        <div class="tt-row">
            <span>{{ __('Audiencia estimada') }}</span>
            <span id="ttAud">—</span>
        </div>
        <div class="tt-row">
            <span>{{ __('Responsable') }}</span>
            <span id="ttOwner">—</span>
        </div>
        <div class="tt-actions">
            <button class="btnVer" data-registro="">
                <i class="fa-solid fa-eye"></i>
                {{ __('Ver') }}
            </button>
            {{-- <button>
                <i class="fa-solid fa-pen"></i>
                {{ __('Editar') }}
            </button> --}}
            {{-- <button>
                <i class="fa-solid fa-clone"></i>
                {{ __('Duplicar') }}
            </button> --}}
            {{-- <button class="danger">
                <i class="fa-solid fa-ban"></i>
                {{ __('Cancelar') }}
            </button> --}}
        </div>
    </div>

    <!-- Drawer -->
    <div class="drawer-overlay" id="drawerOverlay"></div>
    <aside class="drawer" id="drawer" aria-hidden="true">
        <div class="drawer-head">
            <button type="button" class="drawer-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <span class="tt-badge" id="dBadge" style="background:rgba(255,255,255,.25)">{{ __('Programada') }}</span>
            <h3 id="dName" class="text-white">{{ __('Campaña') }}</h3>
            <p id="dCo">{{ __('Empresa') }}</p>
        </div>
        <div class="drawer-body">
            <div class="sec">
                <h5>{{ __('Detalles de la Campaña') }}</h5>
                <div class="kv">
                    <span>{{ __('Fecha programada') }}</span>
                    <span id="dDate">—</span>
                </div>
                <div class="kv">
                    <span>{{ __('Canal') }}</span>
                    <span>{{ __('WhatsApp Cloud API') }}</span>
                </div>
                <div class="kv">
                    <span>{{ __('Plantilla') }}</span>
                    <span id="dTpl">{{ __('promo_black_friday_v3') }}</span>
                </div>
                <div class="kv">
                    <span>{{ __('Responsable') }}</span>
                    <span id="dOwner">—</span>
                </div>
                <div class="kv">
                    <span>{{ __('Segmento') }}</span>
                    <span id="dSeg">{{ __('Clientes VIP · LATAM') }}</span>
                </div>
            </div>

            <div class="sec">
                <h5>{{ __('Vista Previa del Mensaje') }}</h5>
                <div class="msg-box">
                    Hola { nombre } 👋, tenemos una oferta exclusiva para ti: <b>{{ __('50% de descuento') }}</b> {{ __('en toda la tienda durante 24 horas. Usa el código') }} <b>{{ __('VIP50') }}</b> {{ __('antes de que termine. Ver catálogo → gijac.co/promo') }}
                </div>
            </div>

            <div class="sec">
                <h5>{{ __('Estadísticas de Entrega') }}</h5>
                <div class="stat-grid">
                    <div class="stat-mini">
                        <div class="v" id="sMsgs">—</div>
                        <div class="l">{{ __('Mensajes') }}</div>
                    </div>
                    <div class="stat-mini">
                        <div class="v" id="sDel">—</div>
                        <div class="l">{{ __('Entregados') }}</div>
                    </div>
                    <div class="stat-mini">
                        <div class="v" id="sOpen">—</div>
                        <div class="l">{{ __('Aperturas') }}</div>
                    </div>
                </div>
            </div>

            {{-- <div class="sec">
                <h5>{{ __('Archivos & Adjuntos') }}</h5>
                <div class="file-row"><i class="fa-solid fa-image"></i>
                    <div>{{ __('banner-black-friday.jpg') }}<div style="font-size:11px;color:var(--muted)">{{ __('1.2 MB · Imagen') }}</div>
                    </div>
                </div>
                <div class="file-row"><i class="fa-solid fa-file-pdf"></i>
                    <div>{{ __('catalogo-vip.pdf') }}<div style="font-size:11px;color:var(--muted)">{{ __('3.8 MB · PDF') }}</div>
                    </div>
                </div>
            </div> --}}

            <div class="sec">
                <h5>{{ __('Destinatarios') }}</h5>
                <div class="kv">
                    <span>{{ __('Audiencia estimada') }}</span>
                    <span id="dAud">—</span>
                </div>
                {{-- <div class="kv"><span>{{ __('Excluidos') }}</span><span>{{ __('842 contactos (opt-out)') }}</span></div>
                <div class="kv"><span>{{ __('Lista') }}</span><span>{{ __('vip_latam_q4_2026.csv') }}</span></div> --}}
            </div>
        </div>
        {{-- <div class="drawer-foot">
            <button class="btn-ghost"><i class="fa-solid fa-pen"></i> {{ __('Editar') }}</button>
            <button class="btn-ghost"><i class="fa-solid fa-clone"></i> {{ __('Duplicar') }}</button>
            <button class="btn-brand" style="flex:1.4"><i class="fa-solid fa-paper-plane"></i> {{ __('Enviar ahora') }}</button>
        </div> --}}
    </aside>
@endsection

@section('modal')
@endsection

@section('scripts')
    <script src="{{ mix('js/calendario/principal.js') }}"></script>
@endsection
