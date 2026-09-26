@extends('layouts.principal')

@section('meta')
    <meta name="description"
        content="{{ __('Descubre qué es la API de WhatsApp Business, en qué se diferencia de la app normal y por qué es clave para escalar ventas, atención y automatización en tu empresa.') }}">
    <meta name="author" content="GIJAC WEB">
    <link rel="canonical"
        href="https://message-business.gijac.com/api-whatsapp-business-importancia-empresas">
    <!-- Open Graph -->
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="GIJAC MESSAGE BUSINESS">
    <meta property="og:title"
        content="{{ __('API de WhatsApp Business: Qué es y por qué tu empresa la necesita en 2026') }}">
    <meta property="og:description"
        content="{{ __('Descubre qué es la API de WhatsApp Business, en qué se diferencia de la app normal y por qué es clave para escalar ventas, atención y automatización en tu empresa.') }}">
    <meta property="og:url"
        content="https://message-business.gijac.com/api-whatsapp-business-importancia-empresas">
    <meta property="og:image"
        content="https://message-business.gijac.com/img/logo_gmb.png">
    <meta property="og:image:alt"
        content="GIJAC Message Business - API de WhatsApp Business">
    <meta property="article:published_time"
        content="2026-09-24T09:00:00-05:00">
    <meta property="article:modified_time"
        content="2026-09-24T09:00:00-05:00">
    <meta property="article:author"
        content="GIJAC WEB">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title"
        content="{{ __('API de WhatsApp Business: Qué es y por qué tu empresa la necesita en 2026') }}">
    <meta name="twitter:description"
        content="{{ __('Descubre qué es la API de WhatsApp Business, en qué se diferencia de la app normal y por qué es clave para escalar ventas, atención y automatización en tu empresa.') }}">
    <meta name="twitter:image"
        content="https://message-business.gijac.com/img/logo_gmb.png">

    @verbatim
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "BlogPosting",
                "@id": "https://message-business.gijac.com/api-whatsapp-business-importancia-empresas#blogposting",
                "url": "https://message-business.gijac.com/api-whatsapp-business-importancia-empresas",
                "headline": "API de WhatsApp Business: Qué es y por qué tu empresa la necesita en 2026",
                "description": "Descubre qué es la API de WhatsApp Business, en qué se diferencia de la app normal y por qué es clave para escalar ventas, atención y automatización en tu empresa.",
                "image": {
                    "@type": "ImageObject",
                    "url": "https://message-business.gijac.com/img/logo_gmb.png"
                },
                "author": {
                    "@type": "Organization",
                    "name": "GIJAC WEB",
                    "url": "https://gijac.com"
                },
                "publisher": {
                    "@type": "Organization",
                    "name": "GIJAC MESSAGE BUSINESS",
                    "url": "https://message-business.gijac.com",
                    "logo": {
                        "@type": "ImageObject",
                        "url": "https://message-business.gijac.com/img/logo_gmb.png"
                    }
                },
                "datePublished": "2026-09-24T09:00:00-05:00",
                "dateModified": "2026-09-24T09:00:00-05:00",
                "mainEntityOfPage": {
                    "@type": "WebPage",
                    "@id": "https://message-business.gijac.com/api-whatsapp-business-importancia-empresas"
                }
            }
        </script>
    @endverbatim
@endsection

