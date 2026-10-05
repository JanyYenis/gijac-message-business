"use strict";

const seccionListadoCatalogo = ".seccionListadoCatalogo";
const btnPagina = ".btnPagina";
let paginaActual = 1;
let editandoId = null;

$(function () {
    window.cargarListado();
});

window.cargarListado = (pagina = 1, refrescar = false) => {
    generalidades.mostrarCargando(seccionListadoCatalogo);

    const datos = {
        pagina: pagina,
        busqueda: ($('#search').val() || '').trim(),
        disponibilidad: $('#filterAvailability').val() || '',
        categoria: $('#filterCategory').val() || '',
        orden: $('#sortBy').val() || 'name_asc',
        refrescar: refrescar ? 1 : 0,
    };

    generalidades.refrescarSeccion(null, route('catalogo.listado', datos), seccionListadoCatalogo, function (response) {
        generalidades.ocultarCargando(seccionListadoCatalogo);
        paginaActual = pagina;
        pintarKpis(response.kpis);
        llenarCategorias(response.categorias);
        $('#resultsCount').text(response.total + ' ' + 'productos');
    });
};

function pintarKpis(k) {
    if (!k) return;
    $('#kpiTotal').text(k.total);
    $('#kpiActive').text(k.activos);
    $('#kpiLow').text(k.ofertas);
    $('#kpiAvg').text(new Intl.NumberFormat('es-CO', { maximumFractionDigits: 2 }).format(k.promedio));
}

function llenarCategorias(cats) {
    const $sel = $('#filterCategory');
    const actual = $sel.val();
    if ($sel.data('cargado') || !cats) return;      // solo la primera vez
    cats.forEach(c => $sel.append($('<option>').val(c).text(c)));
    $sel.val(actual).data('cargado', true);
}

/* ---- Eventos de listado ---- */
$(document).on('click', btnPagina, function () {
    const pagina = $(this).attr('data-pagina');
    if (pagina) cargarListado(pagina);
});

let tmr;
$('#search').on('input', function () {
    clearTimeout(tmr);
    tmr = setTimeout(() => cargarListado(1), 300);
});
$('#filterAvailability, #filterCategory, #sortBy').on('change', () => cargarListado(1));
$('#refreshBtn, #retryBtn').on('click', () => cargarListado(1, true));

/* ---- Modal ---- */
function abrirModal(p = null) {
    const f = document.getElementById('productForm');
    f.reset();
    f.classList.remove('was-validated');
    editandoId = p ? p.id : null;

    $('#productModalLabel').text(p ? 'Editar producto' : 'Nuevo producto');
    $('#pSku').prop('disabled', !!p);

    if (p) {
        $('#pName').val(p.name);
        $('#pSku').val(p.retailer_id);
        $('#pDesc').val(p.description);
        $('#pCategory').val(p.category);
        $('#pPrice').val(p.precio);
        $('#pSalePrice').val(p.precio_oferta);
        $('#pCurrency').val(p.moneda);
        $('#pAvailability').val(p.agotado ? 'out of stock' : 'in stock');
        $('#pCondition').val(p.condition || 'new');
        $('#pImageUrl').val(p.image_url);
    } else {
        $('#pSku').val('SKU-' + Date.now().toString().slice(-6));
    }
    bootstrap.Modal.getOrCreateInstance(document.getElementById('productModal')).show();
}

$('#addProductBtn').on('click', () => abrirModal());

$(document).on('click', '.js-edit', function () {
    const id = $(this).closest('.p-card').data('id');
    $.get(route('catalogo.obtener', { id: id }))
        .done(r => abrirModal(r.producto))
        .fail(xhr => Swal.fire('Error', xhr.responseJSON?.message ?? 'No se pudo cargar el producto', 'error'));
});

/* ---- Guardar ---- */
$('#productForm').on('submit', function (e) {
    e.preventDefault();
    if (!this.checkValidity()) { this.classList.add('was-validated'); return; }

    let formData = new FormData();
    formData.append('id', editandoId);
    formData.append("nombre", $('#pName').val().trim());
    formData.append("sku", $('#pSku').val().trim());
    formData.append("descripcion", $('#pDesc').val().trim());
    formData.append("categoria", $('#pCategory').val().trim());
    formData.append("precio", $('#pPrice').val());
    formData.append("precio_oferta", $('#pSalePrice').val());
    formData.append("moneda", $('#pCurrency').val());
    formData.append("disponibilidad", $('#pAvailability').val());
    formData.append("cantidad", $('#pQuantity').val());
    formData.append("condicion", $('#pCondition').val());
    formData.append("imagen_url", $('#pImageUrl').val().trim());

    $('#saveProductBtn').prop('disabled', true);

    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
            Swal.fire({ icon: 'success', title: response.mensaje, timer: 1800, showConfirmButton: false });
            cargarListado(paginaActual, true);
        }
        generalidades.ocultarCargando('body');
        $('#saveProductBtn').prop('disabled', false);
    }

    const error = (response) => {
        generalidades.ocultarCargando('body');
        Swal.fire('Error', response?.mensaje ?? 'No se pudo guardar', 'error');
        $('#saveProductBtn').prop('disabled', false);
    }
    const ruta = route("catalogo.store");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando('body');
});

/* ---- Eliminar ---- */
$(document).on('click', '.js-delete', function () {
    const id = $(this).closest('.p-card').data('id');
    const nombre = $(this).closest('.p-card').find('.p-name').text();

    Swal.fire({
        title: '¿Eliminar producto?',
        text: `Se eliminará "${nombre}" del catálogo de WhatsApp. Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#C0392B',
        reverseButtons: true,
    }).then(r => {
        if (!r.isConfirmed) return;
        $.ajax({ url: route('catalogo.eliminar', { id: id }), method: 'DELETE' })
            .done(res => {
                Swal.fire({ icon: 'success', title: res.mensaje, timer: 1500, showConfirmButton: false });
                cargarListado(paginaActual, true);
            })
            .fail(xhr => Swal.fire('Error', xhr.responseJSON?.message ?? 'No se pudo eliminar', 'error'));
    });
});

$('#pDesc').on('input', function () { $('#pDescCount').text(this.value.length); });
