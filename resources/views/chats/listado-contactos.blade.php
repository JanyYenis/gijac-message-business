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
                @if ($item?->conversacion?->tipo_ultimo_mensaje == \App\Models\Mensaje::IMAGEN)
                    <i class="la la-image fs-2"></i>
                @elseif ($item?->conversacion?->tipo_ultimo_mensaje == \App\Models\Mensaje::VIDEO)
                    <i class="la la-video fs-2"></i>
                @elseif ($item?->conversacion?->tipo_ultimo_mensaje == \App\Models\Mensaje::AUDIO)
                    <i class="la la-microphone fs-2"></i>
                @elseif ($item?->conversacion?->tipo_ultimo_mensaje == \App\Models\Mensaje::DOCUMENTO)
                    <i class="las la-file-alt fs-2"></i>
                @endif
                @if ($item?->conversacion?->ultimo_mensaje && $item?->conversacion?->tipo_ultimo_mensaje != \App\Models\Mensaje::AUDIO && $item?->conversacion?->tipo_ultimo_mensaje != \App\Models\Mensaje::FLOWS)
                    {{$item?->conversacion?->ultimo_mensaje ?? ''}}
                @else
                    @if ($item?->conversacion?->tipo_ultimo_mensaje == \App\Models\Mensaje::IMAGEN)
                        Imagen
                    @elseif ($item?->conversacion?->tipo_ultimo_mensaje == \App\Models\Mensaje::VIDEO)
                        Video
                    @elseif ($item?->conversacion?->tipo_ultimo_mensaje == \App\Models\Mensaje::AUDIO)
                        Audio
                    @elseif ($item?->conversacion?->tipo_ultimo_mensaje == \App\Models\Mensaje::DOCUMENTO)
                        Documento
                    @elseif ($item?->conversacion?->tipo_ultimo_mensaje == \App\Models\Mensaje::FLOWS)
                        Respuesta Formulario
                    @endif
                @endif
            </div>
        </div>
        <div class="d-flex flex-column align-items-end gap-1">
            <div class="contact-time">{{ $item?->conversacion?->ultima_fecha ? diffDate($item?->conversacion?->ultima_fecha) : '' }}</div>
            @if ($item?->conversacion?->mensajes_no_leidos)
                <span class="unread-badge">{{$item?->conversacion?->mensajes_no_leidos ? $item?->conversacion?->mensajes_no_leidos : ''}}</span>
            @endif
        </div>
    </div>
@endforeach
