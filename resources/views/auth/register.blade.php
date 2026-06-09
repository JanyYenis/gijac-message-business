@extends('layouts.app')

@section('content')
    <div class="login-card" style="margin-top: 1rem;">
        <h3 class="login-title">Registro</h3>
        <p class="login-subtitle">Accede a tu cuenta de GIJAC MESSAGE BUSINESS</p>

        <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework"
            id="kt_sign_up_form" data-kt-redirect-url="{{ route('register') }}"
            method="POST" action="{{ route('register') }}">
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
                <span style="z-index: 900000000000;">Ingrese con tu correo y clave</span>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="fv-row mb-8 fv-plugins-icon-container">
                        <div class="position-relative">
                            <div class="required position-absolute top-0"></div>
                            <!--begin::Nombre-->
                            <input type="text" placeholder="Nombre" required name="nombre" autocomplete="off"
                                class="form-control bg-transparent @error('nombre') is-invalid @enderror">
                            @error('nombre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <!--end::Nombre-->
                        </div>
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="fv-row mb-8 fv-plugins-icon-container">
                        <div class="position-relative">
                            <div class="required position-absolute top-0"></div>
                            <!--begin::Apellido-->
                            <input type="text" placeholder="Apellido" required name="apellido" autocomplete="off"
                                class="form-control bg-transparent @error('apellido') is-invalid @enderror">
                            @error('apellido')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <!--end::Apellido-->
                        </div>
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        </div>
                    </div>
                </div>
            </div>
            <!--begin::Input group--->

            <div class="fv-row mb-8 fv-plugins-icon-container">
                <div class="position-relative">
                    <div class="required position-absolute top-0"></div>
                    <!--begin::Email-->
                    <input type="text" placeholder="Email" required name="email" autocomplete="off"
                        class="form-control bg-transparent @error('email') is-invalid @enderror">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <!--end::Email-->
                </div>
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                </div>
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

            <!--begin::Accept-->
            <div class="fv-row mb-8 fv-plugins-icon-container">
                <label class="form-check form-check-inline">
                    <span class="form-check-label fw-semibold text-muted fs-base ms-1">
                        Al continuar, Acepto los <a href="{{ route('terminos-condiciones') }}" target="_blank" class="ms-1 link-primary">Terminos y condiciones</a> y las
                        <a href="{{ route('politicas-privacidad') }}" target="_blank" class="ms-1 link-primary">politicas de privacidad</a>
                    </span>
                </label>
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                </div>
            </div>

            <button type="submit" id="kt_sign_up_submit" class="btn btn-primary w-100">
                <!--begin::Indicator label-->
                <span class="indicator-label">
                    Iniciar prueba de 15 días.</span>
                <!--end::Indicator label-->
            </button>

            <a href="{{ route('login') }}" type="button" class="btn btn-trial">
                Iniciar sesión
            </a>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/custom/authentication/sign-up/general.js') }}"></script>
@endsection
