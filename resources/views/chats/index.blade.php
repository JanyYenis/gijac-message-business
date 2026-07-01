@extends('layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ mix('css/chats.css') }}">
    <link rel="stylesheet" href="{{ mix('css/audio.css') }}">
    <link href="{{ asset('css/perfil-whatsapp.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .cancel-button {
            padding: 0 !important;
        }

        ol.no-numbers {
            list-style-type: none;
            padding-left: 20px;
        }

        .recorder-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            width: 300px;
        }
        .recorder-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f0f2f5;
            border-radius: 20px;
            padding: 10px;
        }
        .mic-button {
            background-color: #00a884;
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            font-size: 24px;
            height: 50px;
            width: 50px;
        }
        .recording-indicator {
            /* display: flex; */
            align-items: center;
        }
        .recording-dot {
            background-color: #ff3b30;
            border-radius: 50%;
            height: 10px;
            width: 10px;
            margin-right: 10px;
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        .recording-time {
            font-size: 14px;
        }
        .recording-actions {
            display: none;
        }
        .recording-actions button {
            background-color: #8696a0;
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            font-size: 18px;
            height: 40px;
            width: 40px;
            margin-left: 10px;
        }
        .send-button {
            background-color: #00a884 !important;
        }
        #audioPlayer {
            width: 100%;
            margin-top: 20px;
        }
    </style>

    <style>
        /* Main Container */
        .messaging-container {
            display: flex;
            height: 100vh;
            width: 100%;
            background-color: var(--gray-100);
        }

        /* Left Column - Contacts List */
        .contacts-column {
            width: 35%;
            background-color: #f8f9fa;
            border-right: 1px solid var(--gray-300);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .contacts-header {
            background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark));
            color: white;
            padding: 1.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: var(--shadow-md);
        }

        .app-logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--whatsapp-green);
            box-shadow: var(--shadow-md);
        }

        .user-info h5 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
        }

        .user-info p {
            margin: 0;
            font-size: 0.75rem;
            opacity: 0.9;
        }

        .search-box {
            padding: 1rem;
            background-color: white;
            border-bottom: 1px solid var(--gray-200);
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid var(--gray-300);
            border-radius: 25px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 0.75rem center;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--whatsapp-green);
            box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.1);
        }

        .contacts-list {
            flex: 1;
            overflow-y: auto;
            background-color: white;
        }

        .contact-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid var(--gray-200);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .contact-item:hover {
            background-color: var(--gray-50);
        }

        .contact-item.active {
            background-color: #e3f2fd;
        }

        .contact-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gray-300), var(--gray-400));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .contact-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .contact-info {
            flex: 1;
            min-width: 0;
        }

        .contact-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .contact-last-message {
            font-size: 0.85rem;
            color: var(--gray-500);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .contact-time {
            font-size: 0.75rem;
            color: var(--gray-400);
            white-space: nowrap;
        }

        /* Center Column - Chat Area */
        .chat-column {
            width: 100%;
            display: flex;
            flex-direction: column;
            background-color: var(--whatsapp-bg);
        }

        .chat-header {
            background-color: white;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
        }

        .chat-header-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .chat-header-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            font-weight: 600;
        }

        .chat-header-name {
            font-weight: 600;
            font-size: 1rem;
            color: var(--gray-900);
            margin: 0;
        }

        .chat-header-status {
            font-size: 0.8rem;
            color: var(--gray-500);
            margin: 0;
        }

        .chat-header-actions {
            display: flex;
            gap: 1rem;
        }

        .chat-header-actions button {
            background: none;
            border: none;
            color: var(--gray-600);
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 0.5rem;
            border-radius: 50%;
        }

        .chat-header-actions button:hover {
            background-color: var(--gray-100);
            color: var(--whatsapp-green);
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 2rem 1.5rem;
            background-image:
                repeating-linear-gradient(
                    45deg,
                    transparent,
                    transparent 10px,
                    rgba(0, 0, 0, 0.02) 10px,
                    rgba(0, 0, 0, 0.02) 20px
                );
        }

        .message {
            display: flex;
            margin-bottom: 1rem;
            animation: messageSlide 0.3s ease;
        }

        @keyframes messageSlide {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.sent {
            justify-content: flex-end;
        }

        .message.received {
            justify-content: flex-start;
        }

        .message-bubble {
            max-width: 60%;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            position: relative;
        }

        .message.sent .message-bubble {
            background-color: var(--whatsapp-light);
            border-bottom-right-radius: 4px;
        }

        .message.received .message-bubble {
            background-color: white;
            border-bottom-left-radius: 4px;
        }

        .message-text {
            font-size: 0.95rem;
            color: var(--gray-900);
            line-height: 1.5;
            margin-bottom: 0.25rem;
        }

        .message-time {
            font-size: 0.7rem;
            color: var(--gray-500);
            text-align: right;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.25rem;
        }

        .message.sent .message-time {
            color: var(--gray-600);
        }

        .chat-input-container {
            background-color: white;
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }

        .chat-input-container button {
            background: none;
            border: none;
            color: var(--gray-600);
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 0.5rem;
            border-radius: 50%;
        }

        .chat-input-container button:hover {
            color: var(--whatsapp-green);
            background-color: var(--gray-100);
        }

        .chat-input-container input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: 25px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .chat-input-container input:focus {
            outline: none;
            border-color: var(--whatsapp-green);
            box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.1);
        }

        .btn-send {
            background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark)) !important;
            color: white !important;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .btn-send:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-md);
        }

        /* Right Column - Contact Info */
        .info-column {
            width: 50%;
            background-color: #f9f9f9;
            border-left: 1px solid var(--gray-300);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .info-header {
            background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark));
            color: white;
            padding: 1.5rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-md);
        }

        .info-header h5 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .btn-close-info {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 0.25rem;
            opacity: 0.9;
        }

        .btn-close-info:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .info-content {
            padding: 1.5rem;
        }

        .info-profile {
            text-align: center;
            margin-bottom: 2rem;
        }

        .info-profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            font-weight: 600;
            margin: 0 auto 1rem;
            box-shadow: var(--shadow-lg);
        }

        .info-profile-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .info-profile-subtitle {
            font-size: 0.9rem;
            color: var(--gray-500);
        }

        .info-section {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .info-section-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chatbot-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            background-color: var(--gray-50);
            border-radius: 8px;
            margin-bottom: 0.75rem;
            transition: all 0.2s ease;
        }

        .chatbot-toggle:hover {
            background-color: var(--gray-100);
        }

        .chatbot-toggle-label {
            font-size: 0.9rem;
            color: var(--gray-700);
            font-weight: 500;
        }

        .form-check-input {
            width: 3rem;
            height: 1.5rem;
            cursor: pointer;
            border: 2px solid var(--gray-300);
            background-color: var(--gray-300);
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: var(--whatsapp-green);
            border-color: var(--whatsapp-green);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.2);
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--gray-200);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--whatsapp-green);
            font-size: 1rem;
        }

        .info-item-content {
            flex: 1;
        }

        .info-item-label {
            font-size: 0.8rem;
            color: var(--gray-500);
            margin-bottom: 0.1rem;
        }

        .info-item-value {
            font-size: 0.9rem;
            color: var(--gray-900);
            font-weight: 500;
        }

        .btn-more-info {
            width: 100%;
            background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark));
            color: white;
            border: none;
            padding: 0.875rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .btn-more-info:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Scrollbar Styles */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-400);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-500);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .contacts-column {
                width: 30%;
            }

            .chat-column {
                width: 45%;
            }

            .info-column {
                width: 25%;
            }
        }

        @media (max-width: 992px) {
            .info-column {
                display: none;
            }

            .info-column.show {
                display: flex;
                position: fixed;
                right: 0;
                top: 0;
                height: 100vh;
                z-index: 1000;
                box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            }

            .contacts-column {
                width: 35%;
            }

            .chat-column {
                width: 65%;
            }
        }

        @media (max-width: 768px) {
            .contacts-column {
                width: 100%;
            }

            .chat-column {
                width: 100%;
                display: none;
            }

            .chat-column.show {
                display: flex;
            }

            .contacts-column.hide {
                display: none;
            }
        }

        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--gray-500);
            text-align: center;
            padding: 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            font-size: 1rem;
            opacity: 0.8;
        }

        /* Badge for unread messages */
        .unread-badge {
            background-color: var(--whatsapp-green);
            color: white;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            min-width: 20px;
            text-align: center;
        }

        /* Online status indicator */
        .online-indicator {
            width: 10px;
            height: 10px;
            background-color: var(--success-color);
            border-radius: 50%;
            border: 2px solid white;
            position: absolute;
            bottom: 2px;
            right: 2px;
        }

        .contact-avatar-wrapper {
            position: relative;
        }

        /* Audio Player Component */
        .audio-player {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            box-shadow: 0 1px 3px var(--player-shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            min-width: 23rem !important;
            padding-bottom: 25px;
            margin-top: 1rem;
        }

        .audio-player time {
            font-size: 1rem;
            color: rgba(0, 0, 0, 0.45);
            float: right;
            margin-top: 8px;
        }

        .audio-player.sent {
            background: var(--sent-bg);
            margin-left: auto;
            max-width: 400px;
            background-color: var(--user-color-message);
        }

        .audio-player.received {
            background: var(--received-bg);
            margin-right: auto;
            max-width: 400px;
        }

        .audio-player:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Play/Pause Button */
        .play-button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--player-primary);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }

        .play-button:hover {
            background: var(--player-primary-hover);
            transform: scale(1.05);
        }

        .play-button:active {
            transform: scale(0.95);
        }

        .play-button.playing {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
            }
            50% {
                box-shadow: 0 0 0 8px rgba(37, 211, 102, 0);
            }
        }

        .play-button i {
            font-size: 16px;
            transition: all 0.2s ease;
        }

        .play-button .fa-play {
            margin-left: 2px;
        }

        /* Progress Container */
        .progress-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        /* Waveform Container */
        .waveform-container {
            position: relative;
            height: 24px;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .waveform {
            display: flex;
            align-items: center;
            gap: 2px;
            height: 100%;
            width: 100%;
        }

        .waveform-bar {
            flex: 1;
            background: #8d8e8f;
            border-radius: 2px;
            transition: all 0.3s ease;
            min-width: 2px;
        }

        .waveform-bar.active {
            background: var(--player-progress);
        }

        .waveform-bar.playing {
            animation: wave 1s ease-in-out infinite;
        }

        @keyframes wave {
            0%, 100% {
                transform: scaleY(1);
            }
            50% {
                transform: scaleY(1.5);
            }
        }

        /* Progress Bar */
        .progress-bar-container {
            width: 100%;
            height: 3px;
            background: var(--gray-300);
            border-radius: 2px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: var(--player-progress);
            border-radius: 2px;
            width: 0%;
            transition: width 0.1s linear;
            position: relative;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background: var(--player-progress);
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .progress-bar-container:hover .progress-bar::after {
            opacity: 1;
        }

        /* Time Display */
        .time-display {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: var(--player-text);
            font-weight: 500;
        }

        .time-current {
            color: var(--player-primary);
        }

        /* Audio Icon */
        .audio-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--player-primary);
            flex-shrink: 0;
        }

        .audio-icon i {
            font-size: 20px;
        }

        .audio-icon.playing {
            animation: soundWave 1.5s ease-in-out infinite;
        }

        @keyframes soundWave {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.7;
            }
        }

        /* Loading State */
        .audio-player.loading .play-button {
            pointer-events: none;
        }

        .audio-player.loading .play-button i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            .audio-player.received {
                background: var(--gray-700);
            }

            .waveform-bar {
                background: var(--gray-600);
            }

            .progress-bar-container {
                background: var(--gray-600);
            }

            .time-display {
                color: var(--gray-300);
            }
        }

        /* Responsive Design */
        @media (max-width: 640px) {
            .audio-player {
                max-width: 100% !important;
            }
        }
    </style>
