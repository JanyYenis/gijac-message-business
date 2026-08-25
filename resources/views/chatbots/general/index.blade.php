@extends('layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ mix('/css/gmb.css') }}">
    <style>
        :root {
            --primary: #1E6F78;
            --accent: #2C8F99;
            --wa: #25D366;
            --wa-deep: #128C7E;
            --blue: #2563EB;
            --blue-soft: rgba(37, 99, 235, .10);
            --bg: #F7F9FA;
            --card: #FFFFFF;
            --ink: #0F172A;
            --muted: #64748B;
            --soft: #94A3B8;
            --border: #E9EDF2;
            --green: #16A34A;
            --amber: #D97706;
            --red: #DC2626;
            --radius: 18px;
            --shadow-xs: 0 1px 2px rgba(15, 23, 42, .04);
            --shadow-sm: 0 1px 3px rgba(15, 23, 42, .05), 0 1px 2px rgba(15, 23, 42, .03);
            --shadow-md: 0 12px 28px -18px rgba(15, 23, 42, .30), 0 2px 8px -4px rgba(15, 23, 42, .06);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        .display-font {
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            letter-spacing: -.02em;
            margin: 0
        }

        .muted {
            color: var(--muted)
        }

        .page {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header */
        .brand-mark {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 20px;
            box-shadow: var(--shadow-sm);
            flex: none
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            border-radius: 999px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600
        }

        .pill-on {
            background: rgba(22, 163, 74, .10);
            color: var(--green)
        }

        .pill-off {
            background: #F1F5F9;
            color: var(--muted)
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .15)
        }

        .btn-primary-g {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 12px;
            padding: 10px 18px;
            box-shadow: 0 8px 18px -10px rgba(30, 111, 120, .8)
        }

        .btn-primary-g:hover {
            filter: brightness(1.06);
            color: #fff
        }

        .btn-primary-g:disabled {
            opacity: .55;
            box-shadow: none
        }

        .btn-ghost {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-weight: 500;
            color: #334155;
            padding: 9px 15px
        }

        .btn-ghost:hover {
            border-color: #cfd8e3;
            background: #fbfdfe;
            color: var(--ink)
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 6px;
            overflow-x: auto;
            padding: 6px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: var(--shadow-xs);
            position: sticky;
            top: 12px;
            z-index: 30;
            margin-bottom: 20px;
            scrollbar-width: none
        }

        .tabs::-webkit-scrollbar {
            display: none
        }

        .tabs a {
            white-space: nowrap;
            text-decoration: none;
            color: var(--muted);
            font-weight: 500;
            font-size: 13.5px;
            padding: 8px 14px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: .18s
        }

        .tabs a:hover {
            background: #F4F7F8;
            color: var(--ink)
        }

        .tabs a.active {
            background: var(--blue-soft);
            color: var(--blue);
            font-weight: 600
        }

        /* Cards */
        .card-s {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-xs);
            padding: 22px;
            margin-bottom: 20px;
            scroll-margin-top: 90px
        }

        .card-s:hover {
            box-shadow: var(--shadow-sm)
        }

        .sec-head {
            display: flex;
            gap: 13px;
            align-items: flex-start;
            margin-bottom: 18px
        }

        .sec-ico {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: var(--blue-soft);
            color: var(--blue);
            flex: none;
            font-size: 15px
        }

        .sec-title {
            font-size: 16.5px;
            font-weight: 600
        }

        .sec-sub {
            font-size: 13px;
            color: var(--muted);
            margin-top: 3px;
            max-width: 62ch;
            line-height: 1.55
        }

        .sub-card {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px;
            background: #FCFDFE
        }

        .label {
            font-size: 12.5px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 7px;
            display: block
        }

        .hint {
            font-size: 12px;
            color: var(--soft);
            margin-top: 6px;
            line-height: 1.5
        }

        .form-control,
        .form-select {
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 13.5px;
            padding: 10px 12px;
            color: var(--ink);
            background-color: #FCFDFE
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(44, 143, 153, .12);
            background: #fff
        }

        textarea.form-control {
            line-height: 1.6;
            resize: vertical
        }

        /* Switch */
        .sw {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none
        }

        .sw input {
            display: none
        }

        .sw .track {
            width: 42px;
            height: 24px;
            border-radius: 999px;
            background: #CBD5E1;
            position: relative;
            transition: .22s;
            flex: none
        }

        .sw .track::after {
            content: "";
            position: absolute;
            top: 3px;
            left: 3px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fff;
            transition: .22s cubic-bezier(.2, .8, .2, 1);
            box-shadow: 0 1px 3px rgba(0, 0, 0, .2)
        }

        .sw input:checked+.track {
            background: var(--green)
        }

        .sw input:checked+.track::after {
            transform: translateX(18px)
        }

        .sw .txt {
            font-size: 13.5px;
            font-weight: 500
        }

        .sw.big .track {
            width: 56px;
            height: 31px
        }

        .sw.big .track::after {
            width: 25px;
            height: 25px
        }

        .sw.big input:checked+.track::after {
            transform: translateX(25px)
        }

        /* Days */
        .days {
            display: flex;
            gap: 9px;
            flex-wrap: wrap
        }

        .day {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: #fff;
            font-weight: 600;
            color: var(--muted);
            transition: .18s;
            font-size: 14px
        }

        .day:hover {
            border-color: #cfd8e3;
            color: var(--ink)
        }

        .day.on {
            background: var(--blue);
            border-color: var(--blue);
            color: #fff;
            box-shadow: 0 8px 16px -10px rgba(37, 99, 235, .9)
        }

        .quick {
            border: 1px solid var(--border);
            background: #fff;
            border-radius: 999px;
            padding: 6px 14px;
            font-size: 12.5px;
            color: var(--muted);
            transition: .18s
        }

        .quick:hover {
            border-color: var(--accent);
            color: var(--primary)
        }

        .range-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px
        }

        .range-row .form-control {
            max-width: 150px
        }

        .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--soft);
            display: grid;
            place-items: center;
            transition: .18s;
            flex: none
        }

        .icon-btn:hover {
            color: var(--red);
            border-color: rgba(220, 38, 38, .3);
            background: rgba(220, 38, 38, .05)
        }

        /* Chips */
        .tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(37, 99, 235, .07);
            border: 1px solid rgba(37, 99, 235, .18);
            color: #1D4ED8;
            border-radius: 999px;
            padding: 6px 8px 6px 14px;
            font-size: 13px;
            font-weight: 500
        }

        .tag button {
            border: none;
            background: rgba(37, 99, 235, .12);
            color: #1D4ED8;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            font-size: 10px;
            line-height: 1;
            display: grid;
            place-items: center;
            transition: .16s
        }

        .tag button:hover {
            background: var(--red);
            color: #fff
        }

        .tag.new {
            animation: pop .28s ease
        }

        @keyframes pop {
            from {
                transform: scale(.8);
                opacity: 0
            }

            to {
                transform: scale(1);
                opacity: 1
            }
        }

        /* Radios */
        .opt {
            display: flex;
            gap: 11px;
            align-items: flex-start;
            border: 1px solid var(--border);
            border-radius: 13px;
            padding: 12px 14px;
            background: #fff;
            cursor: pointer;
            transition: .18s;
            margin-bottom: 9px
        }

        .opt:hover {
            border-color: #cfd8e3;
            background: #FCFDFE
        }

        .opt input {
            margin-top: 2px;
            accent-color: var(--blue);
            flex: none;
            width: 16px;
            height: 16px
        }

        .opt.sel {
            border-color: var(--blue);
            background: var(--blue-soft)
        }

        .opt .t {
            font-size: 13.5px;
            font-weight: 500
        }

        .opt .d {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px
        }

        /* Engines drag */
        .engine {
            display: flex;
            align-items: center;
            gap: 13px;
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 14px;
            background: #fff;
            margin-bottom: 11px;
            cursor: grab;
            transition: box-shadow .2s, transform .2s, border-color .2s
        }

        .engine:hover {
            box-shadow: var(--shadow-md);
            border-color: #dfe6ee
        }

        .engine.dragging {
            opacity: .45;
            transform: scale(.99)
        }

        .engine.over {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px var(--blue-soft)
        }

        .handle {
            color: var(--soft);
            font-size: 15px;
            cursor: grab
        }

        .eng-num {
            width: 28px;
            height: 28px;
            border-radius: 9px;
            background: #F1F5F9;
            color: var(--muted);
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 12.5px;
            flex: none
        }

        .eng-ico {
            width: 42px;
            height: 42px;
            border-radius: 13px;
            display: grid;
            place-items: center;
            font-size: 16px;
            flex: none
        }

        .badge-s {
            border-radius: 999px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .02em
        }

        .flowmap {
            background: #FCFDFE;
            border: 1px dashed #D7E0E8;
            border-radius: 14px;
            padding: 18px;
            text-align: center
        }

        .flow-node {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 11px;
            padding: 8px 15px;
            font-size: 13px;
            font-weight: 500;
            box-shadow: var(--shadow-xs)
        }

        .flow-arrow {
            color: var(--soft);
            font-size: 12px;
            margin: 7px 0
        }

        /* Sidebar */
        .side {
            position: sticky;
            top: 78px
        }

        .status-card {
            background: linear-gradient(160deg, #0F3F45, #1E6F78 60%, #2C8F99);
            color: #fff;
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow-md);
            margin-bottom: 18px
        }

        .status-card .row-i {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 9px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .12);
            font-size: 13px
        }

        .status-card .row-i:last-child {
            border-bottom: none
        }

        .status-card .k {
            color: rgba(255, 255, 255, .72)
        }

        .status-card .v {
            font-weight: 600;
            text-align: right
        }

        .qa {
            display: flex;
            align-items: center;
            gap: 11px;
            width: 100%;
            border: 1px solid var(--border);
            background: #fff;
            border-radius: 13px;
            padding: 11px 13px;
            font-size: 13.5px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 9px;
            transition: .18s;
            text-align: left
        }

        .qa:hover {
            border-color: var(--accent);
            color: var(--primary);
            background: #FCFDFE
        }

        .qa i {
            width: 20px;
            color: var(--accent)
        }

        /* Save bar */
        .savebar {
            position: fixed;
            left: 50%;
            transform: translate(-50%, 140%);
            bottom: 20px;
            z-index: 60;
            background: #0F172A;
            color: #fff;
            border-radius: 16px;
            padding: 11px 13px 11px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 22px 45px -18px rgba(15, 23, 42, .7);
            transition: transform .35s cubic-bezier(.2, .9, .3, 1);
            max-width: 94vw
        }

        .savebar.show {
            transform: translate(-50%, 0)
        }

        .savebar .warn {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 13.5px;
            font-weight: 500;
            white-space: nowrap
        }

        .savebar .warn i {
            color: #FBBF24
        }

        .savebar .btn-discard {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, .22);
            color: #fff;
            border-radius: 11px;
            padding: 8px 14px;
            font-size: 13px
        }

        .savebar .btn-discard:hover {
            background: rgba(255, 255, 255, .08);
            color: #fff
        }

        .toast-w {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 80;
            display: flex;
            flex-direction: column;
            gap: 10px
        }

        .toast-i {
            background: #fff;
            border: 1px solid var(--border);
            border-left: 4px solid var(--green);
            border-radius: 13px;
            padding: 13px 17px;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 11px;
            font-size: 13.5px;
            font-weight: 500;
            animation: slidein .3s ease
        }

        @keyframes slidein {
            from {
                transform: translateX(30px);
                opacity: 0
            }

            to {
                transform: none;
                opacity: 1
            }
        }

        .tip {
            color: var(--soft);
            cursor: help;
            font-size: 12px
        }

        .counter {
            font-size: 11.5px;
            color: var(--soft)
        }

        .counter.warn {
            color: var(--amber)
        }

        input[type=range] {
            accent-color: var(--blue);
            width: 100%
        }

        .collapse-soft {
            overflow: hidden
        }

        @media(max-width:991px) {
            .side {
                position: static
            }
        }
    </style>
