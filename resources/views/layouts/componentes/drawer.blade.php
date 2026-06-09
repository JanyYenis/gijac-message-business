<!--begin::View component-->
<div id="kt_drawer_example_basic" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#kt_drawer_example_basic_button" data-kt-drawer-close="#kt_drawer_example_basic_close"
    data-kt-drawer-width="500px">
    <div class="card rounded-0 w-100">
        <!--begin::Card header-->
        <div class="card-header pe-5">
            <!--begin::Title-->
            <div class="card-title">
                <!--begin::User-->
                <div class="d-flex justify-content-center flex-column me-3">
                    <a href="#" class="fs-4 fw-bold text-gijac me-1 lh-1">@yield('titulo_drawer')</a>
                </div>
                <!--end::User-->
            </div>
            <!--end::Title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-light-primary" data-kt-drawer-dismiss="true" id="kt_drawer_example_dismiss_close">
                    <i class="las la-times fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
                <!--end::Close-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body hover-scroll-overlay-y">
            @yield('contenido_drawer')
        </div>
        <!--end::Card body-->

        <!--begin::Card footer-->
        <div class="card-footer">
            <!--begin::Dismiss button-->
            <button class="btn btn-light-danger" data-kt-drawer-dismiss="true">Cerrar</button>
            <!--end::Dismiss button-->
        </div>
        <!--end::Card footer-->
    </div>
</div>