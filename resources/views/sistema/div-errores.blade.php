@php
    $claseColumnas = isset($claseColumnas) ? $claseColumnas : 'col-12';
    $claseOcultarDiv = isset($claseOcultarDiv) ? $claseOcultarDiv : 'd-none';
    $validaciones = $validaciones ?? null;
    $claseFila = isset($claseFila) ? $claseFila : 'row';
@endphp
<div id="{{ $id ?? '' }}" class="form-group {{ $claseFila }} div-validacion {{ $claseOcultarDiv }}">
    <div class="{{ $claseColumnas }}">
        <!--begin::Alert-->
        <div class="alert alert-dismissible bg-light-danger border border-danger border-dotted d-flex flex-column flex-sm-row p-5 mb-10">
            <!--begin::Icon-->
            <i class="fas fa-exclamation-triangle fs-2hx text-danger me-4 mb-5 mb-sm-0">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
            <!--end::Icon-->

            <!--begin::Wrapper-->
            <div class="d-flex flex-column pe-0 pe-sm-10">
                <h5 class="mb-1 text-danger">Confirma la información en los siguientes campos:</h5>

                <!--begin::Content-->
                <ul class="mensaje-validacion text-danger {{ $clases ?? '' }}">
                    @if ($validaciones)
                        @foreach ($validaciones as $validacion)
                            <li>
                                {{ $validacion }}
                            </li>
                        @endforeach
                    @endif
                </ul>
                <!--end::Content-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Close-->
            {{-- <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                data-bs-dismiss="alert">
                <i class="las la-times fs-1 text-danger">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </button> --}}
            <!--end::Close-->
        </div>
        <!--end::Alert-->
    </div>
</div>