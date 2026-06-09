@php
    $usuario = auth()->user();
    $cantidad = $usuario->unreadNotifications->count() ?? 0;
@endphp
<div id="kt_app_header" class="app-header  d-flex d-lg-none " data-kt-sticky="true"
    data-kt-sticky-activate="{default: false, lg: true}" data-kt-sticky-name="app-header-sticky"
    data-kt-sticky-offset="{default: false, lg: '300px'}">

    <!--begin::Header container-->
    <div class="app-container  container-xxl d-flex align-items-center justify-content-between "
        id="kt_app_header_container">
        <!--begin::Logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
            <a href="{{ route('home') }}">
                <img alt="Logo" src="{{ asset('img/logo_mini.png') }}" class="h-30px">
            </a>
        </div>
        <!--end::Logo-->

        <!--begin::Header mobile toggle-->
        <div class="d-flex align-items-center d-lg-none ms-2 me-n3" title="Show sidebar menu">
            <div class="btn btn-icon btn-color-white bg-white bg-opacity-0 bg-hover-opacity-10 w-35px h-35px"
                id="kt_app_sidebar_mobile_toggle">
                <i class="ki-outline ki-abstract-14 fs-1"></i>
            </div>
        </div>
        <!--end::Header mobile toggle-->
    </div>
    <!--end::Header container-->
</div>
