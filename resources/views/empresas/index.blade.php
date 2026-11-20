@php
    $tienePermiso = $tienePermiso ?? false;
    $disabled = $tienePermiso ? '' : 'disabled';
@endphp
@extends('layouts.index', ['nombre_titulo' => 'Negocios'])

@section('tutorial')
    @component('layouts.componentes.tutorial')
    @endcomponent
@endsection

@section('css')
    <link rel="stylesheet" href="{{ mix('css/empresas.css') }}">
@endsection

@section('content')
    <div class="page-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h1 class="text-white">
                    <i class="bi bi-building text-white fs-2"></i>
                    {{ __('Negocio') }}
                </h1>
                <p class="subtitle mb-0">{{ __('Crea y administra la infomación de tu(s) negocio(s).') }}</p>
            </div>
            <div class="mt-3 mt-md-0"></div>
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
                                    <div class="form-section" id="logoSection">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="fa-regular fa-image fs-1"></i>
                                            </div>
                                            <div>
                                                <h2 class="fs-1 text-gijac">{{ __('Logo de la Empresa') }}</h2>
                                                <p class="fs-4">{{ __('Sube una imagen representativa de tu empresa') }}</p>
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
                                                    {{ __('Arrastra tu imagen aquí o haz clic para seleccionar') }}
                                                </div>
                                                <div class="logo-upload-hint fs-3">
                                                    {{ __('Recomendado: imagen cuadrada de 400 x 400px') }}
                                                </div>
                                                <div class="logo-format-badges">
                                                    <span class="format-badge">
                                                        <i class="fa-regular fa-image"></i>
                                                        {{ __('PNG') }}
                                                    </span>
                                                    <span class="format-badge">
                                                        <i class="fa-regular fa-image"></i>
                                                        {{ __('JPG') }}
                                                    </span>
                                                    <span class="format-badge">
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                        {{ __('Hasta 2 MB') }}
                                                    </span>
                                                </div>
                                                <input type="file" id="logoInput" name="imagen" accept="image/png,image/jpeg"
                                                    style="display: none;">
                                                <button type="button" {{ $disabled }} class="btn btn-outline btn-outline-primary btn-sm fs-4" id="btnUploadLogo">
                                                    <i class="las la-cloud-upload-alt fs-1"></i>
                                                    {{ __('Subir Logo') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ===== Información General ===== -->
                                    <div class="form-section" id="generalInfoSection">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="fa-solid fa-shop"></i>
                                            </div>
                                            <div>
                                                <h2 class="fs-4">{{ __('Información General') }}</h2>
                                                <p class="fs-6">{{ __('Datos básicos de identificación de la empresa') }}</p>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label required">{{ __('Nombre o Razón Social') }}</label>
                                                <div class="input-icon-group">
                                                    <input type="text" class="form-control-gijac" id="companyName"
                                                        placeholder="{{ __('Nombre de la empresa') }}" name="razon_social" required
                                                        value="{{ $negocio?->razon_social ?? '' }}" {{ $disabled }}>
                                                    <div class="input-icon">
                                                        <i class="bi bi-building"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label required">{{ __('NIT') }}</label>
                                                <div class="input-icon-group">
                                                    <input type="text" class="form-control-gijac" id="companyNit"
                                                        placeholder="{{ __('NIT de la empresa') }}" name="nit" required inputmode="numeric"
                                                        value="{{ $negocio?->nit ?? '' }}" {{ $disabled }}>
                                                    <div class="input-icon">
                                                        <i class="bi bi-upc"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ===== Dirección ===== -->
                                    <div class="form-section" id="addressSection">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="bi bi-geo-alt-fill text-primary"></i>
                                            </div>
                                            <div>
                                                <h2 class="fs-4">{{ __('Dirección') }}</h2>
                                                <p class="fs-6">{{ __('Ubicación física de la empresa') }}</p>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label required">{{ __('Dirección') }}</label>
                                                <div class="input-icon-group">
                                                    <input type="text" class="form-control-gijac" id="companyAddress"
                                                        placeholder="{{ __('Dirección completa') }}" required name="direccion"
                                                        value="{{ $negocio?->direccion ?? '' }}" {{ $disabled }}>
                                                    <div class="input-icon">
                                                        <i class="bi bi-geo-alt-fill"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ===== Información de Contacto ===== -->
                                    <div class="form-section" id="contactInfoSection">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="las la-headset fs-4 text-primary"></i>
                                            </div>
                                            <div>
                                                <h2 class="fs-4">{{ __('Información de Contacto') }}</h2>
                                                <p class="fs-6">{{ __('Canales de comunicación de la empresa') }}</p>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label required">{{ __('Correo Corporativo') }}</label>
                                                <div class="input-icon-group">
                                                    <input type="email" class="form-control-gijac" id="companyEmail"
                                                        placeholder="{{ __('contacto@empresa.com') }}" required name="email"
                                                        value="{{ $negocio?->email ?? '' }}" {{ $disabled }}>
                                                    <div class="input-icon">
                                                        <i class="bi bi-envelope-fill"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label required">{{ __('Teléfono') }}</label>
                                                <div class="input-icon-group">
                                                    <input type="tel" class="form-control-gijac" id="companyPhone"
                                                        placeholder="{{ __('Teléfono de contacto') }}" inputmode="numeric" name="telefono"
                                                        required value="{{ $negocio && $negocio?->telefono ? '+'.$negocio?->telefono : '' }}"
                                                        {{ $disabled }}>
                                                    <div class="input-icon">
                                                        <i class="bi bi-telephone-fill"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ===== Redes Sociales ===== -->
                                    <div class="form-section" id="socialMediaSection">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="fa-solid fa-share-nodes"></i>
                                            </div>
                                            <div>
                                                <h2 class="fs-4">{{ __('Redes Sociales') }}</h2>
                                                <p class="fs-6">{{ __('Perfiles sociales de la empresa (opcional)') }}</p>
                                            </div>
                                            <span class="section-optional-tag">{{ __('Opcional') }}</span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">{{ __('Instagram') }}</label>
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
                                                <label class="form-label">{{ __('Facebook') }}</label>
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
                                                <label class="form-label">{{ __('TikTok') }}</label>
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
                                                <label class="form-label">{{ __('LinkedIn') }}</label>
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
                                                <label class="form-label">{{ __('Sitio Web') }}</label>
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
                                    <div class="form-section" id="additionalInfoSection">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="fa-regular fa-file-lines"></i>
                                            </div>
                                            <div>
                                                <h2 class="fs-4">{{ __('Información Adicional') }}</h2>
                                                <p class="fs-6">{{ __('Descripción general de la empresa') }}</p>
                                            </div>
                                            <span class="section-optional-tag">{{ __('Opcional') }}</span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label">{{ __('Descripción de la Empresa') }}</label>
                                                <textarea class="form-control-gijac no-icon" id="companyDescription" name="descripcion"
                                                    placeholder="{{ __('Describe brevemente tu empresa, su propósito y los servicios que ofrece.') }}" maxlength="500"
                                                    rows="4" {{ $disabled }}
                                                    >{{ $negocio?->descripcion ?? '' }}</textarea>
                                                <div class="char-counter" id="charCounter">{{ __('0 / 500 caracteres') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ===== Configuración ===== -->
                                    <div class="form-section" id="configSection">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="fa-solid fa-gear"></i>
                                            </div>
                                            <div>
                                                <h2 class="fs-4">{{ __('Configuración') }}</h2>
                                                <p class="fs-6">{{ __('Preferencias de visibilidad y notificaciones') }}</p>
                                            </div>
                                        </div>

                                        <div class="config-row">
                                            <div class="config-info">
                                                <div class="config-title fs-4">
                                                    <i class="fa-solid fa-power-off"></i>
                                                    {{ __('Estado de la empresa') }}
                                                </div>
                                                <div class="config-desc fs-6">{{ __('Una empresa inactiva no puede enviar campañas ni recibir mensajes.') }}</div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="config-status-pill active" id="statusPill">{{ __('Activa') }}</span>
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
                                                    {{ __('Mostrar información pública') }}
                                                </div>
                                                <div class="config-desc fs-6">{{ __('Permite que los datos de contacto de tu empresa sean visibles en el directorio público.') }}</div>
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
                                                    {{ __('Recibir notificaciones por correo/WhatsApp') }}
                                                </div>
                                                <div class="config-desc fs-6">{{ __('Recibe alertas sobre campañas, contactos nuevos y actividad de la cuenta.') }}</div>
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
                                                {{ __('Cancelar') }}
                                            </button>
                                            <button type="submit" class="btn btn-primary" id="btnSave">
                                                <i class="fa-regular fa-floppy-disk"></i>
                                                {{ __('Guardar') }}
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