@endsection

@section('content')
    <input type="hidden" value="{{$numero}}" id="MyTelefono">
    <div class="messaging-container">.
        <!-- Left Column - Contacts List -->
        <div class="contacts-column" id="contactsColumn">
            <div class="contacts-header btnPerfilWhatsapp">
                <div class="app-logo">
                    <img class="profile-image" src="{{ $datosPerfilWhatsapp && isset($datosPerfilWhatsapp['profile_picture_url']) ? $datosPerfilWhatsapp['profile_picture_url'] : asset('img/logo_mini.png') }}" alt="Elad Shechter">
                </div>
                <div class="user-info">
                    <h3 class="text-white">{{ $datosNumero && isset($datosNumero['data']) ? $datosNumero['data'][0]['verified_name'] : 'N/A'}}</h3>
                    <p>En línea</p>
                </div>
            </div>

            <div class="search-box">
                <input type="text" id="inputSearchContactos" placeholder="Buscar contactos">
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
                <h2 class="text-white">Información del contacto</h2>
                <button type="button" class="btn-close-info" id="btnCloseInfo">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="info-content">
                <div class="info-profile">
                    <div class="info-profile-avatar" id="infoProfileAvatar">JD</div>
                    <h3 class="info-profile-name" id="infoProfileName">Juan Pérez</h3>
                    <p class="info-profile-subtitle">Cliente desde 2024</p>
                </div>

                <div class="info-section">
                    <h4 class="info-section-title">
                        <i class="fas fa-robot"></i>
                        Configuración del Chatbot
                    </h4>

                    <div class="chatbot-toggle">
                        <span class="chatbot-toggle-label">Chatbot Tradicional</span>
                        <input class="form-check-input" type="checkbox" id="toggleTraditional">
                    </div>

                    <div class="chatbot-toggle">
                        <span class="chatbot-toggle-label">Chatbot con Inteligencia Artificial</span>
                        <input class="form-check-input" type="checkbox" id="toggleAI">
                    </div>
                </div>

                <div class="info-section">
                    <h4 class="info-section-title">
                        <i class="fas fa-clipboard-list"></i>
                        Información básica
                    </h4>

                    <div class="info-item">
                        <div class="info-item-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="info-item-content">
                            <div class="info-item-label">Teléfono</div>
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
                    Ver más información
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
