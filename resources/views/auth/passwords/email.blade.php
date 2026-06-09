@extends('layouts.app')

@section('content')
    <div class="login-card" style="margin-top: 1rem;">
        <h3 class="login-title">Recuperar contraseña</h3>
        <div class="divider">
            <span></span>
        </div>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" id="kt_password_reset_form"
            data-kt-redirect-url="{{ route('password.email') }}" action="{{ route('password.email') }}" method="POST">
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

            <button type="submit" id="kt_password_reset_submit" class="btn btn-primary w-100">
                <!--begin::Indicator label-->
                <span class="indicator-label">
                    Enviar correo</span>
                <!--end::Indicator label-->
            </button>

            <a href="{{ route('login') }}" type="button" class="btn btn-trial">
                Iniciar Sesión
            </a>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/custom/authentication/reset-password/reset-password.js') }}"></script>
@endsection
