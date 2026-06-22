@php
    $usuario = auth()->user();
    $cantidad = $usuario->unreadNotifications->count() ?? 0;
@endphp
<div id="kt_app_sidebar" class="app-sidebar" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="auto"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div class="app-sidebar-primary ">
        <div class="app-sidebar-logo d-none d-md-flex flex-center pt-10 mb-5 mb-lg-5" id="kt_app_sidebar_logo">
            <a href="{{ route('home') }}">
                <img alt="Logo" src="{{ asset('img/logo_gmb_blanco.png') }}" class="h-50px">
            </a>
        </div>
        <div class="app-sidebar-menu flex-grow-1 hover-scroll-overlay-y scroll-ps mx-2 my-5" id="kt_aside_menu_wrapper"
            data-kt-scroll="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" style="height: 418px;">
            <div id="kt_aside_menu"
                class="menu menu-rounded menu-column menu-title-gray-600 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold fs-6"
                data-kt-menu="true">
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                    class="menu-item py-2 {{request()->is('home') ? 'here' : ''}}">
                    <span class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="fas fa-home fs-2x"></i>
                        </span>
                    </span>
                    <div class="menu-sub menu-sub-dropdown px-2 py-4 w-250px mh-75 overflow-auto">
                        <div class="menu-item">
                            <div class="menu-content ">
                                <span class="menu-section fs-5 fw-bolder ps-1 py-1">Home</span>
                            </div>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('home') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </div>
                    </div>
                </div>
                <a href="{{ route('negocios.index')}}" class="menu-item py-2 {{request()->is('negocios') ? 'here' : ''}}">
                    <span class="menu-link menu-center">
                        <span
                            class="menu-icon me-0">
                            <i class="bi bi-building fs-2x"></i>
                        </span>
                    </span>
                </a>
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                    class="menu-item py-2 {{request()->is('chats') || request()->is('chatbots') || request()->is('chatbots/crear') ? 'here' : ''}}">
                    <span class="menu-link menu-center">
                        <span
                            class="menu-icon me-0">
                            <i class="fas fa-comments fs-2x"></i>
                        </span>
                    </span>
                    <div class="menu-sub menu-sub-dropdown px-2 py-4 w-250px mh-75 overflow-auto">
                        <div class="menu-item">
                            <div class="menu-content ">
                                <span class="menu-section fs-5 fw-bolder ps-1 py-1">Chats</span>
                            </div>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('chats.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Chat</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('chatbots.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Chatbot</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                    class="menu-item py-2 {{request()->is('campañas') || request()->is('plantillas') || request()->is('calendario') || request()->is('configs') ? 'here' : ''}}"><!--begin:Menu link-->
                    <span class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="fas fa-bullhorn fs-2x"></i>
                        </span>
                    </span>
                    <div class="menu-sub menu-sub-dropdown px-2 py-4 w-250px mh-75 overflow-auto">
                        <div class="menu-item">
                            <div class="menu-content ">
                                <span class="menu-section fs-5 fw-bolder ps-1 py-1">Campañas</span>
                            </div>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('campanas.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Mis Campañas</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('plantillas.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Mis Plantillas</span>
                            </a>
                        </div>
                        {{-- <div class="menu-item">
                            <a class="menu-link" href="#">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Calendario</span>
                            </a>
                        </div> --}}
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('configs.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Configuracion</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                    class="menu-item py-2 {{request()->is('contactos') || request()->is('etiquetas') || request()->is('clasificacion-ia') ? 'here' : ''}}"><!--begin:Menu link-->
                    <span class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="fas fa-phone-alt fs-2x"></i>
                        </span>
                    </span>
                    <div class="menu-sub menu-sub-dropdown px-2 py-4 w-250px mh-75 overflow-auto">
                        <div class="menu-item">
                            <div class="menu-content ">
                                <span class="menu-section fs-5 fw-bolder ps-1 py-1">Contactos</span>
                            </div>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('contactos.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Contactos</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('etiquetas.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Etiquetas</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('clasificacion-ia.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Clasificación (Con IA)</span>
                            </a>
                        </div>
                    </div>
                </div>
                @canAny([
                    'usuarios.listado',
                    'usuarios.crear',
                    'usuarios.editar',
                    'usuarios.eliminar',
                    'usuarios.agregar-rol',
                    'usuarios.agregar-permiso',
                ])
                    <a href="{{ route('usuarios.index')}}" class="menu-item py-2 {{request()->is('usuarios') ? 'here' : ''}}">
                        <span class="menu-link menu-center">
                            <span
                                class="menu-icon me-0">
                                <i class="fas fa-users fs-2x"></i>
                            </span>
                        </span>
                    </a>
                @endcanany
                @canany(['planes.crear', 'planes.listado', 'planes.editar', 'planes.eliminar'])
                    <a href="{{ route('planes.index')}}" class="menu-item py-2 {{request()->is('planes') ? 'here' : ''}}">
                        <span class="menu-link menu-center">
                            <span
                                class="menu-icon me-0">
                                <i class="fas fa-tags fs-2x"></i>
                            </span>
                        </span>
                    </a>
                @endcanany
                @canany(['tickets.crear', 'tickets.listado', 'tickets.editar', 'tickets.eliminar'])
                    <a href="{{ route('tickets.index')}}" class="menu-item py-2 {{request()->is('tickets') || request()->is('tickets/editar/*') ? 'here' : ''}}">
                        <span class="menu-link menu-center">
                            <span
                                class="menu-icon me-0">
                                <i class="fas fa-ticket-alt fs-2x"></i>
                            </span>
                        </span>
                    </a>
                @endcanany
            </div>
        </div>
        <div class="d-flex flex-column flex-center pb-4 pb-lg-8" id="kt_app_sidebar_footer">
            <div class="cursor-pointer symbol symbol-40px symbol-circle"
                data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-attach="parent"
                data-kt-menu-placement="right-end">
                <img src="{{$usuario->foto ? asset($usuario->foto) : asset('assets/media/avatars/150-2.jpg') }}" alt="user">
            </div>

            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                data-kt-menu="true">
                <div class="menu-item px-3">
                    <div class="menu-content d-flex align-items-center px-3">
                        <!--begin::Avatar-->
                        <div class="symbol symbol-50px me-5">
                            <img alt="Logo" src="{{$usuario->foto ? asset($usuario->foto) : asset('assets/media/avatars/150-2.jpg') }}">
                        </div>
                        <!--end::Avatar-->

                        <!--begin::Username-->
                        <div class="d-flex flex-column">
                            <div class="fw-bold d-flex align-items-center fs-5">
                                {{$usuario->nombre_completo}}
                                <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">{{!$usuario?->demo ? 'Pro' : 'Demo'}}</span>
                            </div>

                            <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                {{$usuario->email}} </a>
                        </div>
                        <!--end::Username-->
                    </div>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu separator-->
                <div class="separator my-2"></div>
                <!--end::Menu separator-->

                <!--begin::Menu item-->
                <div class="menu-item px-5">
                    <a href="{{ route('perfil') }}" class="menu-link px-5">
                        Mi Perfil
                    </a>
                </div>
                <!--end::Menu item-->
                @hasrole(['super_admin', 'admin', 'cliente'])
                    <!--begin::Menu item-->
                    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="left-end" data-kt-menu-offset="-15px, 0">
                        <a href="#" class="menu-link px-5">
                            <span class="menu-title">Mi Suscripcion</span>
                            <span class="menu-arrow"></span>
                        </a>

                        <!--begin::Menu sub-->
                        <div class="menu-sub menu-sub-dropdown w-175px py-4">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="{{ route('precios') }}" class="menu-link px-5">
                                    Planes
                                </a>
                            </div>
                            <!--end::Menu item-->

                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="{{ route('perfil') }}" class="menu-link px-5">
                                    Facturación
                                </a>
                            </div>
                            <!--end::Menu item-->

                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="{{ route('facturas.pago', ['plan' => 0]) }}" class="menu-link px-5">
                                    Pagos
                                </a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu sub-->
                    </div>
                    <!--end::Menu item-->
                @endhasrole
                <!--begin::Menu separator-->
                <div class="separator my-2"></div>
                <!--end::Menu separator-->

                <!--begin::Menu item-->
                <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                    data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                    <a href="#" class="menu-link px-5">
                        <span class="menu-title position-relative">
                            Apariencia
                            <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                <i class="las la-sun theme-light-show fs-2"></i>
                                <i class="far fa-moon theme-dark-show fs-2"></i>
                            </span>
                        </span>
                    </a>

                    <!--begin::Menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                        data-kt-menu="true" data-kt-element="theme-mode-menu">
                         <!--begin::Menu item-->
                         <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2 active"
                                data-kt-element="mode" data-kt-value="light">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="las la-sun fs-2"></i> </span>
                                <span class="menu-title">
                                    Light
                                </span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                data-kt-value="dark">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="far fa-moon fs-2"></i> </span>
                                <span class="menu-title">
                                    Dark
                                </span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                data-kt-value="system">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="far fa-window-maximize fs-2"></i> </span>
                                <span class="menu-title">
                                    System
                                </span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu-->

                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                    data-kt-menu-placement="left-start" data-kt-menu-offset="0, 0">
                    <a href="#" class="menu-link px-5">
                        <span class="menu-title position-relative">
                            Idioma

                            <span
                                class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">
                                Español <img class="w-15px h-15px rounded-1 ms-2"
                                    src="{{asset('assets/media/flags/spain.svg')}}"
                                    alt="">
                            </span>
                        </span>
                    </a>

                    <!--begin::Menu sub-->
                    <div class="menu-sub menu-sub-dropdown w-175px py-4">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="{{ route('lang.switch', ['locale' => 'es']) }}"
                                class="menu-link d-flex px-5 active">
                                <span class="symbol symbol-20px me-4">
                                    <img class="rounded-1"
                                        src="{{asset('assets/media/flags/spain.svg')}}"
                                        alt="">
                                </span>
                                Español
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="{{ route('lang.switch', ['locale' => 'en']) }}"
                                class="menu-link d-flex px-5">
                                <span class="symbol symbol-20px me-4">
                                    <img class="rounded-1"
                                        src="{{asset('assets/media/flags/united-states.svg')}}"
                                        alt="">
                                </span>
                                Ingles
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="{{ route('lang.switch', ['locale' => 'ale']) }}"
                                class="menu-link d-flex px-5">
                                <span class="symbol symbol-20px me-4">
                                    <img class="rounded-1"
                                        src="{{asset('assets/media/flags/germany.svg')}}"
                                        alt="">
                                </span>
                                Alemania
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="{{ route('lang.switch', ['locale' => 'jan']) }}"
                                class="menu-link d-flex px-5">
                                <span class="symbol symbol-20px me-4">
                                    <img class="rounded-1"
                                        src="{{asset('assets/media/flags/japan.svg')}}"
                                        alt="">
                                </span>
                                Japones
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="{{ route('lang.switch', ['locale' => 'fan']) }}"
                                class="menu-link d-flex px-5">
                                <span class="symbol symbol-20px me-4">
                                    <img class="rounded-1"
                                        src="{{asset('assets/media/flags/france.svg')}}"
                                        alt="">
                                </span>
                                Frances
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu sub-->
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-5">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="btn btn-icon btn-active-color-primary">
                        Salir
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </a>
                </div>
                <!--end::Menu item-->
            </div>
        </div>
    </div>
    <!--end::Sidebar primary-->

    <!--begin::Sidebar secondary-->
    <div class="app-sidebar-secondary ">
        <div class="d-flex flex-column">
            <div class="d-flex flex-column pt-10 ps-11" id="kt_app_sidebar_secondary_header">
                <a href="{{ route('home') }}"
                    class="d-flex align-items-center custom-link fs-6 fw-semibold mb-5">
                    <i class="fas fa-long-arrow-alt-left fs-2 me-3 text-white opacity-50"></i> Ocultar Menu
                </a>
            </div>

            <!--begin::Sidebar secondary menu-->
            <div class="app-sidebar-secondary-menu menu menu-sub-indention menu-rounded menu-column menu-active-bg menu-title-gray-600 menu-icon-gray-500 menu-state-primary menu-arrow-gray-500 fw-semibold fs-6 py-4 py-lg-6"
                id="kt_app_sidebar_secondary_menu" data-kt-menu="true">

                <div id="kt_app_sidebar_secondary_menu_wrapper" class="hover-scroll-y px-3 mx-3"
                    data-kt-scroll="true" data-kt-scroll-activate="{default: true, lg: true}"
                    data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_secondary_header"
                    data-kt-scroll-wrappers="#kt_app_sidebar_secondary_menu" data-kt-scroll-offset="20px"
                    style="height: 477px;">

                    <div class="menu-item">
                        <div class="menu-content ">
                            <span class="menu-section fs-5 fw-bolder ps-1 py-1">Menu</span>
                        </div>
                    </div>
                    @if (request()->is('home'))
                        <div class="menu-item">
                            <a href="{{ route('home') }}" class="menu-link {{request()->is('home') ? 'active' : ''}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Dashboard
                                </span>
                            </a>
                        </div>
                    @endif
                    @if (request()->is('negocios'))
                        <div class="menu-item">
                            <a href="{{ route('negocios.index') }}" class="menu-link {{request()->is('negocios') ? 'active' : ''}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Negocios
                                </span>
                            </a>
                        </div>
                    @endif
                    @if (request()->is('campañas') || request()->is('plantillas') || request()->is('calendario') || request()->is('configs'))
                        <div class="menu-item">
                            <a href="{{ route('campanas.index') }}" class="menu-link {{request()->is('campañas') ? 'active' : ''}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Mis Campañas
                                </span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('plantillas.index') }}" class="menu-link {{request()->is('plantillas') ? 'active' : ''}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Mis Plantillas
                                </span>
                            </a>
                        </div>
                        {{-- <div class="menu-item">
                            <a class="menu-link {{request()->is('calendario') ? 'active' : ''}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Calendario
                                </span>
                            </a>
                        </div> --}}
                        <div class="menu-item">
                            <a href="{{ route('configs.index') }}" class="menu-link {{request()->is('configs') ? 'active' : ''}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Configuracion
                                </span>
                            </a>
                        </div>
                    @endif
                    @if (request()->is('contactos') || request()->is('etiquetas') || request()->is('clasificacion-ia'))
                        <div class="menu-item">
                            <a href="{{ route('contactos.index') }}" class="menu-link {{request()->is('contactos') ? 'active' : ''}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Mis Contactos
                                </span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('etiquetas.index') }}" class="menu-link {{request()->is('etiquetas') ? 'active' : ''}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Etiquetas
                                </span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('clasificacion-ia.index') }}" class="menu-link {{request()->is('clasificacion-ia') ? 'active' : ''}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Clasificación (Con IA)
                                </span>
                            </a>
                        </div>
                    @endif
                    @if (request()->is('chats') || request()->is('chatbots') || request()->is('chatbots/crear'))
                        <div class="menu-item">
                            <a href="{{ route('chats.index') }}" class="menu-link {{request()->is('chats') ? 'active' : ''}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Chat
                                </span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('chatbots.index') }}" class="menu-link {{request()->is('chatbots') || request()->is('chatbots/crear') ? 'active' : ''}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Chatbot
                                </span>
                            </a>
                        </div>
                    @endif
                    @if (request()->is('usuarios'))
                        <div class="menu-item">
                            <a href="{{ route('usuarios.index') }}" class="menu-link {{request()->is('usuarios') ? 'active' : ''}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Usuarios
                                </span>
                            </a>
                        </div>
                    @endif
                    @if (request()->is('planes'))
                        <div class="menu-item">
                            <a href="{{ route('planes.index') }}" class="menu-link {{request()->is('planes') ? 'active' : ''}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Planes
                                </span>
                            </a>
                        </div>
                    @endif
                    @if (request()->is('tickets'))
                        <div class="menu-item">
                            <a href="{{ route('tickets.index') }}" class="menu-link {{request()->is('tickets') ? 'active' : ''}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Tickets
                                </span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--end::Sidebar secondary-->

    <!--begin::Sidebar secondary toggle-->
    <button id="kt_app_sidebar_secondary_toggle"
        class="app-sidebar-secondary-toggle btn btn-sm btn-icon bg-body btn-color-gray-600 btn-active-color-primary position-absolute translate-middle z-index-1 start-100 end-0 bottom-0 shadow-sm d-none d-lg-flex mb-4"
        data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
        data-kt-toggle-name="app-sidebar-secondary-collapse">
        <i class="fas fa-long-arrow-alt-left fs-2 rotate-180"></i>
    </button>
    <!--end::Sidebar secondary toggle-->
</div>
