@extends('layouts.app')

@section('content')
    <div class="auth-card">
        <div class="auth-head">
            <h2>{{ __('Recuperar contraseña') }}</h2>
        </div>

        <div class="divider">{{ __('Email') }}</div>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" id="kt_password_reset_form"
            data-kt-redirect-url="{{ route('password.email') }}" action="{{ route('password.email') }}" method="POST">
            <div class="field">
                <input id="email" type="email" name="email" placeholder=" " required
                    autocomplete="off" class="@error('email') is-invalid @enderror"/>
                <label for="email">{{ __('Email') }}</label>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <button type="submit" id="kt_password_reset_submit" class="btn-primary-x magnetic">
                <!--begin::Indicator label-->
                <span class="indicator-label">{{ __('Enviar correo') }}</span>
                <!--end::Indicator label-->
            </button>

            <a class="btn-outline-x magnetic text-center" type="button" href="{{ route('login') }}">
                <i class="fa-solid fa-right-to-bracket me-1"></i>{{ __('Iniciar sesión') }}</a>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/custom/authentication/reset-password/reset-password.js') }}"></script>
@endsection
