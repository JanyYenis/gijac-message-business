/* ============================================================
   GIJAC Message Business · Catálogo WhatsApp Business
   HTML5 + CSS3 + Bootstrap 5 + jQuery + JS vanilla
   ------------------------------------------------------------
   CONFIGURACIÓN: reemplaza CATALOG_ID y ACCESS_TOKEN abajo.
   ============================================================ */

/* ====== 1. CONFIGURACIÓN DE LA API (EDITA AQUÍ) ====== */
const WHATSAPP_API_URL = 'https://graph.facebook.com/v26.0';
const CATALOG_ID = '671791141972412';     // <-- ID del catálogo de Commerce Manager
const ACCESS_TOKEN = 'EAACsiX5e5eQBSeZA9nmWZAXFzZAI40OTytUfxXgFyq4XWEJVxRTdGCmNx3KegYt3u8LzZC7vJZAc63aKH7VHJkZAUFWcAPmfZCMLhcQbe4BK3mNFzkNGZAfavuhuDmXrVs01elSgREAZCbi5pA3m2tAkX9X8ZA0VCI6FYz6i7CA1LXvmCPRX9cjpZCULEpApzdmzwZDZD';   // <-- Token con permisos catalog_management
const DEFAULT_CURRENCY = 'USD';
const PAGE_SIZE = 12;                  // productos por "página" / lote

/* Campos que pedimos a la Graph API */
const PRODUCT_FIELDS = [
    'id', 'retailer_id', 'name', 'description', 'price', 'sale_price', 'currency',
    'availability', 'image_url', 'url', 'brand', 'condition', 'category'
].join(',');

/* ====== 2. ESTADO EN MEMORIA ====== */
const State = {
    all: [],          // todos los productos cargados
    filtered: [],     // resultado de búsqueda + filtros
    visible: PAGE_SIZE,
    search: '',
    availability: '',
    sort: 'name_asc',
    demo: false,      // true cuando no hay credenciales -> datos de ejemplo
    editingId: null
};

/* ====== 3. UTILIDADES ====== */
function isConfigured() {
    return CATALOG_ID && ACCESS_TOKEN &&
        CATALOG_ID.indexOf('TU_') !== 0 && ACCESS_TOKEN.indexOf('TU_') !== 0;
}

function esc(s) { return $('<i>').text(s == null ? '' : s).html(); }

function money(value, currency) {
    var n = parseFloat(value);
    if (isNaN(n)) return '—';
    try {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency', currency: currency || DEFAULT_CURRENCY, maximumFractionDigits: 2
        }).format(n);
    } catch (e) { return (currency || DEFAULT_CURRENCY) + ' ' + n.toFixed(2); }
}

/* La Graph API devuelve price como string tipo "$25.00" o en centavos según el caso.
   Normalizamos a número para poder mostrarlo bien. */
function normalizePrice(raw) {
    if (raw == null || raw === '') return '';
    if (typeof raw === 'number') return raw > 1000 ? raw / 100 : raw;
    var clean = String(raw).replace(/[^\d.,-]/g, '').replace(/\.(?=\d{3}\b)/g, '').replace(',', '.');
    var n = parseFloat(clean);
    return isNaN(n) ? '' : n;
}

/* Toasts (Bootstrap-like, sin dependencias extra) */
function toast(message, type, title) {
    var icons = { ok: 'bi-check-circle-fill', err: 'bi-exclamation-octagon-fill', info: 'bi-info-circle-fill' };
    var colors = { ok: '#17864C', err: '#C0392B', info: '#2C8F99' };
    type = type || 'info';
    var $t = $(
        '<div class="toast-gj ' + type + '" role="alert" aria-live="polite">' +
        '<i class="bi ' + icons[type] + '" style="color:' + colors[type] + ';font-size:18px"></i>' +
        '<div class="flex-grow-1">' +
        '<div class="fw-bold" style="font-size:13.5px">' + esc(title || (type === 'err' ? 'Error' : type === 'ok' ? 'Listo' : 'Información')) + '</div>' +
        '<div class="text-secondary" style="font-size:13px">' + esc(message) + '</div>' +
        '</div>' +
        '<button class="btn-close btn-close-sm" style="font-size:10px" aria-label="Cerrar"></button>' +
        '</div>'
    ).appendTo('#toastStack');
    $t.find('.btn-close').on('click', function () { $t.remove(); });
    setTimeout(function () { $t.fadeOut(250, function () { $(this).remove(); }); }, 4800);
}

