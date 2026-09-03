<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <title>{{ __('GIJAC MESSAGE BUSINESS') }}</title>
    <meta charset="utf-8">

    <meta name="robots" content="noindex,nofollow">

    <link rel="shortcut icon" href="{{ asset('img/logo_gmb.png') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css">

    <!--begin::Google tag-->
    <!--end::Google tag-->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
    <style>
        :root {
            --primary: #1E6F78;
            --secondary: #287F88;
            --dark: #145962;
            --accent: #2C8F99;
            --bg: #F8FAFC;
            --white: #FFFFFF;
            --glow: 0 0 60px rgba(44, 143, 153, .45);
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            overflow-x: hidden
        }

        h1,
        h2,
        h3,
        .display {
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            letter-spacing: -0.02em
        }

        /* ============ ANIMATED BACKGROUND ============ */
        .bg-stage {
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                radial-gradient(1200px 800px at 10% 20%, rgba(30, 111, 120, .35), transparent 60%),
                radial-gradient(900px 700px at 90% 80%, rgba(44, 143, 153, .30), transparent 60%),
                radial-gradient(700px 600px at 50% 50%, rgba(20, 89, 98, .25), transparent 65%),
                linear-gradient(135deg, #0b2a2e 0%, #103e44 45%, #0b2a2e 100%);
            overflow: hidden
        }

        .mesh {
            position: absolute;
            inset: -20%;
            filter: blur(80px);
            opacity: .7;
            animation: meshMove 18s ease-in-out infinite alternate
        }

        .mesh .blob {
            position: absolute;
            width: 520px;
            height: 520px;
            border-radius: 50%;
            mix-blend-mode: screen
        }

        .blob.b1 {
            background: #2C8F99;
            top: -10%;
            left: -5%;
            animation: float1 14s ease-in-out infinite
        }

        .blob.b2 {
            background: #1E6F78;
            bottom: -15%;
            right: -10%;
            animation: float2 17s ease-in-out infinite
        }

        .blob.b3 {
            background: #287F88;
            top: 35%;
            left: 45%;
            animation: float3 20s ease-in-out infinite
        }

        @keyframes meshMove {
            0% {
                transform: translate(0, 0)
            }

            100% {
                transform: translate(-4%, 3%)
            }
        }

        @keyframes float1 {

            0%,
            100% {
                transform: translate(0, 0) scale(1)
            }

            50% {
                transform: translate(60px, 40px) scale(1.1)
            }
        }

        @keyframes float2 {

            0%,
            100% {
                transform: translate(0, 0) scale(1)
            }

            50% {
                transform: translate(-70px, -50px) scale(1.15)
            }
        }

        @keyframes float3 {

            0%,
            100% {
                transform: translate(0, 0) scale(1)
            }

            50% {
                transform: translate(30px, -60px) scale(1.05)
            }
        }

        /* particles */
        .particles {
            position: absolute;
            inset: 0
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, .6);
            box-shadow: 0 0 10px rgba(255, 255, 255, .6);
            animation: rise linear infinite
        }

        @keyframes rise {
            0% {
                transform: translateY(100vh) scale(.4);
                opacity: 0
            }

            10% {
                opacity: .7
            }

            100% {
                transform: translateY(-10vh) scale(1);
                opacity: 0
            }
        }

        /* network lines */
        .network {
            position: absolute;
            inset: 0;
            opacity: .25
        }

        .network svg {
            width: 100%;
            height: 100%
        }

        .network line {
            stroke: #7fe3ec;
            stroke-width: 1;
            stroke-dasharray: 4 6;
            animation: dash 8s linear infinite
        }

        @keyframes dash {
            to {
                stroke-dashoffset: -200
            }
        }

        /* glass shapes */
        .glass-shape {
            position: absolute;
            border: 1px solid rgba(255, 255, 255, .15);
            background: rgba(255, 255, 255, .04);
            backdrop-filter: blur(14px);
            border-radius: 24px;
            animation: drift 22s ease-in-out infinite
        }

        .gs1 {
            width: 180px;
            height: 180px;
            top: 12%;
            right: 12%;
            transform: rotate(15deg)
        }

        .gs2 {
            width: 120px;
            height: 120px;
            bottom: 15%;
            left: 8%;
            border-radius: 30px;
            animation-duration: 26s
        }

        .gs3 {
            width: 80px;
            height: 80px;
            top: 60%;
            right: 25%;
            border-radius: 50%;
            animation-duration: 19s
        }

        @keyframes drift {

            0%,
            100% {
                transform: translate(0, 0) rotate(0)
            }

            50% {
                transform: translate(20px, -30px) rotate(10deg)
            }
        }

        /* ============ LAYOUT ============ */
        .stage {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr
        }

        @media (max-width:991px) {
            .stage {
                grid-template-columns: 1fr
            }
        }

        /* LEFT */
        .left {
            position: relative;
            padding: 56px 64px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #eafcff
        }

        @media (max-width:991px) {
            .left {
                padding: 40px 24px
            }
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            opacity: 0;
            transform: translateY(-10px);
            animation: fadeIn .9s .1s forwards
        }

        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), var(--primary));
            display: grid;
            place-items: center;
            box-shadow: var(--glow)
        }

        .brand-mark i {
            color: #fff;
            font-size: 20px
        }

        .brand-name {
            font-weight: 700;
            font-size: 18px;
            letter-spacing: .02em
        }

        .brand-name span {
            color: #8bdde6
        }

        .headline {
            margin-top: 56px;
            max-width: 560px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 1s .4s forwards
        }

        .headline h1 {
            font-size: clamp(30px, 3.4vw, 48px);
            font-weight: 700;
            line-height: 1.08;
            color: #fff
        }

        .headline h1 em {
            font-style: normal;
            background: linear-gradient(90deg, #7fe3ec, #2C8F99);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent
        }

        .headline p {
            margin-top: 18px;
            font-size: 17px;
            color: #c8e6ea;
            max-width: 520px;
            line-height: 1.6
        }

        /* Dashboard 3D */
        .dash-wrap {
            position: relative;
            margin-top: 28px;
            perspective: 1400px;
            opacity: 0;
            animation: fadeIn 1.1s .7s forwards
        }

        .dash {
            position: relative;
            width: 100%;
            max-width: 560px;
            border-radius: 20px;
            padding: 18px;
            background: linear-gradient(160deg, rgba(255, 255, 255, .14), rgba(255, 255, 255, .04));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, .18);
            box-shadow: 0 40px 80px -20px rgba(0, 0, 0, .5), inset 0 1px 0 rgba(255, 255, 255, .15);
            transform-style: preserve-3d;
            animation: floatDash 8s ease-in-out infinite;
            transition: transform .3s ease
        }

        @keyframes floatDash {

            0%,
            100% {
                transform: rotateX(6deg) rotateY(-8deg) translateY(0)
            }

            50% {
                transform: rotateX(4deg) rotateY(-6deg) translateY(-10px)
            }
        }

        .dash-top {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 12px
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ff5f57
        }

        .dot:nth-child(2) {
            background: #febc2e
        }

        .dot:nth-child(3) {
            background: #28c840
        }

        .dash-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 12px
        }

        .card-mini {
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 14px;
            padding: 14px;
            color: #eafcff
        }

        .card-mini h4 {
            margin: 0 0 8px;
            font-size: 12px;
            font-weight: 600;
            color: #8bdde6;
            text-transform: uppercase;
            letter-spacing: .08em
        }

        .chat-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            font-size: 13px
        }

        .avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #25D366, #128C7E);
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 12px;
            flex-shrink: 0
        }

        .chat-row .who {
            font-weight: 600
        }

        .chat-row .msg {
            color: #b8d4d8;
            font-size: 12px
        }

        .metric {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-top: 6px
        }

        .metric b {
            font-size: 22px;
            font-weight: 700;
            color: #fff
        }

        .metric span {
            color: #8bdde6;
            font-size: 11px
        }

        .bar {
            height: 6px;
            background: rgba(255, 255, 255, .1);
            border-radius: 6px;
            overflow: hidden;
            margin-top: 6px
        }

        .bar i {
            display: block;
            height: 100%;
            background: linear-gradient(90deg, var(--accent), #7fe3ec);
            border-radius: 6px;
            animation: fill 3s ease-in-out infinite alternate
        }

        @keyframes fill {
            from {
                width: 30%
            }

            to {
                width: 88%
            }
        }

        .spark {
            display: flex;
            align-items: flex-end;
            gap: 4px;
            height: 50px;
            margin-top: 6px
        }

        .spark i {
            flex: 1;
            background: linear-gradient(180deg, #7fe3ec, var(--primary));
            border-radius: 3px;
            animation: sparkA 2.4s ease-in-out infinite
        }

        .spark i:nth-child(2) {
            animation-delay: .2s
        }

        .spark i:nth-child(3) {
            animation-delay: .4s
        }

        .spark i:nth-child(4) {
            animation-delay: .6s
        }

        .spark i:nth-child(5) {
            animation-delay: .8s
        }

        .spark i:nth-child(6) {
            animation-delay: 1s
        }

        .spark i:nth-child(7) {
            animation-delay: 1.2s
        }

        @keyframes sparkA {

            0%,
            100% {
                height: 30%
            }

            50% {
                height: 95%
            }
        }

        /* Floating icons */
        .float-ico {
            position: absolute;
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            font-size: 22px;
            color: #fff;
            background: linear-gradient(135deg, rgba(255, 255, 255, .2), rgba(255, 255, 255, .05));
            border: 1px solid rgba(255, 255, 255, .25);
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .35), 0 0 25px rgba(127, 227, 236, .35)
        }

        .fi1 {
            top: -20px;
            left: -25px;
            animation: floatIco 6s ease-in-out infinite;
            color: #25D366
        }

        .fi2 {
            top: 20%;
            right: -30px;
            animation: floatIco 7s ease-in-out infinite .5s;
            color: #7fe3ec
        }

        .fi3 {
            bottom: 10%;
            left: -35px;
            animation: floatIco 8s ease-in-out infinite 1s;
            color: #c084fc
        }

        .fi4 {
            bottom: -20px;
            right: 20%;
            animation: floatIco 6.5s ease-in-out infinite 1.5s;
            color: #fbbf24
        }

        .fi5 {
            top: 45%;
            left: -45px;
            animation: floatIco 9s ease-in-out infinite 2s;
            color: #f87171
        }

        .fi6 {
            top: -10px;
            right: 15%;
            animation: floatIco 7.5s ease-in-out infinite 2.5s;
            color: #60a5fa
        }

        @keyframes floatIco {

            0%,
            100% {
                transform: translateY(0) rotate(0)
            }

            50% {
                transform: translateY(-14px) rotate(6deg)
            }
        }

        /* Trust */
        .trust {
            margin-top: 32px;
            display: flex;
            flex-wrap: wrap;
            gap: 14px 22px;
            opacity: 0;
            animation: fadeIn 1s 1s forwards
        }

        .trust span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #c8e6ea
        }

        .trust i {
            color: #7fe3ec
        }

        /* RIGHT */
        .right {
            position: relative;
            padding: 56px 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(1000px 800px at 100% 0%, rgba(44, 143, 153, .12), transparent 60%), var(--bg)
        }

        @media (max-width:991px) {
            .right {
                padding: 32px 20px
            }
        }

        .auth-card {
            width: 100%;
            max-width: 460px;
            background: rgba(255, 255, 255, .75);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, .7);
            border-radius: 24px;
            padding: 40px 36px;
            box-shadow: 0 30px 80px -20px rgba(20, 89, 98, .25), 0 0 0 1px rgba(30, 111, 120, .06), inset 0 1px 0 rgba(255, 255, 255, .9);
            opacity: 0;
            transform: translateY(24px);
            animation: cardIn 1s .3s forwards;
            position: relative;
            overflow: hidden
        }

        .auth-card::before {
            content: "";
            position: absolute;
            inset: -1px;
            border-radius: 24px;
            padding: 1px;
            background: linear-gradient(135deg, rgba(44, 143, 153, .5), transparent 40%, transparent 60%, rgba(30, 111, 120, .4));
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            opacity: .6
        }

        @keyframes cardIn {
            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .auth-head h2 {
            font-size: 26px;
            font-weight: 700;
            margin: 0;
            color: #0f2b2f
        }

        .auth-head p {
            margin: 6px 0 0;
            color: #547278;
            font-size: 14px
        }

        .social {
            display: grid;
            gap: 10px;
            margin-top: 24px
        }

        .btn-social {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #0f2b2f;
            transition: all .25s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-social:hover {
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 12px 30px -10px rgba(30, 111, 120, .25), 0 0 25px rgba(44, 143, 153, .18);
            border-color: rgba(44, 143, 153, .35)
        }

        .btn-ms {
            background: linear-gradient(180deg, #fff, #f5f9fa);
            text-decoration: none;
        }

        .btn-ms:hover {
            box-shadow: 0 12px 30px -10px rgba(0, 120, 212, .35), 0 0 25px rgba(0, 120, 212, .2);
            border-color: #0078d4
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #94a3b8;
            font-size: 12px;
            margin: 22px 0 18px;
            text-transform: uppercase;
            letter-spacing: .12em
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, #cbd5e1, transparent)
        }

        /* Floating label inputs */
        .field {
            position: relative;
            margin-bottom: 14px
        }

        .field input {
            width: 100%;
            padding: 18px 16px 10px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            background: #fff;
            color: #0f2b2f;
            transition: all .25s ease;
            outline: none
        }

        .field input:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 4px rgba(44, 143, 153, .15), 0 0 20px rgba(44, 143, 153, .15)
        }

        .field label {
            position: absolute;
            left: 16px;
            top: 14px;
            color: #94a3b8;
            font-size: 14px;
            pointer-events: none;
            transition: all .2s ease;
            background: transparent;
            padding: 0 4px
        }

        .field input:focus+label,
        .field input:not(:placeholder-shown)+label {
            top: -8px;
            font-size: 11px;
            color: var(--primary);
            font-weight: 600;
            background: #fff
        }

        .field .eye {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            cursor: pointer;
            transition: all .2s;
            padding: 6px;
            border-radius: 6px
        }

        .field .eye:hover {
            color: var(--primary);
            transform: translateY(-50%) scale(1.1)
        }

        .strength {
            height: 4px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 6px
        }

        .strength i {
            display: block;
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, #ef4444, #f59e0b, #10b981);
            transition: width .4s ease
        }

        .row-opts {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 6px 0 18px;
            font-size: 13px
        }

        .check {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #334155;
            cursor: pointer;
            user-select: none
        }

        .check input {
            accent-color: var(--primary);
            width: 16px;
            height: 16px
        }

        .link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            position: relative
        }

        .link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 100%;
            height: 1.5px;
            background: var(--primary);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform .3s ease
        }

        .link:hover {
            color: var(--dark)
        }

        .link:hover::after {
            transform: scaleX(1);
            transform-origin: left
        }

        .btn-primary-x {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 30px -10px rgba(30, 111, 120, .5);
            transition: all .3s ease;
            text-decoration: none;
        }

        .btn-primary-x:hover {
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 20px 40px -12px rgba(30, 111, 120, .6), 0 0 30px rgba(44, 143, 153, .4)
        }

        .btn-primary-x:active {
            transform: translateY(0) scale(.99)
        }

        .btn-primary-x .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, .4);
            transform: scale(0);
            animation: ripple .7s linear;
            pointer-events: none
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0
            }
        }

        .btn-outline-x {
            width: 100%;
            margin-top: 10px;
            padding: 13px;
            border: 1.5px solid var(--primary);
            border-radius: 12px;
            background: transparent;
            color: var(--primary);
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: color .35s ease;
            z-index: 1;
            text-decoration: none;
        }

        .btn-outline-x::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            transform: translateY(100%);
            transition: transform .4s ease;
            z-index: -1
        }

        .btn-outline-x:hover {
            color: #fff;
            box-shadow: 0 12px 30px -10px rgba(30, 111, 120, .4), 0 0 25px rgba(44, 143, 153, .3)
        }

        .btn-outline-x:hover::before {
            transform: translateY(0)
        }

        .foot {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #64748b
        }

        /* motion for magnetic subtle */
        .magnetic {
            transition: transform .2s ease
        }
    </style>

    @section('css')
    @show
