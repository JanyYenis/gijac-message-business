<div class="grid">
     @if (count($campanas))
        @foreach ($campanas as $campana)
            <article class="c-card in" data-card="c4">
                <div class="c-head">
                    <span class="badge-status badge-light-{{$campana?->infoEstado?->color}}">
                        <span class="dot"></span>
                        {{( __($campana->infoEstado?->nombre) )}}
                    </span>
                    <span class="type-pill">
                        <i class="{{ $campana?->infoTipo?->icono ?? 'fa-solid fa-align-left' }}"></i>
                        {{ __($campana->infoTipo->nombre) }}
                    </span>
                    <div class="ms-auto">
                        @component('campanas.columnas.acciones')
                            @slot('model', $campana)
                            @slot('estado_eliminado', $campana?->estado == 0)
                            @slot('estado_enviado', $campana?->estado == 1)
                            @slot('puede_listado', $puede_listado)
                            @slot('puede_crear', $puede_crear)
                            @slot('puede_editar', $puede_editar)
                            @slot('puede_eliminar', $puede_eliminar)
                        @endcomponent
                    </div>
                </div>
                <h3 class="c-title">{{ $campana->nombre }}</h3>
                <div class="c-sub">
                    <span>
                        <i class="fa-regular fa-user me-1"></i>
                        {{ $campana?->descripcion ?? 'N/A' }}
                    </span>
                </div>
                <div class="wa-preview">
                    <div class="bubble">
                        @if ($campana?->plantilla?->header)
                            @if ($campana?->plantilla?->header->format == \App\Models\Mensaje::IMAGEN)
                                <div class="media">
                                    <img loading="lazy" src="{{ $campana->contenido_multimedia }}" alt="Vista previa de Campaña imagen · C6">
                                    <span class="media-tag">
                                        <i class="fa-solid fa-image"></i>
                                        Imagen
                                    </span>
                                </div>
                            @elseif ($campana?->plantilla?->header->format == \App\Models\Mensaje::VIDEO)
                                <div class="media">
                                    <video src="{{ $campana->contenido_multimedia }}" controls style="border-radius: 1rem; width: 100%;"
                                        class="mb-2"></video>
                                    <span class="media-tag">
                                        <i class="fa-solid fa-video"></i>
                                        Video
                                    </span>
                                </div>
                            @elseif ($campana?->plantilla?->header->format == \App\Models\Mensaje::DOCUMENTO)
                                @php
                                    $nombre = basename($campana->contenido_multimedia);
                                    $extension = pathinfo($campana->contenido_multimedia, PATHINFO_EXTENSION);
                                    $nombreSinExtension = pathinfo($campana->contenido_multimedia, PATHINFO_FILENAME);
                                    $headers = get_headers($campana->contenido_multimedia, true);
                                    $tamano = $headers['Content-Length'] ?? 0;
                                    $tamanoMB = round($tamano / 1024 / 1024, 2);
                                @endphp
                                <div class="doc-row">
                                    <div class="ic">
                                        <i class="fa-regular fa-file-pdf"></i>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fw-semibold text-truncate" style="font-size:12.5px">
                                            {{ $nombre }}
                                        </div>
                                        <div class="muted" style="font-size:11px">
                                            {{ $extension }} · {{ $tamanoMB }} MB
                                        </div>
                                    </div>
                                    <a href="{{ $campana->contenido_multimedia }}">
                                        <i class="fa-solid fa-download" style="color:#8696A0"></i>
                                    </a>
                                </div>
                            @endif
                        @endif
                        <div class="txt">
                            <span class="clamp" data-clamp="">{{ $campana?->contenido ?? '' }}</span>
                        </div>
                        <div class="meta">
                            {{ $campana?->fecha_envio->translatedFormat('h:i a') }}
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        @if ($campana?->plantilla?->buttons)
                            <div class="wa-btns">
                                @foreach (json_decode($campana?->plantilla?->buttons->buttons) as $boton)
                                    <div class="wa-btn">
                                        @php
                                            $icono = 'fa-solid fa-reply';
                                            if ($boton?->type == 'PHONE_NUMBER') {
                                                $icono = 'fas fa-phone';
                                            } elseif ($boton?->type == 'URL') {
                                                $icono = 'fa-solid fa-arrow-up-right-from-square';
                                            }
                                        @endphp
                                        <i class="{{ $icono }}"></i>
                                        {{ $boton?->text ?? 'N/A' }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bar">
                    <span class="b1" style="width:{{ (count($campana?->mensajesAbiertos) && count($campana?->enviosActivos)) && (count($campana?->mensajesAbiertos) / count($campana?->enviosActivos) * 100) }}%"></span>
                </div>
                <div class="metrics">
                    <div class="metric">
                        <div class="v">{{ count($campana?->enviosActivos) ?? 0 }}</div>
                        <div class="l">Enviados</div>
                    </div>
                    <div class="metric">
                        <div class="v">{{ count($campana?->mensajesAbiertos) ?? 0 }}</div>
                        <div class="l">Entreg.</div>
                    </div>
                    <div class="metric">
                        <div class="v">{{ count($campana?->mensajesAbiertos) ?? 0 }}</div>
                        <div class="l">Leídos</div>
                    </div>
                    <div class="metric fail">
                        <div class="v">0</div>
                        <div class="l">Fallidos</div>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="dates">
                    <div class="date-item">
                        <div class="l">Creada</div>
                        <div class="v">{{ $campana?->created_at->translatedFormat('d M Y, h:i') }}</div>
                    </div>
                    <div class="date-item">
                        <div class="l">Envío</div>
                        <div class="v">{{ $campana?->fecha_envio->translatedFormat('d M Y, h:i') }}</div>
                    </div>
                </div>
            </article>
        @endforeach
    @else
        <div class="text-center">
            <h1 class="text-gijac">{{ __('Sin resultados.') }}</h1>
        </div>
    @endif
</div>

@component('campanas.paginado')
    @slot('catidadDatos', $campanas)
    @slot('ultimaPagina', $ultimaPagina)
    @slot('paginaActual', $paginaActual)
@endcomponent
