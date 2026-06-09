@if ($model->estado == 1)
    <div class="text-lg-center">
        <span class="badge badge-light-success py-5 px-5">
            <i class="fas fa-check-circle text-success"></i>&nbsp;
            Activo
        </span>
    </div>
@else
    <div class="text-lg-center">
        <span class="badge badge-light-danger py-5 px-5">
            <i class="fas fa-check-circle text-danger"></i>&nbsp;
            Inactivo
        </span>
    </div>
@endif
