"use strict";

const tablaUsuarios = "#tablaUsuarios";
const formRoles = '#formRoles';
const formPermisos = '#formPermisos';

$(function () {
    iniciarComponentes();
    generalidades.validarFormulario(formRoles, enviarDatosRoles);
    generalidades.validarFormulario(formPermisos, enviarDatosPermisos);
});

const iniciarComponentes = (form = '') => {
    let configMultiSelect = {
        elemento: '#selectRoles',
        selectableHeaderText: "Roles Disponibles",
        selectionHeaderText: "Roles Asignados",
        selectableHeaderPlaceholder: "Escribe el nombre del rol",
        selectionHeaderPlaceholder: "Escribe el nombre del rol"
    }
    generalidades.multiSelect(configMultiSelect);
    let configMultiSelect1 = {
        elemento: '#selectPermisos',
        selectableHeaderText: "Permisos Disponibles",
        selectionHeaderText: "Permisos Asignados",
        selectableHeaderPlaceholder: "Escribe el nombre del permiso",
        selectionHeaderPlaceholder: "Escribe el nombre del permiso"
    }
    generalidades.multiSelect(configMultiSelect1);
}
/**
 * Función que permite cargar el listado.
 */
window.listadoUsuarios = (id = 0) => {
    if ($.fn.DataTable.isDataTable('#tablaUsuarios')) {
        $('#tablaUsuarios').DataTable().destroy();
    }

    var table = $("#tablaUsuarios").DataTable({
        paging: true,
        responsive: true,
        serverSide: false,
        scrollX: true,
        searchDelay: 500,
        
        ajax: {
            "url": route("usuarios.listado"),
            "type": "GET",                  
            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                generalidades.mostrarCargando(tablaUsuarios);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaUsuarios);
                return json.data
            },
        },
        buttons: [
            {
                extend: "excel",
                text: '<i class="fa fa-download"></i> Excel',
                className: "btn btn-light-primary",
                title: "Listado Usuarios.",
                exportOptions: {
                    columns: ":not(.excluir)"
                }
            },
            {
                text: '<i class="fa fa-sync-alt"></i> Actualizar',
                className: "btn btn-bg-secondary",
                action: function (e, dt, node, config) {
                    dt.ajax.reload(null, false);
                }
            }
        ],
        columnDefs: [
            {
                targets: "all",
                className: "text-center"
            },
            {
                targets: "none",
                className: "text-justify"
            }
        ],
        columns: [
            {
                render: function (data, type, full, meta) {
                    return meta.row + 1;
                }
            },
            {
                data: 'nombre_completo',
                name: 'nombre_completo',
                render: function (data, type, full, meta) {
                    return full?.nombre_completo ?? 'N/A';
                }
            },
            {
                data: 'identificacion',
                name: 'identificacion',
                render: function (data, type, full, meta) {
                    return full?.identificacion ?? 'N/A';
                }
            },
            {
                data: 'info_documento.nombre',
                name: 'info_documento.nombre',
                render: function (data, type, full, meta) {
                    return full?.info_documento?.nombre ?? 'N/A';
                }
            },
            {
                data: 'info_genero.nombre',
                name: 'info_genero.nombre',
                render: function (data, type, full, meta) {
                    return full?.info_genero?.nombre ?? 'N/A';
                }
            },
            {
                data: 'numero_completo',
                name: 'numero_completo',
                render: function (data, type, full, meta) {
                    return full?.numero_completo ?? 'N/A';
                }
            },
            {
                data: 'email',
                name: 'email',
                render: function (data, type, full, meta) {
                    return full?.email ?? 'N/A';
                }
            },
            {
                data: 'ciudad.nombre',
                name: 'ciudad.nombre',
                render: function (data, type, full, meta) {
                    return full?.ciudad?.nombre ?? 'N/A';
                }
            },
            {
                data: 'estado',
                name: 'estado',
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ],
        order: [
            [0, "asc"]
        ], 
        lengthMenu: [
            [15, 20, 50, 100, -1],
            [15, 20, 50, 100, "Todos"]
        ],
        pageLength: 15,
        dom: `<'row d-flex align-items-center justify-content-between'
                <'col-md-6 col-sm-6 col-lg-6 d-flex align-items-center justify-content-start'<''l><'w-100'f>>
                <'col-md-6 col-sm-6 col-lg-6 d-flex align-items-center justify-content-end' <''B>>
                >
            <'table-responsive'tr>
            <'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>`,
        initComplete: function () {},
        drawCallback: function(settings) {
            KTMenu.createInstances();
        }
    });
}

