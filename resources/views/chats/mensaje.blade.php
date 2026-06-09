@foreach ($mensajesAgrupados as $index => $grupos)
    <li class="common-message is-time">
        <p class="common-message-content">
            {{ $index }}
        </p>
    </li>
    @foreach ($grupos as $mensaje)
        {{-- Mensajes del contacto --}}
        @if ($contacto?->numero_completo == $mensaje['wa_from'])
            {{-- AUDIO --}}
            @if ($mensaje['type'] == \App\Models\Mensaje::AUDIO)
                <div class="audio-player received" data-audio-id="{{ $mensaje['wa_message_id'] }}">
                    <button class="play-button" aria-label="Reproducir audio">
                        <i class="fas fa-play"></i>
                    </button>
                    <div class="progress-container">
                        <div class="waveform-container">
                            <div class="waveform">
                                <div class="waveform-bar" style="height: 40%"></div>
                                <div class="waveform-bar" style="height: 70%"></div>
                                <div class="waveform-bar" style="height: 50%"></div>
                                <div class="waveform-bar" style="height: 90%"></div>
                                <div class="waveform-bar" style="height: 60%"></div>
                                <div class="waveform-bar" style="height: 80%"></div>
                                <div class="waveform-bar" style="height: 45%"></div>
                                <div class="waveform-bar" style="height: 75%"></div>
                                <div class="waveform-bar" style="height: 55%"></div>
                                <div class="waveform-bar" style="height: 85%"></div>
                                <div class="waveform-bar" style="height: 50%"></div>
                                <div class="waveform-bar" style="height: 70%"></div>
                                <div class="waveform-bar" style="height: 40%"></div>
                                <div class="waveform-bar" style="height: 65%"></div>
                                <div class="waveform-bar" style="height: 55%"></div>
                                <div class="waveform-bar" style="height: 75%"></div>
                                <div class="waveform-bar" style="height: 45%"></div>
                                <div class="waveform-bar" style="height: 80%"></div>
                                <div class="waveform-bar" style="height: 60%"></div>
                                <div class="waveform-bar" style="height: 50%"></div>
                            </div>
                        </div>
                        <div class="time-display">
                            <span class="time-current">0:00</span>
                            <span class="time-total">0:00</span>
                        </div>
                    </div>
                    <div class="audio-icon">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <audio preload="metadata">
                        <source src="{{ $mensaje['body'] }}" type="audio/mpeg">
                    </audio>
                    <time datetime style="position: absolute; margin-top: 60px;">{{ $mensaje['created_at'] }}</time>
                </div>
            @else
                <li class="common-message is-other">
                    {{-- IMAGEN --}}
                    @if ($mensaje['type'] == \App\Models\Mensaje::IMAGEN)
                        @if (!empty($mensaje['metadata']['image']['id']))
                            <img src="{{ asset('img/chat/' . $mensaje['metadata']['image']['id'] . '.jpg') }}"
                                alt="{{ $mensaje['metadata']['image']['caption'] ?? 'imagen' }}"
                                style="border-radius: 1rem;" class="mb-2">
                        @else
                            <img src="{{ $mensaje['body'] }}"
                                alt="imagen"
                                style="border-radius: 1rem;" class="mb-2">
                        @endif

                    {{-- VIDEO --}}
                    @elseif ($mensaje['type'] == \App\Models\Mensaje::VIDEO)
                        @if (!empty($mensaje['metadata']['video']['id']))
                            <video src="{{ asset('videos/chat/' . $mensaje['metadata']['video']['id'] . '.mp4') }}"
                                controls style="border-radius: 1rem; width: 100%;" class="mb-2"></video>
                        @else
                            <video src="{{ $mensaje['body'] }}" controls style="border-radius: 1rem; width: 100%;"
                                class="mb-2"></video>
                        @endif

                    {{-- DOCUMENTO --}}
                    @elseif ($mensaje['type'] == \App\Models\Mensaje::DOCUMENTO)
                        @php
                            $url = $mensaje['metadata']['document']['id'] ?? $mensaje['body'];
                            $array = explode('/', $mensaje['metadata']['document']['filename']);
                            $nombre = $array[count($array) - 1];
                        @endphp
                        <div style="background-color: #e3e3e3; padding: 1rem; margin-bottom: 1rem; border-radius: 0.2rem;">
                            <a href="{{ asset('documentos/chat/'.$nombre) }}" target="_blank" class="text-dark fs-3">
                                @if (str_contains($nombre, '.xlsx') || str_contains($nombre, '.xls'))
                                    <i class="fs-1 far fa-file-excel text-primary"></i>
                                @elseif (str_contains($nombre, '.doc') || str_contains($nombre, '.docx'))
                                    <i class="fs-1 far fa-file-word text-success"></i>
                                @elseif (str_contains($nombre, '.pptx') || str_contains($nombre, '.ppt'))
                                    <i class="fs-1 far fa-file-powerpoint text-warning"></i>
                                @elseif (str_contains($nombre, '.pdf'))
                                    <i class="fs-1 far fa-file-pdf text-danger"></i>
                                @elseif (str_contains($nombre, '.zip') || str_contains($nombre, '.rar'))
                                    <i class="fs-1 far fa-file-archive text-info"></i>
                                @else
                                    <i class="fs-1 far fa-file-alt text-info"></i>
                                @endif
                                {{ $nombre }}
                            </a>
                        </div>
                    @endif

                    {{-- TEXTO / FLOWS --}}
                    @if ($mensaje['body'])
                        @if ($mensaje['type'] == \App\Models\Mensaje::FLOWS)
                            <div class="p-2">
                                <a href="javascript:;" style="background-color: #e3e3e3; padding: 1rem;
                                    margin-bottom: 2rem; border-radius: 0.2rem; margin-top: 2rem;"
                                    class="text-dark fs-3 btnVerFormulario" data-mensaje="{{ $mensaje['wa_message_id'] }}">
                                    <i class="fs-1 las la-file-alt text-primary"></i>
                                    Ver Respuesta Formulario
                                </a>
                            </div>
                        @else
                            <p class="common-message-content">{{ $mensaje['body'] }}</p>
                        @endif
                    @endif

                    <time datetime>{{ $mensaje['created_at'] }}</time>
                </li>
            @endif

        {{-- Mensajes enviados por mí --}}
        @else
            {{-- AUDIO --}}
            @if ($mensaje['type'] == \App\Models\Mensaje::AUDIO)
                <div class="audio-player sent" data-audio-id="{{ $mensaje['wa_message_id'] }}">
                    @if ($mensaje['estado'] == \App\Models\Mensaje::FALLIDO)
                        {{-- BOTÓN DE OPCIONES --}}
                        <button type="button" style="position: relative; left: 20rem; top: -19px;"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="-80px, 10px">
                            <i class="las la-angle-down"></i>
                        </button>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                            <div class="menu-item px-3">
                                <a href="javascript:;" class="menu-link px-3 btnVerError" data-mensaje="{{ $mensaje['wa_message_id'] }}">
                                    <i class="las la-exclamation text-danger me-1"></i>
                                    Ver Error
                                </a>
                            </div>
                        </div>
                    @endif
                    <button class="play-button" aria-label="Reproducir audio">
                        <i class="fas fa-play"></i>
                    </button>
                    <div class="progress-container">
                        <div class="waveform-container">
                            <div class="waveform">
                                <div class="waveform-bar" style="height: 50%"></div>
                                <div class="waveform-bar" style="height: 75%"></div>
                                <div class="waveform-bar" style="height: 60%"></div>
                                <div class="waveform-bar" style="height: 85%"></div>
                                <div class="waveform-bar" style="height: 55%"></div>
                                <div class="waveform-bar" style="height: 90%"></div>
                                <div class="waveform-bar" style="height: 50%"></div>
                                <div class="waveform-bar" style="height: 70%"></div>
                                <div class="waveform-bar" style="height: 65%"></div>
                                <div class="waveform-bar" style="height: 80%"></div>
                                <div class="waveform-bar" style="height: 55%"></div>
                                <div class="waveform-bar" style="height: 75%"></div>
                                <div class="waveform-bar" style="height: 45%"></div>
                                <div class="waveform-bar" style="height: 70%"></div>
                                <div class="waveform-bar" style="height: 60%"></div>
                                <div class="waveform-bar" style="height: 85%"></div>
                                <div class="waveform-bar" style="height: 50%"></div>
                                <div class="waveform-bar" style="height: 75%"></div>
                                <div class="waveform-bar" style="height: 55%"></div>
                                <div class="waveform-bar" style="height: 65%"></div>
                            </div>
                        </div>
                        <div class="time-display">
                            <span class="time-current">0:00</span>
                            <span class="time-total">0:00</span>
                        </div>
                    </div>
                    <div class="audio-icon">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <audio preload="metadata">
                        <source src="{{ $mensaje['body'] }}" type="audio/mpeg">
                    </audio>

                    <div class="" style="margin-top: 4rem; position: absolute; right: 19px;">
                        {{-- ESTADOS (numéricos) --}}
                        @if ($mensaje['estado'] == \App\Models\Mensaje::ENVIADO)
                            <i class="la la-check status" style="right: 2px; position: absolute;"></i>
                        @elseif ($mensaje['estado'] == \App\Models\Mensaje::ENTREGADO)
                            <i class="la la-check-double fs-3 status" style="right: 2px; position: absolute;"></i>
                        @elseif ($mensaje['estado'] == \App\Models\Mensaje::LEIDO)
                            <i class="la la-check-double text-success status" style="right: 2px; position: absolute;"></i>
                        @elseif ($mensaje['estado'] == \App\Models\Mensaje::FALLIDO)
                            <i class="la la-remove text-danger status" style="right: 2px; position: absolute;"></i>
                        @endif

                        <time datetime>{{ $mensaje['created_at'] }}</time>
                    </div>
                </div>
            @else
                <li class="common-message is-you">
                    @if ($mensaje['estado'] == \App\Models\Mensaje::FALLIDO)
                        {{-- BOTÓN DE OPCIONES --}}
                        <button type="button" style="position: relative; left: 10rem;"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="10px, 10px">
                            <i class="las la-angle-down"></i>
                        </button>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                            <div class="menu-item px-3">
                                <a href="javascript:;" class="menu-link px-3 btnVerError" data-mensaje="{{ $mensaje['wa_message_id'] }}">
                                    <i class="las la-exclamation text-danger me-1"></i>
                                    Ver Error
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- MEDIOS --}}
                    @if ($mensaje['type'] == \App\Models\Mensaje::IMAGEN || ($mensaje['type'] == \App\Models\Mensaje::PLANTILLA && $mensaje['metadata']['tipo_header'] == \App\Models\Mensaje::IMAGEN))
                        @if (!empty($mensaje['metadata']['image']['id']))
                            <img src="{{ asset('img/chat/' . $mensaje['metadata']['image']['id'] . '.jpg') }}"
                                alt="{{ $mensaje['metadata']['image']['caption'] ?? 'imagen' }}"
                                style="border-radius: 1rem;" class="mb-2">
                        @elseif (!empty($mensaje['metadata']['header']))
                            <img src="{{ $mensaje['metadata']['header'] }}"
                                alt="imagen" style="border-radius: 1rem;" class="mb-2">
                        @else
                            <img src="{{ $mensaje['body'] }}"
                                alt="imagen"
                                style="border-radius: 1rem;" class="mb-2">
                        @endif
                    @elseif ($mensaje['type'] == \App\Models\Mensaje::VIDEO || ($mensaje['type'] == \App\Models\Mensaje::PLANTILLA && $mensaje['metadata']['tipo_header'] == \App\Models\Mensaje::VIDEO))
                        @if (!empty($mensaje['metadata']['video']['id']))
                            <video src="{{ asset('videos/chat/' . $mensaje['metadata']['video']['id'] . '.mp4') }}"
                                controls style="border-radius: 1rem; width: 100%;" class="mb-2"></video>
                        @elseif (!empty($mensaje['metadata']['header']))
                            <video src="{{ $mensaje['metadata']['header'] }}"
                                controls style="border-radius: 1rem; width: 100%;" class="mb-2"></video>
                        @else
                            <video src="{{ $mensaje['body'] }}" controls style="border-radius: 1rem; width: 100%;"
                                class="mb-2"></video>
                        @endif
                    @elseif ($mensaje['type'] == \App\Models\Mensaje::DOCUMENTO || ($mensaje['type'] == \App\Models\Mensaje::PLANTILLA && $mensaje['metadata']['tipo_header'] == \App\Models\Mensaje::DOCUMENTO))
                        @php
                            $url = '';
                        @endphp
                        @if (!empty($mensaje['metadata']['header']))
                            @php
                                $url = $mensaje['metadata']['header'];
                                $array = explode('/', $url);
                                $nombre = $array[count($array) - 1];
                            @endphp
                        @else
                            @php
                                $url = $mensaje['metadata']['document']['id'] ?? $mensaje['body'];
                                $array = explode('/', $mensaje['metadata']['document']['filename']);
                                $nombre = $array[count($array) - 1];
                                $url = asset('documentos/chat/'.$nombre);
                            @endphp
                        @endif
                        <div style="background-color: #e3e3e3; padding: 1rem; margin-bottom: 1rem; border-radius: 0.2rem;">
                            <a href="{{ $url }}" target="_blank" class="text-dark fs-3">
                                @if (str_contains($nombre, '.xlsx') || str_contains($nombre, '.xls'))
                                    <i class="fs-1 far fa-file-excel text-primary"></i>
                                @elseif (str_contains($nombre, '.doc') || str_contains($nombre, '.docx'))
                                    <i class="fs-1 far fa-file-word text-success"></i>
                                @elseif (str_contains($nombre, '.pptx') || str_contains($nombre, '.ppt'))
                                    <i class="fs-1 far fa-file-powerpoint text-warning"></i>
                                @elseif (str_contains($nombre, '.pdf'))
                                    <i class="fs-1 far fa-file-pdf text-danger"></i>
                                @elseif (str_contains($nombre, '.zip') || str_contains($nombre, '.rar'))
                                    <i class="fs-1 far fa-file-archive text-info"></i>
                                @else
                                    <i class="fs-1 far fa-file-alt text-info"></i>
                                @endif
                                {{ $nombre }}
                            </a>
                        </div>
                    @endif

                    {{-- TEXTO --}}
                    @if ($mensaje['body'])
                        <p class="common-message-content">{{ $mensaje['body'] }}</p>
                    @endif

                    @if (isset($mensaje['metadata']['buttons']))
                        @php
                            $botones = json_decode($mensaje['metadata']['buttons'], true) ?? [];
                        @endphp
                        <div class="template-buttons mt-2">
                            @foreach ($botones as $item)
                                @php
                                    $buttonClass = $item['type'] === 'PHONE_NUMBER' ? 'call-button' : '';
                                @endphp
                                <button type="button" class="template-button fs-7 text-center {{ $buttonClass }}">
                                    @if($item['type'] === 'PHONE_NUMBER')
                                        <i class="fas fa-phone me-1"></i>
                                    @else
                                        <i class="fas fa-external-link-alt me-1"></i>
                                    @endif
                                    {{ $item['text'] }}
                                </button>
                            @endforeach
                        </div>
                    @endif

                    {{-- ESTADOS (numéricos) --}}
                    @if ($mensaje['estado'] == \App\Models\Mensaje::ENVIADO)
                        <i class="la la-check status"></i>
                    @elseif ($mensaje['estado'] == \App\Models\Mensaje::ENTREGADO)
                        <i class="la la-check-double fs-3 status"></i>
                    @elseif ($mensaje['estado'] == \App\Models\Mensaje::LEIDO)
                        <i class="la la-check-double text-success status"></i>
                    @elseif ($mensaje['estado'] == \App\Models\Mensaje::FALLIDO)
                        <i class="la la-remove text-danger status"></i>
                    @endif

                    <time datetime>{{ $mensaje['created_at'] }}</time>
                </li>
            @endif
        @endif
    @endforeach
@endforeach
