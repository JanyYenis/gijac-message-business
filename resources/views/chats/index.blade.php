@extends('layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ mix('css/chats.css') }}">
    <link rel="stylesheet" href="{{ mix('css/audio.css') }}">
    <link href="{{ asset('css/perfil-whatsapp.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <input type="hidden" value="{{$numero}}" id="MyTelefono">
    <div class="messaging-container">.
        <!-- Left Column - Contacts List -->
        <div class="contacts-column" id="contactsColumn">
            <div class="contacts-header {{ $datosNumero && isset($datosNumero['data']) && !$numeroTelefonoPrueba ? 'btnPerfilWhatsapp' : '' }}">
                <div class="app-logo">
                    <img class="profile-image" src="{{ $datosPerfilWhatsapp && isset($datosPerfilWhatsapp['profile_picture_url']) ? $datosPerfilWhatsapp['profile_picture_url'] : asset('img/logo_mini.png') }}" alt="Elad Shechter">
                </div>
                <div class="user-info">
                    <h3 class="text-white">{{ $datosNumero && isset($datosNumero['data']) ? (collect($datosNumero['data'])->firstWhere('id', $numero)['verified_name'] ?? $datosNumero['data'][0]['verified_name']) : 'N/A' }}</h3>
                    <p>{{ __('En línea') }}</p>
                </div>
            </div>

            <div class="search-box">
                <input type="text" id="inputSearchContactos" placeholder="{{ __('Buscar contactos') }}">
            </div>

            <div class="contacts-list" id="seccionListadoContactos">
                @component('chats.listado-contactos')
                    @slot('contactos', $contactos)
                @endcomponent
            </div>
        </div>

        <!-- Center Column - Chat Area -->
        <div class="chat-column main-content" id="chatColumn">
            <div style="display: flex; justify-content: center; align-items: center;">
                <img src="{{ asset('img/bg-chat.png') }}" width="70%">
            </div>
        </div>

        <!-- Right Column - Contact Info -->
        <div class="info-column d-none" id="infoColumn">
            <div class="info-header">
                <h2 class="text-white">{{ __('Información del contacto') }}</h2>
                <button type="button" class="btn-close-info" id="btnCloseInfo">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="info-content">
                <div class="info-profile">
                    <div class="info-profile-avatar" id="infoProfileAvatar">{{ __('JD') }}</div>
                    <h3 class="info-profile-name" id="infoProfileName">{{ __('Juan Pérez') }}</h3>
                    <p class="info-profile-subtitle">{{ __('Cliente desde 2024') }}</p>
                </div>

                <div class="info-section">
                    <h4 class="info-section-title">
                        <i class="fas fa-robot"></i>
                        {{ __('Configuración del Chatbot') }}
                    </h4>

                    <div class="chatbot-toggle">
                        <span class="chatbot-toggle-label">{{ __('Chatbot Tradicional') }}</span>
                        <input class="form-check-input" type="checkbox" id="toggleTraditional">
                    </div>

                    <div class="chatbot-toggle">
                        <span class="chatbot-toggle-label">{{ __('Chatbot con Inteligencia Artificial') }}</span>
                        <input class="form-check-input" type="checkbox" id="toggleAI">
                    </div>
                </div>

                <div class="info-section">
                    <h4 class="info-section-title">
                        <i class="fas fa-clipboard-list"></i>
                        {{ __('Información básica') }}
                    </h4>

                    <div class="info-item">
                        <div class="info-item-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="info-item-content">
                            <div class="info-item-label">{{ __('Teléfono') }}</div>
                            <div class="info-item-value" id="infoPhone">+57 300 123 4567</div>
                        </div>
                    </div>

                    {{-- <div class="info-item">
                        <div class="info-item-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-item-content">
                            <div class="info-item-label">Correo electrónico</div>
                            <div class="info-item-value" id="infoEmail">juan.perez@email.com</div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-item-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-item-content">
                            <div class="info-item-label">Última conexión</div>
                            <div class="info-item-value" id="infoLastSeen">Hoy a las 14:30</div>
                        </div>
                    </div> --}}
                </div>

                <a href="#" type="button" class="btn-more-info btnMasInfomacion">
                    {{ __('Ver más información') }}
                </a>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @component('chats.modals.camara')
    @endcomponent

    @component('chats.modals.archivo')
    @endcomponent

    @component('perfil-whatsapp.index')
    @endcomponent

    @component('sistema.modales.modal-errores')
    @endcomponent

    @component('campanas.modals.modal-respuesta')
    @endcomponent
@endsection

@section('scripts')
    <script src="{{ mix('/js/chats/principal.js') }}" ></script>
    <script src="{{ mix('/js/perfil-whatsapp.js') }}" ></script>
@endsection
