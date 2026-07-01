@php
    $tienePermiso = $tienePermiso ?? false;
    $disabled = $tienePermiso ? '' : 'disabled';
@endphp
@extends('layouts.index', ['nombre_titulo' => 'Negocios'])

@section('css')
    <style>
        :root {
            --primary: #0abb87;
            --primary-dark: #089968;
            --primary-soft: rgba(10, 187, 135, 0.12);
            --primary-glow: rgba(10, 187, 135, 0.35);
            --primary-light: rgb(164, 235, 150);

            --danger: #fd397a;
            --danger-soft: rgba(253, 57, 122, 0.12);
            --danger-dark: #F8285A;

            --warning: #ffb822;
            --warning-soft: rgba(255, 184, 34, 0.12);

            --info: #5578ff;
            --info-soft: rgba(85, 120, 255, 0.12);

            --instagram: #E1306C;
            --facebook: #1877F2;
            --tiktok: #000000;
            --linkedin: #0A66C2;
            --website: #0abb87;

            --dark-surface: #282a3c;
            --dark-surface-2: #1f2132;

            --bg-page: #f5f6fa;
            --bg-card: #ffffff;
            --bg-input: #ffffff;
            --bg-hover: #f5f6fa;

            --gris-borde: #e4e6ef;
            --gris-borde-strong: #c7ccd8;

            --text-primary: #282a3c;
            --text-secondary: #6b7280;
            --text-muted: #9aa0b0;
            --text-input: #495057;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', system-ui, sans-serif;
            background: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -0.01em;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }

        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        /* ===== Topbar ===== */
        .topbar {
            background: var(--bg-card);
            border-bottom: 1px solid var(--gris-borde);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.03);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            display: grid;
            place-items: center;
            color: white;
            box-shadow: 0 6px 16px var(--primary-glow);
        }

        .brand-mark .material-symbols-outlined {
            color: white;
            font-size: 22px;
        }

        .brand-name {
            font-weight: 700;
            font-size: 1.05rem;
            line-height: 1;
        }

        .brand-tagline {
            font-size: 0.68rem;
            color: var(--text-secondary);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-top: 3px;
            font-weight: 500;
        }

        .topbar-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid var(--gris-borde);
            background: var(--bg-card);
            color: var(--text-secondary);
            display: grid;
            place-items: center;
            transition: all 0.2s ease;
            position: relative;
        }

        .topbar-icon-btn:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
            border-color: var(--gris-borde-strong);
        }

        .topbar-icon-btn .material-symbols-outlined {
            font-size: 20px;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 12px 5px 5px;
            background: var(--bg-page);
            border: 1px solid var(--gris-borde);
            border-radius: 100px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .user-chip:hover {
            background: #eceef5;
            border-color: var(--gris-borde-strong);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 0.8rem;
            color: white;
            border: 2px solid var(--bg-card);
        }

        .user-info-text .user-name {
            font-size: 0.82rem;
            font-weight: 600;
            line-height: 1.1;
        }

        .user-info-text .user-role {
            font-size: 0.68rem;
            color: var(--text-secondary);
        }

        /* ===== Layout ===== */
        .gijac-container {
            max-width: 920px;
            margin: 0 auto;
            padding: 28px 24px 60px;
        }

        /* ===== Page Header ===== */
        .page-header-section {
            margin-bottom: 24px;
        }

        .breadcrumb-gijac {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .breadcrumb-gijac a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .breadcrumb-gijac a:hover {
            color: var(--primary);
        }

        .page-header-title {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .page-header-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: grid;
            place-items: center;
            color: white;
            box-shadow: 0 8px 20px var(--primary-glow);
            flex-shrink: 0;
        }

        .page-header-icon .material-symbols-outlined {
            font-size: 28px;
        }

        .page-header-title h1 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.02em;
        }

        .page-header-title p {
            margin: 4px 0 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* ===== Main Card ===== */
        .main-card {
            background: var(--bg-card);
            border: 1px solid var(--gris-borde);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        }

        /* ===== Form Sections ===== */
        .form-section {
            padding: 28px 32px;
            border-bottom: 1px solid var(--gris-borde);
        }

        .form-section:last-of-type {
            border-bottom: none;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .section-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--primary-soft);
            color: var(--primary);
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .section-icon .material-symbols-outlined {
            font-size: 20px;
        }

        .section-header h2 {
            font-size: 1.05rem;
            font-weight: 600;
            margin: 0;
        }

        .section-header p {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin: 2px 0 0;
        }

        .section-optional-tag {
            margin-left: auto;
            padding: 3px 10px;
            background: var(--bg-page);
            border: 1px solid var(--gris-borde);
            border-radius: 100px;
            font-size: 0.68rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ===== Logo Upload ===== */
        .logo-upload-area {
            display: flex;
            gap: 24px;
            align-items: center;
            flex-wrap: wrap;
        }

        .logo-preview-container {
            width: 120px;
            height: 120px;
            border-radius: 18px;
            border: 2px dashed var(--gris-borde-strong);
            background: var(--bg-page);
            display: grid;
            place-items: center;
            position: relative;
            overflow: hidden;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }

        .logo-preview-container.has-image {
            border-style: solid;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-soft);
        }

        .logo-preview-container.dragover {
            border-color: var(--primary);
            background: var(--primary-soft);
            transform: scale(1.03);
        }

        .logo-placeholder-icon {
            color: var(--text-muted);
            font-size: 40px;
        }

        .logo-preview-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .logo-preview-img.show {
            display: block;
        }

        .logo-remove-btn {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--danger);
            color: white;
            border: 2px solid var(--bg-card);
            display: none;
            place-items: center;
            cursor: pointer;
            transition: transform 0.2s ease;
            z-index: 2;
        }

        .logo-remove-btn.show {
            display: grid;
        }

        .logo-remove-btn:hover {
            transform: scale(1.1);
        }

        .logo-remove-btn .material-symbols-outlined {
            font-size: 14px;
        }

        .logo-actions {
            flex: 1;
            min-width: 240px;
        }

        .logo-upload-text {
            font-weight: 600;
            font-size: 0.92rem;
            margin-bottom: 4px;
        }

        .logo-upload-hint {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .logo-format-badges {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .format-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            background: var(--bg-page);
            border: 1px solid var(--gris-borde);
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .format-badge .material-symbols-outlined {
            font-size: 12px;
        }

        /* ===== Form Controls ===== */
        .form-label-gijac {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 7px;
            display: block;
        }

        .input-icon-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
            transition: color 0.2s ease;
            display: flex;
            align-items: center;
        }

        .input-icon .material-symbols-outlined {
            font-size: 19px;
        }

        .input-icon svg {
            width: 18px;
            height: 18px;
        }

        .form-control-gijac {
            width: 100%;
            padding: 11px 14px 11px 42px;
            font-size: 0.9rem;
            color: var(--text-input);
            background: var(--bg-input);
            border: 1px solid var(--gris-borde);
            border-radius: 9px;
            transition: all 0.2s ease;
            font-family: 'Poppins', sans-serif;
        }

        .form-control-gijac.no-icon {
            padding-left: 14px;
        }

        .form-control-gijac:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-soft);
        }

        .form-control-gijac:focus+.input-icon,
        .input-icon-group:focus-within .input-icon {
            color: var(--primary);
        }

        .form-control-gijac.is-invalid {
            border-color: var(--danger);
            background: var(--danger-soft);
        }

        .form-control-gijac.is-valid {
            border-color: var(--primary);
        }

        .form-control-gijac::placeholder {
            color: var(--text-muted);
        }

        textarea.form-control-gijac {
            resize: vertical;
            min-height: 110px;
            padding-top: 12px;
        }

        .char-counter {
            text-align: right;
            font-size: 0.74rem;
            color: var(--text-muted);
            margin-top: 5px;
        }

        .char-counter.warning {
            color: var(--warning);
        }

        .char-counter.danger {
            color: var(--danger);
        }

        .input-error-msg {
            font-size: 0.74rem;
            color: var(--danger);
            margin-top: 5px;
            display: none;
            align-items: center;
            gap: 4px;
        }

        .input-error-msg.show {
            display: flex;
        }

        .input-error-msg .material-symbols-outlined {
            font-size: 14px;
        }

        /* ===== Social Inputs ===== */
        .social-input-wrapper {
            position: relative;
        }

        .social-input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .social-input-icon svg {
            width: 18px;
            height: 18px;
        }

        /* ===== Toggle Switch ===== */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 26px;
            flex-shrink: 0;
        }

        .toggle-switch input {
            display: none;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background: var(--gris-borde-strong);
            border-radius: 100px;
            transition: 0.3s;
        }

        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            left: 3px;
            top: 3px;
            background: white;
            border-radius: 50%;
            transition: 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .toggle-switch input:checked+.toggle-slider {
            background: var(--primary);
        }

        .toggle-switch input:checked+.toggle-slider::before {
            transform: translateX(22px);
        }

        /* ===== Config Row ===== */
        .config-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 0;
            border-bottom: 1px solid var(--gris-borde);
        }

        .config-row:last-child {
            border-bottom: none;
        }

        .config-info {
            flex: 1;
        }

        .config-info .config-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .config-info .config-title .material-symbols-outlined {
            font-size: 18px;
            color: var(--text-secondary);
        }

        .config-info .config-desc {
            font-size: 0.78rem;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        .config-status-pill {
            font-size: 0.74rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 100px;
            margin-right: 12px;
            transition: all 0.2s ease;
        }

        .config-status-pill.active {
            background: var(--primary-soft);
            color: var(--primary-dark);
        }

        .config-status-pill.inactive {
            background: var(--bg-page);
            color: var(--text-muted);
        }

        /* ===== Buttons ===== */
        .btn-gijac {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 11px 22px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.2s ease;
            font-family: 'Poppins', sans-serif;
            border: 1px solid transparent;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-gijac .material-symbols-outlined {
            font-size: 18px;
        }

        .btn-gijac-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 12px var(--primary-glow);
        }

        .btn-gijac-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px var(--primary-glow);
            color: white;
        }

        .btn-gijac-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-gijac-outline {
            background: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--gris-borde-strong);
        }

        .btn-gijac-outline:hover {
            background: var(--bg-hover);
            border-color: var(--text-secondary);
            color: var(--text-primary);
        }

        .btn-gijac-sm {
            padding: 8px 16px;
            font-size: 0.82rem;
            border-radius: 8px;
        }

        .btn-gijac-sm .material-symbols-outlined {
            font-size: 17px;
        }

        /* ===== Form Footer ===== */
        .form-footer {
            padding: 20px 32px;
            background: var(--bg-page);
            border-top: 1px solid var(--gris-borde);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* ===== Toast ===== */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 2000;
            pointer-events: none;
        }

        .gijac-toast {
            background: var(--bg-card);
            border: 1px solid var(--gris-borde);
            border-left: 4px solid var(--primary);
            border-radius: 11px;
            padding: 12px 16px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 280px;
            max-width: 380px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
            transform: translateX(120%);
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: auto;
        }

        .gijac-toast.show {
            transform: translateX(0);
        }

        .gijac-toast.success {
            border-left-color: var(--primary);
        }

        .gijac-toast.error {
            border-left-color: var(--danger);
        }

        .gijac-toast.info {
            border-left-color: var(--info);
        }

        .gijac-toast .toast-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .gijac-toast.success .toast-icon {
            background: var(--primary-soft);
            color: var(--primary);
        }

        .gijac-toast.error .toast-icon {
            background: var(--danger-soft);
            color: var(--danger);
        }

        .gijac-toast.info .toast-icon {
            background: var(--info-soft);
            color: var(--info);
        }

        .gijac-toast .toast-icon .material-symbols-outlined {
            font-size: 18px;
        }

        .gijac-toast .toast-title {
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1px;
        }

        .gijac-toast .toast-msg {
            font-size: 0.76rem;
            color: var(--text-secondary);
        }

        /* ===== Spinner ===== */
        .gijac-spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-page);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gris-borde-strong);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }

        ::selection {
            background: var(--primary-soft);
            color: var(--primary-dark);
        }

        /* Fade in animation */
        .fade-in {
            animation: fadeInUp 0.4s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .gijac-container {
                padding: 18px 14px 40px;
            }

            .form-section {
                padding: 22px 20px;
            }

            .form-footer {
                padding: 16px 20px;
                flex-direction: column-reverse;
            }

            .form-footer .btn-gijac {
                width: 100%;
                justify-content: center;
            }

            .page-header-title h1 {
                font-size: 1.3rem;
            }

            .logo-upload-area {
                flex-direction: column;
                align-items: flex-start;
            }

            .logo-preview-container {
                width: 100px;
                height: 100px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h1 class="text-white">
                    <i class="bi bi-building text-white fs-2"></i>
                    Negocio
                </h1>
                <p class="subtitle mb-0">Crea y administra la infomación de tu(s) negocio(s).</p>
            </div>
            <div class="mt-3 mt-md-0">
                {{-- <button type="button" id="tutorialBtnCrear" class="btn btn-new-template" data-bs-toggle="modal" data-bs-target="#modalCrearContactos">
                    <i class="fas fa-user-plus text-primary"></i>
                    Crear Contacto
                </button> --}}
            </div>
        </div>
    </div>

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="contenedor" id="kt_content_container">
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 mb-10 mb-lg-0">
                    <div class="card card-flush">
                        <div class="card-body pt-5" id="kt_chat_contacts_body">
                            <form id="formEmpresa" enctype="multipart/form-data">
                                @if ($negocio)
                                    <input type="hidden" name="id" value="{{ $negocio?->id }}">
                                @endif
                                <!-- Main Card -->
                                <div class="main-card fade-in">
                                    <!-- ===== Logo Section ===== -->
                                    <div class="form-section">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="fa-regular fa-image fs-1"></i>
                                            </div>
                                            <div>
                                                <h2 class="fs-1 text-gijac">Logo de la Empresa</h2>
                                                <p class="fs-4">Sube una imagen representativa de tu empresa</p>
                                            </div>
                                        </div>

                                        <div class="logo-upload-area">
                                            <div class="logo-preview-container" id="logoPreviewContainer">
                                                @if ($negocio)
                                                    <img src="{{ asset($negocio?->foto) }}" alt="" width="100%" id="logoPlaceholder">
                                                @else
                                                    <i class="fa-regular fa-image fs-5x logo-placeholder-icon" id="logoPlaceholder"></i>
                                                @endif
                                                <img class="logo-preview-img" id="logoPreview" alt="Logo preview">
                                                <div class="logo-remove-btn" id="logoRemoveBtn">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </div>
                                            </div>
                                            <div class="logo-actions">
                                                <div class="logo-upload-text fs-3">
                                                    Arrastra tu imagen aquí o haz clic para seleccionar
                                                </div>
                                                <div class="logo-upload-hint fs-3">
                                                    Recomendado: imagen cuadrada de 400 x 400px
                                                </div>
                                                <div class="logo-format-badges">
                                                    <span class="format-badge">
                                                        <i class="fa-regular fa-image"></i>
                                                        PNG
                                                    </span>
                                                    <span class="format-badge">
                                                        <i class="fa-regular fa-image"></i>
                                                        JPG
                                                    </span>
                                                    <span class="format-badge">
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                        Hasta 2 MB
                                                    </span>
                                                </div>
                                                <input type="file" id="logoInput" name="imagen" accept="image/png,image/jpeg"
                                                    style="display: none;">
                                                <button type="button" {{ $disabled }} class="btn btn-outline btn-outline-primary btn-sm fs-4" id="btnUploadLogo">
                                                    <i class="las la-cloud-upload-alt fs-1"></i>
                                                    Subir Logo
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ===== Información General ===== -->
                                    <div class="form-section">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="fa-solid fa-shop"></i>
                                            </div>
                                            <div>
                                                <h2 class="fs-4">Información General</h2>
                                                <p class="fs-6">Datos básicos de identificación de la empresa</p>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label required">Nombre o Razón Social</label>
                                                <div class="input-icon-group">
                                                    <input type="text" class="form-control-gijac" id="companyName"
                                                        placeholder="Nombre de la empresa" name="razon_social" required
                                                        value="{{ $negocio?->razon_social ?? '' }}" {{ $disabled }}>
                                                    <div class="input-icon">
                                                        <i class="bi bi-building"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label required">NIT</label>
                                                <div class="input-icon-group">
                                                    <input type="text" class="form-control-gijac" id="companyNit"
                                                        placeholder="NIT de la empresa" name="nit" required inputmode="numeric"
                                                        value="{{ $negocio?->nit ?? '' }}" {{ $disabled }}>
                                                    <div class="input-icon">
                                                        <i class="bi bi-upc"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ===== Dirección ===== -->
                                    <div class="form-section">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="bi bi-geo-alt-fill text-primary"></i>
                                            </div>
                                            <div>
                                                <h2 class="fs-4">Dirección</h2>
                                                <p class="fs-6">Ubicación física de la empresa</p>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label required">Dirección</label>
                                                <div class="input-icon-group">
                                                    <input type="text" class="form-control-gijac" id="companyAddress"
                                                        placeholder="Dirección completa" required name="direccion"
                                                        value="{{ $negocio?->direccion ?? '' }}" {{ $disabled }}>
                                                    <div class="input-icon">
                                                        <i class="bi bi-geo-alt-fill"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ===== Información de Contacto ===== -->
                                    <div class="form-section">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="las la-headset fs-4 text-primary"></i>
                                            </div>
                                            <div>
                                                <h2 class="fs-4">Información de Contacto</h2>
                                                <p class="fs-6">Canales de comunicación de la empresa</p>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label required">Correo Corporativo</label>
                                                <div class="input-icon-group">
                                                    <input type="email" class="form-control-gijac" id="companyEmail"
                                                        placeholder="contacto@empresa.com" required name="email"
                                                        value="{{ $negocio?->email ?? '' }}" {{ $disabled }}>
                                                    <div class="input-icon">
                                                        <i class="bi bi-envelope-fill"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label required">Teléfono</label>
                                                <div class="input-icon-group">
                                                    <input type="tel" class="form-control-gijac" id="companyPhone"
                                                        placeholder="Teléfono de contacto" inputmode="numeric" name="telefono"
                                                        required value="{{ $negocio?->telefono ? '+'.$negocio?->telefono : '' }}"
                                                        {{ $disabled }}>
                                                    <div class="input-icon">
                                                        <i class="bi bi-telephone-fill"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ===== Redes Sociales ===== -->
                                    <div class="form-section">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="fa-solid fa-share-nodes"></i>
                                            </div>
                                            <div>
                                                <h2 class="fs-4">Redes Sociales</h2>
                                                <p class="fs-6">Perfiles sociales de la empresa (opcional)</p>
                                            </div>
                                            <span class="section-optional-tag">Opcional</span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Instagram</label>
                                                <div class="input-icon-group social-input-wrapper">
                                                    <input type="url" class="form-control-gijac" id="socialInstagram"
                                                        placeholder="https://instagram.com/empresa" name="instagram"
                                                        value="{{ $negocio?->instagram ?? '' }}" {{ $disabled }}>
                                                    <div class="social-input-icon">
                                                        <svg viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <rect x="2" y="2" width="20" height="20" rx="5"
                                                                stroke="#E1306C" stroke-width="2" />
                                                            <circle cx="12" cy="12" r="4" stroke="#E1306C"
                                                                stroke-width="2" />
                                                            <circle cx="17.5" cy="6.5" r="1.5" fill="#E1306C" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Facebook</label>
                                                <div class="input-icon-group social-input-wrapper">
                                                    <input type="url" class="form-control-gijac" id="socialFacebook"
                                                        placeholder="https://facebook.com/empresa" name="facebook"
                                                        value="{{ $negocio?->facebook ?? '' }}" {{ $disabled }}>
                                                    <div class="social-input-icon">
                                                        <svg viewBox="0 0 24 24" fill="#1877F2"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M24 12C24 5.373 18.627 0 12 0S0 5.373 0 12c0 5.99 4.388 10.954 10.125 11.854V15.469H7.078V12h3.047V9.356c0-3.007 1.792-4.668 4.533-4.668 1.312 0 2.686.234 2.686.234v2.953H15.83c-1.491 0-1.956.925-1.956 1.874V12h3.328l-.532 3.469h-2.796v8.385C19.612 22.954 24 17.99 24 12z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">TikTok</label>
                                                <div class="input-icon-group social-input-wrapper">
                                                    <input type="url" class="form-control-gijac" id="socialTiktok"
                                                        placeholder="https://tiktok.com/@empresa" name="tiktok"
                                                        value="{{ $negocio?->tiktok ?? '' }}" {{ $disabled }}>
                                                    <div class="social-input-icon">
                                                        <svg viewBox="0 0 24 24" fill="#000000"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.04-.1z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">LinkedIn</label>
                                                <div class="input-icon-group social-input-wrapper">
                                                    <input type="url" class="form-control-gijac" id="socialLinkedin"
                                                        placeholder="https://linkedin.com/company/empresa" name="linkendin"
                                                        value="{{ $negocio?->linkendin ?? '' }}" {{ $disabled }}>
                                                    <div class="social-input-icon">
                                                        <svg viewBox="0 0 24 24" fill="#0A66C2"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Sitio Web</label>
                                                <div class="input-icon-group social-input-wrapper">
                                                    <input type="url" class="form-control-gijac" id="socialWebsite"
                                                        placeholder="https://empresa.com" name="web"
                                                        value="{{ $negocio?->web ?? '' }}" {{ $disabled }}>
                                                    <div class="social-input-icon">
                                                        <svg viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <circle cx="12" cy="12" r="10" stroke="#0abb87"
                                                                stroke-width="2" />
                                                            <path
                                                                d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"
                                                                stroke="#0abb87" stroke-width="2" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ===== Información Adicional ===== -->
                                    <div class="form-section">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="fa-regular fa-file-lines"></i>
                                            </div>
                                            <div>
                                                <h2 class="fs-4">Información Adicional</h2>
                                                <p class="fs-6">Descripción general de la empresa</p>
                                            </div>
                                            <span class="section-optional-tag">Opcional</span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label">Descripción de la Empresa</label>
                                                <textarea class="form-control-gijac no-icon" id="companyDescription" name="descripcion"
                                                    placeholder="Describe brevemente tu empresa, su propósito y los servicios que ofrece." maxlength="500"
                                                    rows="4" {{ $disabled }}
                                                    >{{ $negocio?->descripcion ?? '' }}</textarea>
                                                <div class="char-counter" id="charCounter">0 / 500 caracteres</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ===== Configuración ===== -->
                                    <div class="form-section">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="fa-solid fa-gear"></i>
                                            </div>
                                            <div>
                                                <h2 class="fs-4">Configuración</h2>
                                                <p class="fs-6">Preferencias de visibilidad y notificaciones</p>
                                            </div>
                                        </div>

                                        <div class="config-row">
                                            <div class="config-info">
                                                <div class="config-title fs-4">
                                                    <i class="fa-solid fa-power-off"></i>
                                                    Estado de la empresa
                                                </div>
                                                <div class="config-desc fs-6">Una empresa inactiva no puede enviar campañas ni
                                                    recibir mensajes.</div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="config-status-pill active" id="statusPill">Activa</span>
                                                <label class="toggle-switch">
                                                    <input type="checkbox" {{ !$negocio || $negocio?->estado == 1 ? 'checked' : '' }}
                                                        id="toggleStatus" name="estado" {{ $disabled }}/>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="config-row">
                                            <div class="config-info">
                                                <div class="config-title fs-4">
                                                    <i class="fa-solid fa-globe"></i>
                                                    Mostrar información pública
                                                </div>
                                                <div class="config-desc fs-6">Permite que los datos de contacto de tu empresa sean
                                                    visibles en el directorio público.</div>
                                            </div>
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="togglePublic"
                                                    {{ !$negocio || $negocio?->publicar == 1 ? 'checked' : '' }}
                                                    name="publicar" {{ $disabled }}/>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>

                                        <div class="config-row">
                                            <div class="config-info">
                                                <div class="config-title fs-4">
                                                    <i class="fa-regular fa-bell"></i>
                                                    Recibir notificaciones por correo/WhatsApp
                                                </div>
                                                <div class="config-desc fs-6">Recibe alertas sobre campañas, contactos nuevos y
                                                    actividad de la cuenta.</div>
                                            </div>
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="toggleNotifications" {{ !$negocio || $negocio?->notificacion == 1 ? 'checked' : '' }} name="notificacion">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- ===== Footer Buttons ===== -->
                                    @if ($tienePermiso)
                                        <div class="form-footer">
                                            <button class="btn btn-outline btn-outline-secondary" type="reset">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-primary" id="btnSave">
                                                <i class="fa-regular fa-floppy-disk"></i>
                                                Guardar
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
@endsection

@section('scripts')
    <script src="{{ mix('js/empresas/principal.js') }}"></script>
@endsection
