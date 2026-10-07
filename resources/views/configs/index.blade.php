@extends('layouts.index')

@section('css')
    <style>
        /* ============================================================
                                        1. BASE · Identidad GIJAC
                                        ============================================================ */
        :root {
            --teal: #1e6f78;
            --teal-dark: #145962;
            --teal-mid: #287f88;
            --teal-light: #2c8f99;
            --slate-50: #f8fafc;
            --mint: #eef6f7;
            --ink: #0d2a2e;
            --meta: #0866ff;
            --wa: #25d366;
            --grad: linear-gradient(135deg, #145962, #1e6f78, #2c8f99);
            --shadow-soft: 0 20px 50px -20px rgba(20, 89, 98, 0.35);
            --shadow-card: 0 10px 30px -18px rgba(20, 89, 98, 0.35);
            --ease: cubic-bezier(0.2, 0.8, 0.2, 1);
            --sidebar-w: 264px;
            --font-head: "Plus Jakarta Sans", system-ui, sans-serif;
            --font-body: "Inter", system-ui, sans-serif;
        }

        ::selection {
            background: rgba(44, 143, 153, 0.25);
        }

        :focus-visible {
            outline: 3px solid rgba(44, 143, 153, 0.45);
            outline-offset: 2px;
            border-radius: 8px;
        }

        .gradient-text {
            background: linear-gradient(120deg, #2c8f99, #5fd6c9);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Fondo decorativo: gradientes radiales + partículas discretas */
        .page-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background:
                radial-gradient(700px 420px at 92% -5%,
                    rgba(44, 143, 153, 0.08),
                    transparent 60%),
                radial-gradient(600px 400px at 4% 100%,
                    rgba(20, 89, 98, 0.06),
                    transparent 60%);
        }

        .bg-dot {
            position: fixed;
            border-radius: 50%;
            background: rgba(44, 143, 153, 0.18);
            pointer-events: none;
            z-index: 0;
            animation: floatY 8s ease-in-out infinite;
        }

        /* ============================================================
                                   2. BOTONES
                                   ============================================================ */
        .btn {
            font-family: var(--font-head);
            font-weight: 600;
            border-radius: 999px;
            padding: 0.68rem 1.45rem;
            border: none;
            transition: all 0.3s var(--ease);
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
            background: #fff;
            border: 1px solid rgba(20, 89, 98, 0.2);
        }

        .btn-ghost:hover {
            background: var(--mint);
            color: var(--teal-dark);
        }

        .btn-meta {
            background: var(--meta);
            color: #fff;
            box-shadow: 0 10px 30px -8px rgba(8, 102, 255, 0.55);
        }

        .btn-meta:hover {
            background: #0a5ae0;
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 16px 40px -8px rgba(8, 102, 255, 0.85);
        }

        .btn-danger-soft {
            color: #b3403a;
            background: #fff;
            border: 1px solid rgba(217, 83, 79, 0.35);
        }

        .btn-danger-soft:hover {
            background: #fdeeed;
            color: #b3403a;
        }

        /* ============================================================
                                   3. SHELL · Sidebar blanco + Topbar
                                   ============================================================ */
        .dash-sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: var(--sidebar-w);
            z-index: 1040;
            background: #fff;
            border-right: 1px solid rgba(20, 89, 98, 0.08);
            display: flex;
            flex-direction: column;
            transition: transform 0.35s var(--ease);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 1.15rem 1.25rem;
            text-decoration: none;
            border-bottom: 1px solid rgba(20, 89, 98, 0.06);
        }

        .brand-logo {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            transition: transform 0.3s var(--ease);
        }

        .sidebar-brand:hover .brand-logo {
            transform: scale(1.06) rotate(-4deg);
        }

        .sidebar-brand .brand-name {
            color: var(--teal-dark);
            font-size: 0.95rem;
            line-height: 1.15;
        }

        .sidebar-brand .brand-name small {
            display: block;
            font-family: var(--font-body);
            font-weight: 600;
            font-size: 0.66rem;
            letter-spacing: 1.6px;
            color: #8aa0a2;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem 0.8rem 1rem;
        }

        .nav-section {
            font-size: 0.64rem;
            text-transform: uppercase;
            letter-spacing: 1.6px;
            color: #a3b7b9;
            font-weight: 700;
            padding: 1rem 0.8rem 0.35rem;
            display: block;
        }

        .side-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.85rem;
            margin-bottom: 2px;
            border-radius: 12px;
            color: #55707a;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            position: relative;
            transition: all 0.25s var(--ease);
        }

        .side-link i {
            font-size: 1.08rem;
            width: 22px;
            text-align: center;
            color: #8aa0a2;
            transition: color 0.25s ease;
        }

        .side-link:hover {
            background: #f1f5f7;
            color: var(--teal-dark);
        }

        .side-link:hover i {
            color: var(--teal-light);
        }

        .side-link.active {
            background: var(--mint);
            color: var(--teal-dark);
            font-weight: 700;
        }

        .side-link.active i {
            color: var(--teal-light);
        }

        .side-link.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 20%;
            bottom: 20%;
            width: 3px;
            border-radius: 3px;
            background: var(--teal-light);
        }

        .sidebar-foot {
            padding: 0.9rem 1.25rem;
            border-top: 1px solid rgba(20, 89, 98, 0.06);
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .avatar-sm {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--grad);
            color: #fff;
            display: grid;
            place-items: center;
            font-family: var(--font-head);
            font-weight: 800;
            font-size: 0.78rem;
            flex: 0 0 auto;
        }

        .sidebar-foot strong {
            color: var(--ink);
            font-size: 0.8rem;
            display: block;
            line-height: 1.2;
        }

        .sidebar-foot small {
            color: #8aa0a2;
            font-size: 0.7rem;
        }

        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(7, 20, 25, 0.5);
            z-index: 1035;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-backdrop.show {
            opacity: 1;
            visibility: visible;
        }

        .dash-main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            transition: margin 0.35s var(--ease);
        }

        .dash-topbar {
            position: sticky;
            top: 0;
            z-index: 1020;
            height: 62px;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0 1.6rem;
            background: rgba(248, 250, 252, 0.85);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(20, 89, 98, 0.08);
        }

        .breadcrumb {
            margin: 0;
            font-size: 0.82rem;
        }

        .breadcrumb a {
            color: #8aa0a2;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .breadcrumb a:hover {
            color: var(--teal);
        }

        .breadcrumb-item.active {
            color: var(--teal-dark);
            font-weight: 600;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: #c3d2d4;
        }

        .topbar-actions {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .topbar-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 1px solid rgba(20, 89, 98, 0.12);
            background: #fff;
            color: var(--teal-dark);
            font-size: 1.1rem;
            display: grid;
            place-items: center;
            transition: all 0.25s var(--ease);
        }

        .topbar-btn:hover {
            background: var(--mint);
            transform: translateY(-2px);
            box-shadow: var(--shadow-card);
        }

        .topbar-btn .notif-dot {
            position: absolute;
            top: 9px;
            right: 10px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ef4444;
            border: 2px solid #fff;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.28rem 0.8rem 0.28rem 0.35rem;
            border-radius: 999px;
            border: 1px solid rgba(20, 89, 98, 0.1);
            background: #fff;
            transition: all 0.25s var(--ease);
        }

        .topbar-user:hover {
            box-shadow: var(--shadow-card);
            transform: translateY(-2px);
        }

        .topbar-user .u-name {
            font-family: var(--font-head);
            font-weight: 700;
            font-size: 0.82rem;
            color: var(--teal-dark);
            line-height: 1.15;
            text-align: left;
        }

        .topbar-user .u-name small {
            display: block;
            font-family: var(--font-body);
            font-weight: 500;
            font-size: 0.68rem;
            color: #8aa0a2;
        }

        .dash-content {
            flex: 1;
            padding: 1.8rem 1.6rem 3.2rem;
            max-width: 1220px;
            width: 100%;
            margin: 0 auto;
        }

        /* ============================================================
                                   4. PAGE HEADER
                                   ============================================================ */
        .page-header {
            margin-bottom: 1.6rem;
        }

        .page-header h1 {
            font-size: clamp(1.4rem, 2.4vw, 1.8rem);
            letter-spacing: -0.5px;
            margin: 0 0 0.3rem;
        }

        .page-header p {
            margin: 0;
            color: #6b8083;
            font-size: 0.95rem;
        }

        .btn-menu {
            display: none;
        }

        /* ============================================================
                                   5. HERO DE ONBOARDING (oscuro + composición 3D CSS)
                                   ============================================================ */
        .wa-hero {
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            color: #eafaf9;
            background: radial-gradient(900px 480px at 85% -10%,
                    #1c6b74 0%,
                    #124e57 48%,
                    #0c343b 100%);
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .wa-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(95, 214, 201, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(95, 214, 201, 0.05) 1px, transparent 1px);
            background-size: 48px 48px;
            -webkit-mask-image: radial-gradient(ellipse 70% 80% at 70% 40%,
                    #000 30%,
                    transparent 100%);
            mask-image: radial-gradient(ellipse 70% 80% at 70% 40%,
                    #000 30%,
                    transparent 100%);
        }

        .h-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
        }

        .h-glow-1 {
            width: 320px;
            height: 320px;
            background: #2c8f99;
            opacity: 0.4;
            top: -110px;
            right: -60px;
            animation: floatGlow 10s ease-in-out infinite;
        }

        .h-glow-2 {
            width: 260px;
            height: 260px;
            background: #0f6f6a;
            opacity: 0.45;
            bottom: -110px;
            left: -50px;
            animation: floatGlow 13s ease-in-out infinite reverse;
        }

        .h-particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(95, 214, 201, 0.55);
            pointer-events: none;
            box-shadow: 0 0 8px rgba(95, 214, 201, 0.8);
            animation: floatY 6s ease-in-out infinite;
        }

        .wa-hero-inner {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: 2rem;
            align-items: center;
            padding: 2.8rem 2.6rem;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.22);
            padding: 0.42rem 1rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 2.2px;
            backdrop-filter: blur(8px);
            margin-bottom: 1.2rem;
        }

        .hero-badge .dot-live {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--wa);
            animation: pulse 1.8s infinite;
        }

        .wa-hero h2 {
            font-size: clamp(1.5rem, 2.8vw, 2.1rem);
            letter-spacing: -0.6px;
            line-height: 1.15;
            color: #fff;
            margin-bottom: 0.8rem;
        }

        .wa-hero .hero-txt {
            color: #bfe4e2;
            font-size: 0.98rem;
            line-height: 1.75;
            max-width: 440px;
            margin: 0;
        }

        /* --- Composición 3D: GIJAC → META → WhatsApp --- */
        .flow-perspective {
            perspective: 1100px;
            display: grid;
            place-items: center;
            padding: 1rem 0;
        }

        .flow-stage {
            display: flex;
            align-items: center;
            transform-style: preserve-3d;
            transition: transform 0.35s ease-out;
            will-change: transform;
        }

        .fnode-wrap {
            transform-style: preserve-3d;
        }

        .z1 {
            transform: translateZ(0px);
        }

        .z2 {
            transform: translateZ(70px);
        }

        .z3 {
            transform: translateZ(38px);
        }

        .fnode {
            width: 138px;
            padding: 1.05rem 0.7rem 0.9rem;
            text-align: center;
            border-radius: 20px;
            background: linear-gradient(160deg,
                    rgba(255, 255, 255, 0.09),
                    rgba(255, 255, 255, 0.03));
            border: 1px solid rgba(140, 220, 220, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow:
                0 26px 60px -28px rgba(0, 0, 0, 0.85),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transition:
                border-color 0.35s ease,
                box-shadow 0.35s ease,
                transform 0.35s var(--ease);
        }

        .fnode:hover {
            border-color: rgba(95, 214, 201, 0.55);
            box-shadow:
                0 30px 66px -28px rgba(0, 0, 0, 0.9),
                0 0 30px -6px rgba(44, 143, 153, 0.55);
            transform: translateY(-5px);
        }

        .fnode-chip {
            width: 54px;
            height: 54px;
            margin: 0 auto 0.65rem;
            border-radius: 16px;
            display: grid;
            place-items: center;
            font-family: var(--font-head);
            font-weight: 800;
            font-size: 1.5rem;
            color: #fff;
        }

        .chip-gijac {
            background: var(--grad);
            box-shadow:
                0 12px 26px -10px rgba(44, 143, 153, 0.8),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .chip-meta {
            background: linear-gradient(135deg, #0b2d5c, #1d4ed8);
            box-shadow:
                0 12px 26px -10px rgba(8, 102, 255, 0.7),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            font-size: 1.7rem;
        }

        .chip-wa {
            background: linear-gradient(135deg, #128c7e, #25d366);
            box-shadow:
                0 12px 26px -10px rgba(37, 211, 102, 0.7),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            font-size: 1.6rem;
        }

        .fnode strong {
            display: block;
            font-family: var(--font-head);
            font-weight: 800;
            font-size: 0.92rem;
            color: #fff;
            letter-spacing: 0.4px;
        }

        .fnode small {
            display: block;
            font-size: 0.58rem;
            font-weight: 700;
            letter-spacing: 1.6px;
            color: #7ee0d6;
            margin-top: 0.25rem;
        }

        .fnode-meta small {
            color: #7db4f8;
        }

        /* Conectores con puntos de luz viajando */
        .fconn {
            position: relative;
            flex: 1 1 80px;
            min-width: 56px;
            height: 2px;
            margin: 0 0.4rem;
            border-radius: 2px;
            background: linear-gradient(90deg,
                    rgba(95, 214, 201, 0.12),
                    rgba(95, 214, 201, 0.65),
                    rgba(125, 211, 252, 0.65),
                    rgba(125, 211, 252, 0.12));
        }

        .fconn i {
            position: absolute;
            top: 50%;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }

        .fconn .d1 {
            background: #5fd6c9;
            box-shadow: 0 0 12px 3px rgba(95, 214, 201, 0.8);
            animation: travelX 2.8s linear infinite;
        }

        .fconn .d2 {
            background: #7dd3fc;
            box-shadow: 0 0 10px 2px rgba(125, 211, 252, 0.7);
            animation: travelX 3.8s linear infinite 1.3s;
            opacity: 0.85;
        }

        @keyframes travelX {
            0% {
                left: 0;
                opacity: 0;
            }

            12% {
                opacity: 1;
            }

            88% {
                opacity: 1;
            }

            100% {
                left: 100%;
                opacity: 0;
            }
        }

        @keyframes travelY {
            0% {
                top: 0;
                opacity: 0;
            }

            12% {
                opacity: 1;
            }

            88% {
                opacity: 1;
            }

            100% {
                top: 100%;
                opacity: 0;
            }
        }

        @keyframes floatY {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes floatGlow {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(24px);
            }
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.6);
            }

            70% {
                box-shadow: 0 0 0 7px rgba(37, 211, 102, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }

        .float-a {
            animation: floatY 5s ease-in-out infinite;
        }

        .float-b {
            animation: floatY 6s ease-in-out infinite 0.6s;
        }

        .float-c {
            animation: floatY 5.5s ease-in-out infinite 1.1s;
        }

        .hero-trustline {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.4rem;
            font-size: 0.74rem;
            font-weight: 600;
            color: #8fc4bf;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.14);
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
        }

        .hero-trustline i {
            color: var(--wa);
        }

        /* ============================================================
                                   6. TILT 3D (clase reutilizable · máx 4°)
                                   ============================================================ */
        .tilt-3d {
            transform-style: preserve-3d;
            transition: transform 0.25s var(--ease);
            will-change: transform;
        }

        /* ============================================================
                                   7. ESTADO DE CONEXIÓN
                                   ============================================================ */
        .section-head {
            margin: 2.4rem 0 1.1rem;
        }

        .section-head h3 {
            font-size: 1.15rem;
            color: var(--ink);
            margin: 0;
            letter-spacing: -0.3px;
        }

        .section-head p {
            margin: 0.15rem 0 0;
            color: #6b8083;
            font-size: 0.9rem;
        }

        .gi-card {
            background: #fff;
            border: 1px solid rgba(30, 111, 120, 0.09);
            border-radius: 22px;
            box-shadow: var(--shadow-card);
        }

        .gi-card-pad {
            padding: 1.7rem 1.8rem;
        }

        .gi-card h4 {
            font-size: 1rem;
            color: var(--teal-dark);
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin: 0 0 1.1rem;
        }

        .gi-card h4 i {
            color: var(--teal-light);
        }

        .conn-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            font-family: var(--font-head);
            font-weight: 800;
            font-size: 0.8rem;
            letter-spacing: 1.6px;
            padding: 0.55rem 1.2rem;
            border-radius: 999px;
            border: 1px solid;
            margin-bottom: 1.1rem;
        }

        .conn-badge.on {
            background: #e9f9ef;
            color: #15803d;
            border-color: #c9ecd6;
        }

        .conn-badge.on .dot-live {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--wa);
            animation: pulse 1.8s infinite;
        }

        .conn-badge.off {
            background: #eef1f4;
            color: #5b6b74;
            border-color: #dde4e9;
        }

        .conn-badge.off .dot-off {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #8aa0a2;
        }

        .state-title {
            font-size: 1.25rem;
            margin: 0 0 0.35rem;
            letter-spacing: -0.3px;
        }

        .state-desc {
            color: #6b8083;
            font-size: 0.93rem;
            line-height: 1.7;
            margin: 0 0 1.4rem;
            max-width: 520px;
        }

        /* Checklist "Antes de comenzar" */
        .req-label {
            font-family: var(--font-head);
            font-weight: 700;
            font-size: 0.74rem;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: #8aa0a2;
            margin: 1.7rem 0 0.8rem;
        }

        .req-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.9rem;
        }

        .req-mini {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            background: var(--slate-50);
            border: 1px solid rgba(30, 111, 120, 0.1);
            border-radius: 16px;
            padding: 0.95rem 1rem;
            transition: all 0.3s var(--ease);
        }

        .req-mini:hover {
            background: var(--mint);
            border-color: rgba(44, 143, 153, 0.3);
            transform: translateY(-3px);
        }

        .req-ico {
            flex: 0 0 auto;
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: #e9f9ef;
            color: #15803d;
            display: grid;
            place-items: center;
            font-size: 1rem;
        }

        .req-mini strong {
            display: block;
            font-size: 0.85rem;
            color: var(--ink);
            line-height: 1.3;
        }

        .req-mini small {
            font-size: 0.74rem;
            color: #8aa0a2;
        }

        /* Timeline conectado: META → WHATSAPP → GIJAC */
        .conn-chain {
            display: flex;
            align-items: stretch;
            margin-top: 1.4rem;
        }

        .chain-step {
            flex: 1;
            text-align: center;
            position: relative;
            padding-top: 0.35rem;
        }

        .chain-dot {
            width: 46px;
            height: 46px;
            margin: 0 auto 0.6rem;
            border-radius: 50%;
            background: #e9f9ef;
            border: 2px solid var(--wa);
            color: #15803d;
            display: grid;
            place-items: center;
            font-size: 1.15rem;
            box-shadow: 0 8px 18px -8px rgba(37, 211, 102, 0.5);
            position: relative;
            z-index: 1;
        }

        .chain-dot .mini-live {
            position: absolute;
            top: -3px;
            right: -3px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--wa);
            border: 2px solid #fff;
        }

        .chain-step::before {
            content: "";
            position: absolute;
            top: 24px;
            left: -50%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(37, 211, 102, 0.5),
                    rgba(37, 211, 102, 0.5));
        }

        .chain-step:first-child::before {
            display: none;
        }

        .chain-step strong {
            display: block;
            font-family: var(--font-head);
            font-size: 0.82rem;
            color: var(--teal-dark);
        }

        .chain-step small {
            font-size: 0.72rem;
            color: #8aa0a2;
        }

        /* ============================================================
                                   8. DASHBOARD CONECTADO (3 columnas)
                                   ============================================================ */
        .id-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.8rem;
            padding: 0.68rem 0;
            border-bottom: 1px dashed rgba(30, 111, 120, 0.1);
            font-size: 0.87rem;
        }

        .id-row:last-child {
            border-bottom: 0;
        }

        .id-label {
            color: #7d9497;
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex: 0 0 auto;
        }

        .id-label i {
            color: var(--teal-light);
            font-size: 0.9rem;
        }

        .id-value {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            min-width: 0;
        }

        .id-value code {
            font-family: "Cascadia Code", Consolas, monospace;
            font-size: 0.8rem;
            background: var(--mint);
            color: var(--teal-dark);
            padding: 0.18rem 0.55rem;
            border-radius: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .copy-btn {
            flex: 0 0 auto;
            width: 28px;
            height: 28px;
            border: 0;
            border-radius: 8px;
            background: var(--mint);
            color: var(--teal);
            font-size: 0.8rem;
            display: grid;
            place-items: center;
            transition: all 0.2s ease;
        }

        .copy-btn:hover {
            background: var(--teal);
            color: #fff;
            transform: scale(1.08);
        }

        .copy-btn.copied {
            background: #16a34a;
            color: #fff;
        }

        .tech-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.8rem;
            padding: 0.7rem 0;
            border-bottom: 1px dashed rgba(30, 111, 120, 0.1);
        }

        .tech-row:last-child {
            border-bottom: 0;
        }

        .tech-info strong {
            display: block;
            font-size: 0.86rem;
            color: var(--ink);
        }

        .tech-info small {
            font-size: 0.73rem;
            color: #8aa0a2;
        }

        .mini-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.74rem;
            font-weight: 700;
            padding: 0.28rem 0.75rem;
            border-radius: 999px;
            border: 1px solid;
            white-space: nowrap;
        }

        .mb-ok {
            background: #e9f9ef;
            color: #15803d;
            border-color: #c9ecd6;
        }

        .mb-ok .q-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--wa);
            animation: pulse 2s infinite;
        }

        .mini-bar {
            height: 4px;
            border-radius: 4px;
            background: var(--mint);
            overflow: hidden;
            margin-top: 0.45rem;
        }

        .mini-fill {
            height: 100%;
            width: 0;
            border-radius: 4px;
            background: linear-gradient(90deg, #25d366, #16a34a);
            transition: width 1s var(--ease);
        }

        .api-pill {
            font-family: "Cascadia Code", Consolas, monospace;
            font-size: 0.76rem;
        }

        /* Calidad */
        .q-hero {
            text-align: center;
            padding: 0.4rem 0 1rem;
        }

        .q-big {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            font-family: var(--font-head);
            font-weight: 800;
            font-size: 1.15rem;
            color: #15803d;
            background: #e9f9ef;
            border: 1px solid #c9ecd6;
            padding: 0.6rem 1.4rem;
            border-radius: 999px;
        }

        .q-big .q-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--wa);
            animation: pulse 2s infinite;
        }

        .limit-caption {
            text-align: center;
            font-size: 0.78rem;
            color: #8aa0a2;
            margin: 1rem 0 0.35rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: 700;
        }

        .limit-value {
            text-align: center;
            font-family: var(--font-head);
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--teal-dark);
        }

        .limit-value small {
            font-size: 0.8rem;
            font-weight: 600;
            color: #6b8083;
        }

        .tier-meter {
            display: flex;
            gap: 0.3rem;
            margin-top: 0.9rem;
        }

        .tier-seg {
            flex: 1;
            height: 10px;
            border-radius: 6px;
            background: var(--mint);
            position: relative;
            transition: all 0.5s var(--ease);
        }

        .tier-seg.active {
            background: linear-gradient(90deg, #1e6f78, #2c8f99);
            box-shadow: 0 4px 12px -4px rgba(44, 143, 153, 0.6);
        }

        .tier-labels {
            display: flex;
            gap: 0.3rem;
            margin-top: 0.35rem;
        }

        .tier-labels span {
            flex: 1;
            text-align: center;
            font-size: 0.68rem;
            color: #a3b7b9;
            font-weight: 600;
        }

        .tier-labels span.on {
            color: var(--teal);
            font-weight: 700;
        }

        /* ============================================================
                                   9. ACCIONES
                                   ============================================================ */
        .action-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .action-card {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            width: 100%;
            text-align: left;
            text-decoration: none;
            background: #fff;
            border: 1px solid rgba(30, 111, 120, 0.1);
            border-radius: 18px;
            padding: 1.05rem 1.1rem;
            font-family: var(--font-head);
            font-weight: 700;
            font-size: 0.92rem;
            color: var(--teal-dark);
            transition: all 0.3s var(--ease);
            box-shadow: var(--shadow-card);
        }

        .action-card:hover {
            transform: translateY(-4px);
            border-color: rgba(44, 143, 153, 0.4);
            box-shadow: 0 18px 40px -22px rgba(20, 89, 98, 0.5);
        }

        .action-card i.ac-ico {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            border-radius: 13px;
            background: var(--grad);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 1.15rem;
            box-shadow: 0 8px 18px -8px rgba(44, 143, 153, 0.6);
            transition: transform 0.45s var(--ease);
        }

        .action-card:hover i.ac-ico {
            transform: rotate(-7deg) scale(1.08);
        }

        .action-card small {
            display: block;
            font-family: var(--font-body);
            font-weight: 500;
            font-size: 0.76rem;
            color: #8aa0a2;
        }

        .action-card.danger {
            color: #b3403a;
        }

        .action-card.danger i.ac-ico {
            background: linear-gradient(135deg, #b3403a, #d9534f);
            box-shadow: 0 8px 18px -8px rgba(217, 83, 79, 0.6);
        }

        /* ============================================================
                                   10. LOGS
                                   ============================================================ */
        .logs-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.87rem;
        }

        .logs-table th {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #7d9497;
            font-weight: 700;
            font-family: var(--font-head);
            padding: 0.85rem 1.2rem;
            background: #fbfdfd;
            border-bottom: 1px solid rgba(30, 111, 120, 0.08);
            text-align: left;
        }

        .logs-table td {
            padding: 0.85rem 1.2rem;
            border-bottom: 1px solid rgba(30, 111, 120, 0.06);
            color: #3d5254;
        }

        .logs-table tbody tr {
            transition: background 0.2s ease;
            cursor: default;
        }

        .logs-table tbody tr:hover {
            background: var(--mint);
        }

        .logs-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .ev-cell {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 600;
            color: var(--ink);
        }

        .ev-cell i {
            color: var(--teal-light);
        }

        .mono {
            font-family: "Cascadia Code", Consolas, monospace;
            font-size: 0.78rem;
            color: var(--teal);
        }

        .log-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.74rem;
            font-weight: 700;
            padding: 0.26rem 0.75rem;
            border-radius: 999px;
            border: 1px solid;
        }

        .lb-ok {
            background: #e9f9ef;
            color: #15803d;
            border-color: #c9ecd6;
        }

        .lb-warn {
            background: #fdf7e6;
            color: #a97a06;
            border-color: #f1e0ae;
        }

        /* ============================================================
                                   11. MODALES · TOASTS · DEMO
                                   ============================================================ */
        .modal-content {
            border: 0;
            border-radius: 22px;
            box-shadow: 0 40px 90px -20px rgba(13, 42, 46, 0.5);
        }

        .modal .form-control {
            border: 1px solid rgba(30, 111, 120, 0.16);
            border-radius: 12px;
            padding: 0.65rem 0.9rem;
            font-size: 0.92rem;
        }

        .modal .form-control:focus {
            border-color: var(--teal-light);
            box-shadow: 0 0 0 4px rgba(44, 143, 153, 0.12);
        }

        .modal .form-label {
            font-family: var(--font-head);
            font-weight: 700;
            font-size: 0.8rem;
            color: var(--teal-dark);
            margin-bottom: 0.35rem;
        }

        .confirm-ico {
            width: 72px;
            height: 72px;
            margin: 0 auto 1.1rem;
            border-radius: 22px;
            display: grid;
            place-items: center;
            font-size: 1.9rem;
        }

        .ci-warn {
            background: #fdf7e6;
            color: #a97a06;
        }

        .ci-ok {
            background: #e9f9ef;
            color: #15803d;
        }

        .toast-stack {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 5000;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            max-width: min(380px, calc(100vw - 40px));
        }

        .gi-toast {
            display: flex;
            gap: 0.8rem;
            align-items: flex-start;
            background: #fff;
            border-radius: 16px;
            padding: 0.9rem 1rem;
            box-shadow: 0 24px 60px -18px rgba(13, 42, 46, 0.4);
            border-left: 4px solid var(--tt, var(--teal));
            animation: toastIn 0.35s var(--ease) both;
        }

        .gi-toast.out {
            animation: toastOut 0.3s ease both;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        @keyframes toastOut {
            to {
                opacity: 0;
                transform: translateY(10px);
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
            font-size: 0.88rem;
            color: var(--ink);
        }

        .gi-toast span {
            font-size: 0.82rem;
            color: #6b8083;
            line-height: 1.45;
        }

        .demo-toggle {
            position: fixed;
            left: 20px;
            bottom: 20px;
            z-index: 1500;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.5rem 0.9rem;
            border: 1px dashed rgba(20, 89, 98, 0.4);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(6px);
            color: #6b8083;
            transition: all 0.25s var(--ease);
        }

        .demo-toggle:hover {
            color: var(--teal-dark);
            border-color: var(--teal-light);
            transform: translateY(-2px);
        }

        /* Transición de cambio de estado */
        .fade-swap {
            animation: fadeSwap 0.5s var(--ease) both;
        }

        @keyframes fadeSwap {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* ============================================================
                                   12. RESPONSIVE
                                   ============================================================ */
        @media (max-width: 1199.98px) {
            .action-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 991.98px) {
            .dash-sidebar {
                transform: translateX(-100%);
            }

            .dash-sidebar.open {
                transform: none;
                box-shadow: 30px 0 60px -30px rgba(0, 0, 0, 0.3);
            }

            .dash-main {
                margin-left: 0;
            }

            .btn-menu {
                display: inline-grid;
            }

            .wa-hero-inner {
                grid-template-columns: 1fr;
                padding: 2.2rem 1.8rem;
                text-align: center;
            }

            .wa-hero .hero-txt {
                margin: 0 auto;
            }

            .req-grid {
                grid-template-columns: 1fr;
            }

            .conn-chain {
                flex-direction: column;
                gap: 1.1rem;
            }

            .chain-step::before {
                left: 50%;
                top: -56%;
                width: 2px;
                height: 100%;
                transform: translateX(-50%);
                background: linear-gradient(180deg,
                        rgba(37, 211, 102, 0.5),
                        rgba(37, 211, 102, 0.5));
            }

            .chain-step:first-child::before {
                display: none;
            }
        }

        @media (max-width: 767.98px) {
            .flow-stage {
                flex-direction: column;
                gap: 0;
            }

            .fnode {
                width: min(230px, 74vw);
            }

            .fconn {
                width: 2px;
                height: 42px;
                flex: 0 0 auto;
                margin: 0.4rem 0;
                background: linear-gradient(180deg,
                        rgba(95, 214, 201, 0.12),
                        rgba(95, 214, 201, 0.65),
                        rgba(125, 211, 252, 0.65),
                        rgba(125, 211, 252, 0.12));
            }

            .fconn .d1 {
                animation: travelY 2.8s linear infinite;
            }

            .fconn .d2 {
                animation: travelY 3.8s linear infinite 1.3s;
            }

            .fconn i {
                left: 50%;
                top: 0;
            }

            .action-grid {
                grid-template-columns: 1fr;
            }

            /* Logs → cards */
            .logs-table thead {
                display: none;
            }

            .logs-table,
            .logs-table tbody,
            .logs-table tr,
            .logs-table td {
                display: block;
                width: 100%;
            }

            .logs-table tr {
                border: 1px solid rgba(30, 111, 120, 0.1);
                border-radius: 14px;
                margin-bottom: 0.8rem;
                overflow: hidden;
            }

            .logs-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
                padding: 0.6rem 0.95rem;
                border-bottom: 1px dashed rgba(30, 111, 120, 0.1);
                text-align: right;
            }

            .logs-table td:last-child {
                border-bottom: 0;
            }

            .logs-table td::before {
                content: attr(data-label);
                font-family: var(--font-head);
                font-weight: 700;
                color: var(--teal-dark);
                font-size: 0.7rem;
                text-transform: uppercase;
                letter-spacing: 0.6px;
                text-align: left;
            }

            .topbar-user .u-name {
                display: none;
            }
        }

        @media (max-width: 575.98px) {
            .dash-content {
                padding: 1.2rem 1rem 2.6rem;
            }

            .dash-topbar {
                padding: 0 0.9rem;
            }

            .gi-card-pad {
                padding: 1.3rem 1.2rem;
            }

            .wa-hero-inner {
                padding: 1.8rem 1.2rem;
            }

            .id-value code {
                max-width: 110px;
            }

            .demo-toggle {
                left: 12px;
                bottom: 12px;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .float-a,
            .float-b,
            .float-c,
            .h-glow,
            .h-particle,
            .fconn i,
            .hero-badge .dot-live,
            .conn-badge.on .dot-live,
            .mb-ok .q-dot,
            .q-big .q-dot {
                animation: none !important;
            }

            .fconn i {
                opacity: 0;
            }
        }
    </style>
@endsection

@section('content')
    <main class="dash-content">
        <!-- PAGE HEADER -->
        <div class="page-header">
            <h1 class="text-white">{{ __('Conexión WhatsApp Business API') }}</h1>
            <p class="text-white">
                {{ __('Conecta y administra tu número oficial de WhatsApp desde GIJAC Message Business.') }}
            </p>
        </div>

        <!-- ============================================================
                                    HERO DE ONBOARDING (composición 3D)
                                    ============================================================ -->
        <section class="wa-hero" id="waHero" aria-labelledby="heroTitle">
            <div class="h-glow h-glow-1" aria-hidden="true"></div>
            <div class="h-glow h-glow-2" aria-hidden="true"></div>
            <span class="h-particle" style="width: 5px; height: 5px; top: 18%; left: 8%" aria-hidden="true"></span>
            <span class="h-particle" style="width: 3px; height: 3px; top: 70%; left: 30%; animation-delay: 1.2s;"
                aria-hidden="true"></span>
            <span class="h-particle" style="width: 4px; height: 4px; top: 24%; left: 88%; animation-delay: 0.6s;"
                aria-hidden="true"></span>
            <span class="h-particle" style="width: 3px; height: 3px; top: 80%; left: 70%; animation-delay: 1.8s;"
                aria-hidden="true"></span>

            <div class="wa-hero-inner">
                <div>
                    <span class="hero-badge">
                        <span class="dot-live"></span>
                        {{ __('WHATSAPP BUSINESS PLATFORM') }}
                    </span>
                    <h2 id="heroTitle">
                        {{ __('Conecta tu') }} <span class="gradient-text">{{ __('WhatsApp Business') }}</span>
                    </h2>
                    <p class="hero-txt">
                        {{ __('Integra tu número oficial de WhatsApp y comienza a gestionar conversaciones, campañas y automatizaciones desde GIJAC Message Business.') }}
                    </p>
                </div>

                <!-- Composición 3D: GIJAC → META → WhatsApp -->
                <div>
                    <div class="flow-perspective">
                        <div class="flow-stage" id="flowStage">
                            <div class="fnode-wrap z1">
                                <div class="fnode float-a">
                                    <span class="fnode-chip chip-gijac">
                                        <img src="{{ asset('img/logo_gmb.png') }}" alt="GIJAC Message Business"
                                            width="100%">
                                    </span>
                                    <strong>{{ __('GIJAC') }}</strong><small>{{ __('MESSAGE BUSINESS') }}</small>
                                </div>
                            </div>
                            <div class="fconn" aria-hidden="true">
                                <i class="d1"></i>
                                <i class="d2"></i>
                            </div>
                            <div class="fnode-wrap z2">
                                <div class="fnode fnode-meta float-b">
                                    <span class="fnode-chip chip-meta">
                                        <img src="{{ asset('img/meta.png') }}" alt="META" width="100%">
                                    </span>
                                    <strong>{{ __('META') }}</strong>
                                    <small>{{ __('TECHNOLOGY PROVIDER') }}</small>
                                </div>
                            </div>
                            <div class="fconn" aria-hidden="true">
                                <i class="d1"></i>
                                <i class="d2"></i>
                            </div>
                            <div class="fnode-wrap z3">
                                <div class="fnode float-c">
                                    <span class="fnode-chip chip-wa">
                                        <img src="{{ asset('img/whatsapp-business.png') }}" alt="WhatsApp" width="100%">
                                    </span>
                                    <strong>{{ __('WhatsApp') }}</strong><small>{{ __('BUSINESS') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="hero-trustline">
                            <i class="bi bi-shield-lock-fill"></i>
                            {{ __('Conexión encriptada · Infraestructura oficial') }}
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
                                    ESTADO DE CONEXIÓN
                                    ============================================================ -->
        <div class="section-head">
            <h3 class="fs-2">{{ __('Estado de conexión') }}</h3>
            <p class="fs-5">
                {{ __('Situación actual de tu vinculación con WhatsApp Business Platform.') }}
            </p>
        </div>

        <div class="gi-card tilt-3d" id="stateCard">
            <div class="gi-card-pad">
                <!-- ===== ESTADO: NO CONECTADO ===== -->
                <div id="stateDisconnected" class="fade-swap">
                    <span class="conn-badge off">
                        <span class="dot-off"></span>
                        {{ __('NO CONECTADO') }}
                    </span>
                    <h4 class="state-title fs-3">
                        {{ __('Tu cuenta de WhatsApp Business todavía no está vinculada.') }}
                    </h4>
                    <p class="state-desc fs-7">
                        {{ __('Conecta tu cuenta de Meta Business para comenzar a utilizar WhatsApp Business desde GIJAC.') }}
                    </p>
                    <button type="button" class="btn btn-meta btn-lg" id="btnConnectMeta">
                        <i class="bi bi-meta fs-3 text-white"></i>
                        {{ __('Conectar con Meta') }}
                    </button>

                    <div class="req-label fs-5">{{ __('Antes de comenzar') }}</div>
                    <div class="req-grid">
                        <div class="req-mini">
                            <span class="req-ico">
                                <i class="bi bi-check-lg text-primary"></i>
                            </span>
                            <span>
                                <strong class="fs-4">{{ __('Business Manager de Meta') }}</strong>
                                <small class="fs-7">{{ __('Con acceso de administrador') }}</small>
                            </span>
                        </div>
                        <div class="req-mini">
                            <span class="req-ico">
                                <i class="bi bi-check-lg text-primary"></i>
                            </span>
                            <span>
                                <strong class="fs-4">{{ __('Cuenta de WhatsApp Business') }}</strong>
                                <small class="fs-7">{{ __('WABA activa en tu portafolio') }}</small>
                            </span>
                        </div>
                        <div class="req-mini">
                            <span class="req-ico">
                                <i class="bi bi-check-lg text-primary"></i>
                            </span>
                            <span>
                                <strong class="fs-4">{{ __('Número disponible para WhatsApp') }}</strong>
                                <small class="fs-7">{{ __('Sin vincular a otra plataforma') }}</small>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- ===== ESTADO: CONECTADO ===== -->
                <div id="stateConnected" class="d-none">
                    <span class="conn-badge on">
                        <span class="dot-live"></span>
                        {{ __('CONECTADO') }}
                    </span>
                    <h4 class="state-title fs-3">
                        {{ __('WhatsApp Business conectado correctamente') }}
                    </h4>
                    <p class="state-desc fs-7">
                        {{ __('Tu cuenta está activa y lista para utilizar WhatsApp desde GIJAC Message Business.') }}
                    </p>

                    <!-- Timeline META → WHATSAPP → GIJAC -->
                    <div class="conn-chain" aria-label="Cadena de conexión">
                        <div class="chain-step">
                            <span class="chain-dot">
                                <i class="bi bi-meta fs-3 text-primary"></i>
                                <span class="mini-live"></span>
                            </span>
                            <strong class="fs-4">{{ __('META BUSINESS') }}</strong>
                            <small class="fs-7">{{ __('Portafolio verificado') }}</small>
                        </div>
                        <div class="chain-step">
                            <span class="chain-dot">
                                <i class="bi bi-whatsapp fs-3 text-primary"></i>
                                <span class="mini-live"></span>
                            </span>
                            <strong class="fs-4">{{ __('WHATSAPP BUSINESS') }}</strong>
                            <small class="fs-7">{{ __('Número oficial vinculado') }}</small>
                        </div>
                        <div class="chain-step">
                            <span class="chain-dot">
                                <i class="bi bi-hexagon-fill fs-3 text-primary"></i>
                                <span class="mini-live"></span>
                            </span>
                            <strong class="fs-4">{{ __('GIJAC MESSAGE BUSINESS') }}</strong>
                            <small class="fs-7">{{ __('Plataforma operativa') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================
                                    DASHBOARD CONECTADO (3 columnas · visible solo conectado)
                                    ============================================================ -->
        <div id="dashConnected" class="d-none">
            <div class="row g-4 mt-1">
                <!-- CARD 1 · CUENTA WHATSAPP -->
                <div class="col-lg-4">
                    <div class="gi-card gi-card-pad h-100 tilt-3d">
                        <h4 class="fs-4">
                            <i class="bi bi-person-badge"></i>
                            {{ __('Cuenta WhatsApp Business') }}
                        </h4>
                        <div class="id-row">
                            <span class="id-label fs-6">
                                <i class="bi bi-hash"></i>
                                {{ __('WABA ID') }}
                            </span>
                            <span class="id-value fs-6">
                                <code class="fs-6" data-field="waba_id"></code>
                                <button class="copy-btn" data-copy="" data-bs-toggle="tooltip"
                                    data-bs-title="{{ __('Copiar ID') }}" aria-label="{{ __('Copiar WABA ID') }}">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </span>
                        </div>
                        <div class="id-row">
                            <span class="id-label fs-6">
                                <i class="bi bi-phone"></i>
                                {{ __('Phone Number ID') }}
                            </span>
                            <span class="id-value fs-6">
                                <code class="fs-6" data-field="phone_number_id"></code>
                                <button class="copy-btn" data-copy="" data-bs-toggle="tooltip"
                                    data-bs-title="{{ __('Copiar ID') }}"
                                    aria-label="{{ __('Copiar Phone Number ID') }}">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </span>
                        </div>
                        <div class="id-row">
                            <span class="id-label fs-6">
                                <i class="bi bi-telephone"></i>
                                {{ __('Número vinculado') }}
                            </span>
                            <span class="id-value fs-6">
                                <code class="fs-6" data-field="phone_number"></code>
                                <button class="copy-btn" data-copy="" data-bs-toggle="tooltip"
                                    data-bs-title="{{ __('Copiar ID') }}" aria-label="{{ __('Copiar número') }}">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </span>
                        </div>
                        <div class="id-row">
                            <span class="id-label fs-6">
                                <i class="bi bi-building"></i>
                                {{ __('Business ID') }}
                            </span>
                            <span class="id-value fs-6">
                                <code class="fs-6" data-field="business_id"></code>
                                <button class="copy-btn" data-copy="" data-bs-toggle="tooltip"
                                    data-bs-title="{{ __('Copiar ID') }}" aria-label="{{ __('Copiar Business ID') }}">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- CARD 2 · ESTADO TÉCNICO -->
                <div class="col-lg-4">
                    <div class="gi-card gi-card-pad h-100 tilt-3d">
                        <h4 class="fs-4">
                            <i class="bi bi-cpu"></i>
                            {{ __('Estado técnico') }}
                        </h4>
                        <div class="tech-row">
                            <div class="tech-info">
                                <strong class="fs-6">{{ __('Webhook') }}</strong>
                                <small class="fs-7">{{ __('Eventos en tiempo real') }}</small>
                                <div class="mini-bar">
                                    <div class="mini-fill" data-fill="100"></div>
                                </div>
                            </div>
                            <span class="mini-badge mb-ok fs-7">
                                <span class="q-dot"></span>
                                {{ __('Suscrito') }}
                            </span>
                        </div>
                        <div class="tech-row">
                            <div class="tech-info">
                                <strong class="fs-6">{{ __('Número') }}</strong>
                                <small class="fs-7">{{ __('Registro en Cloud API') }}</small>
                                <div class="mini-bar">
                                    <div class="mini-fill" data-fill="100"></div>
                                </div>
                            </div>
                            <span class="mini-badge mb-ok fs-7">
                                <span class="q-dot"></span>
                                {{ __('Registrado') }}
                            </span>
                        </div>
                        <div class="tech-row">
                            <div class="tech-info">
                                <strong class="fs-6">{{ __('API') }}</strong>
                                <small class="fs-7">{{ __('Envío y recepción') }}</small>
                                <div class="mini-bar">
                                    <div class="mini-fill" data-fill="100"></div>
                                </div>
                            </div>
                            <span class="mini-badge mb-ok fs-7">
                                <span class="q-dot"></span>
                                {{ __('Operativa') }}
                            </span>
                        </div>
                        <div class="tech-row">
                            <div class="tech-info">
                                <strong class="fs-6">{{ __('Token') }}</strong>
                                <small class="fs-7" data-field="token_text">{{ __('Expira en 58 días') }}</small>
                                <div class="mini-bar">
                                    <div class="mini-fill" data-fill="82"></div>
                                </div>
                            </div>
                            <span class="mini-badge mb-ok fs-7">
                                <span class="q-dot"></span>
                                {{ __('Activo') }}
                            </span>
                        </div>
                        <div class="tech-row">
                            <div class="tech-info">
                                <strong class="fs-6">{{ __('API Version') }}</strong>
                                <small class="fs-7">{{ __('Graph API') }}</small>
                            </div>
                            <span class="mini-badge mb-ok fs-7 api-pill" data-field="api_version">v0.0</span>
                        </div>
                    </div>
                </div>

                <!-- CARD 3 · CALIDAD Y CAPACIDAD -->
                <div class="col-lg-4">
                    <div class="gi-card gi-card-pad h-100 tilt-3d">
                        <h4 class="fs-4">
                            <i class="bi bi-heart-pulse"></i>
                            {{ __('Calidad del número') }}
                        </h4>
                        <div class="q-hero">
                            <span class="q-big">
                                <span class="q-dot"></span>
                                <span data-field="quality">
                                    {{ __('Excelente') }}
                                </span>
                            </span>
                        </div>
                        <div class="limit-caption">{{ __('Límite de mensajes') }}</div>
                        <div class="limit-value">
                            <span data-field="limit">10K</span>
                            <small class="fs-7">{{ __('mensajes / día') }}</small>
                        </div>
                        <div class="tier-meter" role="img" aria-label="{{ __('Nivel de capacidad: 10K de 100K') }}">
                            <span class="tier-seg"></span>
                            <span class="tier-seg active"></span>
                            <span class="tier-seg"></span>
                        </div>
                        <div class="tier-labels fs-5">
                            <span>1K</span>
                            <span class="on">10K</span>
                            <span>100K</span>
                        </div>
                        <p class="text-center mb-0 mt-3 fs-6" style="color: #8aa0a2">
                            {{ __('Los límites aumentan automáticamente al mantener una buena calidad.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================
                                    ACCIONES · ADMINISTRAR CONEXIÓN
                                    ============================================================ -->
        <div class="section-head">
            <h3 class="fs-2">{{ __('Administrar conexión') }}</h3>
            <p class="fs-5">{{ __('Acciones disponibles sobre tu número de WhatsApp Business.') }}</p>
        </div>
        <div class="action-grid">
            <button class="action-card" id="btnTest" data-bs-toggle="modal" data-bs-target="#testModal">
                <i class="ac-ico bi bi-send"></i>
                <span class="fs-5">
                    {{ __('Probar conexión') }}
                    <small class="fs-7">{{ __('Envía un mensaje de verificación') }}</small>
                </span>
            </button>
            <a class="action-card" href="#" id="btnTemplates">
                <i class="ac-ico bi bi-layout-text-window-reverse"></i>
                <span class="fs-5">
                    {{ __('Ver plantillas') }}
                    <small class="fs-7">{{ __('Gestiona tus plantillas aprobadas') }}</small>
                </span>
            </a>
            <button class="action-card" id="btnSync">
                <i class="ac-ico bi bi-arrow-clockwise"></i>
                <span class="fs-5">
                    {{ __('Actualizar configuración') }}
                    <small class="fs-7">{{ __('Sincroniza datos de la cuenta') }}</small>
                </span>
            </button>
            <button class="action-card danger" id="btnDisconnect" data-bs-toggle="modal"
                data-bs-target="#disconnectModal">
                <i class="ac-ico bi bi-ban"></i>
                <span class="fs-5">
                    {{ __('Desconectar') }}
                    <small class="fs-7">{{ __('Desvincula tu número actual') }}</small>
                </span>
            </button>
        </div>

        <!-- ============================================================
                                    ACTIVIDAD / LOGS
                                    ============================================================ -->
        <div class="section-head">
            <h3 class="fs-2">{{ __('Actividad reciente') }}</h3>
            <p class="fs-5">{{ __('Últimos eventos relacionados con tu conexión de WhatsApp.') }}</p>
        </div>
        <div class="gi-card" style="overflow: hidden">
            <div class="table-responsive">
                <table class="logs-table fs-5" aria-label="{{ __('Actividad reciente de la conexión') }}">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('Evento') }}</th>
                            <th scope="col">{{ __('Cuenta') }}</th>
                            <th scope="col">{{ __('Fecha') }}</th>
                            <th scope="col">{{ __('Estado') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-label="Evento">
                                <span class="ev-cell">
                                    <i class="bi bi-plug"></i>
                                    {{ __('Webhook conectado') }}
                                </span>
                            </td>
                            <td data-label="Cuenta">
                                <span class="mono">{{ __('WABA 123456') }}</span>
                            </td>
                            <td data-label="Fecha">{{ __('Hoy, 10:32') }}</td>
                            <td data-label="Estado">
                                <span class="log-badge lb-ok">
                                    <i class="bi bi-check-lg"></i>
                                    {{ __('Correcto') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Evento">
                                <span class="ev-cell">
                                    <i class="bi bi-telephone"></i>
                                    {{ __('Número registrado') }}
                                </span>
                            </td>
                            <td data-label="Cuenta">
                                <span class="mono">+57 300 123 4567</span>
                            </td>
                            <td data-label="Fecha">{{ __('Hoy, 10:31') }}</td>
                            <td data-label="Estado">
                                <span class="log-badge lb-ok">
                                    <i class="bi bi-check-lg"></i>
                                    {{ __('Correcto') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Evento">
                                <span class="ev-cell">
                                    <i class="bi bi-link-45deg"></i>
                                    {{ __('Cuenta vinculada') }}
                                </span>
                            </td>
                            <td data-label="Cuenta">
                                <span class="mono">{{ __('Meta Business') }}</span>
                            </td>
                            <td data-label="Fecha">{{ __('Hoy, 10:30') }}</td>
                            <td data-label="Estado">
                                <span class="log-badge lb-ok">
                                    <i class="bi bi-check-lg"></i>
                                    {{ __('Correcto') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Evento">
                                <span class="ev-cell">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    {{ __('Error de sincronización') }}
                                </span>
                            </td>
                            <td data-label="Cuenta">
                                <span class="mono">{{ __('WABA 123456') }}</span>
                            </td>
                            <td data-label="Fecha">{{ __('Ayer, 18:42') }}</td>
                            <td data-label="Estado">
                                <span class="log-badge lb-warn">
                                    <i class="bi bi-exclamation-lg"></i>
                                    {{ __('Revisar') }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Toasts + toggle demo -->
    <div class="toast-stack" id="toastStack" aria-live="polite"></div>
    <button class="demo-toggle" id="demoToggle" title="Herramienta de demostración">
        <i class="bi bi-toggles"></i>
        {{ __('Demo · Cambiar estado') }}
    </button>
@endsection

@section('modal')
    <!-- ============================================================
                             MODAL · PROBAR CONEXIÓN
                             ============================================================ -->
    <div class="modal fade" id="testModal" tabindex="-1" aria-labelledby="testModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="p-4">
                    <div id="testFormWrap">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0" id="testModalTitle">
                                <i class="bi bi-send me-2"
                                    style="color: var(--teal-light)"></i>{{ __('Probar conexión') }}
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <form id="testForm">
                            <div class="mb-3">
                                <label for="testTo" class="form-label">{{ __('Número de WhatsApp') }}</label>
                                <input type="tel" class="form-control" id="testTo" value="+57 300 123 4567" />
                            </div>
                            <div class="mb-4">
                                <label for="testMsg" class="form-label">{{ __('Mensaje') }}</label>
                                <textarea class="form-control" id="testMsg" rows="3">
    {{ __('Hola, este es un mensaje de prueba enviado desde GIJAC Message Business.') }}</textarea>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">
                                    {{ __('Cancelar') }}
                                </button>
                                <button type="submit" class="btn btn-glow" id="btnSubmitTest">
                                    <i class="bi bi-send me-1"></i> {{ __('Enviar mensaje de prueba') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- Vista de éxito (se muestra tras el envío simulado) -->
                    <div id="testSuccess" class="d-none text-center py-3">
                        <div class="confirm-ico ci-ok">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <h4 class="mb-2">{{ __('Mensaje enviado') }}</h4>
                        <p class="text-secondary mb-4" style="font-size: 0.9rem">
                            {{ __('La conexión funciona correctamente. Revisa el WhatsApp de destino.') }}
                        </p>
                        <button type="button" class="btn btn-glow" data-bs-dismiss="modal">
                            {{ __('Listo') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================
                             MODAL · CONFIRMAR DESCONEXIÓN
                             ============================================================ -->
    <div class="modal fade" id="disconnectModal" tabindex="-1" aria-labelledby="discTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 440px">
            <div class="modal-content">
                <div class="p-4 text-center">
                    <div class="confirm-ico ci-warn">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <h4 id="discTitle">{{ __('¿Desconectar WhatsApp Business?') }}</h4>
                    <p class="text-secondary" style="font-size: 0.9rem">
                        {{ __('Al desconectar esta cuenta dejarás de gestionar este número desde GIJAC Message Business.') }}
                    </p>
                    <div class="d-flex justify-content-center gap-2 mt-4">
                        <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">
                            {{ __('Cancelar') }}
                        </button>
                        <button type="button" class="btn btn-danger-soft" id="btnConfirmDisconnect"
                            style="color: #fff; background: #d9534f; border-color: #d9534f">
                            <i class="bi bi-plug-x me-1"></i> {{ __('Desconectar cuenta') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            "use strict";

            /* ==========================================================
            1. ESTADO MOCK · cambia entre los dos escenarios
            ========================================================== */
            const BOOT = @json($boot);
            let connected = BOOT.connected;
            const signup = {
                code: null,
                session: null,
                sent: false,
                timer: null
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': BOOT.csrf,
                    'Accept': 'application/json'
                }
            });

            function errMsg(xhr) {
                return (xhr.responseJSON && xhr.responseJSON.message) || 'Error inesperado';
            }

            const QUALITY = {
                GREEN: 'Excelente',
                YELLOW: 'Media',
                RED: 'Baja',
                UNKNOWN: 'Sin datos'
            };

            function fillAccount(a) {
                ['waba_id', 'phone_number_id', 'phone_number', 'business_id'].forEach(function(f) {
                    if (a[f] == null) return;
                    const $c = $('code[data-field="' + f + '"]').text(a[f]);
                    $c.closest('.id-value').find('.copy-btn').attr('data-copy', a[f]).data('copy', a[f]);
                });
                if (a.quality) $('[data-field="quality"]').text(QUALITY[a.quality] || a.quality);
                if (a.limit) $('[data-field="limit"]').text(a.limit);
                if (a.token_days != null) {
                    $('[data-field="token_text"]').text('Expira en ' + a.token_days + ' días');
                    const pct = Math.min(100, Math.round(a.token_days / 60 * 100));
                    $('[data-field="token_text"]').closest('.tech-row').find('.mini-fill')
                        .attr('data-fill', pct).data('fill', pct);
                }
                $('[data-field="api_version"]').text(BOOT.graph_version);
            }
            if (BOOT.account) fillAccount(BOOT.account);
            if (!BOOT.demo) $('#demoToggle').remove();

            /* ==========================================================
            2. HELPERS · toasts, loading, fade
            ========================================================== */
            function toast(type, title, msg) {
                const cfg = {
                    success: ["bi-check-circle-fill", "#16a34a"],
                    info: ["bi-info-circle-fill", "#2C8F99"],
                    warning: ["bi-exclamation-triangle-fill", "#d99a05"],
                    error: ["bi-x-circle-fill", "#d9534f"],
                } [type];
                const $t = $(
                    '<div class="gi-toast" style="--tt:' +
                    cfg[1] +
                    '"><i class="bi ' +
                    cfg[0] +
                    '"></i><div><strong>' +
                    title +
                    "</strong><span>" +
                    msg +
                    "</span></div></div>",
                );
                $("#toastStack").append($t);
                setTimeout(function() {
                    $t.addClass("out");
                    setTimeout(function() {
                        $t.remove();
                    }, 320);
                }, 4000);
            }

            function setLoading($btn, on, label) {
                if (on) {
                    $btn
                        .data("orig", $btn.html())
                        .prop("disabled", true)
                        .html(
                            '<span class="spinner-border spinner-border-sm me-2"></span>' +
                            (label || ""),
                        );
                } else {
                    $btn.prop("disabled", false).html($btn.data("orig"));
                }
            }

            function refade($el) {
                $el.removeClass("fade-swap");
                void $el[0].offsetWidth;
                $el.addClass("fade-swap");
            }

            /* ==========================================================
            3. RENDER DE ESTADO (conectado / no conectado)
            ========================================================== */
            function renderState() {
                $("#stateDisconnected, #stateConnected").addClass("d-none");
                $("#dashConnected").addClass("d-none");

                if (connected) {
                    $("#stateConnected").removeClass("d-none");
                    $("#dashConnected").removeClass("d-none");
                    refade($("#stateConnected"));
                    /* Animar barras tras el pintado */
                    setTimeout(function() {
                        $("#dashConnected .mini-fill").each(function() {
                            $(this).css("width", $(this).data("fill") + "%");
                        });
                    }, 80);
                } else {
                    $("#stateDisconnected").removeClass("d-none");
                    refade($("#stateDisconnected"));
                    $("#dashConnected .mini-fill").css("width", 0);
                }
            }
            renderState();

            /* ==========================================================
               5. PARALLAX del hero (composición 3D)
               ========================================================== */
            const $stage = $("#flowStage");
            $("#waHero")
                .on("mousemove", function(e) {
                    if (window.innerWidth < 992) return;
                    const r = this.getBoundingClientRect();
                    const x = (e.clientX - r.left) / r.width - 0.5;
                    const y = (e.clientY - r.top) / r.height - 0.5;
                    $stage.css(
                        "transform",
                        "rotateY(" +
                        (x * 7).toFixed(2) +
                        "deg) rotateX(" +
                        (-y * 7).toFixed(2) +
                        "deg)",
                    );
                })
                .on("mouseleave", function() {
                    $stage.css("transform", "");
                });

            /* ==========================================================
               6. COPIAR ID · tooltip + toast + feedback
               ========================================================== */
            /* Inicializar tooltips de Bootstrap */
            document
                .querySelectorAll('[data-bs-toggle="tooltip"]')
                .forEach(function(el) {
                    new bootstrap.Tooltip(el);
                });

            $(document).on("click", ".copy-btn", function() {
                const $btn = $(this),
                    text = $btn.data("copy");
                const tip = bootstrap.Tooltip.getInstance($btn[0]);
                if (tip) tip.hide();

                function feedback() {
                    $btn.addClass("copied").find("i").attr("class", "bi bi-check-lg");
                    setTimeout(function() {
                        $btn
                            .removeClass("copied")
                            .find("i")
                            .attr("class", "bi bi-clipboard");
                    }, 1500);
                }
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(text).then(feedback);
                } else {
                    const ta = document.createElement("textarea");
                    ta.value = text;
                    document.body.appendChild(ta);
                    ta.select();
                    try {
                        document.execCommand("copy");
                    } catch (e) {}
                    document.body.removeChild(ta);
                    feedback();
                }
                toast("success", "ID copiado correctamente", text);
            });

            /* ==========================================================
               7. ACCIONES SIMULADAS (mock · sin backend)
               ========================================================== */
            /* ===== Embedded Signup ===== */
            if (!BOOT.demo) {
                window.fbAsyncInit = function() {
                    FB.init({
                        appId: BOOT.fb_app_id,
                        autoLogAppEvents: true,
                        xfbml: true,
                        version: BOOT.graph_version
                    });
                };
                $('<script>', {
                    async: true,
                    defer: true,
                    crossorigin: 'anonymous',
                    src: 'https://connect.facebook.net/en_US/sdk.js'
                }).appendTo('head');
            }

            function sendSignup() {
                if (signup.sent || !signup.code) return;
                const s = signup.session;
                if (s && !String(s.event).startsWith('FINISH')) return;
                signup.sent = true;
                clearTimeout(signup.timer);

                $.ajax({
                    url: BOOT.routes.exchange,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        code: signup.code,
                        event: s ? s.event : null,
                        waba_id: s && s.data ? s.data.waba_id || null : null,
                        phone_number_id: s && s.data ? s.data.phone_number_id || null : null,
                        business_id: s && s.data ? s.data.business_id || null : null
                    })
                }).done(function(res) {
                    BOOT.account = res.account;
                    fillAccount(res.account);
                    connected = true;
                    renderState();
                    toast('success', 'WhatsApp conectado', res.message);
                    setTimeout(function() {
                        $('#dashConnected')[0].scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }, 350);
                }).fail(function(xhr) {
                    toast('error', 'No se pudo vincular', errMsg(xhr));
                }).always(function() {
                    setLoading($('#btnConnectMeta'), false);
                });
            }

            window.addEventListener('message', function(event) {
                if (!event.origin.endsWith('facebook.com')) return;
                let data;
                try {
                    data = JSON.parse(event.data);
                } catch (e) {
                    return;
                } // mensajes internos del SDK
                if (data.type !== 'WA_EMBEDDED_SIGNUP') return;

                if (data.event === 'CANCEL' || data.event === 'ERROR') {
                    setLoading($('#btnConnectMeta'), false);
                    toast('warning', 'Flujo no completado',
                        data.data && data.data.error_message ? data.data.error_message :
                        'Cancelaste el proceso.');
                    return;
                }
                signup.session = data;
                sendSignup();
            });

            $('#btnConnectMeta').on('click', function() {
                if (BOOT.demo) {
                    toast('warning', 'Modo demo',
                        'Falta configurar client_id y config_id en config/facebook.php.');
                    return;
                }
                signup.code = null;
                signup.session = null;
                signup.sent = false;
                setLoading($(this), true, 'Conectando…');

                FB.login(function(response) {
                    if (response.authResponse) {
                        signup.code = response.authResponse.code;
                        if (signup.session) {
                            sendSignup();
                        } else {
                            // Si el evento con los IDs no llega, el servidor los resuelve solo
                            signup.timer = setTimeout(sendSignup, 6000);
                        }
                    } else {
                        setLoading($('#btnConnectMeta'), false);
                        toast('info', 'Cancelado', 'No se completó el inicio de sesión con Meta.');
                    }
                }, {
                    config_id: BOOT.fb_config_id,
                    response_type: 'code',
                    override_default_response_type: true,
                    extras: {
                        setup: {}
                    }
                });
            });

            /* Desconectar */
            $("#btnConfirmDisconnect").on("click", function() {
                const $b = $(this);
                setLoading($b, true, "Desconectando…");
                $.ajax({
                        url: BOOT.routes.disconnect,
                        method: 'DELETE'
                    })
                    .done(function(res) {
                        bootstrap.Modal.getInstance(document.getElementById("disconnectModal")).hide();
                        connected = false;
                        renderState();
                        toast("info", "Cuenta desconectada", res.message);
                    })
                    .fail(function(xhr) {
                        toast("error", "Error", errMsg(xhr));
                    })
                    .always(function() {
                        setLoading($b, false);
                    });
            });

            /* Probar conexión */
            $("#testForm").on("submit", function(e) {
                e.preventDefault();
                const $b = $("#btnSubmitTest");
                setLoading($b, true, "Enviando…");
                $.post(BOOT.routes.test, {
                        to: $("#testTo").val(),
                        message: $("#testMsg").val().trim()
                    })
                    .done(function(res) {
                        $("#testFormWrap").addClass("d-none");
                        $("#testSuccess").removeClass("d-none").addClass("fade-swap");
                        toast("success", "Mensaje de prueba enviado", res.message);
                    })
                    .fail(function(xhr) {
                        toast("error", "No se pudo enviar", errMsg(xhr));
                    })
                    .always(function() {
                        setLoading($b, false);
                    });
            });

            /* Actualizar configuración */
            $("#btnSync").on("click", function() {
                const $b = $(this);
                setLoading($b, true, "");
                $.get(BOOT.routes.test.replace('test-message', 'status'))
                    .done(function(res) {
                        fillAccount({
                            quality: res.account.quality_rating,
                            limit: res.account.messaging_limit
                        });
                        toast("success", "Configuración actualizada", "Datos sincronizados con Meta.");
                    })
                    .fail(function(xhr) {
                        toast("error", "Error", errMsg(xhr));
                    })
                    .always(function() {
                        setLoading($b, false);
                    });
            });

            /* Ver plantillas (placeholder de navegación) */
            $("#btnTemplates").on("click", function(e) {
                e.preventDefault();
                toast(
                    "info",
                    "Plantillas",
                    "Aquí se abrirá /admin/whatsapp/templates.",
                );
            });

            /* ==========================================================
               8. TOGGLE DEMO · alternar escenarios
               ========================================================== */
            $("#demoToggle").on("click", function() {
                connected = !connected;
                renderState();
                toast(
                    "info",
                    "Demo",
                    "Estado cambiado a: " + (connected ? "CONECTADO" : "NO CONECTADO"),
                );
            });

            /* ==========================================================
               9. SIDEBAR MÓVIL
               ========================================================== */
            function closeSidebar() {
                $("#dashSidebar").removeClass("open");
                $("#sidebarBackdrop").removeClass("show");
            }
            $("#sidebarToggle").on("click", function() {
                $("#dashSidebar").toggleClass("open");
                $("#sidebarBackdrop").toggleClass("show");
            });
            $("#sidebarBackdrop").on("click", closeSidebar);
        });
    </script>
@endsection
