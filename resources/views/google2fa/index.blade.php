@extends('layouts.app')

@section('css')
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/pin-login/jquery.pinlogin.css')}}">
@endsection

@section('content')
{{-- <div id="tarjeta" class="w-lg-500px rounded shadow-sm p-3 p-lg-5 mx-auto">
    <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('2fa') }}" method="POST"
        id="formGoogle2FA" data-kt-redirect-url='{{ route('home') }}'>
        @csrf
        <div class="text-center mb-10">
            <h1 class="text-gijac mb-3">
                Verificación en dos pasos
            </h1>
            <div class="text-dark fw-semibold fs-4">
                Ingrese la <strong>OTP</strong> generada en su aplicación de autenticación. <br>
                Asegúrese de enviar el código actual porque es se actualiza cada 30 segundos.
            </div>
        </div>

        @if($errors->any())
            <div class="col-md-12">
                <div class="alert alert-danger">
                    <strong>{{$errors->first()}}</strong>
                </div>
            </div>
        @endif

        <div class="fv-row mb-10 fv-plugins-icon-container">
            <label class="form-label fw-bold text-gijac fs-6">Código</label>
            <div id="pinwrapper" class="text-center"></div>
            <input class="form-control form-control-lg form-control-solid" type="hidden" placeholder="Ingrese el código" id="campoCodigo" name="one_time_password">
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
            </div>
        </div>

        <div class="d-flex flex-wrap justify-content-center pb-lg-0">
            <button type="submit" id="btnGoogle2FA" class="btn btn-lg btn-primary fw-bold me-4">
                <span class="indicator-label">
                    Enviar
                </span>
                <span class="indicator-progress">
                    Enviando...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
    </form>
</div> --}}

    <div class="login-card" style="margin-top: 1rem;">
        <h3 class="login-title">Verificación en dos pasos (2FA)</h3>
        <p class="login-subtitle">
            Ingrese la <strong>OTP</strong> generada en su aplicación de autenticación. <br>
            Asegúrese de enviar el código actual porque es se actualiza cada 30 segundos.
        </p>

        <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('2fa') }}" method="POST"
            id="formGoogle2FA" data-kt-redirect-url='{{ route('home') }}'>
            @csrf
            <div class="divider">
                <span></span>
            </div>

            @if($errors->any())
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <strong>{{$errors->first()}}</strong>
                    </div>
                </div>
            @endif

            <div class="fv-row mb-10 fv-plugins-icon-container">
                <label class="form-label fw-bold fs-6">Escriba su código de seguridad de 6 dígitos</label>
                <div id="pinwrapper" class="text-center"></div>
                <input class="form-control form-control-lg form-control-solid" type="hidden" placeholder="Ingrese el código" id="campoCodigo" name="one_time_password">
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                </div>
            </div>
            <button type="submit" id="btnGoogle2FA" class="btn btn-primary w-100" style="margin-top: 2rem;">
                <!--begin::Indicator label-->
                <span class="indicator-label">
                    Verificar
                </span>
                <!--end::Indicator label-->
            </button>
        </form>
    </div>
@endsection


@section('js')
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/pin-login/jquery.pinlogin.js') }}"></script>
    <script src="{{ asset('js/google2fa/principal.js') }}"></script>
@endsection
