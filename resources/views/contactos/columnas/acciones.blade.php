<div>
    <button type="button" class="btn btn-bg-secondary rotate btnAcciones" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="30px, 30px">
        <i class="fas fa-ellipsis-h"></i>
    </button>

    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
        <div class="menu-item px-3">
            <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">
                Acciones
            </div>
        </div>
        <div class="separator mb-3 opacity-75"></div>
        {{-- <div class="menu-item px-3">
            <a href="javascript:;" class="menu-link fs-5 px-3 btnVer" data-contacto="{{$model->id}}">
                <i class="far fa-eye text-info fs-4 m-2"></i>
                Ver información
            </a>
        </div> --}}
        <div class="menu-item px-3">
            <a href="{{ route('contactos.showInfo', ['contacto' => $model->id]) }}" class="menu-link fs-5 px-3">
                <i class="far fa-eye text-info fs-4 m-2"></i>
                Ver información
            </a>
        </div>
        <div class="menu-item px-3">
            <a href="javascript:;" class="menu-link fs-5 px-3 editarContacto" data-contacto="{{$model->id}}">
                <i class="fas fa-user-edit text-warning fs-4 m-2"></i>
                Editar
            </a>
        </div>
        <div class="menu-item px-3">
            @if ($model->estado == 1)
                <a href='javascript:;' class='menu-link fs-5 px-3 btnInactivar' data-contacto='{{$model->id}}'>
                    <i class='far fa-times-fas fa-clock text-danger fs-4 m-2'></i>
                    Inactivar
                </a>
            @else
                <a href='javascript:;' class='menu-link fs-5 px-3 btnActivar' data-contacto='{{$model->id}}'>
                    <i class='fas fa-check-circle text-success fs-4 m-2'></i>
                    Activar
                </a>
            @endif
        </div>
        <div class="menu-item px-3">
            <a href="javascript:;" class="menu-link fs-5 px-3 eliminarContacto" data-contacto="{{$model->id}}">
                <i class="fas fa-trash text-danger fs-4 m-2"></i>
                Eliminar
            </a>
        </div>
    </div>
</div>
