<div>
    <button type="button" class="btn btn-bg-secondary rotate btnAccionesCampana" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="30px, 30px">
        <i class="fas fa-ellipsis-h"></i>
    </button>

    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
        <div class="menu-item px-3">
            <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">
                Acciones
            </div>
        </div>
        <div class="separator mb-3 opacity-75"></div>
        @if ($formulario || $error || $links)
            @if ($formulario)
                <div class="menu-item px-3">
                    <a href="javascript:;" class="menu-link fs-5 px-3 btnVerFormulario" data-mensaje="{{$model->wamid}}">
                        <i class="far fa-eye text-info fs-3 m-2"></i>
                        Ver Respuesta
                    </a>
                </div>
            @endif
            @if ($error)
                <div class="menu-item px-3">
                    <a href="javascript:;" class="menu-link fs-5 px-3 btnVerError" data-mensaje="{{$model->wamid}}">
                        <i class="las la-exclamation text-danger fs-3 m-2"></i>
                        Ver Error
                    </a>
                </div>
            @endif
            @if ($links)
                <div class="menu-item px-3">
                    <a href="javascript:;" class="menu-link fs-5 px-3 btnVerLinks" data-mensaje="{{$model->id}}">
                        <i class="las la-link text-info fs-3 m-2"></i>
                        Ver Links
                    </a>
                </div>
            @endif
        @else
            <h1>Sin acciones</h1>
        @endif
    </div>
</div>
