@foreach ($contactos as $item)
    <div class="contact-item btnIrChat" data-contacto="{{$item?->numero_completo}}">
        <div class="contact-avatar-wrapper">
            <div class="contact-avatar">
                <img class="profile-image" src="{{asset('img/user.png')}}" alt="{{$item?->nombre_completo ?? 'Sin Nombre'}}">
            </div>
        </div>
        <div class="contact-info">
            <div class="contact-name">{{$item?->nombre_completo ?? $item?->numero_completo}}</div>
            <div class="contact-last-message">
                @if ($item?->conversaccion?->tipo_ultimo_mensaje == \App\Models\Mensaje::IMAGEN)
                    <i class="la la-image fs-2"></i>
                @elseif ($item?->conversaccion?->tipo_ultimo_mensaje == \App\Models\Mensaje::VIDEO)
                    <i class="la la-video fs-2"></i>
                @elseif ($item?->conversaccion?->tipo_ultimo_mensaje == \App\Models\Mensaje::AUDIO)
                    <i class="la la-microphone fs-2"></i>
                @elseif ($item?->conversaccion?->tipo_ultimo_mensaje == \App\Models\Mensaje::DOCUMENTO)
                    <i class="las la-file-alt fs-2"></i>
                @endif
                @if ($item?->conversaccion?->ultimo_mensaje && $item?->conversaccion?->tipo_ultimo_mensaje != \App\Models\Mensaje::AUDIO && $item?->conversaccion?->tipo_ultimo_mensaje != \App\Models\Mensaje::FLOWS)
                    {{$item?->conversaccion?->ultimo_mensaje ?? ''}}
                @else
                    @if ($item?->conversaccion?->tipo_ultimo_mensaje == \App\Models\Mensaje::IMAGEN)
                        Imagen
                    @elseif ($item?->conversaccion?->tipo_ultimo_mensaje == \App\Models\Mensaje::VIDEO)
                        Video
                    @elseif ($item?->conversaccion?->tipo_ultimo_mensaje == \App\Models\Mensaje::AUDIO)
                        Audio
                    @elseif ($item?->conversaccion?->tipo_ultimo_mensaje == \App\Models\Mensaje::DOCUMENTO)
                        Documento
                    @elseif ($item?->conversaccion?->tipo_ultimo_mensaje == \App\Models\Mensaje::FLOWS)
                        Respuesta Formulario
                    @endif
                @endif
            </div>
        </div>
        <div class="d-flex flex-column align-items-end gap-1">
            <div class="contact-time">{{ $item?->conversaccion?->ultima_fecha ? diffDate($item?->conversaccion?->ultima_fecha) : '' }}</div>
            @if ($item?->conversaccion?->mensajes_no_leidos)
                <span class="unread-badge">{{$item?->conversaccion?->mensajes_no_leidos ? $item?->conversaccion?->mensajes_no_leidos : ''}}</span>
            @endif
        </div>
    </div>
@endforeach
