@php
    $usuario = auth()->user();
    $cantidad = $usuario->unreadNotifications->count() ?? 0;
@endphp
<div id="kt_app_toolbar" class="app-toolbar  py-4 py-lg-6 mb-8 mb-lg-10 " data-kt-sticky="true"
    data-kt-sticky-name="app-toolbar-sticky" data-kt-sticky-offset="{default: 'false', lg: '300px'}">

    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container"
        class="app-container  container-xxl d-flex flex-stack flex-wrap flex-lg-nowrap gap-4 ">
        <!--begin::Toolbar wrapper-->
        <div class="d-flex align-items-center">
            <!--begin::Logo-->
            <img src="{{ asset('img/logo_gmb.png') }}" class="w-40px me-5" alt="">
            <!--end::Logo-->
            <!--begin::Page title-->
            <div class="page-title py-2 py-sm-0 d-flex flex-column justify-content-center me-3 ">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    GIJAC MESSAGE BUSINESS

                    <!--begin::Description-->
                    <span class="page-desc text-muted fs-7 fw-semibold pt-2">
                        GIJAC MESSAGE BUSINESS </span>
                    <!--end::Description-->
                </h1>
                <!--end::Title-->

            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar wrapper--->

        <!--begin::Toolbar wrapper--->
        <div class="d-flex align-items-center flex-wrap flex-lg-nowrap gap-4 gap-lg-10">

            <!--begin::Items-->
            <div class="d-flex align-items-center gap-4 gap-lg-8">
                {{-- <!--begin::Item-->
                <div class="d-flex flex-column">
                    <!--begin::Number-->
                    <span class="text-gray-900 fw-bold fs-6 mb-1">{{}}</span>
                    <!--end::Number-->

                    <!--begin::Section-->
                    <div class="text-gray-500 fw-semibold fs-7">Porcentaje de campañas</div>
                    <!--end::Section-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-column">
                    <!--begin::Number-->
                    <span class="text-gray-900 fw-bold fs-6 mb-1">$1,748.03</span>
                    <!--end::Number-->

                    <!--begin::Section-->
                    <div class="text-gray-500 fw-semibold fs-7">Today Spending</div>
                    <!--end::Section-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-column">
                    <!--begin::Number-->
                    <span class="text-gray-900 fw-bold fs-6 mb-1">3.8%</span>
                    <!--end::Number-->

                    <!--begin::Section-->
                    <div class="text-gray-500 fw-semibold fs-7">Overall Share</div>
                    <!--end::Section-->
                </div>
                <!--end::Item-->
                <!--begin::Item-->
                <div class="d-flex flex-column">
                    <!--begin::Number-->
                    <span class="text-gray-900 fw-bold fs-6 mb-1">-7.4%</span>
                    <!--end::Number-->

                    <!--begin::Section-->
                    <div class="text-gray-500 fw-semibold fs-7">7 Days</div>
                    <!--end::Section-->
                </div>
                <!--end::Item--> --}}
            </div>
            <!--end::Items-->

            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gapl-lg-4">
                @if ($drawer)
                    <!--begin::Trigger button-->
                    <button id="kt_drawer_example_basic_button" class="btn btn-icon btn-color-gray-600 btn-active-light btn-active-color-primary">
                        <i class="fas fa-search fs-2x"></i>
                    </button>
                    <!--end::Trigger button-->
                @endif

                <!--begin::Secondary button-->
                <div class="seccionNotificacionesGeneral m-0">
                    @component('layouts.componentes.notificaciones')
                        @slot('cantidad', $cantidad)
                        @slot('unreadNotifications', $usuario->unreadNotifications)
                        @slot('notifications', $usuario->notifications)
                    @endcomponent
                </div>
                <!--end::Secondary button-->

                <!--begin::Primary button-->
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="btn btn-icon btn-color-gray-600 btn-active-light btn-active-color-primary">
                    <i class="fas fa-sign-out-alt fs-2x"></i>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </a>
                <!--end::Primary button-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar wrapper--->
    </div>
    <!--end::Toolbar container-->
</div>