@endsection

@section('content')
    <form id="">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h1 class="text-white">
                        <i class="fas fa-robot"></i>
                        Configuración del Chatbot
                    </h1>
                    <p class="subtitle mb-0">Administra y configura los diferentes tipos de automatización
                        disponibles para tu negocio.</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <span class="badge-available" id="headState">
                        <i class="bi bi-check2-circle me-1 text-white"></i>Activo
                    </span>
                    <button type="button" class="btn btn-light-wa" id="">
                        <i class="bi bi-save text-primary fs-1"></i>
                        Guardar cambios
                    </button>
                </div>
            </div>
        </div>
        <div class="page">
            <!-- TABS -->
            <nav class="tabs" id="tabs">
                <a href="#sec-general" class="active">
                    <i class="fa-solid fa-sliders"></i>
                    General
                </a>
                <a href="#sec-horario">
                    <i class="fa-regular fa-clock"></i>
                    Horario
                </a>
                <a href="#sec-palabras">
                    <i class="fa-solid fa-tags"></i>
                    Palabras clave
                </a>
                <a href="#sec-mensajes">
                    <i class="fa-regular fa-comment-dots"></i>
                    Mensajes
                </a>
                <a href="#sec-flujo">
                    <i class="fa-solid fa-diagram-project"></i>
                    Flujo de atención
                </a>
                <a href="#sec-transfer">
                    <i class="fa-solid fa-headset"></i>
                    Transferencias
                </a>
                <a href="#sec-avanzado">
                    <i class="fa-solid fa-gears"></i>
                    Avanzado
                </a>
            </nav>

            <div class="row g-4">
                <!-- MAIN COLUMN -->
                <div class="col-lg-8">

                    <!-- GENERAL -->
                    <section class="card-s" id="sec-general">
                        <div class="sec-head">
                            <div class="sec-ico">
                                <i class="fa-solid fa-sliders"></i>
                            </div>
                            <div>
                                <div class="sec-title">General</div>
                                <div class="sec-sub">
                                    Activa o desactiva tu chatbot y define cómo se presenta ante tus clientes.
                                </div>
                            </div>
                        </div>
                        <div class="sub-card d-flex align-items-center justify-content-between gap-3 flex-wrap">
                            <div>
                                <div style="font-weight:600">
                                    Chatbot activo
                                </div>
                                <div class="hint">
                                    Cuando está desactivado, todas las conversaciones llegan directamente a tus agentes.
                                </div>
                            </div>
                            <label class="sw big">
                                <input type="checkbox" class="cfg" id="botActive" checked value="1" name="activo">
                                <span class="track"></span>
                            </label>
                        </div>
                    </section>

                    <!-- HORARIO -->
                    <section class="card-s" id="sec-horario">
                        <div class="sec-head">
                            <div class="sec-ico">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <div>
                                <div class="sec-title">Horario de atención</div>
                                <div class="sec-sub">
                                    Define los días y horarios en los que el chatbot estará disponible para atender conversaciones.
                                </div>
                            </div>
                        </div>

                        <div class="sub-card mb-3 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                            <div>
                                <div style="font-weight:600">Aplicar horario de atención</div>
                                <div class="hint" id="scheduleHint">
                                    El chatbot solo atenderá dentro de los días y horas configurados.
                                </div>
                            </div>
                            <label class="sw">
                                <input type="checkbox" class="cfg" id="useSchedule" checked value="1" name="respetar_horario">
                                <span class="track"></span>
                            </label>
                        </div>

                        <div id="scheduleBody">
                            <label class="label">Días de atención</label>
                            <div class="days mb-2" id="days"></div>
                            <div class="d-flex gap-2 flex-wrap mb-4">
                                <button class="quick" data-preset="all">Todos los días</button>
                                <button class="quick" data-preset="work">Solo días laborales</button>
                            </div>

                            <label class="label">Franjas horarias <i class="fa-regular fa-circle-question tip"
                                    title="Puedes definir varios intervalos, por ejemplo mañana y tarde."></i></label>
                            <div id="ranges"></div>
                            <button class="btn btn-ghost btn-sm mt-1" id="addRange"><i
                                    class="fa-solid fa-plus me-2"></i>Agregar horario</button>
                        </div>
                    </section>

                    <!-- PALABRAS CLAVE -->
                    <section class="card-s" id="sec-palabras">
                        <div class="sec-head">
                            <div class="sec-ico"><i class="fa-solid fa-tags"></i></div>
                            <div>
                                <div class="sec-title">Palabras clave</div>
                                <div class="sec-sub">Si el usuario utiliza alguna de estas palabras o expresiones, la
                                    conversación puede ser transferida automáticamente a un agente humano.</div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            <input class="form-control" id="kwInput" placeholder="Escribe una palabra o frase..."
                                style="max-width:340px" />
                            <button class="btn btn-ghost" id="kwAdd"><i
                                    class="fa-solid fa-plus me-2"></i>Agregar</button>
                        </div>
                        <div class="d-flex gap-2 flex-wrap mb-3" id="kwList"></div>
                        <div class="hint mb-4"><i class="fa-solid fa-circle-info me-1"></i>Las palabras clave se pueden
                            detectar independientemente de mayúsculas y minúsculas.</div>

                        <label class="label">Acción al detectar una palabra clave</label>
                        <div id="kwAction">
                            <label class="opt sel"><input type="radio" name="kwact" value="now" class="cfg"
                                    checked><span><span class="t">Transferir inmediatamente a un agente</span><span
                                        class="d">La conversación pasa al primer asesor
                                        disponible.</span></span></label>
                            <label class="opt"><input type="radio" name="kwact" value="msg"
                                    class="cfg"><span><span class="t">Enviar mensaje y transferir</span><span
                                        class="d">Avisa al usuario antes de pasar con un asesor.</span></span></label>
                            <label class="opt"><input type="radio" name="kwact" value="ask"
                                    class="cfg"><span><span class="t">Preguntar si desea hablar con un
                                        agente</span><span class="d">El usuario confirma antes de la
                                        transferencia.</span></span></label>
                        </div>

                        <div class="sub-card d-flex align-items-center justify-content-between gap-3 flex-wrap mt-3">
                            <div>
                                <div style="font-weight:600">Coincidencia aproximada</div>
                                <div class="hint">Detectar palabras similares o variaciones (por ejemplo "cotizacion" o
                                    "cotizar").</div>
                            </div>
                            <label class="sw"><input type="checkbox" class="cfg" id="fuzzy" checked><span
                                    class="track"></span></label>
                        </div>
                    </section>

                    <!-- MENSAJES -->
                    <section class="card-s" id="sec-mensajes">
                        <div class="sec-head">
                            <div class="sec-ico"><i class="fa-regular fa-comment-dots"></i></div>
                            <div>
                                <div class="sec-title">Mensajes especiales</div>
                                <div class="sec-sub">Configura los mensajes que el chatbot utilizará en situaciones
                                    especiales.</div>
                            </div>
                        </div>
                        <div id="msgCards"></div>
                    </section>

                    <!-- FLUJO -->
                    <section class="card-s" id="sec-flujo">
                        <div class="sec-head">
                            <div class="sec-ico"><i class="fa-solid fa-diagram-project"></i></div>
                            <div>
                                <div class="sec-title">Flujo de atención</div>
                                <div class="sec-sub">Define qué sistema tendrá prioridad para procesar las conversaciones.
                                    Arrastra los elementos para definir el orden de prioridad.</div>
                            </div>
                        </div>
                        <div id="engines"></div>
                        <div class="mt-4">
                            <label class="label">Orden actual</label>
                            <div class="flowmap" id="flowmap"></div>
                        </div>
                    </section>

                    <!-- TRANSFERENCIAS -->
                    <section class="card-s" id="sec-transfer">
                        <div class="sec-head">
                            <div class="sec-ico"><i class="fa-solid fa-headset"></i></div>
                            <div>
                                <div class="sec-title">Transferencia a agente</div>
                                <div class="sec-sub">Controla cuándo y cómo una conversación pasa del chatbot a una persona
                                    de tu equipo.</div>
                            </div>
                        </div>

                        <div class="sub-card d-flex align-items-center justify-content-between gap-3 flex-wrap mb-4">
                            <div>
                                <div style="font-weight:600">¿Permitir transferencia a un agente?</div>
                                <div class="hint">Si se desactiva, el chatbot atenderá siempre sin intervención humana.
                                </div>
                            </div>
                            <label class="sw"><input type="checkbox" class="cfg" id="allowTransfer" checked><span
                                    class="track"></span></label>
                        </div>

                        <label class="label">Motivos de transferencia</label>
                        <div class="row g-2 mb-4" id="reasons"></div>

                        <div class="row g-3 align-items-end mb-4">
                            <div class="col-sm-6">
                                <label class="label">Tiempo máximo de espera para un agente</label>
                                <div class="input-group" style="max-width:220px">
                                    <input type="number" min="1" max="120" value="10"
                                        class="form-control cfg" id="waitMin" />
                                    <span class="input-group-text"
                                        style="border-radius:0 12px 12px 0;border-color:var(--border);background:#F4F7F8;font-size:13px">minutos</span>
                                </div>
                            </div>
                        </div>

                        <label class="label">Si ningún agente está disponible</label>
                        <label class="opt sel"><input type="radio" name="noagent" class="cfg" checked><span><span
                                    class="t">Mantener conversación en espera</span><span class="d">El cliente
                                    permanece en cola hasta que alguien responda.</span></span></label>
                        <label class="opt"><input type="radio" name="noagent" class="cfg"><span><span
                                    class="t">Enviar mensaje alternativo</span><span class="d">Informa que
                                    responderán más tarde.</span></span></label>
                        <label class="opt"><input type="radio" name="noagent" class="cfg"><span><span
                                    class="t">Cerrar conversación</span><span class="d">Finaliza la atención y
                                    registra el caso.</span></span></label>
                    </section>

                    <!-- AVANZADO -->
                    <section class="card-s" id="sec-avanzado">
                        <div class="sec-head">
                            <div class="sec-ico"><i class="fa-solid fa-circle-question"></i></div>
                            <div>
                                <div class="sec-title">Respuestas no resueltas</div>
                                <div class="sec-sub">Define qué debe hacer el chatbot cuando no encuentra información
                                    suficiente para responder.</div>
                            </div>
                        </div>
                        <label class="opt"><input type="radio" name="unres" class="cfg"><span><span
                                    class="t">Intentar nuevamente</span><span class="d">Reformula la pregunta al
                                    usuario una vez más.</span></span></label>
                        <label class="opt sel"><input type="radio" name="unres" class="cfg" checked><span><span
                                    class="t">Transferir a un agente</span><span class="d">Pasa la conversación
                                    a una persona del equipo.</span></span></label>
                        <label class="opt"><input type="radio" name="unres" class="cfg"><span><span
                                    class="t">Ejecutar el siguiente motor disponible</span><span class="d">Continúa
                                    con el siguiente sistema del flujo de
                                    atención.</span></span></label>
                        <label class="opt"><input type="radio" name="unres" class="cfg"><span><span
                                    class="t">Enviar un mensaje personalizado</span><span class="d">Responde con
                                    el texto que definas abajo.</span></span></label>
                        <div class="mt-3">
                            <label class="label">Mensaje cuando no se puede resolver la solicitud</label>
                            <textarea class="form-control cfg" rows="3">No estoy seguro de poder ayudarte con esta solicitud. Permíteme comunicarte con uno de nuestros asesores.</textarea>
                        </div>
                    </section>

                    <section class="card-s">
                        <div class="sec-head">
                            <div class="sec-ico"><i class="fa-solid fa-clock-rotate-left"></i></div>
                            <div>
                                <div class="sec-title">Conversación y contexto</div>
                                <div class="sec-sub">Controla cuánto recuerda el chatbot y cuándo empieza una conversación
                                    nueva.</div>
                            </div>
                        </div>
                        <div class="sub-card d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                            <div>
                                <div style="font-weight:600">Mantener contexto</div>
                                <div class="hint">El chatbot recuerda lo que se habló antes dentro de la misma
                                    conversación.</div>
                            </div>
                            <label class="sw"><input type="checkbox" class="cfg" id="keepCtx" checked><span
                                    class="track"></span></label>
                        </div>
                        <div class="mb-3">
                            <label class="label">Tiempo de sesión</label>
                            <div class="input-group" style="max-width:220px">
                                <input type="number" min="1" value="30" class="form-control cfg"
                                    id="sessionMin" />
                                <span class="input-group-text"
                                    style="border-radius:0 12px 12px 0;border-color:var(--border);background:#F4F7F8;font-size:13px">minutos</span>
                            </div>
                            <div class="hint">Después de este tiempo sin actividad, una nueva interacción se considerará
                                una nueva conversación.</div>
                        </div>
                        <label class="label">Reiniciar conversación</label>
                        <label class="opt sel"><input type="radio" name="reset" class="cfg" checked><span><span
                                    class="t">Cierto tiempo de inactividad</span></span></label>
                        <label class="opt"><input type="radio" name="reset" class="cfg"><span><span
                                    class="t">Transferencia a agente</span></span></label>
                        <label class="opt"><input type="radio" name="reset" class="cfg"><span><span
                                    class="t">Finalización del flujo</span></span></label>
                    </section>

                    <section class="card-s">
                        <div class="sec-head">
                            <div class="sec-ico"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                            <div>
                                <div class="sec-title">Comportamiento de respuesta</div>
                                <div class="sec-sub">Haz que las respuestas se sientan naturales y humanas.</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="label d-flex justify-content-between">Tiempo de espera antes de responder <span
                                    class="counter" id="delayVal">2 s</span></label>
                            <input type="range" min="0" max="10" step="1" value="2"
                                class="cfg" id="delay" />
                            <div class="d-flex justify-content-between hint"><span>0 s</span><span>10 s</span></div>
                            <div class="hint">Simula un tiempo natural de respuesta.</div>
                        </div>
                        <div class="sub-card d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                            <div>
                                <div style="font-weight:600">Mostrar indicador de escritura</div>
                                <div class="hint">El cliente verá "escribiendo..." antes de recibir la respuesta.</div>
                            </div>
                            <label class="sw"><input type="checkbox" class="cfg" id="typing" checked><span
                                    class="track"></span></label>
                        </div>
                        <div>
                            <label class="label">Mensajes consecutivos</label>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span style="font-size:13.5px">Esperar hasta</span>
                                <input type="number" min="0" max="30" value="3" class="form-control cfg"
                                    style="max-width:90px" />
                                <span style="font-size:13.5px">segundos para agrupar mensajes consecutivos del
                                    usuario.</span>
                            </div>
                            <div class="hint">Si el cliente escribe "Hola", "Quiero información" y "Sobre el precio", se
                                tratan como una sola consulta.</div>
                        </div>
                    </section>
                </div>

                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    <div class="side">
                        <div class="status-card">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <div style="font-size:12px;opacity:.75;text-transform:uppercase;letter-spacing:.08em">
                                        Estado del chatbot</div>
                                    <div class="display-font d-flex align-items-center gap-2 mt-1" style="font-size:19px"
                                        id="sideState"><span class="dot" style="color:#4ADE80"></span> Activo</div>
                                </div>
                                <label class="sw big"><input type="checkbox" id="botActiveSide" checked><span
                                        class="track"></span></label>
                            </div>
                            <div class="row-i"><span class="k">Horario</span><span class="v" id="sHorario">Lun -
                                    Vie<br>08:00 - 18:00</span></div>
                            <div class="row-i"><span class="k">Transferencia</span><span class="v"
                                    id="sTransfer">Activada</span></div>
                            <div class="row-i"><span class="k">Palabras clave</span><span class="v"
                                    id="sKw">5 configuradas</span></div>
                            <div class="row-i"><span class="k">Motor principal</span><span class="v"
                                    id="sEngine">Chatbot por Nodos</span></div>
                            <div class="row-i"><span class="k">IA</span><span class="v"
                                    id="sAI">Activada</span></div>
                            <div class="row-i"><span class="k">Automatización</span><span class="v">n8n</span>
                            </div>
                        </div>

                        <div class="card-s">
                            <div class="sec-title mb-3" style="font-size:15px">Acciones rápidas</div>
                            <button class="qa" id="qaTest"><i class="fa-solid fa-comment-dots"></i>Probar
                                conversación</button>
                            <button class="qa" data-goto="#sec-horario"><i class="fa-regular fa-clock"></i>Editar
                                horario</button>
                            <button class="qa" data-goto="#sec-palabras"><i class="fa-solid fa-tags"></i>Palabras
                                clave</button>
                            <button class="qa" data-goto="#sec-flujo"><i
                                    class="fa-solid fa-diagram-project"></i>Prioridad de atención</button>
                            <button class="qa" id="qaCopy"><i class="fa-solid fa-clone"></i>Duplicar
                                configuración</button>
                        </div>

                        <div class="card-s">
                            <div class="sec-title mb-2" style="font-size:15px">Resumen de configuración</div>
                            <div class="hint mb-3">Vista rápida de cómo quedará tu chatbot.</div>
                            <div id="summary"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SAVE BAR -->
        <div class="savebar" id="savebar">
            <div class="warn"><i class="fa-solid fa-triangle-exclamation"></i>Hay cambios sin guardar</div>
            <div class="d-flex gap-2">
                <button class="btn btn-discard" id="btnDiscard">Descartar cambios</button>
                <button class="btn btn-primary-g" id="btnSave">Guardar cambios</button>
            </div>
        </div>

        <div class="toast-w" id="toasts"></div>
    </form>
