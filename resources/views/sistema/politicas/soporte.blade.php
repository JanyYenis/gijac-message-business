@extends('layouts.principal')

@section('title', 'Política de Soporte Técnico | GIJAC MESSAGE BUSINESS')

@section('meta')
    <meta name="description"
        content="{{ __('Conoce el servicio de soporte técnico de GIJAC Message Business: canales de atención, horarios, prioridades, alcance y proceso de atención.') }}">

    <meta name="keywords"
        content="precios WhatsApp Business, planes WhatsApp Business, precios automatización WhatsApp, planes chatbot, WhatsApp API, campañas WhatsApp, inteligencia artificial, automatización empresarial, GIJAC Message Business">

    <meta property="og:locale" content="es_ES">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Política de Soporte Técnico | GIJAC MESSAGE BUSINESS">
    <meta property="og:description"
        content="{{ __('Conoce el servicio de soporte técnico de GIJAC Message Business: canales de atención, horarios, prioridades, alcance y proceso de atención.') }}">
    <meta property="og:url" content="https://message-business.gijac.com/politica-soporte-tecnico">
    <meta property="og:image" content="https://message-business.gijac.com/img/logo_gmb.png">
    <meta property="og:site_name" content="GIJAC MESSAGE BUSINESS">
    <meta property="article:author" content="GIJAC WEB">
    <meta property="og:image:width" content="400">
    <meta property="og:image:height" content="300">

    @php
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Webpage',
            '@id' => 'https://message-business.gijac.com/politica-soporte-tecnico#webpage',
            'name' => 'Política de Soporte Técnico | GIJAC MESSAGE BUSINESS',
            'url' => 'https://message-business.gijac.com/politica-soporte-tecnico',
            'description' => __(
                'Conoce el servicio de soporte técnico de GIJAC Message Business: canales de atención, horarios, prioridades, alcance y proceso de atención.',
            ),
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => 'GIJAC MESSAGE BUSINESS',
                'url' => 'https://message-business.gijac.com',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'GIJAC MESSAGE BUSINESS',
                'url' => 'https://message-business.gijac.com',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => 'https://message-business.gijac.com/img/logo_gmb.png',
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/gmb.css') }}">
    <style>
        /* ============================================================
       GIJAC MESSAGE BUSINESS — Política de Soporte Técnico
       ============================================================ */
        :root {
            --teal: #1E6F78;
            --teal-dark: #145962;
            --teal-mid: #287F88;
            --teal-light: #2C8F99;
            --white: #FFFFFF;
            --slate-50: #F8FAFC;
            --mint: #EEF6F7;
            --ink: #0d2a2e;
            --wa: #25D366;
            --ok: #16a34a;
            --grad: linear-gradient(135deg, #145962, #1E6F78, #2C8F99);
            --shadow-soft: 0 20px 50px -20px rgba(20, 89, 98, .35);
            --shadow-card: 0 10px 30px -18px rgba(20, 89, 98, .35);
            --ease: cubic-bezier(.2, .8, .2, 1);
            --font-head: 'Plus Jakarta Sans', system-ui, sans-serif;
            --font-body: 'Inter', system-ui, sans-serif;
        }

        * {
            scroll-behavior: smooth;
        }

        html,
        body {
            background: var(--slate-50);
            color: var(--ink);
            font-family: var(--font-body);
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: var(--font-head);
            font-weight: 800;
        }

        ::selection {
            background: rgba(44, 143, 153, .25);
        }

        :focus-visible {
            outline: 3px solid rgba(44, 143, 153, .45);
            outline-offset: 2px;
            border-radius: 8px;
        }

        .gradient-text {
            background: linear-gradient(120deg, #2C8F99, #5fd6c9);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* ===== Botones ===== */
        .btn {
            font-family: var(--font-head);
            font-weight: 600;
            border-radius: 999px;
            padding: .7rem 1.5rem;
            transition: all .3s var(--ease);
            border: none;
        }

        .btn-lg {
            padding: .9rem 1.9rem;
        }

        .btn-glow {
            background: var(--grad);
            color: #fff;
            box-shadow: 0 10px 30px -8px rgba(44, 143, 153, .6);
        }

        .btn-glow:hover {
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 16px 40px -8px rgba(44, 143, 153, .9);
        }

        .btn-ghost {
            color: var(--teal-dark);
            background: #fff;
            border: 1px solid rgba(20, 89, 98, .2);
        }

        .btn-ghost:hover {
            background: var(--mint);
            color: var(--teal-dark);
        }

        .btn-light-glow {
            background: #fff;
            color: var(--teal-dark);
            box-shadow: 0 12px 30px -10px rgba(0, 0, 0, .4);
        }

        .btn-light-glow:hover {
            transform: translateY(-3px);
            color: var(--teal-dark);
        }

        .btn-dark-glass {
            background: rgba(255, 255, 255, .08);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .25);
            backdrop-filter: blur(8px);
        }

        .btn-dark-glass:hover {
            background: rgba(255, 255, 255, .18);
            color: #fff;
            transform: translateY(-3px);
        }

        /* ===== Header ===== */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all .4s ease;
            padding: .4rem 0;
        }

        .site-header.scrolled {
            background: rgba(248, 250, 252, .78);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 8px 30px -12px rgba(20, 89, 98, .25);
            border-bottom: 1px solid rgba(20, 89, 98, .08);
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            transition: transform .3s var(--ease);
        }

        .navbar-brand:hover .brand-logo {
            transform: scale(1.06) rotate(-4deg);
        }

        .brand-name {
            font-size: 1rem;
            letter-spacing: .3px;
            color: var(--teal-dark);
        }

        .site-header .brand-name {
            color: #fff !important;
        }

        .site-header.scrolled .brand-name {
            color: var(--teal-dark) !important;
        }

        .site-header .nav-link {
            color: #fff !important;
            font-weight: 500 !important;
            position: relative !important;
            padding: .4rem .9rem !important;
            transition: color .3s ease !important;
        }

        .site-header .nav-link::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 2px;
            width: 0;
            height: 2px;
            background: var(--teal-light);
            transition: all .3s ease;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .site-header .nav-link:hover::after,
        .site-header .nav-link.active::after {
            width: 60%;
        }

        .site-header .nav-link:hover,
        .site-header .nav-link.active {
            color: #fff !important;
        }

        .site-header.scrolled .nav-link {
            color: #123f45 !important;
        }

        .site-header.scrolled .nav-link:hover,
        .site-header.scrolled .nav-link.active {
            color: var(--teal) !important;
        }

        .navbar-toggler {
            border: none;
            font-size: 1.6rem;
            color: var(--teal-dark);
            box-shadow: none;
        }

        .site-header .navbar-toggler {
            color: #fff !important;
        }

        .site-header.scrolled .navbar-toggler {
            color: var(--teal-dark) !important;
        }

        @media (max-width: 991.98px) {
            .site-header .navbar-collapse {
                background: rgba(248, 250, 252, .98);
                border-radius: 16px;
                padding: 1rem 1.2rem;
                margin-top: .6rem;
                box-shadow: var(--shadow-soft);
            }

            .site-header .nav-link,
            .site-header .nav-link:hover,
            .site-header .nav-link.active {
                color: var(--teal-dark) !important;
            }

            .site-header .nav-link::after {
                background: var(--teal-light);
            }

            .site-header .btn-ghost {
                color: var(--teal-dark) !important;
                border-color: rgba(20, 89, 98, .25) !important;
            }
        }

        /* ===== Progreso + back-to-top ===== */
        #readingProgress {
            position: fixed;
            top: 0;
            left: 0;
            height: 4px;
            width: 0%;
            background: linear-gradient(90deg, #145962, #2C8F99, #5fd6c9);
            z-index: 2000;
            transition: width .08s linear;
        }

        /* ===== Hero ===== */
        .policy-hero {
            position: relative;
            z-index: 1;
            padding: 8.5rem 0 7rem;
            background: radial-gradient(1100px 540px at 78% -10%, #1c6b74 0%, #124e57 45%, #0c343b 100%);
            color: #eafaf9;
            overflow: hidden;
        }

        .hero-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: .5;
            z-index: 0;
            pointer-events: none;
        }

        .hero-glow-1 {
            width: 400px;
            height: 400px;
            background: #2C8F99;
            top: -100px;
            right: -80px;
            animation: floatGlow 9s ease-in-out infinite;
        }

        .hero-glow-2 {
            width: 320px;
            height: 320px;
            background: #0f6f6a;
            bottom: -120px;
            left: -60px;
            animation: floatGlow 11s ease-in-out infinite reverse;
        }

        @keyframes floatGlow {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(30px)
            }
        }

        .policy-hero .container {
            position: relative;
            z-index: 2;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .2);
            padding: .45rem 1rem;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 600;
            backdrop-filter: blur(8px);
            margin-bottom: 1.4rem;
        }

        .dot-live {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #4ade80;
            box-shadow: 0 0 0 0 rgba(74, 222, 128, .7);
            animation: pulse 1.8s infinite;
            display: inline-block;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(74, 222, 128, .6)
            }

            70% {
                box-shadow: 0 0 0 8px rgba(74, 222, 128, 0)
            }

            100% {
                box-shadow: 0 0 0 0 rgba(74, 222, 128, 0)
            }
        }

        .hero-title {
            font-size: clamp(2.1rem, 4.6vw, 3.3rem);
            line-height: 1.1;
            letter-spacing: -1px;
            margin: 0 0 1.2rem;
            color: #fff;
        }

        .hero-sub {
            color: #bfe4e2;
            font-size: 1.06rem;
            line-height: 1.75;
            max-width: 720px;
            margin: 0 auto 2rem;
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: .8rem;
        }

        .meta-chip {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .18);
            padding: .5rem 1.05rem;
            border-radius: 999px;
            font-size: .85rem;
            color: #d8f1ef;
            backdrop-filter: blur(8px);
        }

        .meta-chip i {
            color: #6ce0d4;
        }

        /* Ilustración de soporte (CSS puro) */
        .sup-visual {
            position: relative;
            height: 320px;
            margin-top: 3rem;
        }

        .sup-orbit {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 290px;
            height: 290px;
            margin: -145px 0 0 -145px;
            border: 2px dashed rgba(95, 214, 201, .3);
            border-radius: 50%;
            animation: spin 36s linear infinite;
        }

        .sup-orbit::after {
            content: '';
            position: absolute;
            top: -6px;
            left: 50%;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #5fd6c9;
            box-shadow: 0 0 16px #5fd6c9;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        .sup-console {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            border-radius: 24px;
            padding: 1.5rem 1.4rem 1.3rem;
            text-align: center;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .22);
            backdrop-filter: blur(12px);
            box-shadow: 0 30px 70px -30px rgba(0, 0, 0, .7);
            animation: floatY 6s ease-in-out infinite;
        }

        .sup-agent {
            width: 74px;
            height: 74px;
            margin: 0 auto .9rem;
            border-radius: 22px;
            background: var(--grad);
            display: grid;
            place-items: center;
            font-size: 2rem;
            color: #fff;
            box-shadow: 0 14px 30px -10px rgba(44, 143, 153, .8), inset 0 1px 0 rgba(255, 255, 255, .3);
        }

        .sup-console strong {
            display: block;
            font-family: var(--font-head);
            color: #fff;
            font-size: .95rem;
        }

        .sup-line {
            height: 8px;
            border-radius: 6px;
            background: rgba(255, 255, 255, .14);
            margin: .55rem auto 0;
        }

        .sup-line.l1 {
            width: 82%;
        }

        .sup-line.l2 {
            width: 56%;
        }

        .sup-status {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            margin-top: .9rem;
            font-size: .74rem;
            font-weight: 700;
            color: #8fe6b5;
            background: rgba(74, 222, 128, .12);
            border: 1px solid rgba(74, 222, 128, .35);
            padding: .3rem .8rem;
            border-radius: 999px;
        }

        .sup-chip {
            position: absolute;
            width: 56px;
            height: 56px;
            border-radius: 17px;
            display: grid;
            place-items: center;
            font-size: 1.45rem;
            color: #fff;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .25);
            backdrop-filter: blur(10px);
            box-shadow: 0 14px 34px rgba(0, 0, 0, .4);
        }

        .chip-wa {
            top: 8%;
            left: 12%;
            color: var(--wa);
        }

        .chip-tk {
            bottom: 10%;
            left: 4%;
            color: #7ee0d6;
        }

        .chip-mail {
            top: 42%;
            right: 6%;
            color: #bdeee9;
        }

        .float-a {
            animation: floatY 5s ease-in-out infinite;
        }

        .float-b {
            animation: floatY 6s ease-in-out infinite .7s;
        }

        .float-c {
            animation: floatY 5.5s ease-in-out infinite 1.3s;
        }

        @keyframes floatY {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-14px)
            }
        }

        /* ===== Secciones ===== */
        .section {
            position: relative;
            z-index: 1;
            padding: 5rem 0;
        }

        .sec-white {
            background: #fff;
        }

        .sec-alt {
            background: var(--slate-50);
        }

        .sec-mint {
            background: linear-gradient(180deg, #f4fafa, #fff);
        }

        .section-eyebrow {
            color: var(--teal-light);
            font-weight: 700;
            letter-spacing: 2px;
            font-size: .78rem;
            text-transform: uppercase;
            margin-bottom: .5rem;
            display: inline-flex;
            align-items: center;
            gap: .6rem;
        }

        .section-eyebrow::before {
            content: '';
            width: 28px;
            height: 3px;
            border-radius: 3px;
            background: var(--grad);
            display: inline-block;
        }

        .section-title {
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            letter-spacing: -.5px;
            color: var(--ink);
            margin-bottom: .8rem;
        }

        .section-lead {
            color: #5a6b6c;
            max-width: 760px;
            line-height: 1.8;
        }

        .sec-head {
            max-width: 820px;
            margin-bottom: 2.4rem;
        }

        .sec-head.center {
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        .sec-head.center .section-eyebrow {
            justify-content: center;
        }

        .sec-head.center .section-lead {
            margin: 0 auto;
        }

        /* ===== Cards genéricas ===== */
        .gi-card {
            background: #fff;
            border: 1px solid rgba(30, 111, 120, .09);
            border-radius: 22px;
            box-shadow: var(--shadow-card);
            transition: transform .35s var(--ease), box-shadow .35s ease, border-color .35s ease;
        }

        .gi-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 26px 52px -26px rgba(20, 89, 98, .55);
            border-color: rgba(44, 143, 153, .35);
        }

        .gi-card-pad {
            padding: 1.7rem 1.7rem 1.5rem;
        }

        /* ===== Canales ===== */
        .channel-card {
            text-align: center;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .channel-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 18%;
            right: 18%;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(44, 143, 153, .7), transparent);
            opacity: 0;
            transition: opacity .35s ease;
        }

        .channel-card:hover::before {
            opacity: 1;
        }

        .channel-ico {
            width: 68px;
            height: 68px;
            margin: 0 auto 1.05rem;
            border-radius: 20px;
            display: grid;
            place-items: center;
            font-size: 1.7rem;
            color: #fff;
            transition: transform .45s var(--ease);
        }

        .channel-card:hover .channel-ico {
            transform: rotate(-7deg) scale(1.08);
        }

        .ico-mail {
            background: var(--grad);
            box-shadow: 0 12px 26px -10px rgba(44, 143, 153, .7);
        }

        .ico-wa {
            background: linear-gradient(135deg, #128c7e, #25d366);
            box-shadow: 0 12px 26px -10px rgba(37, 211, 102, .6);
        }

        .ico-tk {
            background: linear-gradient(135deg, #145962, #287F88);
            box-shadow: 0 12px 26px -10px rgba(20, 89, 98, .7);
        }

        .channel-card h3 {
            font-size: 1.12rem;
            color: var(--teal-dark);
            margin-bottom: .5rem;
        }

        .channel-card p {
            color: #647476;
            font-size: .92rem;
            line-height: 1.65;
            min-height: 66px;
        }

        .channel-tag {
            display: inline-block;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--teal);
            background: var(--mint);
            border-radius: 999px;
            padding: .28rem .8rem;
            margin-bottom: .9rem;
        }

        /* ===== Horarios ===== */
        .hours-card {
            background: linear-gradient(160deg, #f6fbfb, #fff);
        }

        .hours-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: .85rem 0;
            border-bottom: 1px dashed rgba(30, 111, 120, .12);
        }

        .hours-row:last-child {
            border-bottom: 0;
        }

        .hours-label {
            color: #7d9497;
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .88rem;
        }

        .hours-label i {
            color: var(--teal-light);
        }

        .hours-value {
            font-family: var(--font-head);
            font-weight: 700;
            color: var(--teal-dark);
            font-size: .95rem;
            text-align: right;
        }

        /* ===== Callouts ===== */
        .callout {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            padding: 1.15rem 1.3rem;
            border-radius: 16px;
            border: 1px solid;
            margin: 1.4rem 0;
        }

        .callout-icon {
            flex: 0 0 auto;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 1.15rem;
            color: #fff;
        }

        .callout-title {
            font-family: var(--font-head);
            font-weight: 700;
            font-size: .95rem;
            margin-bottom: .15rem;
        }

        .callout p {
            margin: 0;
            font-size: .92rem;
            line-height: 1.65;
        }

        .callout-info {
            background: #edf5fc;
            border-color: #cfe2f2;
        }

        .callout-info .callout-icon,
        .callout-info .callout-title {
            color: #2470a8;
        }

        .callout-info .callout-icon {
            background: #2470a8;
        }

        .callout-info p {
            color: #33566f;
        }

        .callout-important {
            background: #fdf7e6;
            border-color: #f1e0ae;
        }

        .callout-important .callout-icon,
        .callout-important .callout-title {
            color: #a97a06;
        }

        .callout-important .callout-icon {
            background: #d99a05;
        }

        .callout-important p {
            color: #6b5a2a;
        }

        .callout-tip {
            background: #e9f7f6;
            border-color: #c5e7e5;
        }

        .callout-tip .callout-icon,
        .callout-tip .callout-title {
            color: var(--teal);
        }

        .callout-tip .callout-icon {
            background: var(--teal-light);
        }

        .callout-tip p {
            color: #35605f;
        }

        /* ===== Tabla de prioridades ===== */
        .prio-wrap {
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid rgba(30, 111, 120, .12);
            box-shadow: var(--shadow-card);
            background: #fff;
        }

        .prio-table {
            width: 100%;
            border-collapse: collapse;
        }

        .prio-table th {
            background: var(--grad);
            color: #fff;
            font-family: var(--font-head);
            font-weight: 700;
            font-size: .88rem;
            padding: 1rem 1.2rem;
            border: 0;
            text-align: left;
        }

        .prio-table td {
            padding: 1rem 1.2rem;
            border-bottom: 1px solid rgba(30, 111, 120, .07);
            font-size: .92rem;
            color: #44585a;
            vertical-align: middle;
        }

        .prio-table tbody tr {
            transition: background .2s ease;
        }

        .prio-table tbody tr:hover {
            background: var(--mint);
        }

        .prio-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .prio-badge {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            font-family: var(--font-head);
            font-weight: 700;
            font-size: .76rem;
            letter-spacing: .8px;
            padding: .35rem .85rem;
            border-radius: 999px;
            border: 1px solid;
            white-space: nowrap;
        }

        .prio-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
        }

        .pb-critica {
            background: #fdeeed;
            color: #c0392b;
            border-color: #f3cdca;
        }

        .pb-alta {
            background: #fdf1e7;
            color: #c2620a;
            border-color: #f5ddc4;
        }

        .pb-media {
            background: #fdf7e6;
            color: #a97a06;
            border-color: #f1e0ae;
        }

        .pb-baja {
            background: #e9f7f6;
            color: var(--teal);
            border-color: #c5e7e5;
        }

        .resp-chip {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-weight: 700;
            font-family: var(--font-head);
            color: var(--teal-dark);
            font-size: .86rem;
            white-space: nowrap;
        }

        .resp-chip i {
            color: var(--teal-light);
        }

        @media (max-width: 767.98px) {
            .prio-table thead {
                display: none;
            }

            .prio-table,
            .prio-table tbody,
            .prio-table tr,
            .prio-table td {
                display: block;
                width: 100%;
            }

            .prio-table tr {
                border-radius: 16px;
                border: 1px solid rgba(30, 111, 120, .14);
                margin-bottom: 1rem;
                box-shadow: var(--shadow-card);
                overflow: hidden;
            }

            .prio-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
                padding: .7rem 1rem;
                border-bottom: 1px dashed rgba(30, 111, 120, .1);
                text-align: right;
            }

            .prio-table td:last-child {
                border-bottom: 0;
            }

            .prio-table td::before {
                content: attr(data-label);
                font-family: var(--font-head);
                font-weight: 700;
                color: var(--teal-dark);
                font-size: .72rem;
                text-transform: uppercase;
                letter-spacing: .6px;
                text-align: left;
            }
        }

        /* ===== Alcance ===== */
        .scope-card {
            height: 100%;
        }

        .scope-head {
            display: flex;
            align-items: center;
            gap: .8rem;
            margin-bottom: 1.2rem;
        }

        .scope-ico {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            font-size: 1.3rem;
            color: #fff;
        }

        .si-ok {
            background: linear-gradient(135deg, #15803d, #25d366);
            box-shadow: 0 10px 22px -8px rgba(37, 211, 102, .6);
        }

        .si-no {
            background: linear-gradient(135deg, #5b6b74, #7d9497);
            box-shadow: 0 10px 22px -8px rgba(91, 107, 116, .6);
        }

        .scope-card h3 {
            font-size: 1.12rem;
            margin: 0;
        }

        .scope-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: .75rem;
        }

        .scope-list li {
            display: flex;
            gap: .7rem;
            align-items: flex-start;
            font-size: .92rem;
            color: #3d5254;
            line-height: 1.6;
        }

        .scope-list li i {
            flex: 0 0 auto;
            margin-top: 2px;
            font-size: 1rem;
        }

        .scope-list.ok li i {
            color: var(--ok);
        }

        .scope-list.no li i {
            color: #a3b7b9;
        }

        .scope-include {
            border-top: 4px solid var(--wa);
        }

        .scope-exclude {
            border-top: 4px solid #c3d4d6;
        }

        /* ===== Planes ===== */
        .plan-card {
            position: relative;
            height: 100%;
        }

        .plan-card.featured {
            border-color: rgba(44, 143, 153, .45);
            box-shadow: 0 24px 55px -24px rgba(20, 89, 98, .5);
        }

        .plan-flag {
            position: absolute;
            top: -13px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--grad);
            color: #fff;
            font-family: var(--font-head);
            font-weight: 700;
            font-size: .68rem;
            letter-spacing: 1.4px;
            padding: .32rem .95rem;
            border-radius: 999px;
            box-shadow: 0 8px 18px -8px rgba(44, 143, 153, .7);
            white-space: nowrap;
        }

        .plan-name {
            font-size: 1.15rem;
            color: var(--teal-dark);
            margin-bottom: .25rem;
        }

        .plan-tag {
            font-size: .8rem;
            color: #8aa0a2;
            margin-bottom: 1.2rem;
        }

        .plan-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            padding: .7rem 0;
            border-bottom: 1px dashed rgba(30, 111, 120, .1);
            font-size: .88rem;
        }

        .plan-row:last-of-type {
            border-bottom: 0;
        }

        .plan-row .pr-label {
            color: #7d9497;
            display: flex;
            align-items: center;
            gap: .45rem;
            flex: 0 0 auto;
        }

        .plan-row .pr-label i {
            color: var(--teal-light);
        }

        .plan-row .pr-value {
            font-weight: 600;
            color: #33484a;
            text-align: right;
            font-size: .84rem;
        }

        /* ===== Responsabilidades ===== */
        .resp-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        .resp-item {
            display: flex;
            gap: .9rem;
            align-items: flex-start;
            background: #fff;
            border: 1px solid rgba(30, 111, 120, .09);
            border-radius: 16px;
            padding: 1.05rem 1.1rem;
            transition: all .3s var(--ease);
        }

        .resp-item:hover {
            transform: translateY(-4px);
            border-color: rgba(44, 143, 153, .35);
            box-shadow: var(--shadow-card);
        }

        .resp-ico {
            flex: 0 0 auto;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--mint);
            color: var(--teal);
            display: grid;
            place-items: center;
            font-size: 1.1rem;
        }

        .resp-item strong {
            display: block;
            font-size: .9rem;
            color: var(--teal-dark);
            margin-bottom: .15rem;
        }

        .resp-item small {
            color: #7d9497;
            font-size: .8rem;
            line-height: 1.5;
        }

        /* ===== Mantenimiento ===== */
        .maint-card {
            text-align: center;
            height: 100%;
        }

        .maint-ico {
            width: 58px;
            height: 58px;
            margin: 0 auto .85rem;
            border-radius: 17px;
            background: var(--grad);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 1.45rem;
            box-shadow: 0 10px 22px -8px rgba(44, 143, 153, .65);
        }

        .maint-card h3 {
            font-size: 1.02rem;
            color: var(--teal-dark);
            margin-bottom: .4rem;
        }

        .maint-card p {
            font-size: .88rem;
            color: #647476;
            line-height: 1.6;
            margin: 0;
        }

        .sla-note {
            display: flex;
            align-items: center;
            gap: .6rem;
            justify-content: center;
            font-size: .85rem;
            color: #5a6b6c;
            background: #fff;
            border: 1px dashed rgba(30, 111, 120, .25);
            border-radius: 999px;
            padding: .6rem 1.3rem;
            max-width: 640px;
            margin: 1.6rem auto 0;
        }

        .sla-note i {
            color: var(--teal-light);
        }

        /* ===== Timeline ===== */
        .timeline {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
            position: relative;
            margin: 2.2rem 0 1rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 26px;
            left: 9%;
            right: 9%;
            height: 3px;
            border-radius: 3px;
            background: linear-gradient(90deg, rgba(44, 143, 153, .12), rgba(44, 143, 153, .55), rgba(44, 143, 153, .12));
        }

        .timeline-step {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-dot {
            width: 54px;
            height: 54px;
            margin: 0 auto .85rem;
            border-radius: 50%;
            background: #fff;
            border: 3px solid var(--teal-light);
            color: var(--teal-dark);
            font-family: var(--font-head);
            font-weight: 800;
            display: grid;
            place-items: center;
            box-shadow: 0 10px 22px -10px rgba(44, 143, 153, .55);
            transition: all .35s var(--ease);
        }

        .timeline-step:hover .step-dot {
            background: var(--grad);
            color: #fff;
            transform: scale(1.08);
            border-color: transparent;
        }

        .timeline-step h3 {
            font-size: .98rem;
            color: var(--teal-dark);
            margin-bottom: .35rem;
        }

        .timeline-step p {
            font-size: .84rem;
            color: #647476;
            line-height: 1.55;
            margin: 0;
        }

        @media (max-width: 991.98px) {
            .timeline {
                grid-template-columns: 1fr;
                gap: 1.4rem;
            }

            .timeline::before {
                top: 12px;
                bottom: 12px;
                left: 26px;
                right: auto;
                width: 3px;
                height: auto;
                background: linear-gradient(180deg, rgba(44, 143, 153, .12), rgba(44, 143, 153, .55), rgba(44, 143, 153, .12));
            }

            .timeline-step {
                display: flex;
                gap: 1.1rem;
                text-align: left;
                align-items: flex-start;
            }

            .step-dot {
                margin: 0;
                flex: 0 0 auto;
            }
        }

        /* ===== FAQ acordeón ===== */
        .faq-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-width: 820px;
            margin: 0 auto;
        }

        .faq-item {
            --bs-accordion-btn-icon: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%231E6F78'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
            --bs-accordion-btn-active-icon: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%231E6F78'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
            --bs-accordion-btn-focus-box-shadow: none;
            background: #fff;
            border: 1px solid rgba(30, 111, 120, .1);
            border-radius: 16px !important;
            overflow: hidden;
            box-shadow: var(--shadow-card);
        }

        .faq-item .accordion-button {
            font-family: var(--font-head);
            font-weight: 700;
            color: var(--teal-dark);
            font-size: .98rem;
            padding: 1.05rem 1.25rem;
            background: #fff;
            gap: .75rem;
        }

        .faq-item .accordion-button .faq-q-icon {
            flex: 0 0 auto;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: var(--mint);
            color: var(--teal-light);
            display: grid;
            place-items: center;
            font-size: 1rem;
        }

        .faq-item .accordion-button:not(.collapsed) {
            background: var(--mint);
            color: var(--teal-dark);
            box-shadow: inset 0 -1px 0 rgba(30, 111, 120, .1);
        }

        .faq-item .accordion-button:not(.collapsed) .faq-q-icon {
            background: var(--grad);
            color: #fff;
        }

        .faq-item .accordion-body {
            color: #44585a;
            font-size: .93rem;
            line-height: 1.75;
            padding: 0 1.25rem 1.2rem;
        }

        /* ===== CTA final ===== */
        .cta-final {
            position: relative;
            overflow: hidden;
            border-radius: 30px;
            padding: 3.2rem 2.8rem;
            color: #eafaf9;
            background: radial-gradient(700px 400px at 90% 10%, #1c6b74, #0e3d44 75%);
            border: 1px solid rgba(255, 255, 255, .1);
        }

        .cta-final h2 {
            font-size: clamp(1.7rem, 3.4vw, 2.5rem);
            letter-spacing: -.6px;
            margin-bottom: 1rem;
            color: #fff;
        }

        .cta-final .cta-sub {
            color: #bfe4e2;
            max-width: 620px;
            line-height: 1.75;
            margin-bottom: 2rem;
        }

        .cta-final .h-glow-1 {
            opacity: .35;
        }

        /* ===== Reveal ===== */
        .reveal {
            opacity: 0;
            transition: opacity .8s ease, transform .8s var(--ease);
        }

        .reveal[data-reveal="up"] {
            transform: translateY(36px);
        }

        .reveal.visible {
            opacity: 1;
            transform: none;
        }

        /* ===== Toasts ===== */
        .toast-stack {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 5000;
            display: flex;
            flex-direction: column;
            gap: .6rem;
            max-width: min(380px, calc(100vw - 40px));
        }

        .gi-toast {
            display: flex;
            gap: .8rem;
            align-items: flex-start;
            background: #fff;
            border-radius: 16px;
            padding: .9rem 1rem;
            box-shadow: 0 24px 60px -18px rgba(13, 42, 46, .4);
            border-left: 4px solid var(--tt, var(--teal));
            animation: toastIn .35s var(--ease) both;
        }

        .gi-toast.out {
            animation: toastOut .3s ease both;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateX(30px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        @keyframes toastOut {
            to {
                opacity: 0;
                transform: translateY(10px)
            }
        }

        .gi-toast i {
            color: var(--tt);
            font-size: 1.25rem;
            margin-top: 2px;
        }

        .gi-toast strong {
            display: block;
            font-family: var(--font-head);
            font-size: .88rem;
            color: var(--ink);
        }

        .gi-toast span {
            font-size: .82rem;
            color: #6b8083;
            line-height: 1.45;
        }

        /* ===== Footer ===== */
        .site-footer {
            position: relative;
            z-index: 1;
            background: linear-gradient(180deg, #0e3d44, #0a2c31);
            color: #c9e6e4;
            padding: 4rem 0 1.5rem;
        }

        .footer-brand .brand-name {
            color: #fff;
        }

        .footer-text {
            color: #9dc4c2;
            font-size: .92rem;
            line-height: 1.7;
            max-width: 320px;
        }

        .site-footer h6 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 1.1rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: .7rem;
        }

        .footer-links a {
            color: #9dc4c2;
            text-decoration: none;
            transition: all .25s ease;
        }

        .footer-links a:hover {
            color: #fff;
            padding-left: 6px;
        }

        .social-row {
            display: flex;
            gap: .7rem;
            margin-top: 1.2rem;
        }

        .social-row a {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, .08);
            color: #cdeeeb;
            font-size: 1.1rem;
            transition: all .3s ease;
        }

        .social-row a:hover {
            background: var(--grad);
            color: #fff;
            transform: translateY(-4px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, .1);
            margin-top: 3rem;
            padding-top: 1.5rem;
            text-align: center;
        }

        .footer-bottom p {
            margin: 0;
            color: #7fa8a6;
            font-size: .88rem;
        }

        @media (max-width: 991.98px) {
            .policy-hero {
                padding: 7rem 0 4.5rem;
            }

            .hero-title {
                text-align: center;
            }

            .hero-sub,
            .hero-meta {
                margin-left: auto;
                margin-right: auto;
                justify-content: center;
            }

            .cta-final {
                padding: 2.4rem 1.5rem;
                text-align: center;
            }

            .cta-final .cta-sub {
                margin-left: auto;
                margin-right: auto;
            }

            .cta-final .d-flex {
                justify-content: center;
            }

            .section {
                padding: 3.6rem 0;
            }
        }

        @media (max-width: 575.98px) {
            .sup-visual {
                height: 280px;
                transform: scale(.9);
            }

            .sla-note {
                border-radius: 16px;
                text-align: left;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .reveal {
                transition: none;
                opacity: 1;
                transform: none;
            }

            .sup-orbit,
            .float-a,
            .float-b,
            .float-c,
            .sup-console,
            .hero-glow,
            .dot-live {
                animation: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <!-- ============================================================
        HERO · Política de Soporte Técnico
        BLOQUE DINÁMICO: last_update (Blade: $last_update)
        ============================================================ -->
    <section class="policy-hero">
        <div class="hero-glow hero-glow-1" aria-hidden="true"></div>
        <div class="hero-glow hero-glow-2" aria-hidden="true"></div>
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7 text-center text-lg-start">
                    <span class="badge-pill">
                        <span class="dot-live"></span>
                        GIJAC MESSAGE BUSINESS
                    </span>
                    <h1 class="hero-title">
                        Política de
                        <span class="gradient-text">
                            Soporte Técnico
                        </span>
                    </h1>
                    <p class="hero-sub">
                        Estamos aquí para ayudarte. Conoce cómo funciona nuestro servicio de soporte, los canales de
                        atención y los compromisos que asumimos para acompañarte en el uso de GIJAC Message Business.
                    </p>
                    <div class="hero-meta">
                        <span class="meta-chip">
                            <i class="bi bi-calendar-check"></i>
                            Última actualización: <strong id="lastUpdate">&nbsp;9 Nov 2026</strong>
                        </span>
                        <span class="meta-chip">
                            <i class="bi bi-shield-check"></i>
                            Aplica a todos los planes
                        </span>
                        <span class="meta-chip">
                            <i class="bi bi-file-text"></i>
                            Versión 1.0
                        </span>
                    </div>
                </div>
                <div class="col-lg-5">
                    <!-- Ilustración: consola de soporte animada (CSS puro) -->
                    <div class="sup-visual" aria-hidden="true">
                        <div class="sup-orbit"></div>
                        <div class="sup-console">
                            <div class="sup-agent">
                                <i class="bi bi-headset"></i>
                            </div>
                            <strong>Soporte GIJAC</strong>
                            <div class="sup-line l1"></div>
                            <div class="sup-line l2"></div>
                            <span class="sup-status">
                                <span class="dot-live" style="background:#4ade80"></span>
                                Equipo disponible
                            </span>
                        </div>
                        <span class="sup-chip chip-wa float-a">
                            <i class="bi bi-whatsapp"></i>
                        </span>
                        <span class="sup-chip chip-tk float-b">
                            <i class="bi bi-ticket-perforated"></i>
                        </span>
                        <span class="sup-chip chip-mail float-c">
                            <i class="bi bi-envelope"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
        INTRODUCCIÓN
        ============================================================ -->
    <section class="section sec-white">
        <div class="container">
            <div class="sec-head center reveal" data-reveal="up">
                <span class="section-eyebrow">
                    Introducción
                </span>
                <h2 class="section-title">
                    Nuestro compromiso contigo
                </h2>
                <p class="section-lead">
                    En GIJAC Message Business trabajamos para ofrecer una plataforma confiable que
                    facilite la comunicación y automatización de los procesos empresariales. Por ello, ponemos a disposición
                    de nuestros clientes un servicio de soporte técnico orientado a resolver inconvenientes, atender
                    consultas y acompañar el uso adecuado de las funcionalidades disponibles.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="gi-card gi-card-pad maint-card reveal" data-reveal="up">
                        <span class="maint-ico">
                            <i class="bi bi-compass"></i>
                        </span>
                        <h3>Claridad</h3>
                        <p>Reglas y tiempos definidos para que sepas qué esperar de nuestro servicio en cada situación.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gi-card gi-card-pad maint-card reveal" data-reveal="up">
                        <span class="maint-ico">
                            <i class="bi bi-people"></i>
                        </span>
                        <h3>Cercanía</h3>
                        <p>Canales de atención pensados para acompañarte durante todo el uso de la plataforma.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gi-card gi-card-pad maint-card reveal" data-reveal="up">
                        <span class="maint-ico">
                            <i class="bi bi-graph-up-arrow"></i>
                        </span>
                        <h3>Mejora continua</h3>
                        <p>Cada solicitud nos ayuda a mejorar la plataforma y la experiencia de todos nuestros clientes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
        CANALES DE ATENCIÓN
        BLOQUE DINÁMICO: canales (Laravel: foreach($channels as $c))
        ============================================================ -->
    <section class="section sec-alt" id="canales">
        <div class="container">
            <div class="sec-head center reveal" data-reveal="up">
                <span class="section-eyebrow">
                    Canales de atención
                </span>
                <h2 class="section-title">¿Por dónde puedes contactarnos?</h2>
                <p class="section-lead">Disponemos de canales oficiales para atender tus consultas, solicitudes y reportes.
                    Elige el que mejor se adapte a tu necesidad.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="gi-card gi-card-pad channel-card reveal" data-reveal="up">
                        <span class="channel-ico ico-mail">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <h3>Correo electrónico</h3>
                        <span class="channel-tag">Consultas y reportes</span>
                        <p>Para consultas, solicitudes y reporte de inconvenientes relacionados con la plataforma.</p>
                        <a href="mailto:soporte@gijac.co" class="btn btn-ghost channel-link" data-channel="email">
                            <i class="bi bi-box-arrow-up-right me-1"></i>
                            Abrir correo
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gi-card gi-card-pad channel-card reveal" data-reveal="up">
                        <span class="channel-ico ico-wa">
                            <i class="bi bi-whatsapp"></i>
                        </span>
                        <h3>WhatsApp</h3>
                        <span class="channel-tag">Según tu plan</span>
                        <p>Atención y acompañamiento directo según las condiciones del plan contratado.</p>
                        <a href="https://wa.me/573171789584" class="btn btn-ghost channel-link" data-channel="whatsapp">
                            <i class="bi bi-box-arrow-up-right me-1"></i>
                            Iniciar chat
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gi-card gi-card-pad channel-card reveal" data-reveal="up">
                        <span class="channel-ico ico-tk">
                            <i class="bi bi-ticket-perforated"></i>
                        </span>
                        <h3>Sistema de tickets</h3>
                        <span class="channel-tag">Recomendado</span>
                        <p>
                            Para registrar, realizar seguimiento y consultar el estado de tus solicitudes en todo momento.
                        </p>
                        <a href="{{ route('tickets.index') }}" class="btn btn-glow channel-link" data-channel="tickets">
                            <i class="bi bi-plus-lg me-1"></i>
                            Crear ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
        HORARIOS DE ATENCIÓN
        BLOQUE DINÁMICO: horarios (Blade: $schedule['days'] etc.)
        ============================================================ -->
    <section class="section sec-white" id="horarios">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5 reveal" data-reveal="up">
                    <span class="section-eyebrow">
                        Horarios
                    </span>
                    <h2 class="section-title">
                        Horarios de atención
                    </h2>
                    <p class="section-lead">
                        Nuestro equipo atiende dentro del siguiente horario. Las solicitudes recibidas
                        fuera de este periodo serán atendidas durante el siguiente periodo hábil.
                    </p>
                </div>
                <div class="col-lg-7 reveal" data-reveal="up">
                    <div class="gi-card gi-card-pad hours-card">
                        <div class="hours-row">
                            <span class="hours-label">
                                <i class="bi bi-calendar-week"></i>
                                Días de atención
                            </span>
                            <span class="hours-value" data-support="days">
                                Lunes a viernes
                            </span>
                        </div>
                        <div class="hours-row">
                            <span class="hours-label">
                                <i class="bi bi-clock"></i>
                                Horario de atención
                            </span>
                            <span class="hours-value" data-support="hours">
                                9:00 – 18:00 h
                            </span>
                        </div>
                        <div class="hours-row">
                            <span class="hours-label">
                                <i class="bi bi-globe2"></i>
                                Zona horaria
                            </span>
                            <span class="hours-value" data-support="tz">
                                (GMT-5) · América/Bogotá
                            </span>
                        </div>
                        <div class="callout callout-tip mb-0 mt-3">
                            <span class="callout-icon">
                                <i class="bi bi-moon-stars text-white"></i>
                            </span>
                            <div>
                                <div class="callout-title">Fuera de horario</div>
                                <p>Las solicitudes recibidas fuera del horario establecido serán atendidas durante el
                                    siguiente periodo hábil.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
        PRIORIDADES
        ============================================================ -->
    <section class="section sec-alt" id="prioridades">
        <div class="container">
            <div class="sec-head center reveal" data-reveal="up">
                <span class="section-eyebrow">
                    Prioridades
                </span>
                <h2 class="section-title">Clasificación de solicitudes</h2>
                <p class="section-lead">Cada solicitud se evalúa y clasifica según su impacto en la operación para
                    asignarle el nivel de atención adecuado.</p>
            </div>
            <div class="prio-wrap reveal" data-reveal="up">
                <table class="prio-table">
                    <caption class="visually-hidden">
                        Niveles de prioridad de soporte y tiempos de primera respuesta
                    </caption>
                    <thead>
                        <tr>
                            <th scope="col">Nivel</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Primera respuesta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-label="Nivel">
                                <span class="prio-badge pb-critica">
                                    <span class="prio-dot"></span>
                                    CRÍTICA
                                </span>
                            </td>
                            <td data-label="Descripción">Interrupción total de la plataforma o indisponibilidad
                                generalizada de funcionalidades esenciales.</td>
                            <!-- EDITABLE: tiempo de respuesta Crítica -->
                            <td data-label="Primera respuesta">
                                <span class="resp-chip">
                                    <i class="bi bi-clock"></i>
                                    4 horas hábiles
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Nivel">
                                <span class="prio-badge pb-alta">
                                    <span class="prio-dot"></span>
                                    ALTA
                                </span>
                            </td>
                            <td data-label="Descripción">Problemas que afectan funcionalidades importantes y dificultan
                                significativamente la operación.</td>
                            <td data-label="Primera respuesta">
                                <span class="resp-chip">
                                    <i class="bi bi-clock"></i>
                                    8 horas hábiles
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Nivel">
                                <span class="prio-badge pb-media">
                                    <span class="prio-dot"></span>
                                    MEDIA
                                </span>
                            </td>
                            <td data-label="Descripción">Errores parciales o inconvenientes que permiten continuar
                                trabajando mediante alternativas.</td>
                            <td data-label="Primera respuesta">
                                <span class="resp-chip">
                                    <i class="bi bi-clock"></i>
                                    24 horas hábiles
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Nivel">
                                <span class="prio-badge pb-baja">
                                    <span class="prio-dot"></span>
                                    BAJA
                                </span>
                            </td>
                            <td data-label="Descripción">Consultas generales, solicitudes de orientación y dudas sobre
                                funcionalidades.</td>
                            <td data-label="Primera respuesta">
                                <span class="resp-chip">
                                    <i class="bi bi-clock"></i>
                                    48 horas hábiles
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="callout callout-important reveal" data-reveal="up">
                <span class="callout-icon">
                    <i class="bi bi-info-circle-fill text-white"></i>
                </span>
                <div>
                    <div class="callout-title">Sobre los tiempos de respuesta</div>
                    <p>Los tiempos indicados corresponden a la <strong>primera respuesta</strong> y no garantizan la
                        resolución definitiva del inconveniente dentro de ese mismo periodo. Se contabilizan en horas
                        hábiles, dentro del horario de atención publicado.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
                 ALCANCE DEL SOPORTE
                 ============================================================ -->
    <section class="section sec-white" id="alcance">
        <div class="container">
            <div class="sec-head center reveal" data-reveal="up">
                <span class="section-eyebrow">Alcance</span>
                <h2 class="section-title">¿Qué incluye el soporte técnico?</h2>
                <p class="section-lead">Definimos con transparencia qué está cubierto por el soporte estándar y qué temas
                    se manejan por fuera del servicio.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="gi-card gi-card-pad scope-card scope-include reveal" data-reveal="up">
                        <div class="scope-head">
                            <span class="scope-ico si-ok">
                                <i class="bi bi-check-circle"></i>
                            </span>
                            <h3>Incluido en el soporte</h3>
                        </div>
                        <ul class="scope-list ok">
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                Orientación sobre el funcionamiento de la plataforma.
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                Atención de errores técnicos relacionados con GIJAC Message Business.
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                Ayuda con la configuración de funcionalidades disponibles en el plan.
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                Orientación sobre campañas y administración de contactos.
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                Asistencia sobre automatizaciones y chatbots.
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                Orientación sobre integraciones disponibles.
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                Reporte y seguimiento de incidentes.
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="gi-card gi-card-pad scope-card scope-exclude reveal" data-reveal="up">
                        <div class="scope-head">
                            <span class="scope-ico si-no">
                                <i class="bi bi-slash-circle"></i>
                            </span>
                            <h3>Fuera del alcance del soporte estándar</h3>
                        </div>
                        <ul class="scope-list no">
                            <li>
                                <i class="bi bi-dash-circle-fill"></i>
                                Desarrollo de funcionalidades personalizadas.
                            </li>
                            <li>
                                <i class="bi bi-dash-circle-fill"></i>
                                Integraciones externas no contempladas en el servicio.
                            </li>
                            <li>
                                <i class="bi bi-dash-circle-fill"></i>
                                Administración de infraestructura de terceros.
                            </li>
                            <li>
                                <i class="bi bi-dash-circle-fill"></i>
                                Problemas ocasionados por configuraciones externas ajenas a GIJAC.
                            </li>
                            <li>
                                <i class="bi bi-dash-circle-fill"></i>
                                Capacitación especializada no incluida en el plan.
                            </li>
                            <li>
                                <i class="bi bi-dash-circle-fill"></i>
                                Recuperación de información eliminada por acciones del cliente cuando no exista un mecanismo de recuperación disponible.
                            </li>
                        </ul>
                        <div class="callout callout-info mb-0 mt-3">
                            <span class="callout-icon">
                                <i class="bi bi-plus-circle-fill text-white"></i>
                            </span>
                            <div>
                                <div class="callout-title">Servicios adicionales</div>
                                <p>Algunos de estos servicios podrán contratarse de manera independiente, cuando estén
                                    disponibles.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
        SOPORTE SEGÚN EL PLAN
        BLOQUE DINÁMICO: planes (Laravel: foreach($plans))
        ============================================================ -->
    <section class="section sec-mint" id="planes">
        <div class="container">
            <div class="sec-head center reveal" data-reveal="up">
                <span class="section-eyebrow">Planes</span>
                <h2 class="section-title">Soporte según el plan contratado</h2>
                <p class="section-lead">Los beneficios de acompañamiento varían según el plan. La siguiente información es
                    referencial y se ajusta a las condiciones comerciales de cada plan.</p>
            </div>
            <div class="row g-4 pt-2">
                <div class="col-lg-4">
                    <div class="gi-card gi-card-pad plan-card reveal" data-reveal="up">
                        <h3 class="plan-name">Plan Básico</h3>
                        <p class="plan-tag">Esencial para comenzar</p>
                        <div class="plan-row">
                            <span class="pr-label">
                                <i class="bi bi-envelope"></i>
                                Canales
                            </span>
                            <span class="pr-value">Correo + Tickets</span>
                        </div>
                        <div class="plan-row">
                            <span class="pr-label">
                                <i class="bi bi-sort-down"></i>
                                Prioridad
                            </span>
                            <span class="pr-value">Estándar</span>
                        </div>
                        <div class="plan-row">
                            <span class="pr-label">
                                <i class="bi bi-question-circle"></i>
                                Acompañamiento
                            </span>
                            <span class="pr-value">Guía estándar</span>
                        </div>
                        <div class="plan-row">
                            <span class="pr-label">
                                <i class="bi bi-award"></i>
                                Asistencia especializada
                            </span>
                            <span class="pr-value">No incluida</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="gi-card gi-card-pad plan-card featured reveal" data-reveal="up">
                        <span class="plan-flag">MÁS POPULAR</span>
                        <h3 class="plan-name">Plan Profesional</h3>
                        <p class="plan-tag">Para equipos en crecimiento</p>
                        <div class="plan-row">
                            <span class="pr-label">
                                <i class="bi bi-envelope"></i>
                                Canales
                            </span>
                            <span class="pr-value">Correo + Tickets + WhatsApp</span>
                        </div>
                        <div class="plan-row">
                            <span class="pr-label">
                                <i class="bi bi-sort-down"></i>
                                Prioridad
                            </span>
                            <span class="pr-value">Alta</span>
                        </div>
                        <div class="plan-row">
                            <span class="pr-label">
                                <i class="bi bi-question-circle"></i>
                                Acompañamiento
                            </span>
                            <span class="pr-value">Configuración inicial</span>
                        </div>
                        <div class="plan-row">
                            <span class="pr-label">
                                <i class="bi bi-award"></i>
                                Asistencia especializada
                            </span>
                            <span class="pr-value">Sesiones puntuales</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="gi-card gi-card-pad plan-card reveal" data-reveal="up">
                        <h3 class="plan-name">Plan Empresarial</h3>
                        <p class="plan-tag">Acompañamiento completo</p>
                        <div class="plan-row">
                            <span class="pr-label">
                                <i class="bi bi-envelope"></i>
                                Canales
                            </span>
                            <span class="pr-value">Todos los canales disponibles</span>
                        </div>
                        <div class="plan-row">
                            <span class="pr-label">
                                <i class="bi bi-sort-down"></i>
                                Prioridad
                            </span>
                            <span class="pr-value">Máxima</span>
                        </div>
                        <div class="plan-row">
                            <span class="pr-label">
                                <i class="bi bi-question-circle"></i>
                                Acompañamiento
                            </span>
                            <span class="pr-value">Dedicado</span>
                        </div>
                        <div class="plan-row">
                            <span class="pr-label">
                                <i class="bi bi-award"></i>
                                Asistencia especializada
                            </span>
                            <span class="pr-value">Incluida</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="callout callout-important reveal" data-reveal="up">
                <span class="callout-icon">
                    <i class="bi bi-exclamation-triangle-fill text-white"></i>
                </span>
                <div>
                    <div class="callout-title">Información referencial</div>
                    <p>Los beneficios mostrados son configurables y no constituyen compromisos definitivos. Las condiciones
                        definitivas de soporte corresponden a las establecidas en las condiciones comerciales de cada plan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
        RESPONSABILIDADES DEL CLIENTE
        ============================================================ -->
    <section class="section sec-white" id="responsabilidades">
        <div class="container">
            <div class="sec-head center reveal" data-reveal="up">
                <span class="section-eyebrow">Colaboración</span>
                <h2 class="section-title">Responsabilidades del cliente</h2>
                <p class="section-lead">Una buena atención comienza con una buena comunicación. Estas prácticas nos ayudan
                    a resolver tus solicitudes de forma más rápida y precisa.</p>
            </div>
            <div class="resp-grid reveal" data-reveal="up">
                <div class="resp-item">
                    <span class="resp-ico">
                        <i class="bi bi-chat-left-dots"></i>
                    </span>
                    <span>
                        <strong>Información clara</strong>
                        <small>Describe el inconveniente con el mayor detalle posible.</small>
                    </span>
                </div>
                <div class="resp-item">
                    <span class="resp-ico">
                        <i class="bi bi-paperclip"></i>
                    </span>
                    <span>
                        <strong>Evidencias</strong>
                        <small>Adjunta capturas o archivos cuando sea necesario.</small>
                    </span>
                </div>
                <div class="resp-item">
                    <span class="resp-ico">
                        <i class="bi bi-list-check"></i>
                    </span>
                    <span>
                        <strong>Pasos de reproducción</strong>
                        <small>Indica cómo reproducir el error paso a paso.</small>
                    </span>
                </div>
                <div class="resp-item">
                    <span class="resp-ico">
                        <i class="bi bi-person-check"></i>
                    </span>
                    <span>
                        <strong>Datos actualizados</strong>
                        <small>Mantén vigentes tus datos de contacto.</small>
                    </span>
                </div>
                <div class="resp-item">
                    <span class="resp-ico">
                        <i class="bi bi-shield-lock"></i>
                    </span>
                    <span>
                        <strong>Uso adecuado</strong>
                        <small>Utiliza correctamente las funcionalidades contratadas.</small>
                    </span>
                </div>
                <div class="resp-item">
                    <span class="resp-ico">
                        <i class="bi bi-key"></i>
                    </span>
                    <span>
                        <strong>Credenciales seguras</strong>
                        <small>No compartas contraseñas, códigos OTP ni credenciales sensibles. GIJAC
                            nunca las solicitará.</small>
                    </span>
                </div>
                <div class="resp-item">
                    <span class="resp-ico">
                        <i class="bi bi-plug"></i>
                    </span>
                    <span>
                        <strong>Integraciones al día</strong>
                        <small>Mantén tus
                            integraciones externas correctamente configuradas.</small>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
        MANTENIMIENTO Y DISPONIBILIDAD
        ============================================================ -->
    <section class="section sec-alt" id="mantenimiento">
        <div class="container">
            <div class="sec-head center reveal" data-reveal="up">
                <span class="section-eyebrow">Plataforma</span>
                <h2 class="section-title">Mantenimiento y disponibilidad</h2>
                <p class="section-lead">GIJAC podrá realizar mantenimientos preventivos, correctivos y actualizaciones para
                    mejorar la seguridad, estabilidad y funcionamiento de la plataforma.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="gi-card gi-card-pad maint-card reveal" data-reveal="up">
                        <span class="maint-ico">
                            <i class="bi bi-shield-check"></i>
                        </span>
                        <h3>Preventivo</h3>
                        <p>Mantenimientos planificados para preservar la seguridad y el rendimiento de la plataforma.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gi-card gi-card-pad maint-card reveal" data-reveal="up">
                        <span class="maint-ico">
                            <i class="bi bi-tools"></i>
                        </span>
                        <h3>Correctivo</h3>
                        <p>Intervenciones para resolver incidencias detectadas y restablecer el funcionamiento normal.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gi-card gi-card-pad maint-card reveal" data-reveal="up">
                        <span class="maint-ico">
                            <i class="bi bi-cloud-arrow-down"></i>
                        </span>
                        <h3>Actualizaciones</h3>
                        <p>Mejoras y nuevas funcionalidades que se incorporan de forma continua a la plataforma.</p>
                    </div>
                </div>
            </div>
            <div class="callout callout-tip reveal" data-reveal="up">
                <span class="callout-icon">
                    <i class="bi bi-megaphone text-white"></i>
                </span>
                <div>
                    <div class="callout-title">Mantenimientos programados</div>
                    <p>Cuando sea posible, se comunicarán previamente los mantenimientos programados por los canales
                        oficiales.</p>
                </div>
            </div>
            <div class="callout callout-info mb-0 reveal" data-reveal="up">
                <span class="callout-icon">
                    <i class="bi bi-box-arrow-up-right text-white"></i>
                </span>
                <div>
                    <div class="callout-title">Servicios de terceros</div>
                    <p>Las interrupciones relacionadas con servicios de terceros, proveedores de infraestructura o
                        plataformas externas deberán analizarse según su origen y alcance.</p>
                </div>
            </div>
            <div class="sla-note reveal" data-reveal="up">
                <i class="bi bi-file-earmark-text"></i>
                No se establecen porcentajes de disponibilidad garantizada sin que exista un SLA formal acordado.
            </div>
        </div>
    </section>

    <!-- ============================================================
        PROCESO DE ATENCIÓN
        ============================================================ -->
    <section class="section sec-white" id="proceso">
        <div class="container">
            <div class="sec-head center reveal" data-reveal="up">
                <span class="section-eyebrow">Proceso</span>
                <h2 class="section-title">¿Cómo atendemos tu solicitud?</h2>
                <p class="section-lead">Cada solicitud sigue un proceso claro de cinco etapas, con seguimiento y
                    comunicación en todo momento.</p>
            </div>
            <div class="timeline reveal" data-reveal="up">
                <div class="timeline-step">
                    <div class="step-dot">01</div>
                    <h3>Registro</h3>
                    <p>El cliente presenta su solicitud por un canal habilitado.</p>
                </div>
                <div class="timeline-step">
                    <div class="step-dot">02</div>
                    <h3>Clasificación</h3>
                    <p>GIJAC evalúa el inconveniente y asigna una prioridad.</p>
                </div>
                <div class="timeline-step">
                    <div class="step-dot">03</div>
                    <h3>Atención</h3>
                    <p>El equipo revisa la información y realiza las verificaciones necesarias.</p>
                </div>
                <div class="timeline-step">
                    <div class="step-dot">04</div>
                    <h3>Seguimiento</h3>
                    <p>Se informa al cliente sobre los avances del caso.</p>
                </div>
                <div class="timeline-step">
                    <div class="step-dot">05</div>
                    <h3>Cierre</h3>
                    <p>Se comunica el resultado y se finaliza la solicitud.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
        PREGUNTAS FRECUENTES
        ============================================================ -->
    <section class="section sec-alt" id="faq">
        <div class="container">
            <div class="sec-head center reveal" data-reveal="up">
                <span class="section-eyebrow">FAQ</span>
                <h2 class="section-title">Preguntas frecuentes</h2>
                <p class="section-lead">Respuestas rápidas a las dudas más comunes sobre nuestro servicio de soporte.</p>
            </div>
            <div class="accordion faq-list reveal" data-reveal="up" id="faqAccordion">
                <div class="accordion-item faq-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1"
                            aria-expanded="true" aria-controls="faq1">
                            <span class="faq-q-icon">
                                <i class="bi bi-life-preserver"></i>
                            </span>
                            ¿Cómo puedo solicitar soporte técnico?
                        </button>
                    </h3>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">Puedes solicitar soporte a través de los canales habilitados: correo
                            electrónico, WhatsApp (según las condiciones de tu plan) y el sistema de tickets. Para un mejor
                            seguimiento recomendamos registrar tus solicitudes como tickets, ya que podrás consultar su
                            estado en cualquier momento.</div>
                    </div>
                </div>
                <div class="accordion-item faq-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                            <span class="faq-q-icon">
                                <i class="bi bi-clock-history"></i>
                            </span>
                            ¿Cuánto tiempo tarda GIJAC en responder?
                        </button>
                    </h3>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">El tiempo de <strong>primera respuesta</strong> depende de la prioridad
                            asignada: Crítica 4 horas hábiles, Alta 8, Media 24 y Baja 48. Estos tiempos corresponden
                            únicamente a la primera respuesta y no garantizan la resolución definitiva dentro de ese mismo
                            periodo.</div>
                    </div>
                </div>
                <div class="accordion-item faq-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                            <span class="faq-q-icon">
                                <i class="bi bi-whatsapp"></i>
                            </span>
                            ¿Puedo solicitar soporte por WhatsApp?
                        </button>
                    </h3>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">Sí, la atención por WhatsApp está disponible según las condiciones del
                            plan contratado. Para solicitudes que requieran seguimiento formal y trazabilidad, recomendamos
                            complementar la conversación con un ticket.</div>
                    </div>
                </div>
                <div class="accordion-item faq-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                            <span class="faq-q-icon">
                                <i class="bi bi-meta"></i>
                            </span>
                            ¿Qué sucede si mi problema depende de Meta o WhatsApp?
                        </button>
                    </h3>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">Analizamos el caso y realizamos las verificaciones dentro de la
                            plataforma. Cuando el origen del inconveniente corresponde a servicios de terceros como Meta, te
                            orientaremos sobre los alcances y las acciones posibles conforme a esta política.</div>
                    </div>
                </div>
                <div class="accordion-item faq-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">
                            <span class="faq-q-icon">
                                <i class="bi bi-code-slash"></i>
                            </span>
                            ¿El soporte incluye desarrollos personalizados?
                        </button>
                    </h3>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">No. El desarrollo de funcionalidades personalizadas está fuera del
                            alcance del soporte estándar. Algunos servicios adicionales podrán contratarse de manera
                            independiente, cuando estén disponibles.</div>
                    </div>
                </div>
                <div class="accordion-item faq-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq6" aria-expanded="false" aria-controls="faq6">
                            <span class="faq-q-icon">
                                <i class="bi bi-robot"></i>
                            </span>
                            ¿Puedo solicitar ayuda para configurar mis chatbots?
                        </button>
                    </h3>
                    <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">Sí. La asistencia sobre automatizaciones y chatbots forma parte del
                            soporte incluido, así como la orientación sobre la configuración de las funcionalidades
                            disponibles en tu plan.</div>
                    </div>
                </div>
                <div class="accordion-item faq-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq7" aria-expanded="false" aria-controls="faq7">
                            <span class="faq-q-icon">
                                <i class="bi bi-ticket-perforated"></i>
                            </span>
                            ¿Cómo puedo consultar el estado de mi solicitud?
                        </button>
                    </h3>
                    <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">Desde el sistema de tickets puedes consultar en cualquier momento el
                            estado de tus solicitudes, así como revisar las respuestas y actualizaciones del equipo de
                            soporte.</div>
                    </div>
                </div>
                <div class="accordion-item faq-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq8" aria-expanded="false" aria-controls="faq8">
                            <span class="faq-q-icon">
                                <i class="bi bi-box-seam"></i>
                            </span>
                            ¿Qué sucede si mi plan no incluye determinado servicio?
                        </button>
                    </h3>
                    <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">Te informaremos las alternativas disponibles: algunas capacidades
                            pueden corresponder a un plan superior o podrán contratarse de manera independiente, cuando
                            estén disponibles, según las condiciones comerciales vigentes.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
        CONTACTO Y CIERRE
        BLOQUE DINÁMICO: enlaces CTA (Blade: route('...'))
        ============================================================ -->
    <section class="section sec-white" id="contacto">
        <div class="container">
            <div class="cta-final reveal" data-reveal="up">
                <div class="hero-glow hero-glow-1" aria-hidden="true"></div>
                <div class="row align-items-center g-4 position-relative" style="z-index:2">
                    <div class="col-lg-8">
                        <h2>¿Necesitas ayuda?</h2>
                        <p class="cta-sub">Nuestro objetivo es ayudarte a aprovechar al máximo las herramientas de GIJAC
                            Message Business. Si tienes alguna inquietud o inconveniente, utiliza nuestros canales de
                            atención para recibir acompañamiento.</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('contactarnos') }}" class="btn btn-light-glow channel-link" data-channel="email">
                                <i class="bi bi-headset me-2"></i>
                                Contactar soporte
                            </a>
                            <a href="{{ route('tickets.index') }}" class="btn btn-glow channel-link" data-channel="tickets">
                                <i class="bi bi-ticket-perforated me-2"></i>
                                Crear solicitud
                            </a>
                            <a href="{{ route('preguntas') }}" class="btn btn-dark-glass"><i class="bi bi-patch-question me-2"></i>
                                Preguntas frecuentes</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(function() {
            'use strict';

            /* ==========================================================
               1. CONFIGURACIÓN EDITABLE (en Blade, proviene del CMS/DB)
               Deja en '' para conservar el texto por defecto del HTML.
               ========================================================== */
            const PAGE_CONFIG = {
                // Fecha de última actualización (Blade: $last_update )
                lastUpdate: '',
                // URLs de los canales (Blade: route('support.email') etc.)
                channels: {
                    email: '',
                    whatsapp: '',
                    tickets: ''
                },
                // Horarios (Blade: $schedule->days …)
                schedule: {
                    days: '',
                    hours: '',
                    tz: ''
                }
            };

            if (PAGE_CONFIG.lastUpdate) {
                $('#lastUpdate').text(' ' + PAGE_CONFIG.lastUpdate);
            }
            $.each(PAGE_CONFIG.schedule, function(k, v) {
                if (v) {
                    $('[data-support="' + k + '"]').text(v);
                }
            });
            $('.channel-link').each(function() {
                const url = PAGE_CONFIG.channels[$(this).data('channel')];
                if (url) {
                    this.href = url;
                }
            });
            /* Canales sin URL configurada → aviso en lugar de enlace roto */
            $(document).on('click', '.channel-link', function(e) {
                if (!PAGE_CONFIG.channels[$(this).data('channel')]) {
                    e.preventDefault();
                    toast('info', 'Canal en configuración',
                        'Configura la URL de este canal en PAGE_CONFIG para habilitarlo.');
                }
            });

            /* ==========================================================
               2. TOASTS
               ========================================================== */
            function toast(type, title, msg) {
                const cfg = {
                    success: ['bi-check-circle-fill', '#16a34a'],
                    info: ['bi-info-circle-fill', '#2C8F99'],
                    warning: ['bi-exclamation-triangle-fill', '#d99a05'],
                    error: ['bi-x-circle-fill', '#d9534f']
                } [type];
                const $t = $('<div class="gi-toast" style="--tt:' + cfg[1] + '"><i class="bi ' + cfg[0] +
                    '"></i><div><strong>' + title + '</strong><span>' + msg + '</span></div></div>');
                $('#toastStack').append($t);
                setTimeout(function() {
                    $t.addClass('out');
                    setTimeout(function() {
                        $t.remove();
                    }, 320);
                }, 4000);
            }

            /* ==========================================================
               4. REVEAL AL HACER SCROLL
               ========================================================== */
            if ('IntersectionObserver' in window) {
                const io = new IntersectionObserver(function(entries) {
                    entries.forEach(function(en) {
                        if (en.isIntersecting) {
                            en.target.classList.add('visible');
                            io.unobserve(en.target);
                        }
                    });
                }, {
                    threshold: 0.1
                });
                document.querySelectorAll('.reveal').forEach(function(el) {
                    io.observe(el);
                });
            } else {
                $('.reveal').addClass('visible');
            }
        });
    </script>
@endsection
