@extends('layouts.index', ['nombre_titulo' => 'Perfil'])

@section('css')
    <link rel="stylesheet" href="{{ mix('css/perfil.css') }}">
@endsection

@section('content')
<!-- Page Content -->
<div class="page-content">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title text-white">Mi Perfil</h1>
            <p class="page-subtitle">Gestiona tu informacion personal y configuracion de seguridad</p>
        </div>
    </div>

    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <!-- Profile Layout -->
    <div class="row g-4">
        <!-- Left Column - Profile Card -->
        <div class="col-xl-4">
            <div class="card profile-card">
                <div class="card-body text-center">
                    <!-- Profile Photo -->
                    <div class="profile-photo-container">
                        <div class="profile-photo">
                            <img src="{{ $usuario?->foto ? asset($usuario?->foto ?? '#') : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&h=200&fit=crop' }}"
                                alt="Foto de perfil" id="profilePhoto">
                            <div class="photo-overlay">
                                <i class="bi bi-camera"></i>
                            </div>
                        </div>
                        <input type="file" id="photoInput" accept="image/*" class="d-none">
                        <button class="btn btn-sm btn-outline btn-outline-primary mt-3" id="changePhotoBtn">
                            <i class="bi bi-camera me-2"></i>
                            Cambiar Foto
                        </button>
                    </div>

                    <!-- User Info Summary -->
                    <div class="profile-info mt-4">
                        <h4 class="profile-name" id="displayName">{{ $usuario?->nombre_completo ?? 'N/A' }}</h4>
                        <p class="profile-role">
                            <span class="badge bg-primary-subtle text-primary">Administrador</span>
                        </p>
                        <p class="profile-email" id="displayEmail">{{ $usuario?->email ?? 'N/A' }}</p>
                    </div>

                    <!-- Quick Stats -->
                    {{-- <div class="profile-stats">
                        <div class="stat-item">
                            <span class="stat-number">0</span>
                            <span class="stat-label">Consultas</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">0</span>
                            <span class="stat-label">Pacientes</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">0</span>
                            <span class="stat-label">Rating</span>
                        </div>
                    </div> --}}
                </div>
            </div>

            <!-- Account Status -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-shield-check me-2"></i>
                        Estado de la Cuenta
                    </h5>
                </div>
                <div class="card-body">
                    <div class="status-item">
                        <div class="status-icon {{ $usuario?->email_verified_at ? 'bg-primary' : 'bg-danger' }}">
                            <i class="bi {{ $usuario?->email_verified_at ? 'bi-check-lg' : 'bi-x-lg' }} text-white"></i>
                        </div>
                        <div class="status-info">
                            <span class="status-label">Email Verificado</span>
                            <span class="status-detail">{{ $usuario?->email_verified_at ? 'Verificado el '.$usuario?->email_verified_at : 'Sin verificación' }}</span>
                        </div>
                    </div>
                    <div class="status-item">
                        <div class="status-icon {{ $usuario?->google2fa_secret ? 'bg-primary' : 'bg-danger' }}" id="twoFactorStatusIcon">
                            <i class="bi {{ $usuario?->google2fa_secret ? 'bi-check-lg' : 'bi-x-lg' }} text-white"></i>
                        </div>
                        <div class="status-info">
                            <span class="status-label">Doble Verificacion (2FA)</span>
                            <span class="status-detail" id="twoFactorStatusText">{{ $usuario?->google2fa_secret ? 'Activo' : 'No activado' }}</span>
                        </div>
                    </div>
                    <div class="status-item">
                        <div class="status-icon {{ $usuario?->estado == 1 ? 'bg-primary' : 'bg-danger' }}">
                            <i class="bi {{ $usuario?->estado == 1 ? 'bi-check-lg' : 'bi-x-lg' }} text-white"></i>
                        </div>
                        <div class="status-info">
                            <span class="status-label">Cuenta {{ $usuario?->estado == 1 ? 'Activa' : 'Inactiva' }}</span>
                            <span class="status-detail">{{ $usuario?->estado == 1 ? 'Desde '.$usuario?->created_at : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Tabs -->
        <div class="col-xl-8">
            <div class="card">
                <!-- Tab Navigation -->
                <div class="card-header border-bottom-0">
                    <ul class="nav nav-tabs card-header-tabs mt-6" id="profileTabs" role="tablist">
                        <li class="nav-item text-primary" role="presentation">
                            <button class="nav-link active" id="personal-tab" data-bs-toggle="tab"
                                    data-bs-target="#personal" type="button" role="tab">
                                <i class="bi bi-person me-2 text-primary"></i>Datos Personales
                            </button>
                        </li>
                        <li class="nav-item text-primary" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab"
                                    data-bs-target="#security" type="button" role="tab">
                                <i class="bi bi-shield-lock me-2 text-primary"></i>Seguridad
                            </button>
                        </li>
                        <li class="nav-item text-primary" role="presentation">
                            <button class="nav-link btnTabFacturas" id="faturas-tab" data-bs-toggle="tab"
                                    data-bs-target="#tabFacturas" type="button" role="tab">
                                <i class="bi bi-phone me-2 text-primary"></i>Facturas
                            </button>
                        </li>
                        <li class="nav-item text-primary" role="presentation">
                            <button class="nav-link" id="btnTabApiKey" data-bs-toggle="tab"
                                    data-bs-target="#tabAPI" type="button" role="tab">
                                <i class="bi bi-phone me-2 text-primary"></i>API Keys
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content" id="profileTabsContent">
                    <!-- Personal Data Tab -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                        @component('perfil.componentes.editar')
                            @slot('usuario', $usuario)
                            @slot('generos', $generos)
                            @slot('tiposDocumentos', $tiposDocumentos)
                            @slot('paises', $paises)
                        @endcomponent
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-pane fade" id="security" role="tabpanel">
                        <div class="seccionMetodoInicio">
                            @component('perfil.componentes.seguridad')
                                @slot('usuario', $usuario)
                            @endcomponent
                        </div>
                    </div>

                    <!-- Tab facturas -->
                    <div class="tab-pane fade" id="tabFacturas" role="tabpanel">
                        @component('perfil.componentes.facturacion.index')
                            @slot('ultimaTransaccion', $ultimaTransaccion)
                            @slot('ultimaFacturaPagada', $ultimaFacturaPagada)
                            @slot('validarFechaVencimiento', $validarFechaVencimiento)
                            @slot('plan', $plan)
                            @slot('cantidad_contactos_activos', $cantidad_contactos_activos)
                        @endcomponent
                    </div>

                    <div class="tab-pane fade" id="tabAPI" role="tabpanel">
                        @component('perfil.componentes.apis.api')
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
    @if (!$usuario->google2fa_secret)
        @component('perfil.modals.factor-dos-pasos')
            @slot('usuario', $usuario)
            @slot('qr', $qr)
            @slot('secret', $secret)
        @endcomponent
    @endif
    @component('perfil.componentes.apis.crear')
    @endcomponent
@endsection

@section('scripts')
    <script src="{{ mix('/js/perfil/principal.js') }}"></script>
    <script src="{{ mix('/js/facturas/principal.js') }}"></script>
    <script src="{{ asset('assets/js/custom/modals/two-factor-authentication.js') }}"></script>
    <script src="{{ asset('assets/js/custom/account/settings/signin-methods.js') }}"></script>
@endsection