@endsection

@section('modal')
@endsection

@section('scripts')
    <script>
        $(function() {
            /* ---------- MOCK DATA ---------- */
            var DAYS = [{
                    k: 'lun',
                    l: 'L',
                    full: 'Lunes',
                    on: true
                }, {
                    k: 'mar',
                    l: 'M',
                    full: 'Martes',
                    on: true
                },
                {
                    k: 'mie',
                    l: 'M',
                    full: 'Miércoles',
                    on: true
                }, {
                    k: 'jue',
                    l: 'J',
                    full: 'Jueves',
                    on: true
                },
                {
                    k: 'vie',
                    l: 'V',
                    full: 'Viernes',
                    on: true
                }, {
                    k: 'sab',
                    l: 'S',
                    full: 'Sábado',
                    on: false
                },
                {
                    k: 'dom',
                    l: 'D',
                    full: 'Domingo',
                    on: false
                }
            ];
            var RANGES = [{
                a: '08:00',
                b: '18:00'
            }];
            var KEYWORDS = ['cotización', 'precio', 'asesor', 'factura', 'soporte', 'hablar con una persona',
                'agente', 'humano'
            ];
            var MSGS = [{
                    id: 'welcome',
                    title: 'Mensaje de bienvenida',
                    desc: 'Se envía cuando un usuario inicia una nueva conversación.',
                    on: true,
                    text: '¡Hola! 👋 Soy el asistente virtual de { empresa }. ¿En qué podemos ayudarte hoy?'
                },
                {
                    id: 'offhours',
                    title: 'Mensaje fuera de horario',
                    desc: 'Se envía cuando un usuario escribe fuera del horario de atención.',
                    on: true,
                    text: 'Hola 👋 En este momento estamos fuera de nuestro horario de atención. Nuestro horario es de lunes a viernes de 8:00 a.m. a 6:00 p.m. Te responderemos cuando volvamos a estar disponibles.'
                },
                {
                    id: 'transfer',
                    title: 'Mensaje de transferencia',
                    desc: 'Se envía cuando la conversación pasa de un chatbot a un agente humano.',
                    on: true,
                    text: 'Perfecto 👍 Voy a transferir tu conversación con uno de nuestros asesores. En un momento te atenderán.'
                }
            ];
            var ENGINES = [{
                    id: 'nodes',
                    name: 'Chatbot por Nodos',
                    desc: 'Flujos visuales configurados mediante nodos y condiciones.',
                    badge: 'Visual',
                    icon: 'fa-diagram-project',
                    c: '#2563EB',
                    bg: 'rgba(37,99,235,.10)'
                },
                {
                    id: 'ai',
                    name: 'Asistente IA',
                    desc: 'Permite responder utilizando inteligencia artificial y conocimiento configurado.',
                    badge: 'IA',
                    icon: 'fa-brain',
                    c: '#7C3AED',
                    bg: 'rgba(124,58,237,.10)'
                },
                {
                    id: 'auto',
                    name: 'Automatización',
                    desc: 'Envía la conversación o información a una automatización externa como n8n.',
                    badge: 'Automatización',
                    icon: 'fa-bolt',
                    c: '#D97706',
                    bg: 'rgba(217,119,6,.12)'
                }
            ];
            var REASONS = [{
                    t: 'Palabra clave detectada',
                    on: true
                }, {
                    t: 'Usuario solicita un asesor',
                    on: true
                },
                {
                    t: 'El chatbot no puede resolver la consulta',
                    on: true
                }, {
                    t: 'Error en el chatbot',
                    on: false
                },
                {
                    t: 'Usuario solicita hablar con una persona',
                    on: true
                }
            ];
            var dirty = false;

            /* ---------- RENDER: DAYS ---------- */
            function renderDays() {
                $('#days').html(DAYS.map(function(d, i) {
                    return '<button class="day' + (d.on ? ' on' : '') + '" data-i="' + i + '" title="' +
                        d.full + '">' + d.l + '</button>';
                }).join(''));
            }
            $('#days').on('click', '.day', function() {
                var i = +$(this).data('i');
                DAYS[i].on = !DAYS[i].on;
                renderDays();
                touch();
            });
            $('.quick').on('click', function() {
                var p = $(this).data('preset');
                DAYS.forEach(function(d, i) {
                    d.on = p === 'all' ? true : i < 5;
                });
                renderDays();
                touch();
            });

            /* ---------- RENDER: RANGES ---------- */
            function renderRanges() {
                $('#ranges').html(RANGES.map(function(r, i) {
                    return '<div class="range-row" data-i="' + i + '">' +
                        '<input type="time" class="form-control r-a" value="' + r.a + '">' +
                        '<span class="muted">a</span>' +
                        '<input type="time" class="form-control r-b" value="' + r.b + '">' +
                        (RANGES.length > 1 ?
                            '<button class="icon-btn rm"><i class="fa-solid fa-trash-can"></i></button>' :
                            '') +
                        '</div>';
                }).join(''));
            }
            $('#addRange').on('click', function() {
                RANGES.push({
                    a: '14:00',
                    b: '18:00'
                });
                renderRanges();
                touch();
            });
            $('#ranges').on('click', '.rm', function() {
                RANGES.splice($(this).closest('.range-row').data('i'), 1);
                renderRanges();
                touch();
            });
            $('#ranges').on('change', 'input', function() {
                var row = $(this).closest('.range-row'),
                    i = row.data('i');
                RANGES[i] = {
                    a: row.find('.r-a').val(),
                    b: row.find('.r-b').val()
                };
                touch();
            });

            $('#useSchedule').on('change', function() {
                var on = this.checked;
                $('#scheduleBody').slideToggle(on, 200);
                $('#scheduleHint').text(on ?
                    'El chatbot solo atenderá dentro de los días y horas configurados.' :
                    'El chatbot estará disponible las 24 horas.');
                touch();
            });

            /* ---------- RENDER: KEYWORDS ---------- */
            function renderKw(isNew) {
                $('#kwList').html(KEYWORDS.map(function(k, i) {
                    return '<span class="tag' + (isNew && i === KEYWORDS.length - 1 ? ' new' : '') +
                        '">' + k + '<button data-i="' + i +
                        '"><i class="fa-solid fa-xmark"></i></button></span>';
                }).join(''));
                $('#sKw').text(KEYWORDS.length + ' configuradas');
            }

            function addKw() {
                var v = $.trim($('#kwInput').val());
                if (!v) return;
                if (KEYWORDS.some(function(k) {
                        return k.toLowerCase() === v.toLowerCase();
                    })) {
                    toast('Esa palabra ya existe.', 'amber');
                    return;
                }
                KEYWORDS.push(v);
                $('#kwInput').val('');
                renderKw(true);
                touch();
                summary();
            }
            $('#kwAdd').on('click', addKw);
            $('#kwInput').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addKw();
                }
            });
            $('#kwList').on('click', 'button', function() {
                KEYWORDS.splice(+$(this).data('i'), 1);
                renderKw();
                touch();
                summary();
            });

            /* ---------- RENDER: MESSAGES ---------- */
            function renderMsgs() {
                $('#msgCards').html(MSGS.map(function(m) {
                    return '<div class="sub-card mb-3" data-id="' + m.id + '">' +
                        '<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">' +
                        '<div><div style="font-weight:600;font-size:14.5px">' + m.title +
                        '</div><div class="hint">' + m.desc + '</div></div>' +
                        '<label class="sw"><input type="checkbox" class="msg-on"' + (m.on ? ' checked' :
                            '') +
                        '><span class="track"></span><span class="txt muted">Activo</span></label>' +
                        '</div>' +
                        '<textarea class="form-control msg-text" rows="3">' + m.text + '</textarea>' +
                        '<div class="d-flex justify-content-between align-items-center mt-2 flex-wrap gap-2">' +
                        '<div class="dropdown">' +
                        '<button class="btn btn-ghost btn-sm dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-code me-2"></i>Insertar variable</button>' +
                        '<ul class="dropdown-menu" style="border-radius:12px;border-color:var(--border)">' +
                        ['{ nombre }', '{ empresa }', '{ fecha }'].map(
                            function(v) {
                                return '<li><a class="dropdown-item var-i" href="#" data-v="' + v +
                                    '">' + v + '</a></li>';
                            }).join('') +
                        '</ul>' +
                        '</div>' +
                        '<span class="counter">' + m.text.length + ' / 1024 caracteres</span>' +
                        '</div>' +
                        '</div>';
                }).join(''));
            }
            $('#msgCards').on('input', '.msg-text', function() {
                var card = $(this).closest('.sub-card'),
                    len = this.value.length;
                card.find('.counter').text(len + ' / 1024 caracteres').toggleClass('warn', len > 1024);
                MSGS.forEach(function(m) {
                    if (m.id === card.data('id')) m.text = $(this).val ? '' : m.text;
                }.bind(this));
                touch();
            });
            $('#msgCards').on('change', '.msg-on', function() {
                touch();
            });
            $('#msgCards').on('click', '.var-i', function(e) {
                e.preventDefault();
                var ta = $(this).closest('.sub-card').find('.msg-text')[0];
                var v = $(this).data('v'),
                    s = ta.selectionStart || ta.value.length;
                ta.value = ta.value.slice(0, s) + v + ta.value.slice(s);
                $(ta).trigger('input').focus();
            });

            /* ---------- RENDER: ENGINES (drag & drop) ---------- */
            function renderEngines() {
                $('#engines').html(ENGINES.map(function(e, i) {
                    return '<div class="engine" draggable="true" data-i="' + i + '">' +
                        '<i class="fa-solid fa-grip-vertical handle"></i>' +
                        '<span class="eng-num">' + (i + 1) + '</span>' +
                        '<span class="eng-ico" style="background:' + e.bg + ';color:' + e.c +
                        '"><i class="fa-solid ' + e.icon + '"></i></span>' +
                        '<div class="flex-grow-1"><div style="font-weight:600;font-size:14px">' + e
                        .name + '</div><div class="hint" style="margin-top:2px">' + e.desc +
                        '</div></div>' +
                        '<span class="badge-s" style="background:' + e.bg + ';color:' + e.c + '">' + e
                        .badge + '</span>' +
                        '</div>';
                }).join(''));
                var nodes = [
                        '<span class="flow-node"><i class="fa-solid fa-user" style="color:var(--muted)"></i>Usuario</span>'
                    ]
                    .concat(ENGINES.map(function(e) {
                        return '<span class="flow-node"><i class="fa-solid ' + e.icon + '" style="color:' +
                            e.c + '"></i>' + e.name + '</span>';
                    }));
                $('#flowmap').html(nodes.join(
                    '<div class="flow-arrow"><i class="fa-solid fa-arrow-down"></i></div>'));
                $('#sEngine').text(ENGINES[0].name);
                summary();
            }
            var dragIdx = null;
            $('#engines').on('dragstart', '.engine', function(e) {
                dragIdx = +$(this).data('i');
                $(this).addClass('dragging');
                e.originalEvent.dataTransfer.effectAllowed = 'move';
                e.originalEvent.dataTransfer.setData('text/plain', dragIdx);
            });
            $('#engines').on('dragend', '.engine', function() {
                $('.engine').removeClass('dragging over');
            });
            $('#engines').on('dragover', '.engine', function(e) {
                e.preventDefault();
                $('.engine').removeClass('over');
                $(this).addClass('over');
            });
            $('#engines').on('drop', '.engine', function(e) {
                e.preventDefault();
                var to = +$(this).data('i');
                if (dragIdx === null || dragIdx === to) return;
                var moved = ENGINES.splice(dragIdx, 1)[0];
                ENGINES.splice(to, 0, moved);
                dragIdx = null;
                renderEngines();
                touch();
                toast('Prioridad actualizada: ' + ENGINES[0].name);
            });

            /* ---------- REASONS ---------- */
            $('#reasons').html(REASONS.map(function(r, i) {
                return '<div class="col-md-6"><label class="opt' + (r.on ? ' sel' : '') +
                    '" style="margin-bottom:0">' +
                    '<input type="checkbox" class="cfg chk" style="accent-color:var(--blue)"' + (r.on ?
                        ' checked' : '') + '>' +
                    '<span class="t">' + r.t + '</span></label></div>';
            }).join(''));

            /* ---------- OPT visual state ---------- */
            $(document).on('change', '.opt input', function() {
                var $i = $(this);
                if ($i.attr('type') === 'radio') {
                    $('input[name="' + $i.attr('name') + '"]').closest('.opt').removeClass('sel');
                }
                $i.closest('.opt').toggleClass('sel', this.checked);
            });

            /* ---------- Active switches ---------- */
            function setActive(on) {
                $('#botActive,#botActiveSide').prop('checked', on);
                $('#headState').html(
                    '<i class="bi bi-check2-circle me-1 text-white"></i>' + (on ? 'Activo' : 'Inactivo'));
                $('#sideState').html('<span class="dot" style="color:' + (on ? '#4ADE80' : '#94A3B8') +
                    '"></span> ' + (on ? 'Activo' : 'Inactivo'));
            }
            $('#botActive,#botActiveSide').on('change', function() {
                setActive(this.checked);
                touch();
            });
            $('#allowTransfer').on('change', function() {
                $('#sTransfer').text(this.checked ? 'Activada' : 'Desactivada');
                touch();
            });
            $('#delay').on('input', function() {
                $('#delayVal').text(this.value + ' s');
                touch();
            });

            /* ---------- SUMMARY ---------- */
            function summary() {
                var on = DAYS.filter(function(d) {
                    return d.on;
                });
                var dstr = on.length === 7 ? 'Todos los días' : (on.length === 0 ? 'Sin días' : on.map(function(d) {
                    return d.full.slice(0, 3);
                }).join(', '));
                var hstr = $('#useSchedule').prop('checked') ? RANGES.map(function(r) {
                    return r.a + ' - ' + r.b;
                }).join(' · ') : 'Disponible 24 h';
                $('#sHorario').html(dstr + '<br>' + hstr);
                var items = [
                    ['fa-regular fa-clock', 'Horario', dstr + ' · ' + hstr],
                    ['fa-solid fa-tags', 'Palabras clave', KEYWORDS.length + ' términos'],
                    ['fa-regular fa-comment-dots', 'Mensajes activos', $('.msg-on:checked').length + ' de 3'],
                    ['fa-solid fa-diagram-project', 'Prioridad', ENGINES.map(function(e) {
                        return e.name;
                    }).join(' → ')],
                    ['fa-solid fa-headset', 'Transferencia', $('#allowTransfer').prop('checked') ? 'Activada' :
                        'Desactivada'
                    ]
                ];
                $('#summary').html(items.map(function(it) {
                    return '<div class="d-flex gap-3 align-items-start py-2" style="border-bottom:1px solid var(--border)">' +
                        '<i class="' + it[0] +
                        '" style="color:var(--accent);width:18px;margin-top:3px"></i>' +
                        '<div><div style="font-size:12.5px;font-weight:600">' + it[1] +
                        '</div><div class="hint" style="margin-top:1px">' + it[2] +
                        '</div></div></div>';
                }).join(''));
            }

            /* ---------- DIRTY / SAVE ---------- */
            function touch() {
                dirty = true;
                $('#savebar').addClass('show');
                summary();
            }
            $(document).on('change input', '.cfg, #msgCards input, #msgCards textarea, #days .day', function() {
                touch();
            });

            function save() {
                dirty = false;
                $('#savebar').removeClass('show');
                toast('Configuración guardada correctamente.');
            }
            $('#btnSave,#btnSaveTop').on('click', save);
            $('#btnDiscard').on('click', function() {
                location.reload();
            });
            $('#btnTest,#qaTest').on('click', function() {
                toast('Abriendo simulador de conversación…', 'blue');
            });
            $('#qaCopy').on('click', function() {
                toast('Configuración duplicada como borrador.', 'blue');
            });

            function toast(msg, tone) {
                var color = tone === 'amber' ? '#D97706' : (tone === 'blue' ? '#2563EB' : '#16A34A');
                var icon = tone === 'amber' ? 'fa-triangle-exclamation' : (tone === 'blue' ? 'fa-circle-info' :
                    'fa-circle-check');
                var $t = $('<div class="toast-i" style="border-left-color:' + color + '"><i class="fa-solid ' +
                    icon + '" style="color:' + color + '"></i><span>' + msg + '</span></div>');
                $('#toasts').append($t);
                setTimeout(function() {
                    $t.fadeOut(220, function() {
                        $t.remove();
                    });
                }, 2800);
            }

            /* ---------- TABS / SCROLLSPY ---------- */
            $('#tabs a, [data-goto]').on('click', function(e) {
                e.preventDefault();
                var t = $(this).attr('href') || $(this).data('goto');
                var el = $(t);
                if (!el.length) return;
                $('html,body').animate({
                    scrollTop: el.offset().top - 90
                }, 420);
            });
            var secs = $('#tabs a').map(function() {
                return $(this).attr('href');
            }).get();
            $(window).on('scroll', function() {
                var y = $(window).scrollTop() + 120,
                    cur = secs[0];
                secs.forEach(function(s) {
                    var el = $(s);
                    if (el.length && el.offset().top <= y) cur = s;
                });
                $('#tabs a').removeClass('active').filter('[href="' + cur + '"]').addClass('active');
            });

            /* ---------- INIT ---------- */
            renderDays();
            renderRanges();
            renderKw();
            renderMsgs();
            renderEngines();
            setActive(true);
            summary();
            $('#savebar').removeClass('show');
            dirty = false;
            setTimeout(function() {
                $('#savebar').removeClass('show');
            }, 100);
            $('[title]').each(function() {
                new bootstrap.Tooltip(this);
            });
        });
    </script>
@endsection