$(document).on('click', '.btnRolesPermisos', function(){
    let id = $(this).attr('data-usuario');
    roles(id);
    permisos(id);
    $('#idUsuario').val(id);
    generalidades.modalActual('#modalRolesPermisos');
    // $('#modalRolesPermisos').modal('show');
    $('#seccionTabRoles').trigger('click');
});

const roles = (id) => {
    const config = {
        "method": "POST",
        "body": {
            "usuario": id
        },
        "headers": {
            "Content-Type": generalidades.CONTENT_TYPE_JSON,
            "Accept": generalidades.CONTENT_TYPE_JSON
        }
    };

    const success = (response) => {
        if (response.estado === "success") {
            $("#selectRoles").multiSelect("deselect");
            $("#selectRoles option").remove();
            $("#selectRoles").multiSelect("refresh");
            let options = "";

            let seleccionados = [];
            // console.log(response);
            Object.entries(response.roles).forEach(([id, rol]) => {
                options = options + `<option value="${rol.id}" ${rol.seleccionado ? "selected" : ""}>${rol.text}</option>`;
                if (rol.seleccionado) {
                    seleccionados.push(rol.id);
                }
            });

            $("#selectRoles").append(options);
            $("#selectRoles").val(seleccionados);
            $("#selectRoles").multiSelect("refresh");
        }
        // generalidades.ocultarCargando("body");
    };
    const error = (response) => {
        generalidades.toastrGenerico(response.estado, response.mensaje);
        // generalidades.ocultarCargando("body");
    }

    // generalidades.mostrarCargando("body");
    generalidades.post(route('roles.buscarRol'), config, success, error);
};

const permisos = (id) => {
    const config = {
        "method": "POST",
        "body": {
            "usuario": id
        },
        "headers": {
            "Content-Type": generalidades.CONTENT_TYPE_JSON,
            "Accept": generalidades.CONTENT_TYPE_JSON
        }
    };

    const success = (response) => {
        if (response.estado === "success") {
            $("#selectPermisos").multiSelect("deselect");
            $("#selectPermisos option").remove();
            $("#selectPermisos").multiSelect("refresh");
            let options = "";

            let seleccionados = [];
            // console.log(response);
            Object.entries(response.permisos).forEach(([id, permiso]) => {
                options = options + `<option value="${permiso.id}" ${permiso.seleccionado ? "selected" : ""}>${permiso.text}</option>`;
                if (permiso.seleccionado) {
                    seleccionados.push(permiso.id);
                }
            });

            $("#selectPermisos").append(options);
            $("#selectPermisos").val(seleccionados);
            $("#selectPermisos").multiSelect("refresh");
        }
        // generalidades.ocultarCargando("body");
    };
    const error = (response) => {
        generalidades.toastrGenerico(response.estado, response.mensaje);
        // generalidades.ocultarCargando("body");
    }

    // generalidades.mostrarCargando("body");
    generalidades.post(route('roles.buscarPermisos'), config, success, error);
};

const enviarDatosRoles = (form = '') => {
    let formData = new FormData(document.querySelector(formRoles));
    formData.set('roles', $('#selectRoles').val());
    formData.set('id', $('#idUsuario').val());
    
    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formRoles);
        }
        generalidades.ocultarCargando(formRoles);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formRoles);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formRoles, response.validaciones);
    }
    const ruta = route("roles.asignarRol", {usuario: formData.get('id')});
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando(formRoles);
}

const enviarDatosPermisos = (form = '') => {
    let formData = new FormData(document.querySelector(formPermisos));
    formData.set('permisos', $('#selectPermisos').val());
    formData.set('id', $('#idUsuario').val());
    
    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(formPermisos);
        }
        generalidades.ocultarCargando(formPermisos);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(formPermisos);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(formPermisos, response.validaciones);
    }
    const ruta = route("roles.asignarPermiso", {usuario: formData.get('id')});
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando(formPermisos);
}