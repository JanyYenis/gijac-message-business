@foreach ($usuarios as $item)
    <div class="contact-item btnIrChat" data-contacto="{{$item?->numero_completo}}">
        <div class="contact-avatar-wrapper">
            <div class="contact-avatar">
                <img class="profile-image" src="{{asset('img/user.png')}}" alt="{{$item?->nombre_completo ?? 'Sin Nombre'}}">
            </div>
        </div>
        <div class="contact-info">
            <div class="contact-name">{{$item?->nombre_completo ?? $item?->numero_completo}}</div>
            <div class="contact-last-message">
                @if ($item?->tipo_mensaje == \App\Models\Mensaje::IMAGEN)
                    <i class="la la-image fs-2"></i>
                @elseif ($item?->tipo_mensaje == \App\Models\Mensaje::VIDEO)
                    <i class="la la-video fs-2"></i>
                @elseif ($item?->tipo_mensaje == \App\Models\Mensaje::AUDIO)
                    <i class="la la-microphone fs-2"></i>
                @elseif ($item?->tipo_mensaje == \App\Models\Mensaje::DOCUMENTO)
                    <i class="las la-file-alt fs-2"></i>
                @endif
                @if ($item?->mensaje && $item?->tipo_mensaje != \App\Models\Mensaje::AUDIO && $item?->tipo_mensaje != \App\Models\Mensaje::FLOWS)
                    {{$item?->mensaje ?? ''}}
                @else
                    @if ($item?->tipo_mensaje == \App\Models\Mensaje::IMAGEN)
                        Imagen
                    @elseif ($item?->tipo_mensaje == \App\Models\Mensaje::VIDEO)
                        Video
                    @elseif ($item?->tipo_mensaje == \App\Models\Mensaje::AUDIO)
                        Audio
                    @elseif ($item?->tipo_mensaje == \App\Models\Mensaje::DOCUMENTO)
                        Documento
                    @elseif ($item?->tipo_mensaje == \App\Models\Mensaje::FLOWS)
                        Respuesta Formulario
                    @endif
                @endif
            </div>
        </div>
        <div class="d-flex flex-column align-items-end gap-1">
            <div class="contact-time">{{ $item?->fecha ? diffDate($item?->fecha) : '' }}</div>
            @if ($item?->mensajes_no_leidos)
                <span class="unread-badge">{{$item?->mensajes_no_leidos ? $item?->mensajes_no_leidos : ''}}</span>
            @endif
        </div>
    </div>
@endforeach
