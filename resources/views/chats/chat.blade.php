<input type="hidden" id="idContactoGenereal" value="{{$contacto?->numero_completo}}">
<div class="chat-header">
    <div class="chat-header-info">
        <div class="chat-header-avatar" id="chatHeaderAvatar">
            <img class="profile-image" src="{{asset('img/user.png')}}" alt="{{$contacto?->nombre_completo}}">
        </div>
        <div>
            <h5 class="chat-header-name" id="chatHeaderName">{{$contacto?->nombre_completo}}</h5>
        </div>
    </div>
    <div class="chat-header-actions">
        <button type="button" class="d-none" title="Buscar en conversación">
            <i class="fas fa-search"></i>
        </button>
        <button type="button" id="btnShowInfo" title="Información del contacto">
            <i class="fas fa-info-circle"></i>
        </button>
        <button type="button" class="d-none" title="Más opciones">
            <i class="fas fa-ellipsis-v"></i>
        </button>
    </div>
</div>

<div class="chat-messages" id="messanger">
    <ol class="messanger-list no-numbers">
        @component('chats.mensaje')
            @slot('mensajesAgrupados', $mensajesAgrupados)
            @slot('contacto', $contacto)
        @endcomponent
    </ol>
</div>

@if ($estadoContacto)
    <form id="formMensaje">
        <input type="hidden" id="idContacto" name="id" value="{{$contacto?->numero_completo}}">
        <div class="seccionEnviarMensaje">
            <div class="chat-input-container">
                <button type="button" id="emoji-btn" class="seccion-texto textInput" title="Emoji">
                    <i class="far fa-smile"></i>
                </button>
                <!-- El selector de emojis -->
                <emoji-picker id="emoji-picker" style="display:none; position:absolute; z-index:1000;"></emoji-picker>
                <button type="button" class="common-button seccion-texto" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="20px, 20px">
                    <i class="fas fa-paperclip"></i>
                </button>
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="javascript:;" class="menu-link px-3 btnFotosVideos">
                            <i class="las la-photo-video fs-1 text-info me-1"></i>
                            Fotos y videos
                        </a>
                    </div>
                    <!--end::Menu item-->

                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a type="button" href="javascript:;" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modalCapturarFoto">
                            <i class="las la-camera fs-1 text-warning me-1"></i>
                            Camara
                        </a>
                    </div>
                    <!--end::Menu item-->

                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="javascript:;" class="menu-link px-3 btnDocumentos">
                            <i class="las la-file fs-1 text-primary me-1"></i>
                            Documento
                        </a>
                    </div>
                    <!--end::Menu item-->

                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="javascript:;" class="menu-link px-3">
                            <i class="las la-user fs-1 text-success me-1"></i>
                            Contacto
                        </a>
                    </div>
                    <!--end::Menu item-->
                </div>
                <!--end::Menu-->
                <input type="text" id="message-box" class="seccion-texto" placeholder="Escribe un mensaje...">
                <div class="recording-indicator text-center seccion-audio d-none">
                    <div class="recording-dot"></div>
                    <span class="recording-time">0:00</span>
                </div>
                <div class="recording-actions seccion-audio d-none">
                    <button type="button" class="btn btn-danger cancel-button text-center">🗑️</button>
                    <button type="button" class="btn-send send-button text-center">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <audio id="audioPlayer" controls style="display: none;"></audio>
                <button type="button" id="voice-button" class="common-button btn-send">
                    <i class="fas fa-microphone"></i>
                </button>
                <button type="submit" class="btn-send seccion-texto d-none" id="submit-button" title="Enviar mensaje">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </form>
@else
    <div class="text-center bg-white" style="height: 3rem;">
        <h1 style="margin-top: 1rem;">Sin conversaciones activas</h1>
    </div>
@endif
