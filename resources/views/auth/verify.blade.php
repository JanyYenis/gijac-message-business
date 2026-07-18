@extends('layouts.app')

@section('content')
    <div class="auth-card">
        <div class="auth-head">
            <h2 >Verifique su dirección de correo electrónico</h2>
        </div>
        <div class="divider">
            <span></span>
        </div>
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico.
            </div>
        @endif
        <div class="">
            <p>Antes de continuar, revise su correo electrónico para ver si recibió un enlace de verificación. Si no lo recibió.</p>
        </div>
        <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('verification.resend') }}" method="POST">
            @csrf
            <button type="submit" class="btn-primary-x magnetic">
                <!--begin::Indicator label-->
                <span class="indicator-label">
                    Haga clic aquí para solicitar el correo de verificación
                </span>
                <!--end::Indicator label-->
            </button>
        </form>
    </div>
@endsection