/* Errores de Meta: mensaje claro en UI + detalle completo en consola */
function metaError(jqXHR, contexto) {
    var err = null;
    try { err = JSON.parse(jqXHR.responseText).error; } catch (e) { }
    console.group('%c[Meta Graph API] ' + contexto, 'color:#C0392B;font-weight:bold');
    console.error('HTTP', jqXHR.status, jqXHR.statusText);
    console.error('Respuesta:', jqXHR.responseText || '(vacía)');
    if (err) console.table({
        message: err.message, type: err.type, code: err.code,
        subcode: err.error_subcode, trace: err.fbtrace_id
    });
    console.groupEnd();

    var msg = err ? err.message : 'No se pudo contactar la Graph API (revisa conexión o CORS).';
    if (err && err.code === 190) msg = 'Token inválido o expirado (código 190). Genera uno nuevo.';
    if (err && err.code === 100) msg = 'Parámetro o CATALOG_ID inválido (código 100). ' + err.message;
    if (err && err.code === 200) msg = 'Permisos insuficientes: el token necesita catalog_management.';
    toast(msg, 'err', contexto);
    return msg;
}

/* ====== 4. CAPA DE API (jQuery AJAX) ====== */
const Api = {
    list: function () {
        return $.ajax({
            url: WHATSAPP_API_URL + '/' + CATALOG_ID + '/products',
            method: 'GET',
            data: { fields: PRODUCT_FIELDS, limit: 200, access_token: ACCESS_TOKEN }
        });
    },
    create: function (payload) {
        return $.ajax({
            url: WHATSAPP_API_URL + '/' + CATALOG_ID + '/products',
            method: 'POST',
            data: $.extend({}, payload, { access_token: ACCESS_TOKEN })
        });
    },
    update: function (productId, payload) {
        return $.ajax({
            url: WHATSAPP_API_URL + '/' + productId,
            method: 'POST',                 // Graph API usa POST para actualizar
            headers: { 'X-HTTP-Method-Override': 'PUT' },
            data: $.extend({}, payload, { access_token: ACCESS_TOKEN })
        });
    },
    remove: function (productId) {
        return $.ajax({
            url: WHATSAPP_API_URL + '/' + productId,
            method: 'DELETE',
            data: { access_token: ACCESS_TOKEN }
        });
    }
};

