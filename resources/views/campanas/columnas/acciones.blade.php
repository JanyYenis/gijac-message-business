@php
    $disabled = '';
    if (!$puede_eliminar && !$puede_editar && !$puede_crear && !$puede_listado) {
        $disabled = 'disabled';
    }
@endphp
<div>
    <button type="button" {{ $disabled }} class="btn btn-bg-secondary rotate btnAccionesCampana" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="30px, 30px">
        <i class="fas fa-ellipsis-h"></i>
    </button>

    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
        @if ($puede_eliminar || $puede_editar || $puede_crear || $puede_listado)
            <div class="menu-item px-3">
                <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">
                    Acciones
                </div>
            </div>
            <div class="separator mb-3 opacity-75"></div>
            <div class="menu-item px-3">
                <a href="{{ route('campanas.show', ['campana' => $model->id]) }}" class="menu-link fs-5 px-3">
                    <i class="far fa-eye text-info fs-3 m-2"></i>
                    Ver Reporte
                </a>
            </div>
            <div class="menu-item px-3">
                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#modalDetalleCampana" class="menu-link fs-5 px-3 btnDetalleEnvio" data-campana="{{$model->id}}">
                    <i class="fab fa-whatsapp text-primary fs-3 m-2"></i>
                    Enviados
                </a>
            </div>

            @if ($puede_editar)
                @if (!$estado_eliminado && !$estado_enviado)
                    <div class="menu-item px-3">
                        <a href="javascript:;" class="menu-link fs-5 px-3 btnEditar" data-campana="{{$model->id}}">
                            <i class="fas fa-pencil-alt text-warning fs-3 m-2"></i>
                            Editar
                        </a>
                    </div>
                @endif
            @endif

            @if ($puede_crear)
                <div class="menu-item px-3">
                    <a href="javascript:;" class="menu-link fs-5 px-3 btnReenviar" data-campana="{{$model->id}}">
                        <i class="fas fa-undo text-success fs-3 m-2"></i>
                        Reenviar
                    </a>
                </div>
            @endif

            @if ($puede_eliminar)
                @if (!$estado_eliminado && !$estado_enviado)
                    <div class="menu-item px-3">
                        <a href="javascript:;" class="menu-link fs-5 px-3 btnEliminar" data-campana="{{$model->id}}">
                            <i class="fas fa-trash text-danger fs-3 m-2"></i>
                            Eliminar
                        </a>
                    </div>
                @endif
            @endif
        @else

        @endif
    </div>
</div>
