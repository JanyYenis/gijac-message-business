@extends('layouts.app')

@section('content')
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<div class="login-card" style="margin-top: 1rem;">
        <h3 class="login-title">Recuperar contraseña</h3>
        <div class="divider">
            <span></span>
        </div>
        <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" id="kt_new_password_form"
            data-kt-redirect-url="{{ route('home') }}" action="{{ route('password.update') }}" method="POST">
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="fv-row mb-8 fv-plugins-icon-container">
                <!--begin::Email-->
                <input type="text" name="email" value="{{ $email ?? old('email') }}" autocomplete="off" placeholder="Correo electrónico"
                    class="form-control bg-transparent @error('email') is-invalid @enderror">
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

            <!--begin::Input group-->
            <div class="fv-row mb-8 fv-plugins-icon-container" data-kt-password-meter="true">
                <!--begin::Wrapper-->
                <div class="mb-1">
                    <!--begin::Input wrapper-->
                    <div class="position-relative mb-3">
                        <input class="form-control bg-transparent @error('password') is-invalid @enderror" type="password" placeholder="Password"
                            name="password" required autocomplete="off">

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
                    <!--end::Input wrapper-->

                    <!--begin::Meter-->
                    <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                    </div>
                    <!--end::Meter-->
                </div>
                <!--end::Wrapper-->
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <!--begin::Hint-->
                <div class="text-muted">
                    Utilice 8 o más caracteres con una combinación de letras, números y símbolos.
                </div>
                <!--end::Hint-->
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                </div>
            </div>
            <!--end::Input group--->

            <!--end::Input group--->
            <div class="fv-row mb-8 fv-plugins-icon-container">
                <!--begin::Repeat Password-->
                <input type="text" placeholder="Confirmar Password" required name="password_confirmation" autocomplete="off"
                    class="form-control bg-transparent">
                <!--end::Repeat Password-->
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                </div>
            </div>
            <!--end::Input group--->

            <button type="submit" id="kt_new_password_submit" class="btn btn-primary w-100">
                <!--begin::Indicator label-->
                <span class="indicator-label">
                    Restablecer contraseña</span>
                <!--end::Indicator label-->
            </button>

            <a href="{{ route('login') }}" type="button" class="btn btn-trial">
                Iniciar Sesión
            </a>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/custom/authentication/reset-password/new-password.js') }}"></script>
@endsection