/* ====== 5. DATOS DEMO (solo si faltan credenciales) ====== */
const DEMO_PRODUCTS = [
    { id: 'demo_1', retailer_id: 'SKU-001', name: 'Plan Starter WhatsApp API', description: 'Incluye 1.000 conversaciones mensuales, chatbot básico y soporte por correo.', price: 29.99, sale_price: 24.99, currency: 'USD', availability: 'in stock', brand: 'GIJAC', condition: 'new', image_url: 'https://images.unsplash.com/photo-1611606063065-ee7946f0787a?w=800&q=70', category: 'Software' },
    { id: 'demo_2', retailer_id: 'SKU-002', name: 'Plan Business Pro', description: 'Conversaciones ilimitadas, asistentes IA, automatizaciones n8n y métricas avanzadas.', price: 89.0, sale_price: '', currency: 'USD', availability: 'in stock', brand: 'GIJAC', condition: 'new', image_url: 'https://images.unsplash.com/photo-1551434678-e076c223a692?w=800&q=70', category: 'Software' },
    { id: 'demo_3', retailer_id: 'SKU-003', name: 'Onboarding asistido', description: 'Configuración completa de tu número, plantillas y primer chatbot con un especialista.', price: 149.0, sale_price: '', currency: 'USD', availability: 'in stock', brand: 'GIJAC', condition: 'new', image_url: 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=800&q=70', category: 'Servicios' },
    { id: 'demo_4', retailer_id: 'SKU-004', name: 'Paquete 10.000 mensajes', description: 'Bolsa adicional de mensajes de marketing para campañas masivas.', price: 45.5, sale_price: 39.9, currency: 'USD', availability: 'out of stock', brand: 'GIJAC', condition: 'new', image_url: 'https://images.unsplash.com/photo-1611746872915-64382b5c76da?w=800&q=70', category: 'Créditos' },
    { id: 'demo_5', retailer_id: 'SKU-005', name: 'Asistente IA dedicado', description: 'Entrenamiento con tu base de conocimiento y ajustes de tono y personalidad.', price: 199.0, sale_price: '', currency: 'USD', availability: 'in stock', brand: 'GIJAC', condition: 'new', image_url: 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=800&q=70', category: 'IA' },
    { id: 'demo_6', retailer_id: 'SKU-006', name: 'Integración CRM', description: 'Sincroniza contactos y conversaciones con HubSpot, Zoho o tu CRM propio.', price: 120.0, sale_price: 99.0, currency: 'USD', availability: 'out of stock', brand: 'GIJAC', condition: 'refurbished', image_url: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&q=70', category: 'Integraciones' }
];

/* ====== 6. RENDER ====== */
function skeletons(n) {
    var html = '';
    for (var i = 0; i < n; i++) {
        html += '<div class="col-12 col-sm-6 col-lg-4 col-xxl-3"><div class="sk h-100">' +
            '<div class="img"></div><div class="bar" style="width:70%"></div>' +
            '<div class="bar" style="width:90%"></div><div class="bar" style="width:40%"></div>' +
            '<div class="bar" style="width:60%;margin-bottom:18px"></div></div></div>';
    }
    $('#productsGrid').html(html);
}

function cardHtml(p) {
    var out = (p.availability || '').indexOf('out') === 0;
    var price = normalizePrice(p.price);
    var sale = normalizePrice(p.sale_price);
    var hasSale = sale !== '' && sale > 0 && sale < price;

    var img = p.image_url
        ? '<img src="' + esc(p.image_url) + '" alt="' + esc(p.name) + '" loading="lazy" onerror="this.parentNode.innerHTML=\'<i class=\\\'bi bi-image ph\\\'></i>\'">'
        : '<i class="bi bi-image ph"></i>';

    return '' +
        '<div class="col-12 col-sm-6 col-lg-4 col-xxl-3">' +
        '<article class="p-card" data-id="' + esc(p.id) + '">' +
        '<div class="p-thumb">' + img +
        '<div class="p-badges">' +
        '<span class="pill ' + (out ? 'pill-out' : 'pill-stock') + '">' +
        '<i class="bi ' + (out ? 'bi-x-circle-fill' : 'bi-check-circle-fill') + '"></i>' +
        (out ? 'Agotado' : 'En stock') + '</span>' +
        (hasSale ? '<span class="pill pill-sale"><i class="bi bi-tag-fill"></i>Oferta</span>' : '') +
        '</div>' +
        '<div class="p-kebab dropdown">' +
        '<button class="btn" data-bs-toggle="dropdown" aria-label="Acciones"><i class="bi bi-three-dots-vertical"></i></button>' +
        '<ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:12px">' +
        '<li><button class="dropdown-item js-edit"><i class="bi bi-pencil-square me-2"></i>Editar</button></li>' +
        '<li><button class="dropdown-item js-copy"><i class="bi bi-clipboard me-2"></i>Copiar ID</button></li>' +
        '<li><hr class="dropdown-divider"></li>' +
        '<li><button class="dropdown-item text-danger js-delete"><i class="bi bi-trash3 me-2"></i>Eliminar</button></li>' +
        '</ul>' +
        '</div>' +
        '</div>' +
        '<div class="p-body">' +
        '<h3 class="p-name">' + esc(p.name) + '</h3>' +
        '<p class="p-desc">' + esc(p.description || 'Sin descripción') + '</p>' +
        '<div class="p-price">' + money(hasSale ? sale : price, p.currency) +
        (hasSale ? '<s>' + money(price, p.currency) + '</s>' : '') + '</div>' +
        '<div class="p-meta">' +
        (p.brand ? '<span><i class="bi bi-award me-1"></i>' + esc(p.brand) + '</span>' : '') +
        (p.condition ? '<span><i class="bi bi-box-seam me-1"></i>' + esc(p.condition) + '</span>' : '') +
        '<span><i class="bi bi-upc-scan me-1"></i>' + esc(p.retailer_id || p.id) + '</span>' +
        '</div>' +
        '<div class="p-foot">' +
        '<button class="btn btn-soft js-edit"><i class="bi bi-pencil me-1"></i>Editar</button>' +
        '<button class="btn btn-soft text-danger js-delete"><i class="bi bi-trash3 me-1"></i>Eliminar</button>' +
        '</div>' +
        '</div>' +
        '</article>' +
        '</div>';
}

function applyFilters() {
    var q = State.search.trim().toLowerCase();
    State.filtered = State.all.filter(function (p) {
        var okQ = !q || ((p.name || '') + ' ' + (p.description || '') + ' ' + (p.brand || '') + ' ' + (p.retailer_id || ''))
            .toLowerCase().indexOf(q) > -1;
        var okA = !State.availability || (p.availability || '').indexOf(State.availability) === 0;
        return okQ && okA;
    });

    var s = State.sort;
    State.filtered.sort(function (a, b) {
        if (s === 'name_asc') return (a.name || '').localeCompare(b.name || '');
        if (s === 'name_desc') return (b.name || '').localeCompare(a.name || '');
        if (s === 'price_asc') return (normalizePrice(a.price) || 0) - (normalizePrice(b.price) || 0);
        if (s === 'price_desc') return (normalizePrice(b.price) || 0) - (normalizePrice(a.price) || 0);
        return 0;
    });
    render();
}

function render() {
    var list = State.filtered.slice(0, State.visible);
    $('#emptyState, #errorState').addClass('d-none');

    if (!State.filtered.length) {
        $('#productsGrid').empty();
        $('#emptyState').removeClass('d-none');
        $('#loadMoreWrap').addClass('d-none');
    } else {
        $('#productsGrid').html(list.map(cardHtml).join(''));
        $('#loadMoreWrap').toggleClass('d-none', State.visible >= State.filtered.length);
        $('#remaining').text(Math.max(State.filtered.length - State.visible, 0));
    }
    $('#resultsCount').text(list.length);
    // $('#resultsCount').text(State.filtered.length);
    renderKpis();
}

function renderKpis() {
    var all = State.all;
    var inStock = all.filter(function (p) { return (p.availability || '').indexOf('in') === 0; }).length;
    var offers = all.filter(function (p) {
        var s = normalizePrice(p.sale_price), pr = normalizePrice(p.price);
        return s !== '' && s > 0 && s < pr;
    }).length;
    var prices = all.map(function (p) { return normalizePrice(p.price) || 0; }).filter(Boolean);
    var avg = prices.length ? prices.reduce(function (a, b) { return a + b; }, 0) / prices.length : 0;

    countUp($('#kpiTotal'), all.length);
    countUp($('#kpiActive'), inStock);
    countUp($('#kpiLow'), offers);
    $('#kpiAvg').text(money(avg, all[0] ? all[0].currency : DEFAULT_CURRENCY));
}

function countUp($el, target) {
    var start = parseInt($el.data('v') || 0, 10), t0 = null, dur = 600;
    $el.data('v', target);
    if (start === target) { $el.text(target); return; }
    requestAnimationFrame(function step(ts) {
        if (!t0) t0 = ts;
        var p = Math.min((ts - t0) / dur, 1);
        $el.text(Math.round(start + (target - start) * (1 - Math.pow(1 - p, 3))));
        if (p < 1) requestAnimationFrame(step);
    });
}

/* ====== 7. CARGA DE PRODUCTOS (GET) ====== */
function loadProducts() {
    skeletons(8);
    $('#errorState, #emptyState').addClass('d-none');

    if (!isConfigured()) {
        State.demo = true;
        $('#cfgBanner').removeClass('d-none');
        setTimeout(function () {
            State.all = DEMO_PRODUCTS.slice();
            State.visible = PAGE_SIZE;
            applyFilters();
            toast('Mostrando datos de ejemplo. Configura CATALOG_ID y ACCESS_TOKEN en js/catalog.js.', 'info', 'Modo demo');
        }, 700);
        return;
    }

    State.demo = false;
    $('#cfgBanner').addClass('d-none');

    Api.list()
        .done(function (res) {
            State.all = (res && res.data) ? res.data : [];
            State.visible = PAGE_SIZE;
            applyFilters();
            toast(State.all.length + ' productos cargados desde el catálogo.', 'ok');
        })
        .fail(function (xhr) {
            var msg = metaError(xhr, 'Listar productos');
            $('#productsGrid').empty();
            $('#errorMsg').text(msg);
            $('#errorState').removeClass('d-none');
            $('#loadMoreWrap').addClass('d-none');
        });
}

/* ====== 8. FORMULARIO / MODAL ====== */
function openModal(product) {
    var f = document.getElementById('productForm');
    f.reset();
    f.classList.remove('was-validated');
    $('#imgPreview').html('<div class="p-3"><i class="bi bi-image d-block fs-2 mb-1"></i>Vista previa</div>');

    if (product) {
        State.editingId = product.id;
        $('#productModalLabel').html('<i class="bi bi-pencil-square me-2"></i>Editar producto');
        $('#saveBtnText').text('Guardar cambios');
        $('#pName').val(product.name || '');
        $('#pDesc').val(product.description || '');
        $('#pPrice').val(normalizePrice(product.price));
        $('#pSalePrice').val(normalizePrice(product.sale_price));
        $('#pCurrency').val(product.currency || DEFAULT_CURRENCY);
        $('#pAvailability').val((product.availability || 'in stock').indexOf('out') === 0 ? 'out of stock' : 'in stock');
        $('#pImageUrl').val(product.image_url || '').trigger('input');
        $('#f_brand').val(product.brand || '');
        $('#pCondition').val(product.condition || 'new');
        $('#pSku').val(product.retailer_id || '').prop('disabled', true);
        $('#f_url').val(product.url || '');
    } else {
        State.editingId = null;
        $('#productModalLabel').html('<i class="bi bi-plus-circle me-2"></i>Nuevo producto');
        $('#saveBtnText').text('Crear producto');
        $('#pCurrency').val(DEFAULT_CURRENCY);
        $('#pAvailability').val('in stock');
        $('#pCondition').val('new');
        $('#pSku').prop('disabled', false).val('SKU-' + Date.now().toString().slice(-6));
    }
    bootstrap.Modal.getOrCreateInstance(document.getElementById('productModal')).show();
}

function formPayload() {
    var payload = {
        name: $('#pName').val().trim(),
        description: $('#pDesc').val().trim(),
        price: Math.round(parseFloat($('#pPrice').val()) * 100), // Graph API espera centavos
        currency: $('#pCurrency').val(),
        availability: $('#pAvailability').val(),
        image_url: $('#pImageUrl').val().trim(),
        condition: $('#pCondition').val()
    };
    var sale = parseFloat($('#pSalePrice').val());
    if (!isNaN(sale) && sale > 0) payload.sale_price = Math.round(sale * 100);
    var brand = $('#f_brand').val().trim(); if (brand) payload.brand = brand;
    var url = $('#f_url').val().trim(); if (url) payload.url = url;
    var rid = $('#pSku').val().trim();
    if (rid && !State.editingId) payload.retailer_id = rid;
    return payload;
}

/* Objeto local (para modo demo y para refrescar la UI sin recargar todo) */
function localProduct(payload, id) {
    return {
        id: id || ('demo_' + Date.now()),
        retailer_id: payload.retailer_id || $('#pSku').val(),
        name: payload.name, description: payload.description,
        price: payload.price / 100,
        sale_price: payload.sale_price ? payload.sale_price / 100 : '',
        currency: payload.currency, availability: payload.availability,
        image_url: payload.image_url, brand: payload.brand || '',
        condition: payload.condition, url: payload.url || ''
    };
}

function saveProduct() {
    var form = document.getElementById('productForm');
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        toast('Revisa los campos obligatorios marcados en rojo.', 'err', 'Formulario incompleto');
        return;
    }
    var payload = formPayload();
    var editing = State.editingId;

    $('#saveProductBtn').prop('disabled', true);

    function finish() { $('#saveProductBtn').prop('disabled', false); }

    /* --- Modo demo: guarda en memoria --- */
    if (State.demo) {
        setTimeout(function () {
            if (editing) {
                var i = State.all.findIndex(function (p) { return p.id === editing; });
                if (i > -1) State.all[i] = localProduct(payload, editing);
                toast('Producto actualizado (modo demo).', 'ok');
            } else {
                State.all.unshift(localProduct(payload));
                toast('Producto creado (modo demo).', 'ok');
            }
            finish();
            bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
            applyFilters();
        }, 500);
        return;
    }

    /* --- API real --- */
    var req = editing ? Api.update(editing, payload) : Api.create(payload);
    req.done(function (res) {
        finish();
        bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
        toast(editing ? 'Producto actualizado en el catálogo.' : 'Producto creado correctamente.', 'ok');
        console.log('[Meta Graph API] Respuesta:', res);
        loadProducts();
    }).fail(function (xhr) {
        finish();
        metaError(xhr, editing ? 'Actualizar producto' : 'Crear producto');
    });
}

function deleteProduct(id) {
    var prod = State.all.find(function (p) { return p.id === id; });
    Swal.fire({
        title: '¿Eliminar producto?',
        html: 'Se eliminará <b>' + esc(prod ? prod.name : id) + '</b> del catálogo de WhatsApp. Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-trash3 me-1"></i> Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#C0392B',
        cancelButtonColor: '#6B8285',
        reverseButtons: true,
        customClass: { popup: 'rounded-4' }
    }).then(function (r) {
        if (!r.isConfirmed) return;

        if (State.demo) {
            State.all = State.all.filter(function (p) { return p.id !== id; });
            applyFilters();
            toast('Producto eliminado (modo demo).', 'ok');
            return;
        }

        Swal.fire({ title: 'Eliminando…', didOpen: function () { Swal.showLoading(); }, allowOutsideClick: false });
        Api.remove(id)
            .done(function () {
                Swal.close();
                State.all = State.all.filter(function (p) { return p.id !== id; });
                applyFilters();
                toast('Producto eliminado del catálogo.', 'ok');
            })
            .fail(function (xhr) { Swal.close(); metaError(xhr, 'Eliminar producto'); });
    });
}

/* ====== 9. EVENTOS ====== */
$(function () {
    /* Sidebar móvil */
    $('#burger').on('click', function () {
        $('#sidebar').toggleClass('open');
        $('#sideBackdrop').toggleClass('show');
    });
    $('#sideBackdrop').on('click', function () {
        $('#sidebar').removeClass('open'); $(this).removeClass('show');
    });

    /* Navegación dummy Catálogo / Estadísticas */
    $('.side-link[data-view]').on('click', function (e) {
        e.preventDefault();
        var v = $(this).data('view');
        $('.side-link').removeClass('active'); $(this).addClass('active');
        $('#viewCatalog').toggleClass('d-none', v !== 'catalog');
        $('#viewStats').toggleClass('d-none', v !== 'stats');
        $('#sidebar').removeClass('open'); $('#sideBackdrop').removeClass('show');
    });

    /* Buscador en tiempo real (debounce) */
    var tmr;
    $('#search').on('input', function () {
        var v = this.value;
        $('#searchClear').toggle(v.length > 0);
        clearTimeout(tmr);
        tmr = setTimeout(function () {
            State.search = v; State.visible = PAGE_SIZE; applyFilters();
        }, 180);
    });
    $('#searchClear').on('click', function () {
        $('#search').val('').trigger('input').focus();
    });

    /* Filtros */
    $('#filterAvailability').on('change', function () {
        State.availability = this.value; State.visible = PAGE_SIZE; applyFilters();
    });
    $('#sortBy').on('change', function () { State.sort = this.value; applyFilters(); });
    $('#refreshBtn').on('click', loadProducts);

    /* Cargar más */
    $('#loadMoreBtn').on('click', function () { State.visible += PAGE_SIZE; render(); });

    /* Nuevo / editar / eliminar / copiar */
    $('#addProductBtn, #emptyNewBtn').on('click', function () { openModal(null); });
    $('#retryBtn').on('click', loadProducts);

    $(document).on('click', '.js-edit', function () {
        var id = $(this).closest('.p-card').data('id');
        var p = State.all.find(function (x) { return x.id === id; });
        if (p) openModal(p);
    });
    $(document).on('click', '.js-delete', function () {
        deleteProduct($(this).closest('.p-card').data('id'));
    });
    $(document).on('click', '.js-copy', function () {
        var id = String($(this).closest('.p-card').data('id'));
        navigator.clipboard.writeText(id).then(function () { toast('ID copiado: ' + id, 'ok'); });
    });

    /* Preview de imagen al pegar la URL */
    $('#pImageUrl').on('input paste', function () {
        var url = this.value.trim();
        clearTimeout($(this).data('t'));
        var self = this;
        $(this).data('t', setTimeout(function () {
            if (!/^https?:\/\//i.test(url)) {
                $('#imgPreview').html('<div class="p-3"><i class="bi bi-image d-block fs-2 mb-1"></i>Vista previa</div>');
                return;
            }
            $('#imgPreview').html('<div class="spinner-border spinner-border-sm text-secondary"></div>');
            var img = new Image();
            img.onload = function () { $('#imgPreview').html($('<img>').attr({ src: url, alt: 'Vista previa' })); };
            img.onerror = function () {
                $('#imgPreview').html('<div class="p-3 text-danger"><i class="bi bi-exclamation-triangle d-block fs-3 mb-1"></i>No se pudo cargar la imagen</div>');
            };
            img.src = url;
            void self;
        }, 400));
    });

    /* Guardar */
    $('#saveProductBtn').on('click', saveProduct);
    $('#productForm').on('submit', function (e) { e.preventDefault(); saveProduct(); });

    /* Contador de caracteres de descripción */
    $('#pDesc').on('input', function () { $('#pDescCount').text(this.value.length); });

    /* Arranque */
    loadProducts();
});
