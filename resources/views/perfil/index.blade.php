@extends('layouts.index', ['nombre_titulo' => 'Perfil'])

@section('css')
    {{-- <link href="{{ mix('css/home.css') }}" rel="stylesheet" type="text/css" /> --}}
    <style>
        /* .apexcharts-datalabels text {
                font-size: x-large !important;
            }

            .apexcharts-datalabels.apexcharts-hidden-element-shown text {
                color: #000;
                font-size: 1rem !important;
            }

            .select2-container--bootstrap5 .select2-selection--multiple:not(.form-select-sm):not(.form-select-lg) {
                background: #fff !important;
            } */

        .tablasScroll {
            max-height: 300px !important;
        }
    </style>
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class=" container-xxl " id="kt_content_container">
            <!--begin::Navbar-->
            <div class="card text-white mb-5 mb-xl-10">
                <div class="card-body pt-9 pb-0">
                    <!--begin::Details-->
                    <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                        <!--begin: Pic-->
                        <div class="me-7 mb-4">
                            <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                <img src="{{ $usuario->foto ? asset($usuario->foto) : asset('assets/media/avatars/150-2.jpg') }}"
                                    alt="image">
                                <div
                                    class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                                </div>
                            </div>
                        </div>
                        <!--end::Pic-->

                        <!--begin::Info-->
                        <div class="flex-grow-1">
                            <!--begin::Title-->
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <!--begin::User-->
                                <div class="d-flex flex-column">
                                    <!--begin::Name-->
                                    <div class="d-flex align-items-center mb-2">
                                        <a href="#"
                                            class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $usuario->nombre_completo }}</a>
                                        <a href="#">
                                            <span class="svg-icon svg-icon-1 svg-icon-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z"
                                                        fill="#00A3FF"></path>
                                                    <path class="permanent"
                                                        d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z"
                                                        fill="white"></path>
                                                </svg>
                                            </span>
                                        </a>

                                        <a href="#" class="btn btn-sm btn-light-success fw-bold ms-2 fs-8 py-1 px-3"
                                            data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">Activo</a>
                                    </div>
                                    <!--end::Name-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                        <a href="#"
                                            class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                            <span class="svg-icon svg-icon-4 me-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3"
                                                        d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z"
                                                        fill="currentColor"></path>
                                                    <path
                                                        d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </span>
                                            Usuario
                                        </a>
                                        <a href="#"
                                            class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                            <span class="svg-icon svg-icon-4 me-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3"
                                                        d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z"
                                                        fill="currentColor"></path>
                                                    <path
                                                        d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </span>
                                            {{ $usuario->email }}
                                        </a>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->

                                <!--begin::Actions-->
                                <div class="d-flex">
                                    <!--begin::Ribbon wrapper 2-->
                                    <div class="overflow-hidden position-relative card-rounded">
                                        <!--begin::Ribbon-->
                                        {{-- <div class="ribbon ribbon-triangle ribbon-top-start border-primary">
                                        </div> --}}
                                        <!--end::Ribbon-->

                                        <!--begin::Card-->
                                        {{-- <div class="card card-bordered">
                                            <!--begin::Body-->
                                            <div class="card-body bg-qr">
                                                <div class="text-primary" style="width: 130px; height: 120px;">
                                                    <img src="data:image/svg+xml;base64,{{ base64_encode($QrCode) }}"
                                                        width="100%">
                                                </div>
                                            </div>
                                            <!--end::Body-->
                                        </div> --}}
                                        <!--end::Card-->
                                    </div>
                                    <!--end::Ribbon wrapper 2-->
                                    {{-- <a href="#" class="btn btn-sm btn-light me-2" id="kt_user_follow_button">
                                        <i class="ki-duotone ki-check fs-3 d-none"></i>
                                        <!--begin::Indicator label-->
                                        <span class="indicator-label">
                                            Follow</span>
                                        <!--end::Indicator label-->

                                        <!--begin::Indicator progress-->
                                        <span class="indicator-progress">
                                            Please wait... <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                        <!--end::Indicator progress--> </a>

                                    <a href="#" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_offer_a_deal">Hire Me</a>

                                    <!--begin::Menu-->
                                    <div class="me-0">
                                        <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <i class="ki-solid ki-dots-horizontal fs-2x"></i> </button>

                                        <!--begin::Menu 3-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                                            data-kt-menu="true">
                                            <!--begin::Heading-->
                                            <div class="menu-item px-3">
                                                <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                                    Payments
                                                </div>
                                            </div>
                                            <!--end::Heading-->

                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3">
                                                    Create Invoice
                                                </a>
                                            </div>
                                            <!--end::Menu item-->

                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link flex-stack px-3">
                                                    Create Payment

                                                    <span class="ms-2" data-bs-toggle="tooltip"
                                                        aria-label="Specify a target name for future usage and reference"
                                                        data-bs-original-title="Specify a target name for future usage and reference"
                                                        data-kt-initialized="1">
                                                        <i class="fas fa-info-circle fs-6"><span
                                                                class="path1"></span><span class="path2"></span><span
                                                                class="path3"></span></i> </span>
                                                </a>
                                            </div>
                                            <!--end::Menu item-->

                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3">
                                                    Generate Bill
                                                </a>
                                            </div>
                                            <!--end::Menu item-->

                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                                data-kt-menu-placement="right-end">
                                                <a href="#" class="menu-link px-3">
                                                    <span class="menu-title">Subscription</span>
                                                    <span class="menu-arrow"></span>
                                                </a>

                                                <!--begin::Menu sub-->
                                                <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3">
                                                            Plans
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3">
                                                            Billing
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3">
                                                            Statements
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->

                                                    <!--begin::Menu separator-->
                                                    <div class="separator my-2"></div>
                                                    <!--end::Menu separator-->

                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <div class="menu-content px-3">
                                                            <!--begin::Switch-->
                                                            <label
                                                                class="form-check form-switch form-check-custom form-check-solid">
                                                                <!--begin::Input-->
                                                                <input class="form-check-input w-30px h-20px"
                                                                    type="checkbox" value="1" checked="checked"
                                                                    name="notifications">
                                                                <!--end::Input-->

                                                                <!--end::Label-->
                                                                <span class="form-check-label text-muted fs-6">
                                                                    Recuring
                                                                </span>
                                                                <!--end::Label-->
                                                            </label>
                                                            <!--end::Switch-->
                                                        </div>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu sub-->
                                            </div>
                                            <!--end::Menu item-->

                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3 my-1">
                                                <a href="#" class="menu-link px-3">
                                                    Settings
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu 3-->
                                    </div>
                                    <!--end::Menu--> --}}
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Title-->

                            {{-- <!--begin::Stats-->
                            <div class="d-flex flex-wrap flex-stack">
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-column flex-grow-1 pe-8">
                                    <!--begin::Stats-->
                                    <div class="d-flex flex-wrap">
                                        <!--begin::Stat-->
                                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <!--begin::Number-->
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-arrow-up fs-3 text-success me-2"><span
                                                        class="path1"></span><span class="path2"></span></i>
                                                <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                    data-kt-countup-value="4500" data-kt-countup-prefix="$"
                                                    data-kt-initialized="1">$4,500</div>
                                            </div>
                                            <!--end::Number-->

                                            <!--begin::Label-->
                                            <div class="fw-semibold fs-6 text-gray-400">Earnings</div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Stat-->

                                        <!--begin::Stat-->
                                        <div
                                            class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <!--begin::Number-->
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-arrow-down fs-3 text-danger me-2"><span
                                                        class="path1"></span><span class="path2"></span></i>
                                                <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                    data-kt-countup-value="75" data-kt-initialized="1">75</div>
                                            </div>
                                            <!--end::Number-->

                                            <!--begin::Label-->
                                            <div class="fw-semibold fs-6 text-gray-400">Projects</div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Stat-->

                                        <!--begin::Stat-->
                                        <div
                                            class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <!--begin::Number-->
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-arrow-up fs-3 text-success me-2"><span
                                                        class="path1"></span><span class="path2"></span></i>
                                                <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                    data-kt-countup-value="60" data-kt-countup-prefix="%"
                                                    data-kt-initialized="1">%60</div>
                                            </div>
                                            <!--end::Number-->

                                            <!--begin::Label-->
                                            <div class="fw-semibold fs-6 text-gray-400">Success Rate</div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Stat-->
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Progress-->
                                <div class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3">
                                    <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                        <span class="fw-semibold fs-6 text-gray-400">Profile Compleation</span>
                                        <span class="fw-bold fs-6">50%</span>
                                    </div>

                                    <div class="h-5px mx-3 w-100 bg-light mb-3">
                                        <div class="bg-success rounded h-5px" role="progressbar" style="width: 50%;"
                                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <!--end::Progress-->
                            </div>
                            <!--end::Stats--> --}}
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::Details-->

                    <!--begin::Navs-->
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                        <!--begin::Nav item-->
                        <li class="nav-item mt-2">
                            <a class="nav-link text-active-primary ms-0 me-10 py-5 active" data-bs-toggle="tab"
                                href="#tabDetalle">
                                Detalle
                            </a>
                        </li>
                        <!--end::Nav item-->
                        <!--begin::Nav item-->
                        <li class="nav-item mt-2">
                            <a class="nav-link text-active-primary ms-0 me-10 py-5" id="tabSeccionEditar"
                                data-bs-toggle="tab" href="#tabEditar">
                                Editar </a>
                        </li>
                        <!--end::Nav item-->
                        <!--begin::Nav item-->
                        <li class="nav-item mt-2">
                            <a class="nav-link text-active-primary ms-0 me-10 py-5 btnTabFacturas" data-bs-toggle="tab"
                                href="#tabFacturas">
                                Facturas </a>
                        </li>
                        <!--end::Nav item-->
                        <!--end::Nav item-->
                        <!--begin::Nav item-->
                        <li class="nav-item mt-2">
                            <a class="nav-link text-active-primary ms-0 me-10 py-5" id="btnTabApiKey" data-bs-toggle="tab" href="#tabAPI">
                                API Keys </a>
                        </li>
                        <!--end::Nav item-->
                        <!--begin::Nav item-->
                        <li class="nav-item mt-2 d-none">
                            <a class="nav-link text-active-primary ms-0 me-10 py-5 " data-bs-toggle="tab" href="#tabLog">
                                Logs </a>
                        </li>
                        <!--end::Nav item-->
                    </ul>
                    <!--begin::Navs-->
                </div>
            </div>
            <!--end::Navbar-->

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tabDetalle" role="tabpanel">
                    <!--begin::details View-->
                    <div class="card text-white mb-5 mb-xl-10" id="kt_profile_details_view">
                        <!--begin::Card header-->
                        <div class="card-header cursor-pointer">
                            <!--begin::Card title-->
                            <div class="card-title m-0">
                                <h3 class="fw-bold m-0 text-verdoso">Detalle del perfil</h3>
                            </div>
                            <!--end::Card title-->

                            <!--begin::Action-->
                            {{-- <button type="button" id="btnEditar"
                                class="btnG btnG-primary obalado guardar align-self-center">Editar</button> --}}
                            <!--end::Action-->
                        </div>
                        <!--begin::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body p-9">
                            <!--begin::Row-->
                            <div class="row mb-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted fs-5">Nombre Completo</label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-5 text-gray-800">{{ $usuario->nombre_completo }}</span>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->

                            <!--begin::Input group-->
                            <div class="row mb-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted fs-5">Identificación</label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <span
                                        class="fw-semibold text-gray-800 fs-6">{{ $usuario->infoDocumento->nombre_corto }}
                                        - {{ $usuario->identificacion }}</span>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted fs-5">
                                    Telefono

                                    <span class="ms-1" data-bs-toggle="tooltip"
                                        aria-label="El número de teléfono debe estar activo"
                                        data-bs-original-title="El número de teléfono debe estar activo">
                                        <i class="fas fa-info-circle fs-7">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                </label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-8 d-flex align-items-center">
                                    <span class="fw-bold fs-5 text-gray-800 me-2">{{ $usuario->numero_completo }}</span>
                                    {{-- <span class="badge badge-success">Verified</span> --}}
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted fs-5">
                                    Ciudad y País

                                    <span class="ms-1" data-bs-toggle="tooltip" aria-label="Ciudad y País de origen"
                                        data-bs-original-title="Ciudad y País de origen">
                                        <i class="fas fa-info-circle fs-7">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                </label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <a href="#"
                                        class="fw-semibold fs-5 text-gray-800 text-hover-primary">{{ $usuario?->ciudad?->nombre ?? 'N/A' }}
                                        - {{ $usuario?->ciudad?->pais?->nombre ?? 'N/A' }}</a>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-7">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted fs-5">
                                    Genero
                                </label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-5 text-gray-800">{{ $usuario->infoGenero?->nombre }}</span>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-7 d-none">
                                <!--begin::Label-->
                                <label class="col-lg-4 fw-semibold text-muted fs-5">Comunicación</label>
                                <!--end::Label-->

                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <span class="fw-bold  text-gray-800 fs-5">Email, WhatsApp</span>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Notice-->
                            {{-- <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed  p-6">
                                <!--begin::Icon-->
                                <i class="fas fa-info-circle fs-2tx text-warning me-4"><span class="path1"></span><span
                                        class="path2"></span><span class="path3"></span></i> <!--end::Icon-->

                                <!--begin::Wrapper-->
                                <div class="d-flex flex-stack flex-grow-1 ">
                                    <!--begin::Content-->
                                    <div class=" fw-semibold">
                                        <h4 class="text-gray-900 fw-bold">¡Necesitamos tu atención!</h4>

                                        <div class="fs-6 text-gray-700 ">Your payment was declined. To start using tools, please <a
                                                class="fw-bold" href="/jet-html-pro/account/billing.html">Add Payment Method</a>.
                                        </div>
                                    </div>
                                    <!--end::Content-->

                                </div>
                                <!--end::Wrapper-->
                            </div> --}}
                            <!--end::Notice-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::details View-->
                </div>
                <div class="tab-pane fade" id="tabEditar" role="tabpanel">
                    @component('perfil.componentes.editar')
                        @slot('usuario', $usuario)
                        @slot('generos', $generos)
                        @slot('tiposDocumentos', $tiposDocumentos)
                        @slot('paises', $paises)
                    @endcomponent
                </div>
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
                <div class="tab-pane fade" id="tabLog" role="tabpanel"></div>
            </div>
        </div>
        <!--end::Container-->
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
