@extends('layouts.principal')

@section('css')
    <style>
        :root {
            --bg: #040a16;
            --navy: #071426;
            --cyan: #22d3ee;
            --teal-1: #14b8a6;
            --blue: #2563eb;
            --wa: #25d366;
            --txt: #e6f1f8;
            --muted: #8aa0b6;
        }

        .mtp {
            position: relative;
            min-height: 820px;
            overflow: hidden;
            padding: 90px 0 80px;
            isolation: isolate;
            background: var(--bg);
            color: var(--txt);
            font-family: Manrope, sans-serif;
        }

        .mtp::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -2;
            background:
                radial-gradient(60% 50% at 20% 30%,
                    rgba(20, 184, 166, 0.18),
                    transparent 70%),
                radial-gradient(50% 45% at 80% 35%,
                    rgba(37, 99, 235, 0.22),
                    transparent 70%),
                radial-gradient(40% 40% at 50% 100%,
                    rgba(34, 211, 238, 0.12),
                    transparent 70%);
        }

        .mtp::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -2;
            opacity: 0.35;
            background-image:
                linear-gradient(rgba(34, 211, 238, 0.07) 1px,
                    transparent 1px),
                linear-gradient(90deg,
                    rgba(34, 211, 238, 0.07) 1px,
                    transparent 1px);
            background-size: 56px 56px;
            mask-image: radial-gradient(ellipse at center,
                    #000 30%,
                    transparent 75%);
        }

        #mtpCanvas {
            position: absolute;
            inset: 0;
            z-index: -1;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .eyebrow {
            font: 600 0.75rem Sora;
            letter-spacing: 0.3em;
            color: var(--cyan);
            text-transform: uppercase;
        }

        h2.title {
            font: 700 clamp(2rem, 4.5vw, 3.6rem)/1.05 Sora;
            letter-spacing: -0.03em;
            margin: 0.6rem 0 1rem;
        }

        h2.title span {
            background: linear-gradient(90deg,
                    var(--cyan),
                    var(--teal-1),
                    #60a5fa);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .lead-t {
            color: var(--muted);
            max-width: 620px;
            margin: 0 auto;
            font-size: 1.08rem;
        }

        /* Stage */
        .stage {
            position: relative;
            margin: 56px auto 50px;
            max-width: 1080px;
            height: 340px;
            perspective: 1200px;
        }

        .stage-inner {
            position: absolute;
            inset: 0;
            transform-style: preserve-3d;
            transition: transform 0.4s ease-out;
        }

        .node {
            position: absolute;
            top: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            transform: translateY(-50%);
        }

        .node.gijac {
            left: 2%;
        }

        .node.meta {
            left: 50%;
            transform: translate(-50%, -50%) translateZ(80px);
        }

        .node.wa {
            right: 2%;
        }

        .badge-3d {
            width: 120px;
            height: 120px;
            border-radius: 30px;
            display: grid;
            place-items: center;
            position: relative;
            background: linear-gradient(145deg,
                    rgba(255, 255, 255, 0.12),
                    rgba(255, 255, 255, 0.02));
            border: 1px solid rgba(125, 211, 252, 0.3);
            backdrop-filter: blur(14px);
            box-shadow:
                0 30px 60px -20px rgba(0, 0, 0, 0.8),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .gijac .badge-3d {
            animation: floatA 6s ease-in-out infinite;
        }

        .wa .badge-3d {
            border-color: rgba(37, 211, 102, 0.45);
            box-shadow:
                0 0 50px -10px rgba(37, 211, 102, 0.5),
                0 30px 60px -20px #000;
            animation: floatA 7s ease-in-out infinite reverse;
        }

        .g-logo {
            font: 700 2.4rem Sora;
            background: linear-gradient(135deg,
                    var(--cyan),
                    var(--teal-1) 50%,
                    var(--blue));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .node-label {
            text-align: center;
            font: 600 0.95rem Sora;
            line-height: 1.2;
        }

        .node-label small {
            display: block;
            font: 500 0.72rem Manrope;
            letter-spacing: 0.2em;
            color: var(--muted);
            text-transform: uppercase;
            margin-top: 4px;
        }

        /* Meta crystal */
        .crystal {
            width: 200px;
            height: 200px;
            position: relative;
            transform-style: preserve-3d;
            animation: floatB 8s ease-in-out infinite;
        }

        .crystal .face {
            position: absolute;
            inset: 0;
            border-radius: 44px;
            transform-style: preserve-3d;
            background: conic-gradient(from 210deg,
                    rgba(96, 165, 250, 0.35),
                    rgba(34, 211, 238, 0.15),
                    rgba(37, 99, 235, 0.45),
                    rgba(34, 211, 238, 0.25),
                    rgba(96, 165, 250, 0.35));
            border: 1px solid rgba(147, 197, 253, 0.5);
            backdrop-filter: blur(16px);
            box-shadow:
                0 0 90px -10px rgba(37, 99, 235, 0.7),
                inset 0 0 40px rgba(34, 211, 238, 0.35),
                inset 0 2px 0 rgba(255, 255, 255, 0.4);
            animation: spin 24s linear infinite;
        }

        .crystal .layer {
            position: absolute;
            inset: 18px;
            border-radius: 34px;
            border: 1px solid rgba(186, 230, 253, 0.35);
            transform: translateZ(30px);
        }

        .crystal .shine {
            position: absolute;
            inset: 0;
            border-radius: 44px;
            background: linear-gradient(115deg,
                    transparent 30%,
                    rgba(255, 255, 255, 0.35) 45%,
                    transparent 60%);
            background-size: 250% 100%;
            animation: shine 5s ease-in-out infinite;
        }

        .crystal .logo-slot {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            transform: translateZ(50px);
            text-align: center;
        }

        .logo-ph {
            font: 700 1.6rem Sora;
            color: #fff;
            text-shadow: 0 0 20px rgba(96, 165, 250, 0.9);
        }

        .logo-ph small {
            display: block;
            font: 500 0.6rem Manrope;
            letter-spacing: 0.15em;
            color: #bfdbfe;
            opacity: 0.75;
            margin-top: 4px;
        }

        .crystal-glow {
            position: absolute;
            inset: -40%;
            background: radial-gradient(circle,
                    rgba(37, 99, 235, 0.35),
                    transparent 60%);
            filter: blur(20px);
            z-index: -1;
            animation: pulse 4s ease-in-out infinite;
        }

        .links {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            overflow: visible;
        }

        .links path {
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        .links .base {
            stroke: rgba(34, 211, 238, 0.18);
        }

        .links .flow {
            stroke: url(#lg);
            stroke-dasharray: 6 18;
            stroke-dashoffset: 0;
            animation: dash 1.6s linear infinite;
            filter: drop-shadow(0 0 6px var(--cyan));
            opacity: 0;
            transition: opacity 1.2s;
        }

        .links .flow.wa {
            stroke: url(#lgw);
        }

        .in .links .flow {
            opacity: 1;
        }

        /* Cards */
        .trust {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .tcard {
            position: relative;
            padding: 24px;
            border-radius: 20px;
            background: linear-gradient(160deg,
                    rgba(255, 255, 255, 0.06),
                    rgba(255, 255, 255, 0.015));
            border: 1px solid rgba(125, 211, 252, 0.16);
            backdrop-filter: blur(12px);
            transition:
                transform 0.35s,
                box-shadow 0.35s,
                border-color 0.35s;
            overflow: hidden;
        }

        .tcard::before {
            content: "";
            position: absolute;
            left: 20%;
            right: 20%;
            top: 0;
            height: 1px;
            background: linear-gradient(90deg,
                    transparent,
                    var(--cyan),
                    transparent);
        }

        .tcard:hover {
            transform: translateY(-6px);
            border-color: rgba(34, 211, 238, 0.45);
            box-shadow: 0 20px 50px -20px rgba(34, 211, 238, 0.45);
        }

        .ticon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            font-size: 1.3rem;
            margin-bottom: 14px;
            color: var(--cyan);
            background: rgba(34, 211, 238, 0.1);
            border: 1px solid rgba(34, 211, 238, 0.3);
        }

        .tcard.w .ticon {
            color: var(--wa);
            background: rgba(37, 211, 102, 0.1);
            border-color: rgba(37, 211, 102, 0.35);
        }

        .tcard h3 {
            font: 600 1.05rem Sora;
            margin: 0 0 4px;
        }

        .tcard p {
            margin: 0;
            color: var(--muted);
            font-size: 0.9rem;
        }

        /* Reveal */
        .rv {
            opacity: 0;
            transform: translateY(30px);
            transition:
                opacity 0.9s cubic-bezier(0.2, 0.7, 0.2, 1),
                transform 0.9s cubic-bezier(0.2, 0.7, 0.2, 1);
        }

        .rv.l {
            transform: translateX(-60px) translateY(-50%);
        }

        .rv.r {
            transform: translateX(60px) translateY(-50%);
        }

        .rv.c {
            transform: translate(-50%, -50%) translateZ(80px) scale(0.7);
        }

        .in .rv {
            opacity: 1;
            transform: none;
        }

        .in .rv.l,
        .in .rv.r {
            transform: translateY(-50%);
        }

        .in .rv.c {
            transform: translate(-50%, -50%) translateZ(80px);
        }

        .note {
            font-size: 0.72rem;
            color: #5b7088;
            text-align: center;
            margin-top: 34px;
        }

        @keyframes floatA {
            50% {
                transform: translateY(-12px) rotateY(10deg);
            }
        }

        @keyframes floatB {

            0%,
            100% {
                transform: translateY(0) rotateX(8deg);
            }

            50% {
                transform: translateY(-16px) rotateX(-6deg);
            }
        }

        @keyframes spin {
            from {
                transform: rotateY(0);
            }

            to {
                transform: rotateY(360deg);
            }
        }

        @keyframes shine {

            0%,
            100% {
                background-position: 150% 0;
            }

            50% {
                background-position: -50% 0;
            }
        }

        @keyframes pulse {
            50% {
                opacity: 0.6;
                transform: scale(1.1);
            }
        }

        @keyframes dash {
            to {
                stroke-dashoffset: -48;
            }
        }

        @media (max-width: 991px) {
            .trust {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 767px) {
            .mtp {
                padding: 70px 0 60px;
            }

            .stage {
                height: auto;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 56px;
                perspective: none;
            }

            .stage-inner {
                position: relative;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 60px;
            }

            .node,
            .node.meta {
                position: relative;
                left: auto !important;
                right: auto !important;
                top: auto;
                transform: none !important;
            }

            .rv.l,
            .rv.r,
            .rv.c {
                transform: translateY(30px) !important;
            }

            .in .rv.l,
            .in .rv.r,
            .in .rv.c {
                transform: none !important;
            }

            .crystal {
                width: 170px;
                height: 170px;
            }

            .links {
                display: none;
            }

            .stage-inner::before {
                content: "";
                position: absolute;
                top: 60px;
                bottom: 60px;
                left: 50%;
                width: 2px;
                z-index: -1;
                background: repeating-linear-gradient(180deg,
                        var(--cyan) 0 6px,
                        transparent 6px 20px);
                animation: vdash 1.4s linear infinite;
                filter: drop-shadow(0 0 6px var(--cyan));
            }

            .trust {
                grid-template-columns: 1fr;
            }
        }

        @keyframes vdash {
            to {
                background-position: 0 40px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <!-- ===== HERO ===== -->
    <section id="hero" class="hero-section">
        <div class="hero-glow-1"></div>
        <div class="hero-glow-2"></div>
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge-pill reveal" data-reveal="up">
                        <span class="dot-live"></span> {{ __('Plataforma Oficial de WhatsApp Business API') }}
                    </span>
                    <h1 class="hero-title">
                        <span class="reveal-word">{{ __('Conecta con tus clientes a través de') }}
                            <span class="gradient-text">{{ __('WhatsApp Business') }}</span>
                        </span>
                    </h1>
                    <p class="hero-sub reveal" data-reveal="up">
                        {{ __('La plataforma más completa para gestionar campañas, automatizar respuestas y optimizar tu comunicación empresarial en WhatsApp.') }}
                    </p>
                    <div class="d-flex flex-wrap gap-3 reveal" data-reveal="up">
                        <a href="{{ route('login') }}" class="btn btn-glow btn-lg magnetic">
                            <i class="bi bi-send-fill me-2"></i> {{ __('Comenzar ahora') }}
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-dark-glass btn-lg magnetic">
                            <i class="bi bi-play-circle me-2"></i> {{ __('Probar Gratis') }}
                        </a>
                    </div>
                    <div class="hero-trust reveal" data-reveal="up">
                        <span>
                            <i class="bi bi-shield-check"></i> {{ __('Seguridad Garantizada') }}
                        </span>
                        <span>
                            <i class="bi bi-headset"></i> {{ __('Soporte 24/7') }}
                        </span>
                        <span>
                            <i class="bi bi-hand-thumbs-up"></i> {{ __('Fácil de Usar') }}
                        </span>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-visual tilt-3d" data-tilt>
                        <img src="{{ asset('img/hero-dashboard.png') }}"
                            alt="Dashboard 3D de la plataforma GIJAC Message Business" class="hero-dashboard float-slow" />
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
                        <div class="stat-label">{{ __('Mensajes enviados') }}</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-reveal="up">
                    <div class="stat-card glass">
                        <div class="stat-number" data-count="2000" data-suffix="+">0</div>
                        <div class="stat-label">{{ __('Empresas activas') }}</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-reveal="up">
                    <div class="stat-card glass">
                        <div class="stat-number" data-count="99.9" data-suffix="%" data-decimals="1">0</div>
                        <div class="stat-label">{{ __('Uptime garantizado') }}</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal" data-reveal="up">
                    <div class="stat-card glass">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">{{ __('Soporte técnico') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== MODULOS ===== -->
    <section id="modulos" class="section modules-section">
        <div class="container">
            <div class="text-center mb-5">
                <p class="section-eyebrow reveal" data-reveal="up">{{ __('MÓDULOS DE LA PLATAFORMA') }}</p>
                <h2 class="section-title reveal" data-reveal="up">
                    {{ __('Todo lo que necesitas en') }} <span class="gradient-text">{{ __('un solo lugar') }}</span>
                </h2>
                <p class="section-lead reveal" data-reveal="up">
                    {{ __('Descubre todas las herramientas que necesitas para gestionar tu comunicación empresarial de manera eficiente.') }}
                </p>
            </div>

            <div class="row g-4" id="modules-grid">
                <!-- cards injected by JS -->
            </div>

            <div class="text-center mt-5 reveal" data-reveal="up">
                <a href="#" class="btn btn-outline-teal magnetic">
                    <i class="bi bi-grid-3x3-gap me-2"></i>
                    {{ __('Ver todas las funcionalidades') }}
                </a>
            </div>
        </div>
    </section>

    <section class="mtp" id="metaTechnologyProvider" aria-labelledby="mtpTitle">
        <canvas id="mtpCanvas" aria-hidden="true"></canvas>
        <div class="container text-center">
            <div class="rv" style="transition-delay: 0.05s">
                <span class="eyebrow">
                    <i class="bi bi-patch-check-fill me-2"></i>
                    {{ __('Infraestructura oficial') }}
                </span>
                <h2 class="title" id="mtpTitle">
                    {{ __('Proveedor tecnológico de') }} <span>{{ __('Meta') }}</span>
                </h2>
                <p class="lead-t">
                    {{ __('GIJAC MESSAGE BUSINESS integra la tecnología oficial de Meta para ayudarte a gestionar y potenciar la comunicación de tu empresa a través de WhatsApp.') }}
                </p>
            </div>

            <div class="stage" id="mtpStage">
                <svg class="links" viewBox="0 0 1080 340" preserveAspectRatio="none" aria-hidden="true">
                    <defs>
                        <linearGradient id="lg" x1="0" x2="1">
                            <stop offset="0" stop-color="#14b8a6" />
                            <stop offset="1" stop-color="#60a5fa" />
                        </linearGradient>
                        <linearGradient id="lgw" x1="0" x2="1">
                            <stop offset="0" stop-color="#60a5fa" />
                            <stop offset="1" stop-color="#25d366" />
                        </linearGradient>
                    </defs>
                    <path class="base" d="M150 170 C300 60 400 60 440 170" />
                    <path class="flow" d="M150 170 C300 60 400 60 440 170" style="transition-delay: 1s" />
                    <path class="base" d="M640 170 C680 280 780 280 930 170" />
                    <path class="flow wa" d="M640 170 C680 280 780 280 930 170" style="transition-delay: 1.4s" />
                </svg>
                <div class="stage-inner" id="mtpInner">
                    <div class="node gijac rv l" style="transition-delay: 0.35s" data-depth="12">
                        <div class="badge-3d">
                            <span class="g-logo">
                                <img src="{{ asset('img/logo_gmb.png') }}" alt="GIJAC Message Business" width="100%">
                            </span>
                        </div>
                        <div class="node-label">
                            GIJAC<small>Message Business</small>
                        </div>
                    </div>
                    <div class="node meta rv c" style="transition-delay: 0.8s" data-depth="24">
                        <div class="crystal">
                            <div class="crystal-glow"></div>
                            <div class="face">
                                <div class="layer">
                                    <img src="{{ asset('img/meta.png') }}" alt="META" width="100%">
                                </div>
                            </div>
                            <div class="shine">
                            </div>
                            <div class="logo-slot">
                                <div class="logo-ph"></div>
                            </div>
                        </div>
                        <div class="node-label">
                            {{ __('Meta') }}<small>{{ __('Technology Provider') }}</small>
                        </div>
                    </div>
                    <div class="node wa rv r" style="transition-delay: 0.55s" data-depth="16">
                        <div class="badge-3d">
                            <!-- Reemplazar por el logo oficial de WhatsApp Business -->
                            <span>
                                <img src="{{ asset('img/whatsapp-business.png') }}" alt="{{ __('WhatsApp Business') }}" width="100%">
                            </span>
                        </div>
                        <div class="node-label">
                            {{ __('WhatsApp') }}<small>{{ __('Business API') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="trust">
                <div class="tcard w rv" style="transition-delay: 1.6s">
                    <div class="ticon">
                        <i class="bi bi-chat-dots-fill"></i>
                    </div>
                    <h3>{{ __('WhatsApp Business') }}</h3>
                    <p>{{ __('API Oficial') }}</p>
                </div>
                <div class="tcard rv" style="transition-delay: 1.8s">
                    <div class="ticon">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                    <h3>{{ __('Tecnología Meta') }}</h3>
                    <p>{{ __('Infraestructura y ecosistema oficial') }}</p>
                </div>
                <div class="tcard rv" style="transition-delay: 2s">
                    <div class="ticon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h3>{{ __('Seguridad y confiabilidad') }}</h3>
                    <p>{{ __('Tecnología empresarial') }}</p>
                </div>
            </div>
            <p class="note">
                {{ __('Los logotipos de Meta y WhatsApp son marcas de Meta Platforms, Inc. Sustituye los marcadores por los recursos oficiales.') }}
            </p>
        </div>
    </section>

    <!-- ===== BENEFITS ===== -->
    <section id="beneficios" class="section benefits-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="tilt-3d" data-tilt>
                        <img src="{{ asset('img/benefits-3d.png') }}"
                            alt="Ilustración de seguridad y analítica de la plataforma" class="benefits-img float-slow" />
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2 class="section-title text-start reveal" data-reveal="up">
                        {{ __('¿Por qué elegir') }} <span class="gradient-text">{{ __('nuestra plataforma?') }}</span>
                    </h2>
                    <div class="benefit-list">
                        <div class="benefit-item reveal" data-reveal="right">
                            <div class="benefit-icon">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                            <div>
                                <h5>{{ __('API Oficial de WhatsApp') }}</h5>
                                <p>{{ __('Integración directa con la API oficial de WhatsApp Business para máxima confiabilidad y entrega garantizada.') }}
                                </p>
                            </div>
                        </div>
                        <div class="benefit-item reveal" data-reveal="right">
                            <div class="benefit-icon">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <div>
                                <h5>{{ __('Seguridad Garantizada') }}</h5>
                                <p>
                                    {{ __('Encriptación end-to-end y cumplimiento total con las políticas de privacidad de WhatsApp.') }}
                                </p>
                            </div>
                        </div>
                        <div class="benefit-item reveal" data-reveal="right">
                            <div class="benefit-icon">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <div>
                                <h5>{{ __('Analytics Avanzados') }}</h5>
                                <p>
                                    {{ __('Métricas detalladas de entrega, apertura, clics y conversaciones para optimizar tus campañas.') }}
                                </p>
                            </div>
                        </div>
                        <div class="benefit-item reveal" data-reveal="right">
                            <div class="benefit-icon">
                                <i class="bi bi-headset"></i>
                            </div>
                            <div>
                                <h5>{{ __('Soporte 24/7') }}</h5>
                                <p>
                                    {{ __('Equipo de soporte técnico disponible las 24 horas para resolver cualquier consulta o incidencia.') }}
                                </p>
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
                    <span class="section-eyebrow reveal" data-reveal="up">{{ __('INTELIGENCIA ARTIFICIAL') }}</span>
                    <h2 class="section-title text-start reveal" data-reveal="up">
                        {{ __('Potencia tu negocio con') }} <span
                            class="gradient-text">{{ __('Inteligencia Artificial') }}</span>
                    </h2>
                    <p class="section-lead text-start reveal" data-reveal="up">
                        {{ __('Automatiza conversaciones, analiza el sentimiento de tus clientes y responde al instante con agentes inteligentes entrenados para tu empresa.') }}
                    </p>
                    <div class="row g-3 mt-2">
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="ia-feature glass">
                                <i class="bi bi-robot"></i>
                                <span>{{ __('Agentes IA') }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="ia-feature glass">
                                <i class="bi bi-lightning-charge-fill"></i>
                                <span>{{ __('Respuestas automáticas') }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="ia-feature glass">
                                <i class="bi bi-chat-square-heart"></i>
                                <span>{{ __('Análisis de conversaciones') }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="ia-feature glass">
                                <i class="bi bi-gear-wide-connected"></i>
                                <span>{{ __('Automatizaciones') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="chat-mockup glass tilt-3d" data-tilt>
                        <div class="chat-header">
                            <img src="{{ asset('img/logo_gmb.png') }}" alt="" class="chat-avatar" />
                            <div>
                                <strong>{{ __('Asistente IA · GIJAC') }}</strong>
                                <small>
                                    <span class="dot-live"></span>
                                    {{ __('En línea') }}
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
                        <span class="dot-live"></span> {{ __('App móvil') }} · {{ __('iOS') }} &amp;
                        {{ __('Android') }}
                    </span>
                    <h2 class="app-title">
                        <span class="app-line reveal" data-reveal="up">{{ __('Lleva el control de tus') }}</span>
                        <span class="app-line reveal" data-reveal="up">{{ __('conversaciones') }}</span>
                        <span class="app-line reveal" data-reveal="up">
                            <span class="gradient-text">{{ __('a donde vayas') }}</span>
                        </span>
                    </h2>
                    <p class="app-sub reveal" data-reveal="up">
                        {{ __('Gestiona campañas, responde a tus clientes y monitorea tus métricas en tiempo real desde la palma de tu mano. Todo el poder de GIJAC, ahora móvil.') }}
                    </p>

                    <div class="row g-3 app-features">
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="app-feature glass tilt-3d" data-tilt>
                                <div class="app-feature-icon">
                                    <i class="bi bi-chat-dots-fill"></i>
                                </div>
                                <div>
                                    <h6>{{ __('Chats en tiempo real') }}</h6>
                                    <p>{{ __('Responde al instante desde cualquier lugar.') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="app-feature glass tilt-3d" data-tilt>
                                <div class="app-feature-icon">
                                    <i class="bi bi-graph-up-arrow"></i>
                                </div>
                                <div>
                                    <h6>{{ __('Analíticas al alcance') }}</h6>
                                    <p>{{ __('Métricas y campañas siempre contigo.') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="app-feature glass tilt-3d" data-tilt>
                                <div class="app-feature-icon">
                                    <i class="bi bi-bell-fill"></i>
                                </div>
                                <div>
                                    <h6>{{ __('Notificaciones inteligentes') }}</h6>
                                    <p>{{ __('Entérate de todo lo importante al momento.') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 reveal" data-reveal="up">
                            <div class="app-feature glass tilt-3d" data-tilt>
                                <div class="app-feature-icon">
                                    <i class="bi bi-shield-lock-fill"></i>
                                </div>
                                <div>
                                    <h6>{{ __('Seguridad garantizada') }}</h6>
                                    <p>{{ __('Encriptación de extremo a extremo.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="download-card glass reveal" data-reveal="up">
                        <span class="download-label">{{ __('Descarga la app') }}</span>
                        <div class="store-row">
                            <a href="#" class="store-btn magnetic" aria-label="Descargar en App Store">
                                <i class="bi bi-apple"></i>
                                <span>
                                    <small>{{ __('Descárgalo en') }}</small>
                                    <strong>{{ __('App Store') }}</strong>
                                </span>
                            </a>
                            <a href="#" class="store-btn magnetic" aria-label="Disponible en Google Play">
                                <i class="bi bi-google-play"></i>
                                <span>
                                    <small>{{ __('Disponible en') }}</small>
                                    <strong>{{ __('Google Play') }}</strong>
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
                            {{ __('¿Listo para') }} <span class="gradient-text">{{ __('despegar?') }}</span>
                        </h2>
                        <p class="cta-lead reveal" data-reveal="up">
                            {{ __('Únete a miles de empresas que ya están utilizando nuestra plataforma para mejorar su comunicación con clientes.') }}
                        </p>
                        <div class="d-flex flex-wrap gap-3 reveal" data-reveal="up">
                            <a href="{{ route('precios') }}" class="btn btn-light-glow btn-lg magnetic">
                                <i class="bi bi-eye me-2"></i>
                                {{ __('Ver Precios') }}
                            </a>
                            <a href="{{ route('contactarnos') }}" class="btn btn-dark-glass btn-lg magnetic">
                                <i class="bi bi-telephone me-2"></i>
                                {{ __('Contactar Ventas') }}
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
    <script>
        $(function() {
            var $s = $("#metaTechnologyProvider"),
                mobile = window.matchMedia("(max-width:767px)").matches;

            // Entrada al hacer scroll
            new IntersectionObserver(
                function(e, o) {
                    if (e[0].isIntersecting) {
                        $s.addClass("in");
                        o.disconnect();
                    }
                }, {
                    threshold: 0.2
                },
            ).observe($s[0]);

            // Parallax 3D con mouse (desktop) / automático (móvil)
            var $inner = $("#mtpInner"),
                tx = 0,
                ty = 0,
                cx = 0,
                cy = 0;
            if (!mobile) {
                $s.on("mousemove", function(ev) {
                    var r = this.getBoundingClientRect();
                    tx = (ev.clientX - r.left) / r.width - 0.5;
                    ty = (ev.clientY - r.top) / r.height - 0.5;
                }).on("mouseleave", function() {
                    tx = ty = 0;
                });
            }
            (function loop(t) {
                if (mobile) {
                    tx = Math.sin(t / 3000) * 0.15;
                    ty = Math.cos(t / 3500) * 0.1;
                }
                cx += (tx - cx) * 0.06;
                cy += (ty - cy) * 0.06;
                if (!mobile)
                    $inner.css(
                        "transform",
                        "rotateY(" +
                        cx * 14 +
                        "deg) rotateX(" +
                        -cy * 10 +
                        "deg)",
                    );
                requestAnimationFrame(loop);
            })(0);

            // Partículas de fondo (Canvas 2D)
            var c = document.getElementById("mtpCanvas"),
                x = c.getContext("2d"),
                P = [],
                W,
                H;

            function size() {
                var d = Math.min(devicePixelRatio || 1, 2);
                W = $s.outerWidth();
                H = $s.outerHeight();
                c.width = W * d;
                c.height = H * d;
                x.setTransform(d, 0, 0, d, 0, 0);
                P = [];
                var n = mobile ? 25 : 70;
                for (var i = 0; i < n; i++)
                    P.push({
                        x: Math.random() * W,
                        y: Math.random() * H,
                        r: Math.random() * 1.6 + 0.4,
                        vx: (Math.random() - 0.5) * 0.2,
                        vy: (Math.random() - 0.5) * 0.2,
                        o: Math.random() * 0.5 + 0.15,
                    });
            }

            function draw() {
                x.clearRect(0, 0, W, H);
                for (var i = 0; i < P.length; i++) {
                    var p = P[i];
                    p.x = (p.x + p.vx + W) % W;
                    p.y = (p.y + p.vy + H) % H;
                    x.beginPath();
                    x.arc(p.x, p.y, p.r, 0, 6.283);
                    x.fillStyle = "rgba(125,227,236," + p.o + ")";
                    x.fill();
                    for (var j = i + 1; j < P.length; j++) {
                        var q = P[j],
                            dx = p.x - q.x,
                            dy = p.y - q.y,
                            d = dx * dx + dy * dy;
                        if (d < 12000) {
                            x.beginPath();
                            x.moveTo(p.x, p.y);
                            x.lineTo(q.x, q.y);
                            x.strokeStyle =
                                "rgba(56,189,248," +
                                0.12 * (1 - d / 12000) +
                                ")";
                            x.stroke();
                        }
                    }
                }
                requestAnimationFrame(draw);
            }
            $(window).on("resize", size);
            size();
            draw();
        });
    </script>
@endsection
