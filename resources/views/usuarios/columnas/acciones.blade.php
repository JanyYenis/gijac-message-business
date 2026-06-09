<div>
    <button type="button" class="btn btn-bg-secondary rotate" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="30px, 30px">
        <i class="fas fa-ellipsis-h"></i>
    </button>

    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px btnAccionesUsuarios" data-kt-menu="true">
        <div class="menu-item px-3">
            <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">
                Acciones
            </div>
        </div>
        <div class="separator mb-3 opacity-75"></div>
        @if ($puedeAgregarRol)
            <div class="menu-item px-3">
                <a href="javascript:;" class="menu-link fs-5 px-3 btnRolesPermisos" data-usuario="{{$model->uuid}}">
                    <i class="fas fa-user-lock text-gray fs-4 m-2"></i>
                    Roles y Permisos
                </a>
            </div>
        @endif

        @if ($puedeEditar)
            <div class="menu-item px-3">
                <a href="javascript:;" class="menu-link fs-5 px-3 btnEditar" data-usuario="{{$model->uuid}}">
                    <i class="fas fa-pencil-alt text-gray fs-4 m-2"></i>
                    Editar
                </a>
            </div>

            <div class="menu-item px-3">
                @if ($model->estado == 1)
                    <a href='javascript:;' class='menu-link fs-5 px-3 btnInactivar' data-usuario='{{$model->uuid}}'>
                        <i class='far fa-times-fas fa-clock text-gray fs-4 m-2'></i>
                        Inactivar
                    </a>
                @else
                    <a href='javascript:;' class='menu-link fs-5 px-3 btnActivar' data-usuario='{{$model->uuid}}'>
                        <i class='fas fa-check-circle text-gray fs-4 m-2'></i>
                        Activar
                    </a>
                @endif
            </div>
        @endif

        @if ($puedeEliminar)
            <div class="menu-item px-3">
                <a href="javascript:;" class="menu-link fs-5 px-3 btnEliminar" data-usuario="{{$model->uuid}}">
                    <i class="fas fa-trash text-gray fs-4 m-2"></i>
                    Eliminar
                </a>
            </div>
        @endif
    </div>
</div>
