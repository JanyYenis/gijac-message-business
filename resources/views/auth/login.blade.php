@extends('layouts.app')

@section('content')
    <div class="login-card" style="margin-top: 1rem;">
        <h3 class="login-title">Iniciar Sesión</h3>
        <p class="login-subtitle">Accede a tu cuenta de GIJAC MESSAGE BUSINESS</p>

        <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" id="kt_sign_in_form"
            data-kt-redirect-url="{{ route('login') }}" action="{{ route('login') }}" method="POST">
            <a href="{{ route('login-google') }}" type="button" class="btn btn-google">
                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24" style="width: 1.3rem;">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continuar con Google
            </a>

            <div class="divider">
                <span style="z-index: 30;">o continúa con email</span>
            </div>

            <div class="fv-row mb-8 fv-plugins-icon-container">
                <!--begin::Email-->
                <input type="text" name="email" autocomplete="off" placeholder="Correo electrónico"
                    class="form-control bg-transparent">
                <!--end::Email-->
                <div
                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                </div>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="fv-row mb-10 fv-plugins-icon-container" data-kt-password-meter="true">
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack ">
                </div>
                <!--end::Wrapper-->

                <!--begin::Input-->
                <div class="position-relative mb-2">
                    <input class="form-control bg-transparent" type="password" name="password"
                        autocomplete="off" placeholder="Contraseña" required>
                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                        data-kt-password-meter-control="visibility">
                        <i class="far fa-eye fs-4 text-muted">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                        <i class="far fa-eye-slash d-none fs-4 text-muted">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <!--end::Input-->
                <div
                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label" for="remember">
                        Recordarme
                    </label>
                </div>
                <a href="{{ route('password.request') }}" class="forgot-password">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary w-100">
                <!--begin::Indicator label-->
                <span class="indicator-label">
                    Iniciar Sesión</span>
                <!--end::Indicator label-->
            </button>

            <a href="{{ route('register') }}" type="button" class="btn btn-trial">
                Iniciar Prueba Gratis
            </a>
        </form>
    </div>
@endsection

@section('js')
    <!-- Google Identity Services -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="{{ asset('assets/js/custom/authentication/sign-in/general.js') }}"></script>
@endsection
