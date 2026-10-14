@php
    $puederVer = $puederVer ?? false;
    $esAdmin = $esAdmin ?? false;
    $puedeSolicitarFe = $puedeSolicitarFe ?? false;
@endphp
<div>
    <button type="button" class="btn btn-bg-secondary rotate" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="30px, 30px">
        <i class="fas fa-ellipsis-h"></i>
    </button>

    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
        <div class="menu-item px-3">
            <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">
                {{ __('Acciones') }}
            </div>
        </div>
        <div class="separator mb-3 opacity-75"></div>
        @if ($puederVer)
            <div class="menu-item px-3">
                <a href="{{ route('facturas.ver', ['factura' => $model->invoice]) }}" target="_blank" class="menu-link fs-5 px-3">
                    <i class="fas fa-eye text-info fs-4 m-2"></i>
                    {{ __('Ver comprobante') }}
                </a>
            </div>
        @endif
        @if ($puedeSolicitarFe)
            <div class="menu-item px-3">
                <a href="javascript:;" class="menu-link fs-5 px-3 btnSolicitarFe" data-invoice="{{ $model->invoice }}">
                    <i class="fas fa-file-invoice text-primary fs-4 m-2"></i>
                    {{ __('Solicitar factura electrónica') }}
                </a>
            </div>
        @endif
        @if ($esAdmin && $model->x_response === 'Aceptada' && ($model->estado_fe ?? 1) !== 3)
            <div class="menu-item px-3">
                <a href="javascript:;" class="menu-link fs-5 px-3 btnRegistrarFe" data-invoice="{{ $model->invoice }}" data-obs="{{ $model->fe_observaciones }}">
                    <i class="fas fa-file-circle-check text-success fs-4 m-2"></i>
                    {{ __('Registrar factura electrónica') }}
                </a>
            </div>
        @endif
    </div>
</div>