@section('css')
    <style>
        /* ============================================================
       GIJAC MESSAGE BUSINESS — Estilos base (entregados por el cliente)
       ============================================================ */
        :root {
            --teal: #1e6f78;
            --teal-dark: #145962;
            --teal-mid: #287f88;
            --teal-light: #2c8f99;
            --white: #ffffff;
            --slate-50: #f8fafc;
            --mint: #eef6f7;
            --ink: #0d2a2e;
            --grad: linear-gradient(135deg, #145962, #1e6f78, #2c8f99);
            --shadow-soft: 0 20px 50px -20px rgba(20, 89, 98, 0.35);
            --font-head: "Plus Jakarta Sans", system-ui, sans-serif;
            --font-body: "Inter", system-ui, sans-serif;
        }

        * {
            scroll-behavior: smooth;
        }

        html,
        body {
            background-color: var(--slate-50);
            color: var(--ink);
            font-family: var(--font-body);
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .brand-name {
            font-family: var(--font-head);
            font-weight: 800;
        }

        .gradient-text {
            background: linear-gradient(120deg, #2c8f99, #5fd6c9);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn {
            font-family: var(--font-head);
            font-weight: 600;
            border-radius: 999px;
            padding: 0.7rem 1.5rem;
            transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
            border: none;
        }

        .btn-lg {
            padding: 0.9rem 1.9rem;
        }

        .btn-glow {
            background: var(--grad);
            color: #fff;
            box-shadow: 0 10px 30px -8px rgba(44, 143, 153, 0.6);
        }

        .btn-glow:hover {
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 16px 40px -8px rgba(44, 143, 153, 0.9);
        }

        .btn-ghost {
            color: var(--teal-dark);
            background: transparent;
            border: 1px solid rgba(20, 89, 98, 0.2);
        }

        .btn-ghost:hover {
            background: var(--mint);
            color: var(--teal-dark);
        }

        .site-header .btn-ghost {
            color: #fff !important;
            background: transparent !important;
            border: 1px solid rgba(255, 255, 255, 0.35) !important;
            transition: all 0.3s ease !important;
        }

        .site-header .btn-ghost:hover {
            background: rgba(255, 255, 255, 0.12) !important;
            color: #fff !important;
            border-color: rgba(255, 255, 255, 0.6) !important;
        }

        .site-header.scrolled .btn-ghost {
            color: var(--teal-dark) !important;
            background: transparent !important;
            border: 1px solid rgba(20, 89, 98, 0.2) !important;
        }

        .site-header.scrolled .btn-ghost:hover {
            background: var(--mint) !important;
            color: var(--teal-dark) !important;
            border-color: rgba(20, 89, 98, 0.3) !important;
        }

        .btn-dark-glass {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(8px);
        }

        .btn-dark-glass:hover {
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
            transform: translateY(-3px);
        }

        .btn-outline-teal {
            color: var(--teal);
            background: #fff;
            border: 1px solid rgba(30, 111, 120, 0.25);
            box-shadow: var(--shadow-soft);
        }

        .btn-outline-teal:hover {
            background: var(--teal);
            color: #fff;
            transform: translateY(-3px);
        }

        .btn-light-glow {
            background: #fff;
            color: var(--teal-dark);
            box-shadow: 0 12px 30px -10px rgba(0, 0, 0, 0.4);
        }

        .btn-light-glow:hover {
            transform: translateY(-3px);
            color: var(--teal-dark);
            box-shadow: 0 16px 40px -10px rgba(0, 0, 0, 0.5);
        }

        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.4s ease;
            padding: 0.4rem 0;
        }

        .site-header.scrolled {
            background: rgba(248, 250, 252, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 8px 30px -12px rgba(20, 89, 98, 0.25);
            border-bottom: 1px solid rgba(20, 89, 98, 0.08);
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.15));
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover .brand-logo {
            transform: scale(1.08) rotate(-4deg);
        }

        .brand-name {
            font-size: 1rem;
            letter-spacing: 0.3px;
            color: var(--teal-dark);
            transition: color 0.3s ease;
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
            padding: 0.4rem 0.9rem !important;
            transition: color 0.3s ease !important;
        }

        .site-header .nav-link::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 2px;
            width: 0;
            height: 2px;
            background: var(--teal-light);
            transition: all 0.3s ease;
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

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.45rem 1rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
            backdrop-filter: blur(8px);
            margin-bottom: 1.4rem;
        }

        .dot-live {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #4ade80;
            box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.7);
            animation: pulse 1.8s infinite;
            display: inline-block;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.6);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(74, 222, 128, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(74, 222, 128, 0);
            }
        }

        .float-slow {
            animation: floatY 6s ease-in-out infinite;
        }

        .float-a {
            animation: floatY 4s ease-in-out infinite;
        }

        .float-b {
            animation: floatY 5s ease-in-out infinite 0.5s;
        }

        .float-c {
            animation: floatY 4.5s ease-in-out infinite 1s;
        }

        @keyframes floatY {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-16px);
            }
        }

        @keyframes floatGlow {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(30px);
            }
        }

        .section {
            position: relative;
            z-index: 1;
            padding: 6rem 0;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(14px);
            box-shadow: var(--shadow-soft);
        }

        .floating-chip {
            position: absolute;
            width: 58px;
            height: 58px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            font-size: 1.5rem;
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .chip-1 {
            top: 6%;
            left: -18px;
            color: #25d366;
        }

        .chip-2 {
            bottom: 22%;
            right: -20px;
            color: #7ee0d6;
        }

        .chip-3 {
            bottom: -10px;
            left: 24%;
            color: #bdeee9;
        }

        .app-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            z-index: 0;
            pointer-events: none;
        }

        .app-orb-1 {
            width: 360px;
            height: 360px;
            background: #2c8f99;
            opacity: 0.35;
            top: -80px;
            left: -60px;
            animation: floatGlow 10s ease-in-out infinite;
        }

        .app-orb-2 {
            width: 300px;
            height: 300px;
            background: #145962;
            opacity: 0.45;
            bottom: -90px;
            right: 10%;
            animation: floatGlow 13s ease-in-out infinite reverse;
        }

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
            font-size: 0.92rem;
            line-height: 1.7;
            max-width: 320px;
        }

        .site-footer h6 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 1.1rem;
            font-family: var(--font-head);
        }

        .footer-links,
        .footer-contact {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.7rem;
        }

        .footer-links a {
            color: #9dc4c2;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .footer-links a:hover {
            color: #fff;
            padding-left: 6px;
        }

        .footer-contact li {
            color: #9dc4c2;
            font-size: 0.92rem;
            display: flex;
            gap: 0.6rem;
            align-items: flex-start;
        }

        .footer-contact i {
            color: #6ce0d4;
            margin-top: 3px;
        }

        .social-row {
            display: flex;
            gap: 0.7rem;
            margin-top: 1.2rem;
        }

        .social-row a {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, 0.08);
            color: #cdeeeb;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .social-row a:hover {
            background: var(--grad);
            color: #fff;
            transform: translateY(-4px);
            box-shadow: 0 10px 20px -6px rgba(44, 143, 153, 0.7);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 3rem;
            padding-top: 1.5rem;
            text-align: center;
        }

        .footer-bottom p {
            margin: 0;
            color: #7fa8a6;
            font-size: 0.88rem;
        }

        #back-to-top {
            position: fixed;
            right: 24px;
            bottom: 87px;
            z-index: 999;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: none;
            color: #fff;
            background: var(--grad);
            box-shadow: 0 10px 24px -6px rgba(44, 143, 153, 0.8);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.35s ease;
            cursor: pointer;
        }

        #back-to-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        #back-to-top:hover {
            transform: translateY(-4px);
        }

        .reveal {
            opacity: 0;
            transition:
                opacity 0.8s ease,
                transform 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .reveal[data-reveal="up"] {
            transform: translateY(40px);
        }

        .reveal[data-reveal="right"] {
            transform: translateX(-40px);
        }

        .reveal.visible {
            opacity: 1;
            transform: none;
        }

        .tilt-3d {
            transform-style: preserve-3d;
            transition: transform 0.2s ease;
            will-change: transform;
        }

        @media (max-width: 991px) {
            .header-cta {
                margin-top: 1rem;
            }

            .site-header .navbar-collapse {
                background: rgba(248, 250, 252, 0.98);
                border-radius: 16px;
                padding: 1rem 1.2rem;
                margin-top: 0.6rem;
                box-shadow: var(--shadow-soft);
            }
        }

        @media (max-width: 575px) {
            .brand-name {
                font-size: 0.85rem;
            }
        }

        /* ============================================================
       ARTÍCULO — Sistema de componentes reutilizable (GIJAC Blog)
       Bloques dinámicos previstos: paragraph · heading · image ·
       video · gallery · quote · table · alert · list · code · CTA
       ============================================================ */

        /* --- Barra de progreso de lectura --- */
        #readingProgress {
            position: fixed;
            top: 0;
            left: 0;
            height: 4px;
            width: 0%;
            background: linear-gradient(90deg, #145962, #2c8f99, #5fd6c9);
            z-index: 2000;
            transition: width 0.08s linear;
        }

        /* --- Skeleton de imágenes --- */
        .img-loading {
            background: linear-gradient(100deg,
                    #e6edf0 40%,
                    #f4f8fa 50%,
                    #e6edf0 60%);
            background-size: 200% 100%;
            animation: shimmer 1.2s infinite linear;
        }

        @keyframes shimmer {
            to {
                background-position: -200% 0;
            }
        }

        /* --- Header móvil: links legibles sobre panel claro --- */
        @media (max-width: 991.98px) {

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
                border-color: rgba(20, 89, 98, 0.25) !important;
            }
        }

        /* --- Hero del artículo (oscuro corporativo) --- */
        .article-hero {
            position: relative;
            z-index: 1;
            padding: 7.5rem 0 7.5rem;
            background: radial-gradient(1100px 540px at 78% -10%,
                    #1c6b74 0%,
                    #124e57 45%,
                    #0c343b 100%);
            color: #eafaf9;
            overflow: hidden;
        }

        .hero-glow-1,
        .hero-glow-2 {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.5;
            z-index: 0;
            pointer-events: none;
        }

        .hero-glow-1 {
            width: 400px;
            height: 400px;
            background: #2c8f99;
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

        .article-hero .container {
            position: relative;
            z-index: 2;
        }

        .article-breadcrumb {
            font-size: 0.84rem;
            margin-bottom: 2.4rem;
        }

        .article-breadcrumb .breadcrumb {
            margin: 0;
        }

        .article-breadcrumb a {
            color: #a8ded9;
            text-decoration: none;
            transition: color 0.25s ease;
        }

        .article-breadcrumb a:hover {
            color: #fff;
        }

        .article-breadcrumb .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(168, 222, 217, 0.5);
        }

        .article-breadcrumb .breadcrumb-item.active {
            color: #eafaf9;
            font-weight: 600;
        }

        .article-cat {
            color: #7ee0d6;
            letter-spacing: 3px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
        }

        .article-cat::before {
            content: "";
            width: 28px;
            height: 2px;
            background: var(--teal-light);
            border-radius: 2px;
            display: inline-block;
        }

        .article-title {
            font-size: clamp(1.9rem, 4.6vw, 3.4rem);
            line-height: 1.12;
            letter-spacing: -1px;
            max-width: 960px;
            margin: 0 auto 1.3rem;
            color: #fff;
        }

        .article-sub {
            color: #bfe4e2;
            font-size: 1.08rem;
            line-height: 1.75;
            max-width: 820px;
            margin: 0 auto 2rem;
        }

        .article-meta {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.8rem;
        }

        .meta-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 0.5rem 1.05rem;
            border-radius: 999px;
            font-size: 0.85rem;
            color: #d8f1ef;
            backdrop-filter: blur(8px);
        }

        .meta-chip i {
            color: #6ce0d4;
        }

        .author-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--grad);
            color: #fff;
            display: grid;
            place-items: center;
            font-family: var(--font-head);
            font-weight: 800;
            font-size: 0.8rem;
        }

        /* --- Imagen principal (16:9, solapada sobre el hero) --- */
        .featured-wrap {
            position: relative;
            z-index: 5;
            max-width: 1080px;
            margin: -5.2rem auto 0;
            padding: 0 1.25rem;
        }

        .featured-figure {
            margin: 0;
        }

        .featured-figure img {
            width: 100%;
            aspect-ratio: 16/9;
            object-fit: cover;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.65);
            box-shadow: 0 40px 90px -35px rgba(13, 42, 46, 0.55);
            display: block;
        }

        /* --- Layout de artículo --- */
        .article-layout {
            position: relative;
            z-index: 1;
            background: #fff;
            padding: 4.5rem 0 5.5rem;
        }

        .article-content {
            max-width: 780px;
        }

        .article-section {
            scroll-margin-top: 105px;
        }

        .article-content h2 {
            font-size: clamp(1.55rem, 2.6vw, 2.1rem);
            color: var(--teal-dark);
            letter-spacing: -0.5px;
            margin: 0 0 1.1rem;
        }

        .article-content h2::before {
            content: "";
            display: block;
            width: 46px;
            height: 4px;
            border-radius: 4px;
            background: var(--grad);
            margin-bottom: 0.9rem;
        }

        .article-content h3 {
            font-size: 1.22rem;
            color: var(--teal-dark);
            margin: 1.8rem 0 0.6rem;
        }

        .article-content p {
            color: #3d5254;
            line-height: 1.85;
            font-size: 1.02rem;
            margin-bottom: 1.15rem;
        }

        .article-content .lead-para {
            font-size: 1.12rem;
            color: #2e4346;
        }

        .article-content a:not(.btn) {
            color: var(--teal-light);
            font-weight: 600;
            text-decoration: underline;
            text-decoration-color: rgba(44, 143, 153, 0.35);
            text-underline-offset: 3px;
            transition: all 0.25s ease;
        }

        .article-content a:not(.btn):hover {
            color: var(--teal-dark);
            text-decoration-color: var(--teal-dark);
        }

        .article-content strong {
            color: var(--teal-dark);
        }

        .article-content ul.fancy-list {
            list-style: none;
            padding: 0;
            margin: 0 0 1.2rem;
        }

        .article-content ul.fancy-list li {
            position: relative;
            padding-left: 1.7rem;
            margin-bottom: 0.55rem;
            color: #3d5254;
            line-height: 1.7;
        }

        .article-content ul.fancy-list li::before {
            content: "\F26E";
            font-family: "bootstrap-icons";
            position: absolute;
            left: 0;
            top: 2px;
            color: var(--teal-light);
        }

        .separator {
            border: 0;
            height: 2px;
            border-radius: 2px;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(44, 143, 153, 0.4),
                    transparent);
            margin: 2.6rem 0;
        }

        /* --- Declaración destacada (intro) --- */
        .highlight-statement {
            display: flex;
            align-items: center;
            gap: 1.1rem;
            background: linear-gradient(120deg, #e9f7f6, #f4fbfa);
            border-left: 5px solid var(--teal-light);
            border-radius: 16px;
            padding: 1.35rem 1.5rem;
            margin: 1.8rem 0;
            font-family: var(--font-head);
            font-weight: 700;
            font-size: 1.12rem;
            color: var(--teal-dark);
            box-shadow: 0 16px 36px -28px rgba(20, 89, 98, 0.6);
        }

        .highlight-statement .hs-icon {
            flex: 0 0 auto;
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, #25d366, #128c7e);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 1.4rem;
            box-shadow: 0 10px 20px -8px rgba(18, 140, 126, 0.7);
        }

        /* --- Callouts reutilizables (alert: info | importante | consejo | advertencia) --- */
        .callout {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            padding: 1.2rem 1.3rem;
            border-radius: 16px;
            border: 1px solid;
            margin: 1.6rem 0;
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
            font-size: 0.98rem;
            margin-bottom: 0.2rem;
        }

        .callout p {
            margin: 0;
            font-size: 0.93rem;
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

        .callout-danger {
            background: #fdeeed;
            border-color: #f3cdca;
        }

        .callout-danger .callout-icon,
        .callout-danger .callout-title {
            color: #c0392b;
        }

        .callout-danger .callout-icon {
            background: #d9534f;
        }

        .callout-danger p {
            color: #7a3b37;
        }

        /* --- Chip de definición --- */
        .def-chip {
            display: inline-block;
            background: var(--mint);
            border: 1.5px dashed var(--teal-light);
            color: var(--teal-dark);
            font-family: var(--font-head);
            font-weight: 700;
            padding: 0.55rem 1.1rem;
            border-radius: 12px;
            margin: 0.3rem 0 1rem;
            font-size: 0.95rem;
        }

        /* --- Mini comparación App vs API --- */
        .vs-wrap {
            display: flex;
            gap: 1rem;
            align-items: stretch;
            margin: 1.8rem 0;
        }

        .vs-card {
            flex: 1;
            background: #fff;
            border-radius: 18px;
            padding: 1.35rem 1.4rem;
            border: 1px solid rgba(30, 111, 120, 0.1);
            box-shadow: 0 14px 34px -26px rgba(20, 89, 98, 0.55);
        }

        .vs-card.vs-api {
            border-top: 4px solid var(--teal-light);
            background: linear-gradient(180deg, #f6fbfb, #ffffff 40%);
        }

        .vs-card.vs-app {
            border-top: 4px solid #c3d4d6;
        }

        .vs-head {
            font-family: var(--font-head);
            font-weight: 700;
            color: var(--teal-dark);
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .vs-head i {
            font-size: 1.25rem;
            color: var(--teal-light);
        }

        .vs-card p {
            margin: 0 !important;
            font-size: 0.94rem;
        }

        .vs-arrow {
            align-self: center;
            flex: 0 0 auto;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--grad);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 1.25rem;
            box-shadow: 0 10px 22px -8px rgba(44, 143, 153, 0.7);
        }

        @media (max-width: 767.98px) {
            .vs-wrap {
                flex-direction: column;
            }

            .vs-arrow {
                transform: rotate(90deg);
                align-self: center;
            }
        }

        /* --- Tabla comparativa responsive --- */
        .compare-wrap {
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid rgba(30, 111, 120, 0.12);
            box-shadow: 0 20px 46px -30px rgba(20, 89, 98, 0.55);
            background: #fff;
            margin: 1.8rem 0;
        }

        .table-compare {
            margin: 0;
            width: 100%;
        }

        .table-compare thead th {
            background: var(--grad);
            color: #fff;
            font-family: var(--font-head);
            font-weight: 700;
            font-size: 0.92rem;
            padding: 1rem 1.1rem;
            border: 0;
            vertical-align: middle;
        }

        .table-compare thead i {
            margin-right: 0.45rem;
            font-size: 1.05rem;
        }

        .table-compare tbody td {
            padding: 0.95rem 1.1rem;
            vertical-align: middle;
            border-color: rgba(30, 111, 120, 0.08);
            font-size: 0.93rem;
            color: #44585a;
        }

        .table-compare tbody tr:last-child td {
            border-bottom: 0;
        }

        .table-compare td.feat {
            font-family: var(--font-head);
            font-weight: 700;
            color: var(--teal-dark);
        }

        .api-check {
            color: var(--teal-light);
            margin-right: 0.5rem;
        }

        @media (max-width: 767.98px) {
            .table-compare thead {
                display: none;
            }

            .table-compare,
            .table-compare tbody,
            .table-compare tr,
            .table-compare td {
                display: block;
                width: 100%;
            }

            .table-compare tr {
                border-radius: 16px;
                border: 1px solid rgba(30, 111, 120, 0.14);
                margin-bottom: 1rem;
                box-shadow: 0 12px 26px -20px rgba(20, 89, 98, 0.55);
                overflow: hidden;
            }

            .table-compare td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
                padding: 0.75rem 1rem;
                border: 0;
                border-bottom: 1px dashed rgba(30, 111, 120, 0.12);
                text-align: right;
            }

            .table-compare td:last-child {
                border-bottom: 0;
            }

            .table-compare td::before {
                content: attr(data-label);
                font-family: var(--font-head);
                font-weight: 700;
                color: var(--teal-dark);
                font-size: 0.74rem;
                text-transform: uppercase;
                letter-spacing: 0.6px;
                text-align: left;
                flex: 0 0 auto;
            }
        }

        /* --- 7 razones --- */
        .reasons-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.2rem;
            margin-top: 1.8rem;
        }

        .reason-card {
            background: #fff;
            border-radius: 20px;
            padding: 1.5rem 1.5rem 1.3rem;
            border: 1px solid rgba(30, 111, 120, 0.09);
            box-shadow: 0 16px 40px -30px rgba(20, 89, 98, 0.6);
            transition:
                transform 0.35s cubic-bezier(0.2, 0.8, 0.2, 1),
                box-shadow 0.35s ease,
                border-color 0.35s ease;
            position: relative;
            overflow: hidden;
        }

        .reason-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 26px 52px -26px rgba(20, 89, 98, 0.6);
            border-color: rgba(44, 143, 153, 0.35);
        }

        .reason-card:last-child:nth-child(odd) {
            grid-column: 1 / -1;
        }

        .reason-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.8rem;
        }

        .reason-num {
            font-family: var(--font-head);
            font-weight: 800;
            font-size: 2.4rem;
            line-height: 1;
            background: linear-gradient(120deg, #2c8f99, #5fd6c9);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            opacity: 0.95;
        }

        .reason-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: var(--grad);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 1.3rem;
            box-shadow: 0 10px 20px -8px rgba(44, 143, 153, 0.65);
        }

        .reason-card h3 {
            font-size: 1.08rem;
            color: var(--teal-dark);
            margin: 0 0 0.4rem;
        }

        .reason-card p {
            font-size: 0.9rem;
            line-height: 1.65;
            margin: 0 !important;
            color: #647476;
        }

        @media (max-width: 991.98px) {
            .reasons-grid {
                grid-template-columns: 1fr;
            }
        }

        /* --- Bloque de video --- */
        .video-block {
            position: relative;
            display: block;
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 22px;
            overflow: hidden;
            border: 0;
            padding: 0;
            cursor: pointer;
            box-shadow: 0 30px 70px -30px rgba(13, 42, 46, 0.6);
            background: #0c343b;
        }

        .video-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.6s ease;
        }

        .video-block:hover .video-thumb {
            transform: scale(1.04);
        }

        .video-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg,
                    rgba(12, 52, 59, 0.1),
                    rgba(12, 52, 59, 0.7));
            display: grid;
            place-items: center;
        }

        .play-btn {
            width: 86px;
            height: 86px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(8px);
            color: #fff;
            font-size: 2rem;
            display: grid;
            place-items: center;
            padding-left: 6px;
            animation: playPulse 2.2s infinite;
            transition:
                transform 0.3s ease,
                background 0.3s ease;
        }

        .video-block:hover .play-btn {
            transform: scale(1.1);
            background: rgba(255, 255, 255, 0.25);
        }

        @keyframes playPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.35);
            }

            70% {
                box-shadow: 0 0 0 22px rgba(255, 255, 255, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }

        .video-tag {
            position: absolute;
            right: 16px;
            bottom: 14px;
            background: rgba(0, 0, 0, 0.55);
            color: #fff;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.3rem 0.7rem;
            border-radius: 8px;
            backdrop-filter: blur(6px);
        }

        .video-caption {
            text-align: center;
            font-size: 0.85rem;
            color: #6b7f81;
            margin-top: 0.8rem;
        }

        /* --- Figuras dentro del artículo ---
       Variantes listas para el CMS: .af-full (100%) · .af-center (centrada) ·
       .af-left / .af-right (flotadas ~46%)                                --- */
        .article-figure {
            margin: 2rem 0;
        }

        .article-figure img {
            width: 100%;
            border-radius: 18px;
            border: 1px solid rgba(30, 111, 120, 0.1);
            box-shadow: 0 22px 50px -30px rgba(20, 89, 98, 0.6);
            display: block;
        }

        .article-figure figcaption {
            font-size: 0.85rem;
            color: #6b7f81;
            margin-top: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            text-align: center;
        }

        .article-figure figcaption i {
            color: var(--teal-light);
        }

        .af-center {
            max-width: 640px;
            margin-left: auto;
            margin-right: auto;
        }

        .af-left {
            float: left;
            max-width: 46%;
            margin: 0.4rem 1.6rem 1rem 0;
        }

        .af-right {
            float: right;
            max-width: 46%;
            margin: 0.4rem 0 1rem 1.6rem;
        }

        .article-figure.clearfix {
            clear: both;
        }

        @media (max-width: 767.98px) {

            .af-left,
            .af-right {
                float: none;
                max-width: 100%;
                margin: 1.4rem 0;
            }
        }

        /* --- Galería + Lightbox --- */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin: 1.8rem 0;
        }

        .gallery-item {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            cursor: zoom-in;
            border: 1px solid rgba(30, 111, 120, 0.1);
            box-shadow: 0 14px 34px -26px rgba(20, 89, 98, 0.6);
        }

        .gallery-item img {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
            display: block;
            transition: transform 0.55s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.07);
        }

        .gallery-item::after {
            content: "\F2F0";
            font-family: "bootstrap-icons";
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            font-size: 1.6rem;
            color: #fff;
            background: rgba(12, 52, 59, 0.45);
            opacity: 0;
            transition: opacity 0.35s ease;
        }

        .gallery-item:hover::after {
            opacity: 1;
        }

        @media (max-width: 767.98px) {
            .gallery-grid {
                grid-template-columns: 1fr;
            }
        }

        .lightbox {
            position: fixed;
            inset: 0;
            z-index: 3000;
            background: rgba(7, 20, 25, 0.93);
            backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .lightbox.open {
            display: flex;
        }

        .lb-figure {
            margin: 0;
            max-width: min(1000px, 92vw);
            text-align: center;
        }

        .lb-figure img {
            max-width: 100%;
            max-height: 76vh;
            border-radius: 16px;
            box-shadow: 0 40px 90px rgba(0, 0, 0, 0.6);
        }

        .lb-caption {
            color: #cdeeeb;
            margin-top: 0.9rem;
            font-size: 0.9rem;
        }

        .lb-counter {
            display: inline-block;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 999px;
            padding: 0.25rem 0.85rem;
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
        }

        .lb-btn {
            position: absolute;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            font-size: 1.25rem;
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .lb-btn:hover {
            background: var(--grad);
            border-color: transparent;
            transform: scale(1.08);
        }

        .lb-close {
            top: 22px;
            right: 22px;
        }

        .lb-prev {
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
        }

        .lb-next {
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
        }

        .lb-prev:hover,
        .lb-next:hover {
            transform: translateY(-50%) scale(1.08);
        }

        @media (max-width: 575px) {
            .lb-prev {
                left: 8px;
            }

            .lb-next {
                right: 8px;
            }
        }

        /* --- Casos de uso por industria --- */
        .industry-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.1rem;
            margin-top: 1.8rem;
        }

        .industry-card {
            background: #fff;
            border-radius: 18px;
            padding: 1.5rem 1.1rem;
            text-align: center;
            border: 1px solid rgba(30, 111, 120, 0.09);
            box-shadow: 0 16px 38px -30px rgba(20, 89, 98, 0.6);
            transition:
                transform 0.35s cubic-bezier(0.2, 0.8, 0.2, 1),
                box-shadow 0.35s ease;
            height: 100%;
        }

        .industry-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 26px 50px -26px rgba(20, 89, 98, 0.55);
        }

        .industry-icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 0.9rem;
            border-radius: 16px;
            background: var(--grad);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 1.45rem;
            box-shadow: 0 12px 24px -10px rgba(44, 143, 153, 0.65);
            transition: transform 0.45s ease;
        }

        .industry-card:hover .industry-icon {
            transform: rotate(-8deg) scale(1.08);
        }

        .industry-card h3 {
            font-size: 1.02rem;
            color: var(--teal-dark);
            margin-bottom: 0.4rem;
        }

        .industry-card p {
            font-size: 0.85rem;
            color: #647476;
            line-height: 1.55;
            margin: 0 !important;
        }

        /* --- Timeline de pasos --- */
        .timeline {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
            position: relative;
            margin: 2.2rem 0 1rem;
        }

        .timeline::before {
            content: "";
            position: absolute;
            top: 26px;
            left: 9%;
            right: 9%;
            height: 3px;
            border-radius: 3px;
            background: linear-gradient(90deg,
                    rgba(44, 143, 153, 0.12),
                    rgba(44, 143, 153, 0.55),
                    rgba(44, 143, 153, 0.12));
        }

        .timeline-step {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-dot {
            width: 54px;
            height: 54px;
            margin: 0 auto 0.85rem;
            border-radius: 50%;
            background: #fff;
            border: 3px solid var(--teal-light);
            color: var(--teal-dark);
            font-family: var(--font-head);
            font-weight: 800;
            display: grid;
            place-items: center;
            box-shadow: 0 10px 22px -10px rgba(44, 143, 153, 0.55);
        }

        .timeline-step h3 {
            font-size: 0.98rem;
            color: var(--teal-dark);
            margin-bottom: 0.35rem;
        }

        .timeline-step p {
            font-size: 0.84rem;
            color: #647476;
            line-height: 1.55;
            margin: 0 !important;
        }

        @media (max-width: 991.98px) {
            .timeline {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .timeline::before {
                top: 12px;
                bottom: 12px;
                left: 26px;
                right: auto;
                width: 3px;
                height: auto;
                background: linear-gradient(180deg,
                        rgba(44, 143, 153, 0.12),
                        rgba(44, 143, 153, 0.55),
                        rgba(44, 143, 153, 0.12));
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

        /* --- Bloque de código --- */
        .code-block {
            background: #0b2d33;
            border-radius: 18px;
            overflow: hidden;
            margin: 1.8rem 0;
            box-shadow: 0 22px 50px -30px rgba(10, 45, 51, 0.9);
        }

        .code-head {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.8rem 1.1rem;
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .code-dot {
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
        }

        .code-lang {
            margin-left: 0.6rem;
            color: #7ee0d6;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .code-block pre {
            margin: 0;
            padding: 1.2rem 1.3rem;
            color: #d7f0ee;
            font-family: "Cascadia Code", Consolas, monospace;
            font-size: 0.84rem;
            line-height: 1.75;
            overflow-x: auto;
        }

        .tk {
            color: #7ee0d6;
        }

        .ts {
            color: #ffd479;
        }

        .tc {
            color: #6f9a99;
        }

        /* --- Cita --- */
        .article-quote {
            position: relative;
            background: var(--mint);
            border-left: 5px solid var(--teal);
            border-radius: 20px;
            padding: 1.7rem 2rem 1.5rem 3.6rem;
            margin: 2rem 0;
        }

        .article-quote .qmark {
            position: absolute;
            left: 1.1rem;
            top: 1.15rem;
            font-size: 2rem;
            color: var(--teal-light);
        }

        .article-quote p {
            font-family: var(--font-head);
            font-weight: 700;
            font-size: 1.18rem;
            color: var(--teal-dark);
            margin: 0 0 0.4rem !important;
            line-height: 1.5;
        }

        .article-quote footer {
            color: #647476;
            font-size: 0.87rem;
        }

        /* --- Byline de autor --- */
        .byline {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--mint);
            border: 1px solid rgba(30, 111, 120, 0.1);
            border-radius: 18px;
            padding: 1.1rem 1.3rem;
            margin-top: 2.4rem;
        }

        .byline .author-avatar {
            width: 48px;
            height: 48px;
            font-size: 1.1rem;
            flex: 0 0 auto;
        }

        .byline h4 {
            font-size: 1rem;
            color: var(--teal-dark);
            margin: 0;
        }

        .byline p {
            margin: 0 !important;
            font-size: 0.85rem;
            color: #647476;
        }

        /* --- Sidebar --- */
        .sidebar-sticky {
            position: sticky;
            top: 100px;
            display: flex;
            flex-direction: column;
            gap: 1.4rem;
        }

        .sidebar-card {
            background: #fff;
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid rgba(30, 111, 120, 0.09);
            box-shadow: 0 18px 44px -32px rgba(20, 89, 98, 0.65);
        }

        .sidebar-card h3 {
            font-size: 1.02rem;
            color: var(--teal-dark);
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin-bottom: 1rem;
        }

        .sidebar-card h3 i {
            color: var(--teal-light);
        }

        .toc-list {
            list-style: none;
            counter-reset: toc;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .toc-link {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.55rem 0.7rem;
            border-radius: 12px;
            color: #4a6163;
            font-size: 0.92rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.25s ease;
            border: 1px solid transparent;
        }

        .toc-num {
            counter-increment: toc;
            flex: 0 0 auto;
            width: 26px;
            height: 26px;
            border-radius: 8px;
            background: var(--mint);
            color: var(--teal);
            font-size: 0.74rem;
            font-weight: 700;
            display: grid;
            place-items: center;
            transition: all 0.25s ease;
        }

        .toc-num::before {
            content: counter(toc);
        }

        .toc-link:hover {
            background: var(--mint);
            color: var(--teal-dark);
        }

        .toc-link.active {
            background: var(--grad);
            color: #fff;
            box-shadow: 0 10px 20px -10px rgba(44, 143, 153, 0.7);
        }

        .toc-link.active .toc-num {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .toc-toggle {
            width: 100%;
            background: none;
            border: 0;
            padding: 0;
            font-family: var(--font-head);
            font-weight: 700;
            font-size: 1rem;
            color: var(--teal-dark);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .toc-toggle .bi {
            transition: transform 0.3s ease;
        }

        .toc-toggle[aria-expanded="true"] .bi {
            transform: rotate(180deg);
        }

        .sidebar-cta {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            padding: 1.7rem 1.5rem;
            color: #eafaf9;
            background: radial-gradient(320px 220px at 100% 0%,
                    #1c6b74,
                    #0e3d44 78%);
        }

        .sidebar-cta::after {
            content: "";
            position: absolute;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: #2c8f99;
            filter: blur(60px);
            opacity: 0.4;
            bottom: -60px;
            left: -40px;
        }

        .sidebar-cta h3 {
            color: #fff;
            font-size: 1.15rem;
            position: relative;
            z-index: 1;
        }

        .sidebar-cta p {
            color: #bfe4e2;
            font-size: 0.9rem;
            line-height: 1.65;
            position: relative;
            z-index: 1;
        }

        .sidebar-cta .btn {
            position: relative;
            z-index: 1;
        }

        .share-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        .share-btn {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(30, 111, 120, 0.15);
            background: #fff;
            color: var(--teal-dark);
            font-size: 1.15rem;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
            cursor: pointer;
        }

        .share-btn:hover {
            transform: translateY(-4px);
            color: #fff;
        }

        .share-wa:hover {
            background: #25d366;
            border-color: #25d366;
        }

        .share-fb:hover {
            background: #1877f2;
            border-color: #1877f2;
        }

        .share-li:hover {
            background: #0a66c2;
            border-color: #0a66c2;
        }

        .share-copy:hover {
            background: var(--teal);
            border-color: var(--teal);
        }

        .share-copy.copied {
            background: #16a34a !important;
            border-color: #16a34a !important;
            color: #fff !important;
        }

        /* --- CTA final --- */
        .cta-final-section {
            position: relative;
            z-index: 1;
            padding: 5.5rem 0;
        }

        .final-cta {
            position: relative;
            overflow: hidden;
            border-radius: 32px;
            padding: 3.6rem 3rem;
            color: #fff;
            background: linear-gradient(135deg,
                    #0b1f35 0%,
                    #145962 55%,
                    #2c8f99 120%);
        }

        .final-cta h2 {
            font-size: clamp(1.8rem, 3.6vw, 2.8rem);
            letter-spacing: -0.8px;
            line-height: 1.15;
        }

        .final-cta .cta-sub {
            color: #bfe4e2;
            max-width: 560px;
            line-height: 1.75;
        }

        .final-cta .floating-chip {
            z-index: 2;
        }

        @media (max-width: 767.98px) {
            .final-cta {
                padding: 2.4rem 1.5rem;
            }
        }

        /* --- Artículos relacionados --- */
        .related-section {
            background: #fff;
            padding: 5.5rem 0;
            position: relative;
            z-index: 1;
        }

        .related-card {
            background: #fff;
            border: 1px solid rgba(30, 111, 120, 0.09);
            border-radius: 20px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 18px 44px -32px rgba(20, 89, 98, 0.6);
            transition:
                transform 0.35s cubic-bezier(0.2, 0.8, 0.2, 1),
                box-shadow 0.35s ease;
            text-decoration: none;
        }

        .related-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px -28px rgba(20, 89, 98, 0.55);
        }

        .related-thumb {
            aspect-ratio: 16/10;
            overflow: hidden;
        }

        .related-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.6s ease;
        }

        .related-card:hover .related-thumb img {
            transform: scale(1.06);
        }

        .related-body {
            padding: 1.4rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .related-tag {
            align-self: flex-start;
            background: var(--mint);
            color: var(--teal);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            margin-bottom: 0.8rem;
        }

        .related-body h3 {
            font-size: 1.06rem;
            color: var(--teal-dark);
            line-height: 1.4;
            margin-bottom: 0.5rem;
        }

        .related-body p {
            font-size: 0.88rem;
            color: #647476;
            line-height: 1.6;
            margin-bottom: 1rem;
            flex: 1;
        }

        .related-foot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.82rem;
            color: #8aa0a2;
        }

        .related-link {
            color: var(--teal-light);
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition:
                gap 0.25s ease,
                color 0.25s ease;
        }

        .related-card:hover .related-link {
            color: var(--teal-dark);
            gap: 0.65rem;
        }
    </style>
@endsection

@section('content')
    <main>
        <!-- ===================== HERO DEL ARTÍCULO ===================== -->
        <section class="article-hero">
            <div class="hero-glow-1"></div>
            <div class="hero-glow-2"></div>
            <div class="container">
                <!-- BLOQUE DINÁMICO: category · title · subtitle · meta -->
                <div class="text-center">
                    <span class="article-cat">
                        <i class="bi bi-whatsapp"></i> {{ __('WhatsApp Business') }}
                    </span>
                    <h1 class="article-title">
                        {{ __('API de WhatsApp Business: Qué es y por qué tu empresa la necesita en') }} <span class="gradient-text">2026</span>
                    </h1>
                    <p class="article-sub">
                        {{ __('Descubre qué es la API de WhatsApp Business, en qué se diferencia de la app normal y por qué es clave para escalar ventas, atención y automatización en tu empresa.') }}
                    </p>
                    <div class="article-meta">
                        <span class="meta-chip">
                            <span class="author-avatar">G</span> {{ __('Por') }}
                            <strong>{{ __('GIJAC WEB') }}</strong></span>
                        <span class="meta-chip">
                            <i class="bi bi-calendar3"></i> {{ __('24 Sep 2026') }}
                        </span>
                        <span class="meta-chip">
                            <i class="bi bi-clock-history"></i> {{ __('8 min de lectura') }}
                        </span>
                        <span class="meta-chip">
                            <i class="bi bi-eye"></i> {{ __('2.4k vistas') }}
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== IMAGEN PRINCIPAL ===================== -->
        <!-- BLOQUE DINÁMICO: featured_image -->
        <div class="featured-wrap">
            <figure class="featured-figure">
                <img id="featuredImg" class="img-loading" src="{{ asset('img/articulos/api-whatsapp-business-importancia-empresas/featured.png') }}"
                    alt="{{ __('API de WhatsApp Business para empresas: conexiones, automatización, CRM e inteligencia artificial') }}"
                    fetchpriority="high" />
            </figure>
        </div>

        <!-- ===================== CONTENIDO + SIDEBAR ===================== -->
        <div class="article-layout">
            <div class="container">
                <div class="row g-5">
                    <!-- ============ COLUMNA: CONTENIDO ============ -->
                    <div class="col-lg-8">
                        <article class="article-content">
                            <!-- TOC desplegable (solo móvil) -->
                            <div class="sidebar-card d-lg-none mb-5">
                                <button class="toc-toggle" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#mobileToc" aria-expanded="false" aria-controls="mobileToc">
                                    {{ __('En este artículo') }}
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <div class="collapse" id="mobileToc">
                                    <ul class="toc-list mt-3">
                                        <li>
                                            <a class="toc-link" href="#que-es">
                                                <span class="toc-num"></span>
                                                {{ __('¿Qué es la API de WhatsApp Business?') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="toc-link" href="#diferencias">
                                                <span class="toc-num"></span>
                                                {{ __('Diferencias entre App y API') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="toc-link" href="#razones">
                                                <span class="toc-num"></span>
                                                {{ __('¿Por qué es indispensable para las empresas?') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="toc-link" href="#casos-uso">
                                                <span class="toc-num"></span>
                                                {{ __('Casos de uso por industria') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="toc-link" href="#empezar">
                                                <span class="toc-num"></span>
                                                {{ __('¿Cómo empezar con la API?') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="toc-link" href="#conclusion">
                                                <span class="toc-num"></span>
                                                {{ __('Conclusión') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- BLOQUE DINÁMICO: heading + paragraph -->
                            <div class="article-section">
                                <h2>
                                    {{ __('API de WhatsApp Business: La herramienta que separa a las empresas que crecen de las que se quedan atrás') }}
                                </h2>
                                <p class="lead-para">
                                    {{ __('Si tu empresa todavía atiende clientes desde el WhatsApp personal o la app Business normal,') }}
                                    <strong>{{ __('estás perdiendo ventas, tiempo y credibilidad') }}</strong>
                                    {{ __('. Hoy, más de 2.000 millones de personas usan WhatsApp a diario, y el cliente ya no quiere llamar ni escribir un correo. Quiere respuesta inmediata, por donde ya está.') }}
                                </p>

                                <!-- BLOQUE DINÁMICO: highlight -->
                                <div class="highlight-statement reveal" data-reveal="up">
                                    <span class="hs-icon">
                                        <i class="bi bi-whatsapp"></i>
                                    </span>
                                    <span>
                                        {{ __('Ahí entra la') }}
                                        <span class="gradient-text">
                                            {{ __('API de WhatsApp Business') }}
                                        </span>.
                                    </span>
                                </div>
                            </div>

                            <!-- ============ SECCIÓN 1: ¿QUÉ ES? ============ -->
                            <div class="article-section" id="que-es">
                                <h2>{{ __('¿Qué es la API de WhatsApp Business?') }}</h2>
                                <p>
                                    {{ __('La API de WhatsApp Business es la') }}
                                    <strong>{{ __('versión profesional y escalable') }}</strong>
                                    {{ __('de WhatsApp, diseñada para que empresas de cualquier tamaño conecten el canal de mensajería más usado del mundo directamente con sus sistemas: CRM, e-commerce, plataformas de atención y flujos de automatización.') }}
                                </p>
                                <span class="def-chip">
                                    <i class="bi bi-code-slash me-2"></i>
                                    {{ __('API = Application Programming Interface') }}
                                </span>
                                <p>
                                    {{ __('En pocas palabras: es la capa tecnológica que permite que') }}
                                    <strong>{{ __('las conversaciones dejen de depender de un celular') }}</strong>
                                    {{ __('y pasen a ser gestionadas por equipos completos, chatbots e inteligencia artificial, con la seguridad y las reglas oficiales de Meta.') }}
                                </p>

                                <!-- Mini comparación -->
                                <div class="vs-wrap reveal" data-reveal="up">
                                    <div class="vs-card vs-app">
                                        <div class="vs-head">
                                            <i class="bi bi-phone"></i> {{ __('App WhatsApp Business') }}
                                        </div>
                                        <p>
                                            {{ __('Atención') }} <strong>{{ __('manual') }}</strong> {{ __('desde dispositivos individuales.') }}
                                        </p>
                                    </div>
                                    <div class="vs-arrow">
                                        <i class="bi bi-arrow-right"></i>
                                    </div>
                                    <div class="vs-card vs-api">
                                        <div class="vs-head">
                                            <i class="bi bi-plug"></i> {{ __('API de WhatsApp Business') }}
                                        </div>
                                        <p>
                                            <strong>{{ __('Integración + automatización + múltiples usuarios + sistemas empresariales.') }}</strong>
                                        </p>
                                    </div>
                                </div>

                                <!-- BLOQUE DINÁMICO: alert tipo "importante" -->
                                <div class="callout callout-important reveal" data-reveal="up">
                                    <span class="callout-icon">
                                        <i class="bi bi-exclamation-triangle-fill text-white"></i>
                                    </span>
                                    <div>
                                        <div class="callout-title">{{ __('Importante') }}</div>
                                        <p>
                                            {{ __('El acceso a la API se gestiona a través de Meta o de un socio oficial (BSP). Trabaja siempre con proveedores verificados para proteger tu número y tu reputación de marca.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- ============ SECCIÓN 2: APP VS API ============ -->
                            <div class="article-section" id="diferencias">
                                <h2>{{ __('Diferencias clave: App vs. API') }}</h2>
                                <p>
                                    {{ __('La app Business es un buen primer paso, pero tiene un techo. La API rompe ese techo y convierte WhatsApp en') }}
                                    <strong>{{ __('infraestructura empresarial real') }}</strong>:
                                </p>

                                <!-- BLOQUE DINÁMICO: table (responsive: tabla → cards en móvil) -->
                                <div class="compare-wrap reveal" data-reveal="up">
                                    <table class="table-compare">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Característica') }}</th>
                                                <th>
                                                    <i class="bi bi-phone"></i>{{ __('App WhatsApp Business') }}
                                                </th>
                                                <th>
                                                    <i class="bi bi-plug"></i>{{ __('API de WhatsApp Business') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="feat" data-label="Característica">
                                                    {{ __('Usuarios') }}
                                                </td>
                                                <td data-label="App WhatsApp Business">
                                                    {{ __('1 a 4 usuarios en un mismo número') }}
                                                </td>
                                                <td data-label="API de WhatsApp Business">
                                                    <i class="bi bi-check-circle-fill api-check"></i>{{ __('Usuarios ilimitados / equipos y plataformas, según configuración') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="feat" data-label="Característica">
                                                    {{ __('Automatización') }}
                                                </td>
                                                <td data-label="App WhatsApp Business">
                                                    {{ __('Respuestas rápidas básicas') }}
                                                </td>
                                                <td data-label="API de WhatsApp Business">
                                                    <i class="bi bi-check-circle-fill api-check"></i>{{ __('Chatbots, IA y automatizaciones') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="feat" data-label="Característica">
                                                    {{ __('Integración') }}
                                                </td>
                                                <td data-label="App WhatsApp Business">{{ __('Limitada') }}</td>
                                                <td data-label="API de WhatsApp Business">
                                                    <i class="bi bi-check-circle-fill api-check"></i>{{ __('CRM, e-commerce, automatizaciones y otros sistemas') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="feat" data-label="Característica">
                                                    {{ __('Gestión empresarial') }}
                                                </td>
                                                <td data-label="App WhatsApp Business">
                                                    {{ __('Atención desde aplicación') }}
                                                </td>
                                                <td data-label="API de WhatsApp Business">
                                                    <i class="bi bi-check-circle-fill api-check"></i>{{ __('Plataforma centralizada') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="feat" data-label="Característica">
                                                    {{ __('Envíos') }}
                                                </td>
                                                <td data-label="App WhatsApp Business">
                                                    {{ __('Funciones limitadas') }}
                                                </td>
                                                <td data-label="API de WhatsApp Business">
                                                    <i class="bi bi-check-circle-fill api-check"></i>{{ __('Mensajería empresarial mediante las capacidades oficiales de Meta') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- BLOQUE DINÁMICO: alert tipo "info" -->
                                <div class="callout callout-info reveal" data-reveal="up">
                                    <span class="callout-icon">
                                        <i class="bi bi-info-circle-fill text-white"></i>
                                    </span>
                                    <div>
                                        <div class="callout-title">
                                            {{ __('¿Ya usas un número de WhatsApp?') }}
                                        </div>
                                        <p>
                                            {{ __('Es posible migrar tu número actual a la API conservando tu identidad de marca, para que tus clientes no noten ningún cambio en la transición.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- ============ SECCIÓN 3: 7 RAZONES ============ -->
                            <div class="article-section" id="razones">
                                <h2>{{ __('¿Por qué es indispensable para las empresas en 2026?') }}</h2>
                                <p>
                                    {{ __('Estas son las') }} <strong>{{ __('7 razones') }}</strong> {{ __('por las que miles de empresas están dando el salto a la API este año:') }}
                                </p>

                                <!-- BLOQUE DINÁMICO: lista de razones (cards numeradas) -->
                                <div class="reasons-grid">
                                    <div class="reason-card reveal" data-reveal="up">
                                        <div class="reason-top">
                                            <span class="reason-num">01</span><span class="reason-icon"><i
                                                    class="bi bi-moon-stars"></i></span>
                                        </div>
                                        <h3>{{ __('Atención al cliente que no duerme') }}</h3>
                                        <p>
                                            {{ __('Chatbots e IA responden al instante, 24/7, incluso fuera de horario. Tu cliente recibe respuesta inmediata sin depender de turnos ni horarios.') }}
                                        </p>
                                    </div>
                                    <div class="reason-card reveal" data-reveal="up">
                                        <div class="reason-top">
                                            <span class="reason-num">02</span><span class="reason-icon"><i
                                                    class="bi bi-cart-check"></i></span>
                                        </div>
                                        <h3>
                                            {{ __('Ventas conversacionales y recuperación de carritos') }}
                                        </h3>
                                        <p>
                                            {{ __('Acompaña al cliente en todo el embudo: recordatorios de carrito abandonado, seguimiento de pedidos y ofertas personalizadas que convierten.') }}
                                        </p>
                                    </div>
                                    <div class="reason-card reveal" data-reveal="up">
                                        <div class="reason-top">
                                            <span class="reason-num">03</span><span class="reason-icon"><i
                                                    class="bi bi-people"></i></span>
                                        </div>
                                        <h3>{{ __('Un solo número para toda la empresa') }}</h3>
                                        <p>
                                            {{ __('Ventas, soporte y marketing operan sobre el mismo número con bandejas centralizadas, asignación de conversaciones y trazabilidad total.') }}
                                        </p>
                                    </div>
                                    <div class="reason-card reveal" data-reveal="up">
                                        <div class="reason-top">
                                            <span class="reason-num">04</span><span class="reason-icon"><i
                                                    class="bi bi-cpu"></i></span>
                                        </div>
                                        <h3>{{ __('Automatización que ahorra costos') }}</h3>
                                        <p>
                                            {{ __('Automatiza respuestas frecuentes, agendamientos y notificaciones. Tu equipo se enfoca en lo que realmente requiere criterio humano.') }}
                                        </p>
                                    </div>
                                    <div class="reason-card reveal" data-reveal="up">
                                        <div class="reason-top">
                                            <span class="reason-num">05</span><span class="reason-icon"><i
                                                    class="bi bi-patch-check"></i></span>
                                        </div>
                                        <h3>{{ __('Confianza y profesionalismo') }}</h3>
                                        <p>
                                            {{ __('Nombre verificado, perfil empresarial completo y mensajes coherentes: tu marca se ve sólida y confiable en cada conversación.') }}
                                        </p>
                                    </div>
                                    <div class="reason-card reveal" data-reveal="up">
                                        <div class="reason-top">
                                            <span class="reason-num">06</span><span class="reason-icon"><i
                                                    class="bi bi-shield-check"></i></span>
                                        </div>
                                        <h3>{{ __('Cumplimiento y seguridad') }}</h3>
                                        <p>
                                            {{ __('Comunicación con cifrado y reglas claras de mensajería, alineadas con las políticas oficiales de Meta y las buenas prácticas del sector.') }}
                                        </p>
                                    </div>
                                    <div class="reason-card reveal" data-reveal="up">
                                        <div class="reason-top">
                                            <span class="reason-num">07</span><span class="reason-icon"><i
                                                    class="bi bi-graph-up-arrow"></i></span>
                                        </div>
                                        <h3>{{ __('Datos para tomar decisiones') }}</h3>
                                        <p>
                                            {{ __('Métricas de apertura, respuesta y conversión que se integran con tu CRM para entender a tus clientes y mejorar cada interacción.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- ============ MULTIMEDIA: VIDEO ============ -->
                            <!-- BLOQUE DINÁMICO: video (video_url · thumbnail · titulo reemplazables) -->
                            <div class="article-section">
                                <h2>{{ __('Mira la API en acción') }}</h2>
                                <button class="video-block reveal" data-reveal="up" id="videoBlock" type="button"
                                    aria-label="Reproducir video de demostración">
                                    <img id="videoThumbImg" class="video-thumb img-loading" src="{{ asset('img/articulos/api-whatsapp-business-importancia-empresas/video-thumb.png') }}"
                                        alt="Video de demostración de WhatsApp Business API" loading="lazy" />
                                    <span class="video-overlay"><span class="play-btn"><i
                                                class="bi bi-play-fill"></i></span></span>
                                    <span class="video-tag"><i class="bi bi-play-circle me-1"></i>2:45</span>
                                </button>
                                <p class="video-caption">
                                    <i class="bi bi-youtube me-1"></i>{{ __('Video de demostración: automatización de ventas con WhatsApp Business API.') }}
                                </p>
                            </div>

                            <!-- ============ IMAGEN INTERMEDIA ============ -->
                            <!-- BLOQUE DINÁMICO: image (variantes: af-full | af-center | af-left | af-right) -->
                            <figure class="article-figure af-center reveal" data-reveal="up">
                                <img id="inlineImg" class="img-loading" src="{{ asset('img/articulos/api-whatsapp-business-importancia-empresas/inline-1.png') }}"
                                    alt="Empresa conectando WhatsApp con CRM, automatización e inteligencia artificial"
                                    loading="lazy" />
                                <figcaption>
                                    <i class="bi bi-camera"></i> {{ __('WhatsApp Business API permite conectar las conversaciones con el ecosistema tecnológico de una empresa.') }}
                                </figcaption>
                            </figure>

                            <!-- ============ GALERÍA ============ -->
                            <!-- BLOQUE DINÁMICO: gallery (lightbox con navegación y contador) -->
                            <div id="gallery" class="gallery-grid reveal" data-reveal="up">
                                <div class="gallery-item"
                                    data-caption="Constructor visual de flujos de conversación automatizados con chatbot">
                                    <img class="img-loading" src="{{ asset('img/articulos/api-whatsapp-business-importancia-empresas/gallery-1.png') }}" alt="Flujos de conversación automatizados"
                                        loading="lazy" />
                                </div>
                                <div class="gallery-item"
                                    data-caption="Integración de WhatsApp Business API con e-commerce y CRM">
                                    <img class="img-loading" src="{{ asset('img/articulos/api-whatsapp-business-importancia-empresas/gallery-2.png') }}" alt="Integración con CRM y automatizaciones"
                                        loading="lazy" />
                                </div>
                                <div class="gallery-item"
                                    data-caption="Dashboard de métricas de WhatsApp Business API en tiempo real">
                                    <img class="img-loading" src="{{ asset('img/articulos/api-whatsapp-business-importancia-empresas/gallery-3.png') }}"
                                        alt="Métricas y reportes de WhatsApp Business API" loading="lazy" />
                                </div>
                            </div>

                            <hr class="separator" />

                            <!-- ============ SECCIÓN 4: CASOS DE USO ============ -->
                            <div class="article-section" id="casos-uso">
                                <h2>{{ __('Casos de uso por industria') }}</h2>

                                <!-- Ejemplo de imagen flotada (variante af-right) -->
                                <figure class="article-figure af-right">
                                    <img id="floatImg" class="img-loading" src="{{ asset('img/articulos/api-whatsapp-business-importancia-empresas/float-1.png') }}"
                                        alt="Atención automatizada por WhatsApp en distintos sectores" loading="lazy" />
                                </figure>

                                <p>
                                    {{ __('La API se adapta a') }} <strong>{{ __('cualquier industria') }}</strong> {{ __('que necesite comunicarse de forma ágil, automática y personalizada con sus clientes. Estos son algunos ejemplos reales de cómo las empresas la están aprovechando hoy.') }}
                                </p>

                                <!-- BLOQUE DINÁMICO: cards por industria -->
                                <div class="industry-grid">
                                    <div class="industry-card reveal" data-reveal="up">
                                        <span class="industry-icon"><i class="bi bi-bag-check"></i></span>
                                        <h3>{{ __('E-commerce') }}</h3>
                                        <p>
                                            {{ __('Confirmaciones de pedido, recuperación de carritos y seguimiento de envíos automáticos.') }}
                                        </p>
                                    </div>
                                    <div class="industry-card reveal" data-reveal="up">
                                        <span class="industry-icon"><i class="bi bi-mortarboard"></i></span>
                                        <h3>{{ __('Educación') }}</h3>
                                        <p>
                                            {{ __('Recordatorios de clases, procesos de admisión y comunicación directa con padres y alumnos.') }}
                                        </p>
                                    </div>
                                    <div class="industry-card reveal" data-reveal="up">
                                        <span class="industry-icon"><i class="bi bi-heart-pulse"></i></span>
                                        <h3>{{ __('Salud y clínicas') }}</h3>
                                        <p>
                                            {{ __('Confirmación de citas, envío de resultados y recordatorios que reducen las inasistencias.') }}
                                        </p>
                                    </div>
                                    <div class="industry-card reveal" data-reveal="up">
                                        <span class="industry-icon"><i class="bi bi-house-door"></i></span>
                                        <h3>{{ __('Inmobiliario y servicios') }}</h3>
                                        <p>
                                            {{ __('Cualificación automática de leads y agendamiento inteligente de visitas y cotizaciones.') }}
                                        </p>
                                    </div>
                                    <div class="industry-card reveal" data-reveal="up">
                                        <span class="industry-icon"><i class="bi bi-shop"></i></span>
                                        <h3>{{ __('Restaurantes y retail') }}</h3>
                                        <p>
                                            {{ __('Reservas, pedidos a domicilio y promociones segmentadas que aumentan la recompra.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- ============ SECCIÓN 5: CÓMO EMPEZAR ============ -->
                            <div class="article-section" id="empezar">
                                <h2>{{ __('¿Cómo empezar con la API?') }}</h2>
                                <p>
                                    {{ __('Poner en marcha la API es más simple de lo que parece. Estos son los') }} <strong>{{ __('5 pasos') }}</strong> {{ __('del proceso:') }}
                                </p>

                                <!-- BLOQUE DINÁMICO: timeline -->
                                <div class="timeline reveal" data-reveal="up">
                                    <div class="timeline-step">
                                        <div class="step-dot">01</div>
                                        <h3>{{ __('Número de teléfono') }}</h3>
                                        <p>
                                            {{ __('Registra y verifica el número que usará tu empresa para comunicarse.') }}
                                        </p>
                                    </div>
                                    <div class="timeline-step">
                                        <div class="step-dot">02</div>
                                        <h3>{{ __('Cuenta empresarial') }}</h3>
                                        <p>
                                            {{ __('Crea y verifica tu cuenta en Meta Business Manager con los datos de tu empresa.') }}
                                        </p>
                                    </div>
                                    <div class="timeline-step">
                                        <div class="step-dot">03</div>
                                        <h3>{{ __('Configuración de WhatsApp') }}</h3>
                                        <p>
                                            {{ __('Conecta el número a la API a través de Meta o de un socio oficial (BSP).') }}
                                        </p>
                                    </div>
                                    <div class="timeline-step">
                                        <div class="step-dot">04</div>
                                        <h3>{{ __('Plantillas y mensajes') }}</h3>
                                        <p>
                                            {{ __('Diseña y aprueba tus plantillas de mensajes para iniciar conversaciones con calidad.') }}
                                        </p>
                                    </div>
                                    <div class="timeline-step">
                                        <div class="step-dot">05</div>
                                        <h3>{{ __('Automatización e integración') }}</h3>
                                        <p>
                                            {{ __('Conecta CRM, chatbots, IA y flujos con herramientas como n8n o tu e-commerce.') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- BLOQUE DINÁMICO: code -->
                                <div class="code-block reveal" data-reveal="up">
                                    <div class="code-head">
                                        <span class="code-dot"></span><span class="code-dot"></span><span
                                            class="code-dot"></span>
                                        <span class="code-lang">POST /v18.0/{phone-number-id}/messages</span>
                                    </div>
                                    <pre><span class="tc">{{ __('// Ejemplo: envío de plantilla aprobada por Meta') }}</span>
{
  <span class="tk">{{ __('"messaging_product"') }}</span>: <span class="ts">{{ __('"whatsapp"') }}</span>,
  <span class="tk">{{ __('"to"') }}</span>: <span class="ts">"50255012345"</span>,
  <span class="tk">{{ __('"type"') }}</span>: <span class="ts">{{ __('"template"') }}</span>,
  <span class="tk">{{ __('"template"') }}</span>: {
    <span class="tk">{{ __('"name"') }}</span>: <span class="ts">{{ __('"confirmacion_pedido"') }}</span>,
    <span class="tk">{{ __('"language"') }}</span>: { <span class="tk">{{ __('"code"') }}</span>: <span class="ts">{{ __('"es"') }}</span> }
  }
}</pre>
                                </div>

                                <!-- BLOQUE DINÁMICO: alert tipo "consejo" -->
                                <div class="callout callout-tip reveal" data-reveal="up">
                                    <span class="callout-icon">
                                        <i class="bi bi-lightbulb-fill text-white"></i>
                                    </span>
                                    <div>
                                        <div class="callout-title">{{ __('Consejo') }}</div>
                                        <p>
                                            {{ __('Antes de automatizar, define tus respuestas frecuentes y tus flujos de venta. Las plantillas bien escritas aumentan la tasa de aprobación de Meta y la tasa de respuesta de tus clientes.') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- BLOQUE DINÁMICO: alert tipo "advertencia" -->
                                <div class="callout callout-danger reveal" data-reveal="up">
                                    <span class="callout-icon">
                                        <i class="bi bi-x-octagon-fill text-white"></i>
                                    </span>
                                    <div>
                                        <div class="callout-title">{{ __('Advertencia') }}</div>
                                        <p>
                                            {{ __('No utilices modificaciones no oficiales ni APIs no autorizadas: incumplen los términos de Meta y ponen en riesgo tu número y la reputación de tu marca.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- ============ SECCIÓN 6: CONCLUSIÓN ============ -->
                            <div class="article-section" id="conclusion">
                                <h2>{{ __('Conclusión') }}</h2>
                                <p>
                                    {{ __('En 2026, la pregunta ya no es') }} <em>{{ __('si') }}</em> {{ __('tu empresa debería usar la API de WhatsApp Business, sino') }}
                                    <strong>{{ __('qué tan rápido puede implementarla') }}</strong> {{ __('antes de que tus competidores lo hagan. Atención inmediata, automatización inteligente y datos accionables ya no son un lujo: son la nueva forma de hacer negocios.') }}
                                </p>

                                <!-- BLOQUE DINÁMICO: quote -->
                                <blockquote class="article-quote reveal" data-reveal="up">
                                    <i class="bi bi-quote qmark"></i>
                                    <p>{{ __('“No es una opción, es el nuevo estándar.”') }}</p>
                                    <footer>
                                        {{ __('— La forma en que las empresas conversan cambió para siempre.') }}
                                    </footer>
                                </blockquote>

                                <p>
                                    {{ __('¿Tu empresa ya está lista para dar el salto? En GIJAC te acompañamos en todo el proceso: desde la configuración del número hasta los chatbots con IA y las automatizaciones con n8n.') }}
                                </p>
                                <a href="{{ route('register') }}" class="btn btn-glow btn-lg mt-2">
                                    <i class="bi bi-rocket-takeoff me-2"></i>
                                    {{ __('Dar el salto con GIJAC') }}
                                </a>
                            </div>

                            <!-- Byline -->
                            <div class="byline reveal" data-reveal="up">
                                <span class="author-avatar">G</span>
                                <div>
                                    <h4>{{ __('Equipo GIJAC') }}</h4>
                                    <p>
                                        {{ __('Especialistas en WhatsApp Business API, automatización e inteligencia artificial aplicada a la comunicación empresarial.') }}
                                    </p>
                                </div>
                            </div>
                        </article>
                    </div>

                    <!-- ============ COLUMNA: SIDEBAR ============ -->
                    <aside class="col-lg-4">
                        <div class="sidebar-sticky">
                            <!-- Tabla de contenidos -->
                            <div class="sidebar-card d-none d-lg-block reveal" data-reveal="right">
                                <h3><i class="bi bi-list-ul"></i> {{ __('En este artículo') }}</h3>
                                <ul class="toc-list">
                                    <li>
                                        <a class="toc-link" href="#que-es"><span class="toc-num"></span>{{ __('¿Qué es la API de WhatsApp Business?') }}</a>
                                    </li>
                                    <li>
                                        <a class="toc-link" href="#diferencias"><span class="toc-num"></span>{{ __('Diferencias entre App y API') }}</a>
                                    </li>
                                    <li>
                                        <a class="toc-link" href="#razones"><span class="toc-num"></span>{{ __('¿Por qué es indispensable para las empresas?') }}</a>
                                    </li>
                                    <li>
                                        <a class="toc-link" href="#casos-uso"><span class="toc-num"></span>{{ __('Casos de uso por industria') }}</a>
                                    </li>
                                    <li>
                                        <a class="toc-link" href="#empezar"><span class="toc-num"></span>{{ __('¿Cómo empezar con la API?') }}</a>
                                    </li>
                                    <li>
                                        <a class="toc-link" href="#conclusion"><span
                                                class="toc-num"></span>{{ __('Conclusión') }}</a>
                                    </li>
                                </ul>
                            </div>

                            <!-- CTA lateral -->
                            <div class="sidebar-cta reveal" data-reveal="right">
                                <h3>{{ __('¿Quieres automatizar WhatsApp en tu empresa?') }}</h3>
                                <p>
                                    {{ __('Conecta WhatsApp Business con tu CRM, automatizaciones, chatbots e inteligencia artificial.') }}
                                </p>
                                <a href="{{ route('contactarnos') }}" class="btn btn-light-glow w-100">
                                    <i class="bi bi-headset me-2"></i>
                                    {{ __('Habla con un asesor') }}
                                </a>
                            </div>

                            <!-- Compartir -->
                            <div class="sidebar-card reveal" data-reveal="right">
                                <h3><i class="bi bi-share"></i> {{ __('Compartir artículo') }}</h3>
                                <div class="share-row">
                                    <a class="share-btn share-wa" href="#" target="_blank" rel="noopener"
                                        aria-label="Compartir en WhatsApp"><i class="bi bi-whatsapp"></i></a>
                                    <a class="share-btn share-fb" href="#" target="_blank" rel="noopener"
                                        aria-label="Compartir en Facebook"><i class="bi bi-facebook"></i></a>
                                    <a class="share-btn share-li" href="#" target="_blank" rel="noopener"
                                        aria-label="Compartir en LinkedIn"><i class="bi bi-linkedin"></i></a>
                                    <button class="share-btn share-copy" type="button" aria-label="Copiar enlace">
                                        <i class="bi bi-link-45deg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>

        <!-- ===================== CTA FINAL ===================== -->
        <section class="cta-final-section">
            <div class="container">
                <div class="final-cta reveal" data-reveal="up">
                    <span class="floating-chip chip-1 float-a"><i class="bi bi-whatsapp"></i></span>
                    <span class="floating-chip chip-2 float-b"><i class="bi bi-diagram-3"></i></span>
                    <span class="floating-chip chip-3 float-c"><i class="bi bi-robot"></i></span>

                    <div class="row align-items-center g-4 position-relative" style="z-index: 3">
                        <div class="col-lg-8">
                            <h2>
                                {{ __('Convierte WhatsApp en un canal de ventas y atención para tu empresa') }}
                            </h2>
                            <p class="cta-sub">
                                {{ __('Conecta WhatsApp Business con automatizaciones, CRM, chatbots e inteligencia artificial. Nodos de conexión entre tu empresa y tus clientes, sin fricción.') }}
                            </p>
                            <div class="d-flex flex-wrap gap-3 mt-4">
                                <a href="{{ route('contactarnos') }}" class="btn btn-light-glow btn-lg">
                                    <i class="bi bi-chat-dots me-2"></i>
                                    {{ __('Habla con GIJAC WEB') }}
                                </a>
                                <a href="{{ url('/') }}" class="btn btn-dark-glass btn-lg">
                                    {{ __('Conocer GIJAC Message Business') }}
                                    <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== ARTÍCULOS RELACIONADOS ===================== -->
        <section class="related-section">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="article-cat justify-content-center" style="color: var(--teal-light)">{{ __('Recursos') }}</span>
                    <h2
                        style="
                font-size: clamp(1.7rem, 3vw, 2.4rem);
                color: var(--teal-dark);
                letter-spacing: -0.5px;
              ">
                        {{ __('También te puede interesar') }}
                    </h2>
                </div>

                <!-- BLOQUE DINÁMICO: related_articles -->
                <div class="row g-4" id="relatedRow">
                    <div class="col-md-6 col-lg-4">
                        <a href="#" class="related-card reveal" data-reveal="up">
                            <div class="related-thumb">
                                <img class="img-loading" src="{{ asset('img/articulos/api-whatsapp-business-importancia-empresas/video-thumb.png') }}" alt="Automatización con n8n" loading="lazy" />
                            </div>
                            <div class="related-body">
                                <span class="related-tag">{{ __('Automatización') }}</span>
                                <h3>
                                    {{ __('Automatización con n8n: qué es y cómo puede ayudar a tu empresa') }}
                                </h3>
                                <p>
                                    {{ __('Descubre cómo n8n conecta tus herramientas y automatiza procesos sin escribir código, paso a paso.') }}
                                </p>
                                <div class="related-foot">
                                    <span><i class="bi bi-calendar3 me-1"></i>{{ __('18 Sep 2026') }}</span><span
                                        class="related-link">{{ __('Leer artículo') }} <i class="bi bi-arrow-right"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="#" class="related-card reveal" data-reveal="up">
                            <div class="related-thumb">
                                <img class="img-loading" src="{{ asset('img/articulos/api-whatsapp-business-importancia-empresas/video-thumb.png') }}" alt="Chatbot de WhatsApp" loading="lazy" />
                            </div>
                            <div class="related-body">
                                <span class="related-tag">{{ __('Chatbots e IA') }}</span>
                                <h3>
                                    {{ __('Cómo implementar un chatbot de WhatsApp para atención al cliente') }}
                                </h3>
                                <p>
                                    {{ __('Guía práctica para diseñar, entrenar y lanzar un asistente virtual que responde 24/7 en WhatsApp.') }}
                                </p>
                                <div class="related-foot">
                                    <span><i class="bi bi-calendar3 me-1"></i>{{ __('11 Sep 2026') }}</span><span
                                        class="related-link">{{ __('Leer artículo') }} <i class="bi bi-arrow-right"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="#" class="related-card reveal" data-reveal="up">
                            <div class="related-thumb">
                                <img class="img-loading" src="{{ asset('img/articulos/api-whatsapp-business-importancia-empresas/video-thumb.png') }}" alt="WhatsApp Business vs API" loading="lazy" />
                            </div>
                            <div class="related-body">
                                <span class="related-tag">{{ __('Comparativas') }}</span>
                                <h3>{{ __('WhatsApp Business vs WhatsApp Business API') }}</h3>
                                <p>
                                    {{ __('Comparamos ambas soluciones en usuarios, automatización, costos y escalabilidad para tu empresa.') }}
                                </p>
                                <div class="related-foot">
                                    <span><i class="bi bi-calendar3 me-1"></i>{{ __('02 Sep 2026') }}</span><span
                                        class="related-link">{{ __('Leer artículo') }} <i class="bi bi-arrow-right"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('modal')
    <!-- ===================== MODAL DE VIDEO ===================== -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bg-transparent">
                <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                    <span id="videoModalLabel" class="text-white fw-bold">{{ __('Demostración: WhatsApp Business API') }}</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow">
                    <iframe id="videoFrame" src="" title="Video de demostración de WhatsApp Business API"
                        allow="
                accelerometer;
                autoplay;
                clipboard-write;
                encrypted-media;
                gyroscope;
                picture-in-picture;
              "
                        allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== LIGHTBOX DE GALERÍA ===================== -->
    <div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Visor de imágenes">
        <button class="lb-btn lb-close" id="lbClose" aria-label="Cerrar">
            <i class="bi bi-x-lg"></i>
        </button>
        <button class="lb-btn lb-prev" id="lbPrev" aria-label="Anterior">
            <i class="bi bi-chevron-left"></i>
        </button>
        <figure class="lb-figure">
            <img id="lbImage" src="" alt="" />
            <figcaption class="lb-caption">
                <span class="lb-counter" id="lbCounter">1 / 3</span>
                <div id="lbCaption"></div>
            </figcaption>
        </figure>
        <button class="lb-btn lb-next" id="lbNext" aria-label="Siguiente">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            "use strict";

            /* ==========================================================
               GENERADOR DE IMÁGENES PLACEHOLDER (SVG data-URI)
               En producción, Laravel reemplazará estos <img> por URLs
               reales desde MySQL/CMS. Solo cambia el atributo src.
               ========================================================== */
            function svgArt(o) {
                var w = o.w || 1600,
                    h = o.h || 900,
                    t = o.type || "network",
                    acc = "#5fd6c9";

                function node(x, y, r) {
                    return (
                        "<circle cx='" +
                        x +
                        "' cy='" +
                        y +
                        "' r='" +
                        r +
                        "' fill='" +
                        acc +
                        "' fill-opacity='.95'/>" +
                        "<circle cx='" +
                        x +
                        "' cy='" +
                        y +
                        "' r='" +
                        (r + 8) +
                        "' fill='none' stroke='" +
                        acc +
                        "' stroke-opacity='.35' stroke-width='2'/>"
                    );
                }
                var art = "";
                if (t === "network") {
                    var cx = w / 2,
                        cy = h / 2,
                        bw = w * 0.32,
                        bh = h * 0.44;
                    art =
                        "<g stroke='" +
                        acc +
                        "' stroke-opacity='.45' stroke-width='2'>" +
                        "<line x1='" +
                        cx +
                        "' y1='" +
                        cy +
                        "' x2='" +
                        w * 0.14 +
                        "' y2='" +
                        h * 0.2 +
                        "'/>" +
                        "<line x1='" +
                        cx +
                        "' y1='" +
                        cy +
                        "' x2='" +
                        w * 0.86 +
                        "' y2='" +
                        h * 0.24 +
                        "'/>" +
                        "<line x1='" +
                        cx +
                        "' y1='" +
                        cy +
                        "' x2='" +
                        w * 0.18 +
                        "' y2='" +
                        h * 0.82 +
                        "'/>" +
                        "<line x1='" +
                        cx +
                        "' y1='" +
                        cy +
                        "' x2='" +
                        w * 0.82 +
                        "' y2='" +
                        h * 0.8 +
                        "'/>" +
                        "<line x1='" +
                        cx +
                        "' y1='" +
                        cy +
                        "' x2='" +
                        w * 0.5 +
                        "' y2='" +
                        h * 0.1 +
                        "'/>" +
                        "<line x1='" +
                        cx +
                        "' y1='" +
                        cy +
                        "' x2='" +
                        w * 0.9 +
                        "' y2='" +
                        h * 0.6 +
                        "'/></g>" +
                        node(w * 0.14, h * 0.2, 16) +
                        node(w * 0.86, h * 0.24, 14) +
                        node(w * 0.18, h * 0.82, 13) +
                        node(w * 0.82, h * 0.8, 17) +
                        node(w * 0.5, h * 0.1, 12) +
                        node(w * 0.9, h * 0.6, 11) +
                        "<g transform='translate(" +
                        (cx - bw / 2) +
                        "," +
                        (cy - bh / 2) +
                        ")'>" +
                        "<rect width='" +
                        bw +
                        "' height='" +
                        bh +
                        "' rx='30' fill='rgba(255,255,255,.08)' stroke='rgba(255,255,255,.35)' stroke-width='2'/>" +
                        "<circle cx='" +
                        bw / 2 +
                        "' cy='" +
                        bh * 0.32 +
                        "' r='" +
                        bh * 0.17 +
                        "' fill='url(#ac)'/>" +
                        "<rect x='" +
                        bw * 0.2 +
                        "' y='" +
                        bh * 0.58 +
                        "' width='" +
                        bw * 0.6 +
                        "' height='10' rx='5' fill='rgba(255,255,255,.5)'/>" +
                        "<rect x='" +
                        bw * 0.3 +
                        "' y='" +
                        bh * 0.72 +
                        "' width='" +
                        bw * 0.4 +
                        "' height='10' rx='5' fill='rgba(255,255,255,.28)'/></g>";
                } else if (t === "chat") {
                    art =
                        "<g transform='translate(" +
                        w * 0.07 +
                        "," +
                        h * 0.16 +
                        ")'>" +
                        "<rect width='" +
                        w * 0.36 +
                        "' height='" +
                        h * 0.2 +
                        "' rx='24' fill='rgba(255,255,255,.1)' stroke='rgba(255,255,255,.3)' stroke-width='2'/>" +
                        "<rect x='28' y='" +
                        h * 0.05 +
                        "' width='" +
                        w * 0.22 +
                        "' height='12' rx='6' fill='rgba(255,255,255,.45)'/>" +
                        "<rect x='28' y='" +
                        h * 0.105 +
                        "' width='" +
                        w * 0.14 +
                        "' height='12' rx='6' fill='rgba(255,255,255,.25)'/></g>" +
                        "<g transform='translate(" +
                        w * 0.56 +
                        "," +
                        h * 0.4 +
                        ")'>" +
                        "<rect width='" +
                        w * 0.34 +
                        "' height='" +
                        h * 0.22 +
                        "' rx='24' fill='url(#ac)'/>" +
                        "<rect x='28' y='" +
                        h * 0.06 +
                        "' width='" +
                        w * 0.2 +
                        "' height='12' rx='6' fill='rgba(9,43,49,.55)'/>" +
                        "<rect x='28' y='" +
                        h * 0.12 +
                        "' width='" +
                        w * 0.12 +
                        "' height='12' rx='6' fill='rgba(9,43,49,.35)'/></g>" +
                        "<g transform='translate(" +
                        w * 0.1 +
                        "," +
                        h * 0.64 +
                        ")'>" +
                        "<rect width='" +
                        w * 0.28 +
                        "' height='" +
                        h * 0.17 +
                        "' rx='24' fill='rgba(255,255,255,.09)' stroke='rgba(255,255,255,.22)' stroke-width='2'/>" +
                        "<rect x='28' y='" +
                        h * 0.055 +
                        "' width='" +
                        w * 0.16 +
                        "' height='12' rx='6' fill='rgba(255,255,255,.4)'/></g>" +
                        "<circle cx='" +
                        w * 0.62 +
                        "' cy='" +
                        h * 0.73 +
                        "' r='6' fill='#fff' fill-opacity='.7'/>" +
                        "<circle cx='" +
                        w * 0.655 +
                        "' cy='" +
                        h * 0.73 +
                        "' r='6' fill='#fff' fill-opacity='.45'/>" +
                        "<circle cx='" +
                        w * 0.69 +
                        "' cy='" +
                        h * 0.73 +
                        "' r='6' fill='#fff' fill-opacity='.25'/>";
                } else if (t === "flow") {
                    var bw2 = w * 0.17,
                        bh2 = h * 0.24,
                        y0 = h / 2 - bh2 / 2,
                        gap = (w - bw2 * 4) / 5;
                    art = "";
                    for (var i = 0; i < 4; i++) {
                        var x0 = gap + (bw2 + gap) * i;
                        art +=
                            "<rect x='" +
                            x0 +
                            "' y='" +
                            y0 +
                            "' width='" +
                            bw2 +
                            "' height='" +
                            bh2 +
                            "' rx='20' fill='rgba(255,255,255,.08)' stroke='rgba(255,255,255,.3)' stroke-width='2'/>" +
                            "<circle cx='" +
                            (x0 + bw2 / 2) +
                            "' cy='" +
                            (y0 + bh2 * 0.3) +
                            "' r='" +
                            bh2 * 0.14 +
                            "' fill='url(#ac)'/>" +
                            "<rect x='" +
                            (x0 + bw2 * 0.18) +
                            "' y='" +
                            (y0 + bh2 * 0.55) +
                            "' width='" +
                            bw2 * 0.64 +
                            "' height='10' rx='5' fill='rgba(255,255,255,.45)'/>" +
                            "<rect x='" +
                            (x0 + bw2 * 0.3) +
                            "' y='" +
                            (y0 + bh2 * 0.7) +
                            "' width='" +
                            bw2 * 0.4 +
                            "' height='10' rx='5' fill='rgba(255,255,255,.22)'/>";
                        if (i < 3) {
                            var xa = x0 + bw2,
                                xb = x0 + bw2 + gap;
                            art +=
                                "<line x1='" +
                                (xa + 8) +
                                "' y1='" +
                                h / 2 +
                                "' x2='" +
                                (xb - 14) +
                                "' y2='" +
                                h / 2 +
                                "' stroke='" +
                                acc +
                                "' stroke-width='3' stroke-dasharray='7 7'/>" +
                                "<path d='M " +
                                xb +
                                " " +
                                h / 2 +
                                " L " +
                                (xb - 14) +
                                " " +
                                (h / 2 - 7) +
                                " L " +
                                (xb - 14) +
                                " " +
                                (h / 2 + 7) +
                                " Z' fill='" +
                                acc +
                                "'/>";
                        }
                    }
                    art +=
                        "<path d='M " +
                        w * 0.25 +
                        " " +
                        (y0 - 34) +
                        " H " +
                        w * 0.75 +
                        "' stroke='#fff' stroke-opacity='.25' stroke-width='2' stroke-dasharray='4 6'/>" +
                        "<circle cx='" +
                        w * 0.25 +
                        "' cy='" +
                        (y0 - 34) +
                        "' r='8' fill='#fff' fill-opacity='.35'/>" +
                        "<circle cx='" +
                        w * 0.75 +
                        "' cy='" +
                        (y0 - 34) +
                        "' r='8' fill='#fff' fill-opacity='.35'/>";
                } else if (t === "chart") {
                    var baseY = h * 0.82,
                        hs = [0.16, 0.3, 0.24, 0.45, 0.58, 0.75],
                        n = 6,
                        span = w * 0.74,
                        x1 = w * 0.13,
                        bw3 = (span / n) * 0.55;
                    art =
                        "<line x1='" +
                        w * 0.08 +
                        "' y1='" +
                        baseY +
                        "' x2='" +
                        w * 0.92 +
                        "' y2='" +
                        baseY +
                        "' stroke='rgba(255,255,255,.3)' stroke-width='2'/>";
                    var pts = [];
                    for (var j = 0; j < n; j++) {
                        var bh3 = h * hs[j],
                            xr = x1 + (span / n) * j + (span / n - bw3) / 2;
                        art +=
                            "<rect x='" +
                            xr +
                            "' y='" +
                            (baseY - bh3) +
                            "' width='" +
                            bw3 +
                            "' height='" +
                            bh3 +
                            "' rx='10' fill='" +
                            (j === n - 1 ? "url(#ac)" : "rgba(255,255,255,.14)") +
                            "'/>";
                        pts.push(xr + bw3 / 2 + "," + (baseY - bh3 - 16));
                    }
                    art +=
                        "<polyline points='" +
                        pts.join(" ") +
                        "' fill='none' stroke='" +
                        acc +
                        "' stroke-width='3' stroke-linecap='round'/>";
                    pts.forEach(function(p) {
                        var c = p.split(",");
                        art +=
                            "<circle cx='" +
                            c[0] +
                            "' cy='" +
                            c[1] +
                            "' r='6' fill='" +
                            acc +
                            "'/>";
                    });
                }
                var grid = "";
                for (var gx = 60; gx < w; gx += 90)
                    grid +=
                    "<line x1='" +
                    gx +
                    "' y1='0' x2='" +
                    gx +
                    "' y2='" +
                    h +
                    "' stroke='#fff' stroke-opacity='.035'/>";
                for (var gy = 60; gy < h; gy += 90)
                    grid +=
                    "<line x1='0' y1='" +
                    gy +
                    "' x2='" +
                    w +
                    "' y2='" +
                    gy +
                    "' stroke='#fff' stroke-opacity='.035'/>";
                var dots = "";
                [
                    [0.08, 0.18],
                    [0.2, 0.34],
                    [0.92, 0.28],
                    [0.8, 0.72],
                    [0.34, 0.88],
                    [0.62, 0.08],
                    [0.05, 0.62],
                    [0.96, 0.55],
                    [0.5, 0.94],
                ].forEach(function(p) {
                    dots +=
                        "<circle cx='" +
                        w * p[0] +
                        "' cy='" +
                        h * p[1] +
                        "' r='3.5' fill='#fff' fill-opacity='.22'/>";
                });
                var svg =
                    "<svg xmlns='http://www.w3.org/2000/svg' width='" +
                    w +
                    "' height='" +
                    h +
                    "' viewBox='0 0 " +
                    w +
                    " " +
                    h +
                    "'>" +
                    "<defs><linearGradient id='bg' x1='0' y1='0' x2='1' y2='1'><stop offset='0' stop-color='#115a64'/><stop offset='1' stop-color='#092b31'/></linearGradient>" +
                    "<linearGradient id='ac' x1='0' y1='0' x2='1' y2='1'><stop offset='0' stop-color='#5fd6c9'/><stop offset='1' stop-color='#1E6F78'/></linearGradient>" +
                    "<radialGradient id='gl' cx='.5' cy='.5' r='.5'><stop offset='0' stop-color='#5fd6c9' stop-opacity='.4'/><stop offset='1' stop-color='#5fd6c9' stop-opacity='0'/></radialGradient></defs>" +
                    "<rect width='" +
                    w +
                    "' height='" +
                    h +
                    "' fill='url(#bg)'/>" +
                    grid +
                    "<circle cx='" +
                    w * 0.85 +
                    "' cy='" +
                    h * 0.12 +
                    "' r='" +
                    h * 0.42 +
                    "' fill='url(#gl)'/>" +
                    "<circle cx='" +
                    w * 0.1 +
                    "' cy='" +
                    h * 0.9 +
                    "' r='" +
                    h * 0.36 +
                    "' fill='url(#gl)'/>" +
                    art +
                    dots +
                    "</svg>";
                return "data:image/svg+xml;charset=utf-8," + encodeURIComponent(svg);
            }

            /* Quitar skeleton al cargar cada imagen */
            $("img.img-loading").on("load", function() {
                $(this).removeClass("img-loading");
            });

            /* ==========================================================
               HEADER · PROGRESO DE LECTURA · BACK TO TOP · TOC ACTIVO
               ========================================================== */
            var $header = $("#siteHeader"),
                $tocSections = $(".article-section[id]"),
                $tocLinks = $(".toc-link");

            function highlightToc() {
                var pos = $(window).scrollTop() + 150,
                    current = "#";
                $tocSections.each(function() {
                    if ($(this).offset().top <= pos) current = "#" + this.id;
                });
                $tocLinks
                    .removeClass("active")
                    .filter('[href="' + current + '"]')
                    .addClass("active");
            }

            function onScroll() {
                var st = $(window).scrollTop();
                $header.toggleClass("scrolled", st > 40);
                var docH = $(document).outerHeight() - $(window).outerHeight();
                $("#readingProgress").css(
                    "width",
                    (docH > 0 ? Math.min((st / docH) * 100, 100) : 0) + "%",
                );
                $("#back-to-top").toggleClass("show", st > 500);
                highlightToc();
            }
            $(window).on("scroll resize", onScroll);
            onScroll();

            $("#back-to-top").on("click", function() {
                $("html, body").animate({
                    scrollTop: 0
                }, 700);
            });

            /* Smooth scroll con offset del header fijo */
            $(document).on(
                "click",
                'a[href^="#"]:not([data-bs-toggle])',
                function(e) {
                    var hash = $(this).attr("href");
                    if (!hash || hash === "#" || hash.length < 2) return;
                    var $t = $(hash);
                    if (!$t.length) return;
                    e.preventDefault();
                    $("html, body")
                        .stop()
                        .animate({
                            scrollTop: Math.max($t.offset().top - 96, 0)
                        }, 650);
                    if (window.innerWidth < 992 && $("#navMenu").hasClass("show")) {
                        bootstrap.Collapse.getOrCreateInstance(
                            document.getElementById("navMenu"),
                        ).hide();
                    }
                },
            );

            /* ==========================================================
               ANIMACIONES REVEAL
               ========================================================== */
            if ("IntersectionObserver" in window) {
                var io = new IntersectionObserver(
                    function(entries) {
                        entries.forEach(function(en) {
                            if (en.isIntersecting) {
                                en.target.classList.add("visible");
                                io.unobserve(en.target);
                            }
                        });
                    }, {
                        threshold: 0.12
                    },
                );
                document.querySelectorAll(".reveal").forEach(function(el) {
                    io.observe(el);
                });
            } else {
                $(".reveal").addClass("visible");
            }

            /* ==========================================================
               MODAL DE VIDEO (carga perezosa del iframe)
               video_url / thumbnail / titulo reemplazables desde el CMS
               ========================================================== */
            var VIDEO_URL =
                "https://www.youtube.com/embed/aqz-KE-bpKQ?autoplay=1&rel=0";
            $("#videoBlock").on("click", function() {
                $("#videoModal").modal("show");
            });
            $("#videoModal").on("show.bs.modal", function() {
                $("#videoFrame").attr("src", VIDEO_URL);
            });
            $("#videoModal").on("hidden.bs.modal", function() {
                $("#videoFrame").attr("src", "");
            });

            /* ==========================================================
               LIGHTBOX DE GALERÍA (navegación · contador · teclado)
               ========================================================== */
            var $items = $("#gallery .gallery-item"),
                lbIndex = 0;

            function lbShow(i) {
                lbIndex = (i + $items.length) % $items.length;
                var $img = $items.eq(lbIndex).find("img");
                $("#lbImage").attr({
                    src: $img.attr("src"),
                    alt: $img.attr("alt")
                });
                $("#lbCounter").text(lbIndex + 1 + " / " + $items.length);
                $("#lbCaption").text($items.eq(lbIndex).data("caption") || "");
            }

            function lbOpen(i) {
                lbShow(i);
                $("#lightbox").addClass("open");
                $("body").css("overflow", "hidden");
            }

            function lbClose() {
                $("#lightbox").removeClass("open");
                $("body").css("overflow", "");
            }
            $items.on("click", function() {
                lbOpen($items.index(this));
            });
            $("#lbClose").on("click", lbClose);
            $("#lbPrev").on("click", function(e) {
                e.stopPropagation();
                lbShow(lbIndex - 1);
            });
            $("#lbNext").on("click", function(e) {
                e.stopPropagation();
                lbShow(lbIndex + 1);
            });
            $("#lightbox").on("click", function(e) {
                if (e.target === this) lbClose();
            });
            $(document).on("keydown", function(e) {
                if (!$("#lightbox").hasClass("open")) return;
                if (e.key === "Escape") lbClose();
                if (e.key === "ArrowLeft") lbShow(lbIndex - 1);
                if (e.key === "ArrowRight") lbShow(lbIndex + 1);
            });

            /* ==========================================================
               COMPARTIR (URL dinámica según dominio actual)
               ========================================================== */
            var pageUrl = encodeURIComponent(window.location.href);
            var pageTitle = encodeURIComponent(document.title);
            $(".share-wa").attr(
                "href",
                "https://wa.me/?text=" + pageTitle + "%20" + pageUrl,
            );
            $(".share-fb").attr(
                "href",
                "https://www.facebook.com/sharer/sharer.php?u=" + pageUrl,
            );
            $(".share-li").attr(
                "href",
                "https://www.linkedin.com/sharing/share-offsite/?url=" + pageUrl,
            );

            $(".share-copy").on("click", function() {
                var $btn = $(this),
                    url = window.location.href;

                function feedback() {
                    $btn.addClass("copied").find("i").attr("class", "bi bi-check-lg");
                    setTimeout(function() {
                        $btn
                            .removeClass("copied")
                            .find("i")
                            .attr("class", "bi bi-link-45deg");
                    }, 1800);
                }

                function fallbackCopy(txt) {
                    var ta = document.createElement("textarea");
                    ta.value = txt;
                    ta.style.position = "fixed";
                    ta.style.opacity = "0";
                    document.body.appendChild(ta);
                    ta.select();
                    try {
                        document.execCommand("copy");
                    } catch (err) {}
                    document.body.removeChild(ta);
                }
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard
                        .writeText(url)
                        .then(feedback)
                        .catch(function() {
                            fallbackCopy(url);
                            feedback();
                        });
                } else {
                    fallbackCopy(url);
                    feedback();
                }
            });
        });
    </script>
@endsection
