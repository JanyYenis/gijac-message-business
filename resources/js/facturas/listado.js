"use strict";

const tablaFacturas = "#tablaFacturas";

$(function () {
    iniciarComponentes();
});

const iniciarComponentes = (form = '') => {
}

$(document).on('click', '.btnTabFacturas', function() {
    window.listadoFacturas();
});

const escaparHtml = (texto) => $('<div>').text(texto ?? '').html();

const enviarFe = async (url, payload) => {
    try {
        const respuesta = await fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(payload),
        });
        const json = await respuesta.json();
        if (!respuesta.ok || json.estado !== 'success') {
            throw new Error(json.mensaje || json.message || __('No se pudo completar la acción.'));
        }
        generalidades.toastrGenerico('success', json.mensaje);
        $(tablaFacturas).DataTable().ajax.reload(null, false);
    } catch (error) {
        generalidades.toastrGenerico('error', error.message);
    }
};

// Cliente: solicitar la factura electrónica de un pago aprobado
$(document).on('click', '.btnSolicitarFe', async function () {
    const invoice = this.dataset.invoice;
    const resultado = await Swal.fire({
        title: __('Solicitar factura electrónica'),
        input: 'textarea',
        inputLabel: __('Datos para la factura (razón social, NIT y correo)'),
        inputAttributes: { maxlength: 500 },
        showCancelButton: true,
        confirmButtonText: __('Solicitar'),
        cancelButtonText: __('Cancelar'),
    });
    if (!resultado.isConfirmed) return;
    await enviarFe(route('facturas.fe.solicitar', { factura: invoice }), { observaciones: resultado.value || '' });
});

// Administrador: registrar la factura electrónica ya emitida en la DIAN
$(document).on('click', '.btnRegistrarFe', async function () {
    const invoice = this.dataset.invoice;
    const resultado = await Swal.fire({
        title: __('Registrar factura electrónica'),
        html: `<p class="text-start small">${escaparHtml(this.dataset.obs)}</p>
               <input id="feNumero" class="swal2-input" placeholder="${__('Número de la factura')}">
               <input id="feCufe" class="swal2-input" placeholder="CUFE">`,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: __('Guardar'),
        cancelButtonText: __('Cancelar'),
        preConfirm: () => {
            const numero_fe = document.getElementById('feNumero').value.trim();
            const cufe = document.getElementById('feCufe').value.trim();
            if (!numero_fe || !cufe) {
                Swal.showValidationMessage(__('El número y el CUFE son obligatorios'));
                return false;
            }
            return { numero_fe, cufe };
        },
    });
    if (!resultado.isConfirmed) return;
    await enviarFe(route('facturas.fe.registrar', { factura: invoice }), resultado.value);
});

/**
 * Función que permite cargar el listado.
 */
window.listadoFacturas = () => {
    if ($.fn.DataTable.isDataTable('#tablaFacturas')) {
        $('#tablaFacturas').DataTable().destroy();
    }

    var table = $("#tablaFacturas").DataTable({
        paging: true,
        responsive: true,
        serverSide: false,
        scrollX: true,
        searchDelay: 500,

        ajax: {
            "url": route("facturas.listado"),
            "type": "GET",
            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                generalidades.mostrarCargando(tablaFacturas);
                data = Object.assign(data);
            },
            dataSrc: function (json) {
                generalidades.ocultarCargando(tablaFacturas);
                return json.data
            },
        },
        buttons: [
            {
                extend: "excel",
                text: `<i class="fa fa-download"></i> ${__('Excel')}`,
                className: "btn btn-light-primary",
                title: __("Listado Facturas."),
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8],
                    format: {
                        body: function (data, row, column, node) {
                            // eliminar HTML
                            let text = $('<div>').html(data).text();
                            return text.trim();
                        }
                    }
                }
            },
            {
                text: `<i class="fa fa-sync-alt"></i> ${__('Actualizar')}`,
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
                data: 'invoice',
                name: 'invoice',
                render: function (data, type, full, meta) {
                    return full?.invoice ?? 'N/A';
                }
            },
            {
                data: 'x_ref_payco',
                name: 'x_ref_payco',
                render: function (data, type, full, meta) {
                    return full?.x_ref_payco ?? 'N/A';
                }
            },
            {
                data: 'plan.nombre',
                name: 'plan.nombre',
                render: function (data, type, full, meta) {
                    return full?.plan?.nombre ?? 'N/A';
                }
            },
            {
                data: 'bank',
                name: 'bank',
                render: function (data, type, full, meta) {
                    return full?.bank ?? 'N/A';
                }
            },
            {
                data: 'x_franchise',
                name: 'x_franchise',
                render: function (data, type, full, meta) {
                    return full?.x_franchise ?? 'N/A';
                }
            },
            {
                data: 'value',
                name: 'value',
                render: function (data, type, full, meta) {
                    return full?.value ?? 0;
                }
            },
            {
                data: 'x_response',
                name: 'x_response',
                render: function (data, type, full, meta) {
                    const base = full?.x_response ?? 'N/A';
                    if (type !== 'display' || full?.x_response !== 'Aceptada') return base;
                    if (full?.estado_fe === 3) {
                        return `${base}<br><span class="badge badge-light-success">${__('FE emitida')} ${escaparHtml(full?.numero_fe)}</span>`;
                    }
                    if (full?.estado_fe === 2) {
                        return `${base}<br><span class="badge badge-light-warning">${__('FE solicitada')}</span>`;
                    }
                    return base;
                }
            },
            {
                data: 'tiempo',
                name: 'tiempo',
                render: function (data, type, full, meta) {
                    let mes = 'mes';
                    if (full?.tiempo && full?.tiempo > 1) {
                        mes = 'meses';
                    }
                    return full?.tiempo ? full?.tiempo+' '+mes : '0 meses';
                }
            },
            {
                data: 'fecha_vencimiento',
                name: 'fecha_vencimiento',
                render: function (data, type, full, meta) {
                    return full?.fecha_vencimiento ?? 'N/A';
                }
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
            [15, 20, 50, 100, __("Todos")]
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