</head>

<body>
    <!--begin::Theme mode setup on page load-->
    <script>{{ __('var defaultThemeMode = "light";
        var themeMode;

        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }

            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }

            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }') }</script>
    <!--end::Theme mode setup on page load-->
    <div class="bg-stage">
        <div class="mesh">
            <div class="blob b1"></div>
            <div class="blob b2"></div>
            <div class="blob b3"></div>
        </div>
        <div class="network">
            <svg preserveAspectRatio="none" viewBox="0 0 1000 1000">
                <g>
                    <line x1="100" y1="200" x2="400" y2="450" />
                    <line x1="400" y1="450" x2="700" y2="200" />
                    <line x1="700" y1="200" x2="900" y2="500" />
                    <line x1="200" y1="700" x2="500" y2="850" />
                    <line x1="500" y1="850" x2="800" y2="700" />
                    <line x1="300" y1="300" x2="600" y2="700" />
                    <line x1="150" y1="500" x2="450" y2="500" />
                </g>
            </svg>
        </div>
        <div class="particles" id="particles"></div>
        <div class="glass-shape gs1"></div>
        <div class="glass-shape gs2"></div>
        <div class="glass-shape gs3"></div>
    </div>

    <div class="stage">
        <!-- LEFT -->
        <section class="left">
            <div class="brand">
                <div class="brand-mark">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <div class="brand-name">{{ __('GIJAC') }}<span>{{ __('MESSAGE BUSINESS') }}</span>
                </div>
            </div>

            <div>
                <div class="headline">
                    <h1>{{ __('Conecta con tus clientes de forma') }}<em>{{ __('inteligente') }}</em></h1>
                    <p>{{ __('Gestiona campañas, conversaciones, automatizaciones y agentes de IA desde una sola plataforma.') }}</p>
                </div>

                <div class="dash-wrap" id="dashWrap">
                    <div class="dash" id="dash">
                        <div class="dash-top">
                            <span class="dot"></span>
                            <span class="dot"></span>
                            <span class="dot"></span>
                        </div>
                        <div class="dash-grid">
                            <div class="card-mini">
                                <h4>
                                    <i class="fa-brands fa-whatsapp"></i>{{ __('Conversaciones') }}</h4>
                                <div class="chat-row">
                                    <div class="avatar">{{ __('MG') }}</div>
                                    <div>
                                        <div class="who">{{ __('María G.') }}</div>
                                        <div class="msg">{{ __('¿Tienen disponibilidad para hoy?') }}</div>
                                    </div>
                                </div>
                                <div class="chat-row">
                                    <div class="avatar" style="background:linear-gradient(135deg,#c084fc,#7c3aed)">{{ __('AI') }}</div>
                                    <div>
                                        <div class="who">{{ __('Agente IA') }}</div>
                                        <div class="msg">{{ __('Respondiendo automáticamente…') }}</div>
                                    </div>
                                </div>
                                <div class="chat-row">
                                    <div class="avatar" style="background:linear-gradient(135deg,#fbbf24,#f59e0b)">{{ __('JR') }}</div>
                                    <div>
                                        <div class="who">{{ __('Juan R.') }}</div>
                                        <div class="msg">{{ __('Perfecto, muchas gracias 🙌') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div style="display:grid;gap:12px">
                                <div class="card-mini">
                                    <h4>{{ __('Campaña activa') }}</h4>
                                    <div class="metric">
                                        <b id="metric1">{{ __('12,847') }}</b>
                                        <span>+18.2%</span>
                                    </div>
                                    <div class="bar">
                                        <i></i>
                                    </div>
                                </div>
                                <div class="card-mini">
                                    <h4>{{ __('IA · Respuestas') }}</h4>
                                    <div class="spark">
                                        <i></i>
                                        <i></i>
                                        <i></i>
                                        <i></i>
                                        <i></i>
                                        <i></i>
                                        <i></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="float-ico fi1">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div class="float-ico fi2">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <div class="float-ico fi3">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <div class="float-ico fi4">
                        <i class="fa-solid fa-address-book"></i>
                    </div>
                    <div class="float-ico fi5">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div class="float-ico fi6">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                </div>
            </div>

            <div class="trust">
                <span>
                    <i class="fa-solid fa-circle-check"></i>{{ __('API Oficial de WhatsApp') }}</span>
                <span>
                    <i class="fa-solid fa-shield-halved"></i>{{ __('Seguridad Empresarial') }}</span>
                <span>
                    <i class="fa-solid fa-headset"></i>{{ __('Soporte 24/7') }}</span>
                <span>
                    <i class="fa-solid fa-wand-magic-sparkles"></i>{{ __('IA Integrada') }}</span>
            </div>
        </section>

        <!-- RIGHT -->
        <section class="right">
            @yield('content')
        </section>
    </div>

    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/pin-login/jquery.pinlogin.js') }}"></script>

    <script>{{ __('$(function() {
            // particles
            const $p = $(\'#particles\');
            for (let i = 0; i') }< 40; i++) {
                const s = Math.random() * 4 + 2;
                $('<span class="particle"></span>{{ __('\') }}.css({
                    left: (Math.random() * 100) + \'%\',
                    width: s,
                    height: s,
                    animationDuration: (Math.random() * 14 + 10) + \'s\',
                    animationDelay: (Math.random() * 10) + \'s\',
                    opacity: Math.random() * .6 + .2
                }).appendTo($p);
            }

            // Parallax dashboard
            const $dash = $(\'#dash\'),
                $wrap = $(\'#dashWrap\');
            $wrap.on(\'mousemove\', function(e) {
                const r = this.getBoundingClientRect();
                const x = (e.clientX - r.left) / r.width - .5;
                const y = (e.clientY - r.top) / r.height - .5;
                $dash.css(\'transform\', `rotateX(${6 - y*10}deg) rotateY(${-8 + x*14}deg) translateY(0)`);
                $dash.css(\'animation\', \'none\');
            }).on(\'mouseleave\', function() {
                $dash.css(\'animation\', \'\');
            });

            // Magnetic buttons
            $(\'.magnetic\').on(\'mousemove\', function(e) {
                const r = this.getBoundingClientRect();
                const x = e.clientX - r.left - r.width / 2;
                const y = e.clientY - r.top - r.height / 2;
                $(this).css(\'transform\', `translate(${x*.15}px, ${y*.2}px)`);
            }).on(\'mouseleave\', function() {
                $(this).css(\'transform\', \'\');
            });

            // Animate metric counter
            let n = 0;
            const target = 12847;
            const iv = setInterval(() => {
                n += Math.ceil((target - n) / 12);
                $(\'#metric1\').text(n.toLocaleString(\'es-ES\'));
                if (n >= target) {
                    $(\'#metric1\').text(target.toLocaleString(\'es-ES\'));
                    clearInterval(iv);
                }
            }, 50);
        });') }</script>
    @section('js')
    @show

</body>

</html>
