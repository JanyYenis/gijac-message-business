"use strict";

/* ============================================================
   GIJAC Message Business · Editar plantilla WhatsApp
   Mismo motor que crear.js (HeaderBuilder, BodyBuilder,
   VariableManager, FooterBuilder, ButtonBuilder, Preview,
   validate, buildMetaPayload) pero apuntando a los ids únicos
   del modal de editar (sufijo "Edit") para no chocar con el
   modal de crear, que vive en el mismo DOM.

   OJO: la llamada a generalidades.ejecutar() en cargarDatos()
   asume que el callback recibe la respuesta completa del
   backend (para poder leer response.datos). Si tu helper
   generalidades.ejecutar() no pasa la respuesta al callback,
   ajusta esa única línea (por ejemplo, guardando "datos" en un
   atributo del contenedor antes de inyectar el HTML, o haciendo
   un segundo fetch a plantillas.edit para leer el JSON).
   ============================================================ */
(function ($) {
    'use strict';

    // rutas
    const rutaEditar = "plantillas.edit";
    const rutaActualizar = "plantillas.update";

    // id y clases
    const formEditarPlantilla = "#formEditarPlantilla";
    const seccionEditar = ".seccionEditar";
    const modalEditar = "#modalEditarPlantilla";

    /* ---------------- Constantes (idénticas a crear.js) ---------------- */
    var LIMITS = {
        name: 512, headerText: 60, body: 1024, footer: 60, btnText: 25,
        maxButtons: 10, maxQuickReply: 10, maxUrl: 2, maxPhone: 1,
        maxCopyCode: 1, maxOtp: 1, copyCodeExample: 20
    };

    var CATEGORY_AUTH = 1;
    var CATEGORY_MARKETING = 2;
    var CATEGORY_UTILITY = 3;
    var CATEGORY_META_MAP = { 1: __('AUTHENTICATION'), 2: __('MARKETING'), 3: __('UTILITY') };
    var CATEGORIES = {
        2: 'Promociones, ofertas y comunicaciones comerciales.',
        3: 'Actualizaciones, confirmaciones y mensajes relacionados con servicios.',
        1: 'Códigos de autenticación y verificación. Meta genera el texto automáticamente.'
    };

    var BUTTON_TYPES = {
        QUICK_REPLY: { label: 'Respuesta rápida', icon: 'fa-reply', fields: ['text'] },
        URL: { label: 'URL', icon: 'fa-link', fields: ['text', 'url'] },
        PHONE_NUMBER: { label: 'Número de teléfono', icon: 'fa-phone', fields: ['text', 'phone'] },
        COPY_CODE: { label: 'Copiar código (cupón)', icon: 'fa-copy', fields: ['example'] },
        CATALOG: { label: 'Catálogo', icon: 'fa-store', fields: ['text'] },
        MPM: { label: 'Multi Product Message', icon: 'fa-boxes-stacked', fields: ['text'] }
    };
    var OTP_TYPE_META = { label: 'OTP (autenticación)', icon: 'fa-key' };
    var HEADER_TYPES = [
        { key: 'TEXT', label: 'Texto', icon: 'fa-font' },
        { key: 'IMAGE', label: 'Imagen', icon: 'fa-image' },
        { key: 'VIDEO', label: 'Video', icon: 'fa-video' },
        { key: 'DOCUMENT', label: 'Documento', icon: 'fa-file-pdf' },
        { key: 'LOCATION', label: 'Ubicación', icon: 'fa-location-dot' }
    ];

    /* ---------------- Estado ---------------- */
    var state = {
        plantillaId: null,
        buttons: [],
        seq: 0,
        media: { IMAGE: null, VIDEO: null, DOCUMENT: null },
        listo: false // true una vez que los builders ya se inicializaron
    };

    function esc(s) { return $('<i>').text(s == null ? '' : s).html(); }
    function human(bytes) {
        if (!bytes && bytes !== 0) return '';
        var u = ['B', 'KB', 'MB', 'GB'], i = 0, n = bytes;
        while (n >= 1024 && i < u.length - 1) { n /= 1024; i++; }
        return n.toFixed(n < 10 && i > 0 ? 1 : 0) + ' ' + u[i];
    }
    function isAuth() { return Number($('#tplCategoryEdit').val()) === CATEGORY_AUTH; }

    /* ---------------- VariableManager (igual a crear.js) ---------------- */
    var VariableManager = {
        detect: function (text) {
            var found = [], re = /\{\{\s*([A-Za-z0-9_]+)\s*\}\}/g, m;
            while ((m = re.exec(text || '')) !== null) {
                if (found.indexOf(m[1]) === -1) found.push(m[1]);
            }
            return found;
        },
        isNumeric: function (v) { return /^\d+$/.test(v); },
        kind: function (vars) {
            if (!vars.length) return 'none';
            var numeric = vars.filter(VariableManager.isNumeric).length;
            if (numeric === vars.length) return 'numeric';
            if (numeric === 0) return 'named';
            return 'mixed';
        },
        isSequential: function (vars) {
            if (VariableManager.kind(vars) !== 'numeric') return true;
            var nums = vars.map(function (v) { return parseInt(v, 10); }).sort(function (a, b) { return a - b; });
            for (var i = 0; i < nums.length; i++) if (nums[i] !== i + 1) return false;
            return true;
        },
        next: function (text) {
            var nums = VariableManager.detect(text).filter(VariableManager.isNumeric).map(function (v) { return parseInt(v, 10); });
            return nums.length ? Math.max.apply(null, nums) + 1 : 1;
        },
        render: function ($container, vars, prefix) {
            var existing = {};
            $container.find('input[data-var]').each(function () { existing[$(this).data('var')] = this.value; });
            if (!vars.length) {
                $container.html(`<div class="var-empty">${__('Aún no hay variables. Usa')} <b>{{1}}</b> ${__('(numerada) o')} <b>{{${__('nombre')}}}</b> ${__('(con nombre), o los botones de arriba.')}</div>`);
                return;
            }
            var html = '';
            if (VariableManager.kind(vars) === 'mixed') {
                html += `<div class="field-error show mb-2">${__('Meta no permite mezclar variables numeradas ({{1}}) y con nombre ({{nombre}}) en el mismo texto. Usa un solo estilo.')}</div>`;
            }
            html += `<table class="var-table"><thead><tr><th style="width:120px">${__('Variable')}</th><th>${__('Ejemplo')}</th></tr></thead><tbody>`;
            vars.forEach(function (v) {
                html += '<tr><td><span class="var-token">{{' + esc(v) + '}}</span></td><td>' +
                    '<input type="text" class="form-control form-control-sm var-example" data-var="' + esc(v) +
                    '" data-prefix="' + prefix + '" placeholder="Ej: Carlos" value="' + esc(existing[v] || '') + '"></td></tr>';
            });
            html += `</tbody></table><div class="field-error" data-error="` + prefix + `-examples">${__('Completa un ejemplo para cada variable.')}</div>`;
            $container.html(html);
        },
        values: function ($container, vars) {
            return vars.map(function (v) { return ($container.find('input[data-var="' + v + '"]').val() || '').trim(); });
        }
    };

    /* ---------------- Counter ---------------- */
    var Counter = {
        update: function ($el, max) {
            var $c = $('[data-counter="' + $el.attr('id') + '"]');
            var len = ($el.val() || '').length;
            $c.text(len + ' / ' + max).toggleClass('over', len > max);
        }
    };

    /* ============================================================
       HeaderBuilder (ids con sufijo Edit)
       ============================================================ */
    var HeaderBuilder = {
        init: function () {
            var html = '';
            HEADER_TYPES.forEach(function (t) {
                html += '<div class="type-opt' + (t.key === 'TEXT' ? ' active' : '') + '" data-htype="' + t.key +
                    `" data-bs-toggle="tooltip" title="${__('Encabezado de ')}` + __(t.label.toLowerCase()) + `"><i class="fa-solid ` + t.icon + `"></i>` + __(t.label) + `</div>`;
            });
            $('#headerTypeGridEdit').html(html);

            $('#headerSwitchEdit').on('change', function () {
                $('#headerOffEdit').toggleClass('d-none', this.checked);
                $('#headerOnEdit').toggleClass('d-none', !this.checked);
                Preview.render();
            });

            $('#headerTypeGridEdit').on('click', '.type-opt', function () {
                $('#headerTypeGridEdit .type-opt').removeClass('active');
                $(this).addClass('active');
                HeaderBuilder.showPane($(this).data('htype'));
                Preview.render();
            });

            $('#headerTextEdit').on('input', function () {
                Counter.update($(this), LIMITS.headerText);
                VariableManager.render($('#headerVarsEdit'), VariableManager.detect(this.value), 'headerEdit');
                Preview.render();
            });

            $('#headerOnEdit').on('input', '.var-example, #locLatEdit, #locLngEdit, #locNameEdit, #locAddressEdit', function () {
                Preview.render();
            });

            ['IMAGE', 'VIDEO', 'DOCUMENT'].forEach(function (kind) { HeaderBuilder.bindDrop(kind); });
            HeaderBuilder.showPane('TEXT');
        },
        showPane: function (type) {
            $('#headerOnEdit .h-pane').addClass('d-none');
            $('#hpane-' + type + '-Edit').removeClass('d-none');
        },
        accept: function (kind) {
            return kind === 'IMAGE' ? 'image/jpeg,image/jpg,image/png'
                : kind === 'VIDEO' ? 'video/mp4,video/3gpp' : 'application/pdf';
        },
        bindDrop: function (kind) {
            var $dz = $('#dz-' + kind + '-Edit'), $input = $('#file-' + kind + '-Edit');
            $input.attr('accept', HeaderBuilder.accept(kind));
            $dz.on('click', function () { $input.trigger('click'); })
                .on('dragover', function (e) { e.preventDefault(); $dz.addClass('dragover'); })
                .on('dragleave drop', function () { $dz.removeClass('dragover'); })
                .on('drop', function (e) {
                    e.preventDefault();
                    var f = e.originalEvent.dataTransfer.files[0];
                    if (f) HeaderBuilder.setFile(kind, f);
                });
            $input.on('change', function () { if (this.files[0]) HeaderBuilder.setFile(kind, this.files[0]); });
            $(document).on('click', '#chip-' + kind + '-Edit .icon-btn', function () {
                state.media[kind] = null; $('#chip-' + kind + '-Edit').empty().addClass('d-none'); $input.val('');
                Preview.render();
            });
        },
        setFile: function (kind, file) {
            var ok = HeaderBuilder.accept(kind).split(',');
            if (ok.indexOf(file.type) === -1) { generalidades.toastrGenerico('error', __('Formato no permitido para ') + kind.toLowerCase() + '.'); return; }
            if (file.size > 16 * 1024 * 1024) { generalidades.toastrGenerico('error', __('El archivo supera 16 MB.')); return; }
            var url = URL.createObjectURL(file);
            state.media[kind] = { name: file.name, size: file.size, type: file.type, url: url, file: file };
            var media = kind === 'IMAGE' ? '<img src="' + url + '" alt="">'
                : kind === 'VIDEO' ? '<video src="' + url + '" muted></video>'
                    : '<i class="fa-solid fa-file-pdf fa-2x text-danger"></i>';
            $('#chip-' + kind + '-Edit').removeClass('d-none').html(
                '<div class="file-chip">' + media +
                '<div class="flex-grow-1"><div class="fname">' + esc(file.name) + '</div><div class="fsize">' + human(file.size) + '</div></div>' +
                `<button type="button" class="icon-btn" title="${__('Quitar archivo')}"><i class="fa-solid fa-trash-can"></i></button></div>`
            );
            Preview.render();
        },
        // NOTA: cuando se edita una plantilla que ya tenía header IMAGE/VIDEO/
        // DOCUMENT y el usuario no sube un archivo nuevo, no hay manera de
        // reconstruir el archivo original en el navegador — el backend
        // conserva el media_handle previo si no llega "header_media" (ver
        // update() del controlador). Aquí solo mostramos que había un header
        // de ese formato, sin preview de archivo.
        data: function () {
            if (isAuth()) return null;
            if (!$('#headerSwitchEdit').is(':checked')) return null;
            var type = $('#headerTypeGridEdit .type-opt.active').data('htype');
            if (type === 'TEXT') {
                var text = ($('#headerTextEdit').val() || '').trim();
                var vars = VariableManager.detect(text);
                var h = { format: 'TEXT', text: text };
                if (vars.length) h.examples = VariableManager.values($('#headerVarsEdit'), vars);
                return h;
            }
            if (type === 'LOCATION') {
                return {
                    format: 'LOCATION',
                    latitude: ($('#locLatEdit').val() || '').trim(),
                    longitude: ($('#locLngEdit').val() || '').trim(),
                    name: ($('#locNameEdit').val() || '').trim(),
                    address: ($('#locAddressEdit').val() || '').trim()
                };
            }
            var f = state.media[type];
            return { format: type, file: f ? { name: f.name, size: f.size, type: f.type } : null, url: f ? f.url : null };
        },
        load: function (h) {
            $('#headerSwitchEdit').prop('checked', !!(h && h.format)).trigger('change');
            if (!h || !h.format) return;
            $('#headerTypeGridEdit .type-opt').removeClass('active').filter('[data-htype="' + h.format + '"]').addClass('active');
            HeaderBuilder.showPane(h.format);
            if (h.format === 'TEXT') {
                $('#headerTextEdit').val(h.text || '').trigger('input');
                var vars = VariableManager.detect(h.text || '');
                var examples = h.examples || [];
                vars.forEach(function (v, i) { $('#headerVarsEdit input[data-var="' + v + '"]').val(examples[i] || ''); });
            } else if (h.format === 'LOCATION') {
                $('#locLatEdit').val(h.latitude || ''); $('#locLngEdit').val(h.longitude || '');
                $('#locNameEdit').val(h.name || ''); $('#locAddressEdit').val(h.address || '');
            }
            // IMAGE/VIDEO/DOCUMENT: ver nota en data() — no hay archivo que precargar.
        }
    };

    /* ============================================================
       BodyBuilder
       ============================================================ */
    var BodyBuilder = {
        init: function () {
            $('#bodyTextEdit').on('input', function () {
                Counter.update($(this), LIMITS.body);
                BodyBuilder.sync();
                Preview.render();
            });
            $('#addVarBtnEdit').on('click', function () {
                var $t = $('#bodyTextEdit'), el = $t[0], token = '{{' + VariableManager.next($t.val()) + '}}';
                var start = el.selectionStart, end = el.selectionEnd, v = $t.val();
                $t.val(v.slice(0, start) + token + v.slice(end)).trigger('input').focus();
                el.selectionStart = el.selectionEnd = start + token.length;
            });
            $('#addVarNamedBtnEdit').on('click', function () {
                var name = window.prompt(__('Nombre de la variable (solo letras, números y guion bajo):'), __('nombre'));
                if (!name) return;
                name = name.trim().replace(/[^A-Za-z0-9_]/g, '');
                if (!name) return;
                var $t = $('#bodyTextEdit'), el = $t[0], token = '{{' + name + '}}';
                var start = el.selectionStart, end = el.selectionEnd, v = $t.val();
                $t.val(v.slice(0, start) + token + v.slice(end)).trigger('input').focus();
                el.selectionStart = el.selectionEnd = start + token.length;
            });
            $('#bodyVarsEdit').on('input', '.var-example', function () { Preview.render(); });
            $('#authSecurityRecEdit').on('change', function () { Preview.render(); });
            BodyBuilder.sync();
        },
        sync: function () {
            VariableManager.render($('#bodyVarsEdit'), VariableManager.detect($('#bodyTextEdit').val()), 'bodyEdit');
        },
        authText: function () {
            var withSec = $('#authSecurityRecEdit').is(':checked');
            return '{{1}} is your verification code.' + (withSec ? ' For your security, do not share this code.' : '');
        },
        data: function () {
            if (isAuth()) {
                return { text: BodyBuilder.authText(), examples: ['123456'], add_security_recommendation: $('#authSecurityRecEdit').is(':checked') };
            }
            var text = ($('#bodyTextEdit').val() || '').trim();
            var vars = VariableManager.detect(text);
            return { text: text, examples: VariableManager.values($('#bodyVarsEdit'), vars) };
        },
        load: function (b) {
            $('#bodyTextEdit').val(b && b.text ? b.text : '').trigger('input');
            var vars = VariableManager.detect((b && b.text) || '');
            var examples = (b && b.examples) || [];
            vars.forEach(function (v, i) { $('#bodyVarsEdit input[data-var="' + v + '"]').val(examples[i] || ''); });
            $('#authSecurityRecEdit').prop('checked', !b || b.add_security_recommendation !== false);
        }
    };

    /* ============================================================
       FooterBuilder
       ============================================================ */
    var FooterBuilder = {
        init: function () {
            $('#footerSwitchEdit').on('change', function () {
                $('#footerOffEdit').toggleClass('d-none', this.checked);
                $('#footerOnEdit').toggleClass('d-none', !this.checked);
                Preview.render();
            });
            $('#footerTextEdit').on('input', function () {
                Counter.update($(this), LIMITS.footer);
                Preview.render();
            });
            $('#authExpirationEdit').on('input', function () { Preview.render(); });
        },
        data: function () {
            if (isAuth()) {
                var min = parseInt($('#authExpirationEdit').val(), 10);
                if (!min) return null;
                return { text: 'This code expires in ' + min + ' minutes.', code_expiration_minutes: min };
            }
            if (!$('#footerSwitchEdit').is(':checked')) return null;
            return { text: ($('#footerTextEdit').val() || '').trim() };
        },
        load: function (f) {
            $('#footerSwitchEdit').prop('checked', !!(f && f.text)).trigger('change');
            if (f) {
                $('#footerTextEdit').val(f.text || '').trigger('input');
                if (f.code_expiration_minutes) $('#authExpirationEdit').val(f.code_expiration_minutes);
            }
        }
    };

    /* ============================================================
       ButtonBuilder
       ============================================================ */
    var ButtonBuilder = {
        init: function () {
            ButtonBuilder.buildMenu();

            $('#btnTypeMenuEdit').on('click', '[data-btype]', function (e) {
                e.preventDefault();
                ButtonBuilder.add($(this).data('btype'));
            });

            $('#buttonsListEdit')
                .on('input change', 'input, select', function () {
                    var idx = $(this).closest('.btn-item').index();
                    var field = $(this).data('field');
                    if (this.type === 'checkbox') state.buttons[idx][field] = this.checked;
                    else state.buttons[idx][field] = this.value;
                    if (field === 'url') ButtonBuilder.renderUrlVars($(this).closest('.btn-item'), idx);
                    if (field === 'otp_type') ButtonBuilder.render();
                    Preview.render();
                })
                .on('click', '.btn-remove', function () {
                    var $item = $(this).closest('.btn-item'), idx = $item.index();
                    if (!window.confirm(__('¿Eliminar este botón? Esta acción no se puede deshacer.'))) return;
                    state.buttons.splice(idx, 1);
                    ButtonBuilder.render(); Preview.render();
                })
                .on('dragstart', '.btn-item', function (e) {
                    $(this).addClass('dragging');
                    e.originalEvent.dataTransfer.setData('text/plain', $(this).index());
                })
                .on('dragend', '.btn-item', function () { $(this).removeClass('dragging'); })
                .on('dragover', '.btn-item', function (e) { e.preventDefault(); })
                .on('drop', '.btn-item', function (e) {
                    e.preventDefault();
                    var from = parseInt(e.originalEvent.dataTransfer.getData('text/plain'), 10), to = $(this).index();
                    if (isNaN(from) || from === to) return;
                    state.buttons.splice(to, 0, state.buttons.splice(from, 1)[0]);
                    ButtonBuilder.render(); Preview.render();
                });
        },
        buildMenu: function () {
            var menu = '';
            if (isAuth()) {
                menu += `<div class="menu-item px-3">
                    <a href="javascript:;" class="menu-link fs-5 px-3 text-dark" data-btype="OTP">
                        <i class="fa-solid ${OTP_TYPE_META.icon} me-2 text-info"></i>
                        ${__(OTP_TYPE_META.label)}
                    </a>
                </div>`;
            } else {
                Object.keys(BUTTON_TYPES).forEach(function (k) {
                    menu += `<div class="menu-item px-3">
                        <a href="javascript:;" class="menu-link fs-5 px-3 text-dark" data-btype="${k}">
                            <i class="fa-solid ${BUTTON_TYPES[k].icon} me-2 text-info"></i>
                            ${__(BUTTON_TYPES[k].label)}
                        </a>
                    </div>`;
                });
            }
            $('#btnTypeMenuEdit').html(menu);
        },
        add: function (type) {
            if (state.buttons.length >= LIMITS.maxButtons) { ButtonBuilder.warn(__('Meta permite un máximo de ') + LIMITS.maxButtons + __(' botones por plantilla.')); return; }

            if (type === 'OTP') {
                if (!isAuth()) return;
                if (state.buttons.length >= LIMITS.maxOtp) { ButtonBuilder.warn(__('Autenticación solo admite 1 botón OTP.')); return; }
                state.buttons.push({ id: ++state.seq, type: 'OTP', otp_type: 'COPY_CODE', text: '', package: '', signature: '', zeroTermsAccepted: false });
                ButtonBuilder.render(); Preview.render();
                return;
            }

            if (isAuth()) return;

            var count = state.buttons.filter(function (b) { return b.type === type; }).length;
            if (type === 'URL' && count >= LIMITS.maxUrl) { ButtonBuilder.warn(__('Solo se permiten ') + LIMITS.maxUrl + __(' botones de tipo URL.')); return; }
            if (type === 'PHONE_NUMBER' && count >= LIMITS.maxPhone) { ButtonBuilder.warn(__('Solo se permite 1 botón de llamada.')); return; }
            if (type === 'COPY_CODE' && count >= LIMITS.maxCopyCode) { ButtonBuilder.warn(__('Solo se permite 1 botón de copiar código.')); return; }
            state.buttons.push({ id: ++state.seq, type: type, text: '', url: '', phone: '', example: '' });
            ButtonBuilder.render(); Preview.render();
        },
        warn: function (msg) { $('#btnLimitWarnEdit').text(msg).addClass('show'); setTimeout(function () { $('#btnLimitWarnEdit').removeClass('show'); }, 5000); },
        field: function (b, field) {
            var map = {
                text: [__('Texto del botón'), __('Ej: Confirmar'), LIMITS.btnText],
                url: [__('URL'), 'https://ejemplo.com/promocion', 2000],
                phone: [__('Número telefónico'), '+573001234567', 20],
                example: [__('Código de ejemplo'), 'Ej: SUMMER20', LIMITS.copyCodeExample],
                package: [__('Package name'), 'com.empresa.app', 200],
                signature: [__('Signature hash'), 'K8a/AINcGX7', 200]
            }[field];
            return '<div class="col-md-6 mb-2"><label class="form-label">' + map[0] + '</label>' +
                '<input type="text" class="form-control form-control-sm" data-field="' + field + '" maxlength="' + map[2] +
                '" placeholder="' + map[1] + '" value="' + esc(b[field] || '') + '">' +
                '<div class="field-error"></div></div>';
        },
        otpTypeField: function (b) {
            var opts = ['COPY_CODE', 'ONE_TAP', 'ZERO_TAP'].map(function (v) {
                return '<option value="' + v + '"' + (b.otp_type === v ? ' selected' : '') + '>' + v + '</option>';
            }).join('');
            return `<div class="col-12 mb-2"><label class="form-label">${__('Tipo de OTP')}</label>` +
                '<select class="form-select form-select-sm" data-field="otp_type">' + opts + '</select></div>' +
                `<div class="col-12 mb-2"><label class="form-label">${__('Texto del botón (opcional, Meta lo traduce)')}</label>` +
                '<input type="text" class="form-control form-control-sm" data-field="text" maxlength="' + LIMITS.btnText +
                `" placeholder="${__('Copy Code')}" value="` + __(esc(b.text || '')) + `"></div>`;
        },
        oneTapFields: function (b) {
            return `<div class="col-md-6 mb-2"><label class="form-label">${__('Package name')}</label>` +
                '<input type="text" class="form-control form-control-sm" data-field="package" placeholder="com.empresa.app" value="' + esc(b.package || '') + '">' +
                '<div class="field-error"></div></div>' +
                `<div class="col-md-6 mb-2"><label class="form-label">${__('Signature hash')}</label>` +
                '<input type="text" class="form-control form-control-sm" data-field="signature" placeholder="K8a/AINcGX7" value="' + esc(b.signature || '') + '">' +
                '<div class="field-error"></div></div>';
        },
        zeroTapFields: function (b) {
            return ButtonBuilder.oneTapFields(b) +
                '<div class="col-12 mb-2"><div class="form-check">' +
                '<input type="checkbox" class="form-check-input" id="zeroTapTermsEdit" data-field="zeroTermsAccepted"' + (b.zeroTermsAccepted ? ' checked' : '') + '>' +
                `<label class="form-check-label" for="zeroTapTermsEdit">${__('Acepto los términos de Zero Tap')}</label></div>` +
                '<div class="field-error"></div></div>';
        },
        render: function () {
            if (!state.buttons.length) { $('#buttonsListEdit').html(''); $('#buttonsEmptyEdit').removeClass('d-none'); return; }
            $('#buttonsEmptyEdit').addClass('d-none');
            var html = '';
            state.buttons.forEach(function (b) {
                if (b.type === 'OTP') {
                    var fields = ButtonBuilder.otpTypeField(b);
                    if (b.otp_type === 'ONE_TAP') fields += ButtonBuilder.oneTapFields(b);
                    if (b.otp_type === 'ZERO_TAP') fields += ButtonBuilder.zeroTapFields(b);
                    html += '<div class="btn-item" data-id="' + b.id + '">' +
                        '<div class="btn-item-head"><i class="fa-solid ' + OTP_TYPE_META.icon + ' text-info"></i>' +
                        '<span class="bi-type-1">' + __(OTP_TYPE_META.label) + '</span>' +
                        `<button type="button" class="icon-btn ms-auto btn-remove" title="${__('Eliminar botón')}"><i class="fa-solid fa-trash-can"></i></button></div>` +
                        '<div class="row g-2">' + fields + '</div></div>';
                    return;
                }
                var meta = BUTTON_TYPES[b.type];
                html += '<div class="btn-item" draggable="true" data-id="' + b.id + '">' +
                    '<div class="btn-item-head"><i class="fa-solid fa-grip-vertical drag-h" title="Arrastra para reordenar"></i>' +
                    '<i class="fa-solid ' + meta.icon + ' text-info"></i>' +
                    '<span class="bi-type-1">' + __(meta.label) + '</span>' +
                    `<button type="button" class="icon-btn ms-auto btn-remove" title="${__('Eliminar botón')}"><i class="fa-solid fa-trash-can"></i></button></div>` +
                    '<div class="row g-2">' + meta.fields.map(function (f) { return ButtonBuilder.field(b, f); }).join('') + '</div>' +
                    '<div class="btn-url-vars mt-1"></div></div>';
            });
            $('#buttonsListEdit').html(html);
            state.buttons.forEach(function (b, i) {
                if (b.type === 'URL') ButtonBuilder.renderUrlVars($('#buttonsListEdit .btn-item').eq(i), i);
            });
        },
        renderUrlVars: function ($item, idx) {
            var b = state.buttons[idx];
            var vars = VariableManager.detect(b.url);
            var $box = $item.find('.btn-url-vars');
            if (!vars.length) { $box.empty(); return; }
            if (!$box.find('input').length) {
                $box.html(`<label class="form-label">${__('Ejemplo de la URL dinámica')}</label>` +
                    '<input type="text" class="form-control form-control-sm" data-field="urlExample" placeholder="https://ejemplo.com/promo-123" value="' + esc(b.urlExample || '') + '">');
            }
        },
        data: function () {
            return state.buttons.map(function (b) {
                if (b.type === 'OTP') return { type: 'OTP', text: (b.text || __('Copiar código')).trim() };
                var out = { type: b.type, text: (b.text || '').trim() };
                if (b.type === 'URL') {
                    out.url = (b.url || '').trim();
                    if (VariableManager.detect(out.url).length) out.example = [(b.urlExample || '').trim()];
                }
                if (b.type === 'PHONE_NUMBER') out.phone_number = (b.phone || '').trim();
                if (b.type === 'COPY_CODE') { out.text = __('Copiar código'); out.example = [(b.example || '').trim()]; }
                return out;
            });
        },
        // Recibe el arreglo "buttons" tal como lo devuelve el backend
        // (mapearComponentesParaFormulario): [{type:'OTP'|'otp', otp_type,
        // text, package_name, signature_hash, ...}, {type:'URL', url,
        // example:[...]}, {type:'COPY_CODE', example:[...]}, ...].
        load: function (list) {
            state.buttons = (list || []).map(function (b) {
                if (b.type === 'otp' || b.type === 'OTP') {
                    return {
                        id: ++state.seq, type: 'OTP', otp_type: b.otp_type || 'COPY_CODE', text: b.text || '',
                        package: b.package_name || '', signature: b.signature_hash || '', zeroTermsAccepted: !!b.zero_tap_terms_accepted
                    };
                }
                return {
                    id: ++state.seq, type: b.type, text: b.text || '', url: b.url || '',
                    urlExample: (b.example && b.example[0]) || '', phone: b.phone_number || '',
                    example: (b.example && b.example[0]) || ''
                };
            });
            ButtonBuilder.render();
        }
    };

    /* ============================================================
       Preview
       ============================================================ */
    var Preview = {
        fill: function (text, examples) {
            return (text || '').replace(/\{\{(\d+)\}\}/g, function (m, n) {
                var v = examples[parseInt(n, 10) - 1];
                return v ? esc(v) : '<span class="wa-var">' + m + '</span>';
            });
        },
        render: function () {
            if (!state.listo) return; // evita renderizar antes de que existan los ids
            var d = getTemplateData();
            var html = '';

            if (d.header) {
                if (d.header.format === 'TEXT' && d.header.text) {
                    html += '<div class="wa-header-text">' + Preview.fill(d.header.text, d.header.examples || []) + '</div>';
                } else if (d.header.format === 'IMAGE') {
                    html += '<div class="wa-media">' + (d.header.url ? `<img src="` + d.header.url + `" alt="${__('Encabezado')}">` : '<i class="fa-solid fa-image fa-2x"></i>') + '</div>';
                } else if (d.header.format === 'VIDEO') {
                    html += '<div class="wa-media">' + (d.header.url ? '<video src="' + d.header.url + '" muted controls></video>' : '<i class="fa-solid fa-video fa-2x"></i>') + '</div>';
                } else if (d.header.format === 'DOCUMENT') {
                    html += '<div class="wa-doc"><i class="fa-solid fa-file-pdf"></i><span>' + esc(d.header.file ? d.header.file.name : 'documento.pdf') + '</span></div>';
                } else if (d.header.format === 'LOCATION') {
                    html += '<div class="wa-media"><i class="fa-solid fa-location-dot fa-2x"></i></div>' +
                        '<div class="wa-header-text">' + esc(d.header.name || 'Ubicación') + '</div>' +
                        '<div class="wa-footer mb-1">' + esc(d.header.address || '') + '</div>';
                }
            }

            html += '<div class="wa-body">' + (d.body.text ? Preview.fill(d.body.text, d.body.examples) :
                `<span class="text-muted-3">${__('El contenido del mensaje aparecerá aquí…')}</span>`) + '</div>';

            if (d.footer && d.footer.text) html += '<div class="wa-footer">' + esc(d.footer.text) + '</div>';
            html += '<div class="wa-time">' + new Date().toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' }) + ' ✓✓</div>';

            var btns = '';
            d.buttons.forEach(function (b) {
                var ic = b.type === 'URL' ? 'fa-arrow-up-right-from-square'
                    : b.type === 'PHONE_NUMBER' ? 'fa-phone'
                        : (b.type === 'COPY_CODE' || b.type === 'OTP') ? 'fa-copy' : 'fa-reply';
                btns += '<div class="wa-btn"><i class="fa-solid ' + ic + '"></i>' + esc(b.text || __('Botón')) + '</div>';
            });

            $('#waPreviewEdit').html('<div class="wa-bubble">'
                + html + (btns ? '<div class="wa-btns" style="max-width:90%">' + btns + '</div>' : '') + '</div>');
            $('#jsonPreviewEdit').text(JSON.stringify(buildMetaPayload(), null, 2));
        }
    };

    /* ============================================================
       Validación (misma lógica que crear.js, ids con sufijo Edit)
       ============================================================ */
    function clearErrors() {
        $(formEditarPlantilla + ' .is-invalid').removeClass('is-invalid');
        $(formEditarPlantilla + ' .field-error').removeClass('show');
    }
    function fail($input, msg) {
        $input.addClass('is-invalid');
        var $e = $input.siblings('.field-error').first();
        if (!$e.length) $e = $input.closest('.mb-3, .col-md-6, .mb-2, .col-12').find('.field-error').first();
        $e.text(msg).addClass('show');
    }
    function quickRepliesAreGrouped(buttons) {
        var idxs = [];
        buttons.forEach(function (b, i) { if (b.type === 'QUICK_REPLY') idxs.push(i); });
        if (idxs.length < 2) return true;
        for (var i = 1; i < idxs.length; i++) if (idxs[i] !== idxs[i - 1] + 1) return false;
        return true;
    }

    function validate() {
        clearErrors();
        var ok = true, d = getTemplateData(), category = $('#tplCategoryEdit').val();

        var $name = $('#tplNameEdit');
        if (!d.name) { fail($name, 'El nombre es obligatorio.'); ok = false; }
        else if (!/^[a-z0-9_]+$/.test(d.name)) { fail($name, __('Solo letras minúsculas, números y guiones bajos.')); ok = false; }
        else if (d.name.length > LIMITS.name) { fail($name, __('Máximo ') + LIMITS.name + __(' caracteres.')); ok = false; }

        if (!category) { fail($('#tplCategoryEdit'), __('Selecciona una categoría.')); ok = false; }
        if (!d.language) { fail($('#tplLanguageEdit'), __('Selecciona un idioma.')); ok = false; }

        if (Number(category) === CATEGORY_AUTH) {
            var otp = state.buttons[0];
            if (state.buttons.length !== 1 || !otp || otp.type !== 'OTP') {
                ButtonBuilder.warn(__('Autenticación requiere exactamente 1 botón OTP.')); ok = false;
            } else if (otp.otp_type === 'ONE_TAP' || otp.otp_type === 'ZERO_TAP') {
                var $item = $('#buttonsListEdit .btn-item').eq(0);
                if (!otp.package) { fail($item.find('[data-field="package"]'), __('Package name obligatorio.')); ok = false; }
                if (!otp.signature) { fail($item.find('[data-field="signature"]'), __('Signature hash obligatorio.')); ok = false; }
                if (otp.otp_type === 'ZERO_TAP' && !otp.zeroTermsAccepted) {
                    fail($item.find('[data-field="zeroTermsAccepted"]'), __('Debes aceptar los términos de Zero Tap.')); ok = false;
                }
            }
            var min = $('#authExpirationEdit').val();
            if (min && (min < 1 || min > 90)) { fail($('#authExpirationEdit'), __('Debe estar entre 1 y 90 minutos.')); ok = false; }

            if (!ok) generalidades.toastrGenerico('error', __('Revisa los campos marcados en rojo.'));
            return ok;
        }

        var $body = $('#bodyTextEdit');
        if (!d.body.text) { fail($body, __('El contenido del mensaje es obligatorio.')); ok = false; }
        else if (d.body.text.length > LIMITS.body) { fail($body, __('Máximo ') + LIMITS.body + __(' caracteres.')); ok = false; }
        else {
            var bvars = VariableManager.detect(d.body.text);
            if (VariableManager.kind(bvars) === 'mixed') { fail($body, __('No mezcles variables numeradas ({{1}}) y con nombre ({{nombre}}).')); ok = false; }
            else if (!VariableManager.isSequential(bvars)) { fail($body, __('Las variables numeradas deben ir consecutivas desde {{1}}.')); ok = false; }
            if (d.body.examples.some(function (v) { return !v; })) {
                $('#bodyVarsEdit [data-error="bodyEdit-examples"]').addClass('show'); ok = false;
            }
        }

        if (d.header) {
            if (d.header.format === 'TEXT') {
                var $h = $('#headerTextEdit');
                var hvars = VariableManager.detect(d.header.text);
                if (!d.header.text) { fail($h, __('Escribe el texto del encabezado.')); ok = false; }
                else if (d.header.text.length > LIMITS.headerText) { fail($h, __('Máximo ') + LIMITS.headerText + __(' caracteres.')); ok = false; }
                else if (hvars.length > 1) { fail($h, __('El encabezado admite máximo 1 variable.')); ok = false; }
                else if (VariableManager.kind(hvars) === 'mixed') { fail($h, __('No mezcles estilos de variable.')); ok = false; }
                else if (!VariableManager.isSequential(hvars)) { fail($h, __('Numera la variable como {{1}}.')); ok = false; }
                else if ((d.header.examples || []).some(function (v) { return !v; })) {
                    $('#headerVarsEdit [data-error="headerEdit-examples"]').addClass('show'); ok = false;
                }
            } else if (d.header.format === 'LOCATION') {
                if (!/^-?\d+(\.\d+)?$/.test(d.header.latitude)) { fail($('#locLatEdit'), __('Latitud inválida.')); ok = false; }
                if (!/^-?\d+(\.\d+)?$/.test(d.header.longitude)) { fail($('#locLngEdit'), __('Longitud inválida.')); ok = false; }
                if (!d.header.name) { fail($('#locNameEdit'), __('Indica el nombre del lugar.')); ok = false; }
            } else if (!d.header.file && !(state.media[d.header.format])) {
                // Si el header ya tenía IMAGE/VIDEO/DOCUMENT guardado y el
                // usuario no tocó el dropzone, se deja pasar (se conserva
                // el archivo/handle existente en el backend).
            }
        }

        if (d.footer && !d.footer.text) { fail($('#footerTextEdit'), __('Escribe el texto del pie de página.')); ok = false; }

        if (!quickRepliesAreGrouped(state.buttons)) {
            ButtonBuilder.warn(__('Los botones de Respuesta rápida deben ir agrupados entre sí (ej: [URL, QR, QR], no [QR, URL, QR]).'));
            ok = false;
        }

        d.buttons.forEach(function (b, i) {
            var $item = $('#buttonsListEdit .btn-item').eq(i);
            if (b.type !== 'COPY_CODE' && !b.text) { fail($item.find('[data-field="text"]'), __('El texto es obligatorio.')); ok = false; }
            else if (b.text && b.text.length > LIMITS.btnText) { fail($item.find('[data-field="text"]'), __('Máximo ') + LIMITS.btnText + __(' caracteres.')); ok = false; }
            if (b.type === 'URL') {
                var raw = (state.buttons[i].url || '');
                var u = raw.replace(/\{\{\d+\}\}/g, 'x');
                if (!/^https?:\/\/[^\s]+\.[^\s]+/.test(u)) { fail($item.find('[data-field="url"]'), __('Ingresa una URL válida (https://…).')); ok = false; }
                var urlVars = VariableManager.detect(raw);
                if (urlVars.length > 1) { fail($item.find('[data-field="url"]'), __('Un botón URL solo admite 1 variable.')); ok = false; }
                else if (urlVars.length === 1 && !/\{\{\d+\}\}$/.test(raw.trim())) {
                    fail($item.find('[data-field="url"]'), __('La variable debe ir al final de la URL.')); ok = false;
                }
                if (urlVars.length && !state.buttons[i].urlExample) { fail($item.find('[data-field="urlExample"]'), __('Agrega un ejemplo de la URL.')); ok = false; }
            }
            if (b.type === 'PHONE_NUMBER' && !/^\+?\d{7,15}$/.test(b.phone_number || '')) {
                fail($item.find('[data-field="phone"]'), __('Número inválido. Ej: +573001234567')); ok = false;
            }
            if (b.type === 'COPY_CODE') {
                var code = state.buttons[i].example || '';
                if (!code) { fail($item.find('[data-field="example"]'), __('Agrega el código de ejemplo.')); ok = false; }
                else if (code.length > LIMITS.copyCodeExample) { fail($item.find('[data-field="example"]'), __('Máximo ') + LIMITS.copyCodeExample + __(' caracteres.')); ok = false; }
            }
        });

        if (!ok) {
            var $first = $(formEditarPlantilla + ' .is-invalid, ' + formEditarPlantilla + ' .field-error.show').first();
            if ($first.length) $first[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            generalidades.toastrGenerico('error', __('Revisa los campos marcados en rojo.'));
        }
        return ok;
    }

    /* ============================================================
       Modelo uniforme (para preview) y payload real de Meta
       ============================================================ */
    function getTemplateData() {
        return {
            name: ($('#tplNameEdit').val() || '').trim(),
            language: $('#tplLanguageEdit').val(),
            category: $('#tplCategoryEdit').val(),
            header: HeaderBuilder.data(),
            body: BodyBuilder.data(),
            footer: FooterBuilder.data(),
            buttons: ButtonBuilder.data()
        };
    }

    function buildMetaPayload() {
        var name = ($('#tplNameEdit').val() || '').trim();
        var language = $('#tplLanguageEdit').val();
        var categoryCode = Number($('#tplCategoryEdit').val());
        var metaCategory = CATEGORY_META_MAP[categoryCode];

        if (categoryCode === CATEGORY_AUTH) {
            var authComponents = [
                { type: 'BODY', add_security_recommendation: $('#authSecurityRecEdit').is(':checked') }
            ];
            var min = parseInt($('#authExpirationEdit').val(), 10);
            if (min) authComponents.push({ type: 'FOOTER', code_expiration_minutes: min });

            var otp = state.buttons[0] || { otp_type: 'COPY_CODE' };
            var otpBtn = { type: 'OTP', otp_type: otp.otp_type || 'COPY_CODE' };
            if (otp.text) otpBtn.text = otp.text;
            if (otp.otp_type === 'ONE_TAP' || otp.otp_type === 'ZERO_TAP') {
                otpBtn.package_name = otp.package || '';
                otpBtn.signature_hash = otp.signature || '';
            }
            if (otp.otp_type === 'ZERO_TAP') otpBtn.zero_tap_terms_accepted = !!otp.zeroTermsAccepted;
            authComponents.push({ type: 'BUTTONS', buttons: [otpBtn] });

            return { name: name, language: language, category: metaCategory, components: authComponents };
        }

        var components = [];

        var h = HeaderBuilder.data();
        if (h) {
            if (h.format === 'TEXT') {
                var hc = { type: 'HEADER', format: 'TEXT', text: h.text };
                if (h.examples && h.examples.length) hc.example = { header_text: h.examples };
                components.push(hc);
            } else if (h.format === 'LOCATION') {
                components.push({ type: 'HEADER', format: 'LOCATION' });
            } else {
                components.push({ type: 'HEADER', format: h.format });
            }
        }

        var b = BodyBuilder.data();
        var bodyComponent = { type: 'BODY', text: b.text };
        var bvars = VariableManager.detect(b.text);
        if (bvars.length) {
            if (VariableManager.kind(bvars) === 'named') {
                bodyComponent.example = {
                    body_text_named_params: bvars.map(function (v, i) { return { param_name: v, example: b.examples[i] }; })
                };
            } else {
                bodyComponent.example = { body_text: [b.examples] };
            }
        }
        components.push(bodyComponent);

        var f = FooterBuilder.data();
        if (f && f.text) components.push({ type: 'FOOTER', text: f.text });

        var buttons = state.buttons.map(function (bt) {
            if (bt.type === 'URL') {
                var out = { type: 'URL', text: bt.text, url: bt.url };
                if (VariableManager.detect(bt.url).length) out.example = [bt.urlExample];
                return out;
            }
            if (bt.type === 'PHONE_NUMBER') return { type: 'PHONE_NUMBER', text: bt.text, phone_number: bt.phone };
            if (bt.type === 'COPY_CODE') return { type: 'COPY_CODE', example: [bt.example] };
            return { type: bt.type, text: bt.text };
        });
        if (buttons.length) components.push({ type: 'BUTTONS', buttons: buttons });

        return { name: name, language: language, category: metaCategory, components: components };
    }

    function applyCategoryUI() {
        var auth = isAuth();

        $('#secHeaderEdit').toggleClass('d-none', auth);
        if (auth) $('#headerSwitchEdit').prop('checked', false).trigger('change');

        $('#bodyAuthNoticeEdit').toggleClass('d-none', !auth);
        $('#bodyFreePaneEdit').toggleClass('d-none', auth);
        $('#bodyAuthPaneEdit').toggleClass('d-none', !auth);

        $('#footerFreePaneEdit').toggleClass('d-none', auth);
        $('#footerAuthPaneEdit').toggleClass('d-none', !auth);
        $('#footerHintEdit').text(auth ? __('Solo se define el tiempo de expiración del código; Meta arma el texto.') : __('Una línea breve visible debajo del mensaje.'));

        $('#authButtonNoticeEdit').toggleClass('d-none', !auth);
        $('#buttonsHintEdit').toggleClass('d-none', auth);

        var keep = state.buttons.filter(function (b) { return auth ? b.type === 'OTP' : b.type !== 'OTP'; });
        if (keep.length !== state.buttons.length) state.buttons = keep;

        ButtonBuilder.buildMenu();
        ButtonBuilder.render();

        if (auth) $('#authBodyPreviewEdit').text(BodyBuilder.authText());
    }

    /* ============================================================
       Carga inicial (flujo propio de editar.js)
       ============================================================ */
    $(document).on('click', '.btnEditar', function () {
        let id = $(this).attr('data-plantilla');
        if (id) cargarDatos(id);
    });

    const cargarDatos = (id) => {
        const ruta = route(rutaEditar, { "plantilla": id });
        generalidades.mostrarCargando('body');
        generalidades.ejecutar('GET', ruta, 'body', modalEditar, seccionEditar, function (response) {
            // OJO: si tu generalidades.ejecutar() no pasa "response" al
            // callback, ajusta esta línea para obtener response.datos de
            // otra forma (ver nota al inicio del archivo).
            iniciarComponentes(formEditarPlantilla, id, (response && response.datos) || {});
        });
    };

    const iniciarComponentes = (form, plantillaId, datos) => {
        state.plantillaId = plantillaId;
        state.buttons = [];
        state.media = { IMAGE: null, VIDEO: null, DOCUMENT: null };
        state.listo = false;

        HeaderBuilder.init();
        BodyBuilder.init();
        FooterBuilder.init();
        ButtonBuilder.init();

        $('#tplNameEdit').on('input', function () {
            this.value = this.value.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
            Counter.update($(this), LIMITS.name);
        });
        $('#tplCategoryEdit').on('change', function () {
            $('#catDescEdit').text(CATEGORIES[this.value] || '');
            applyCategoryUI();
            Preview.render();
        });

        // Precarga desde los datos del backend.
        HeaderBuilder.load(datos.header || null);
        BodyBuilder.load(datos.body || null);
        FooterBuilder.load(datos.footer || null);
        ButtonBuilder.load(datos.buttons || []);
        $('#tplCategoryEdit').trigger('change'); // aplica el panel correcto según la categoría ya seleccionada en el <select>

        $('[data-bs-toggle="tooltip"]', form).each(function () { new bootstrap.Tooltip(this); });

        state.listo = true;
        Preview.render();
        generalidades.ocultarCargando('body');
    };

    const enviarDatos = (form) => {
        if (!validate()) return;

        let formData = new FormData(document.getElementById("formEditarPlantilla"));
        formData.append('payload', JSON.stringify(buildMetaPayload()));

        if ($('#headerSwitchEdit').is(':checked')) {
            var headerType = $('#headerTypeGridEdit .type-opt.active').data('htype');
            var media = state.media[headerType];
            if (media && media.file) formData.append('header_media', media.file);
        }

        const config = {
            'method': 'POST', // Laravel: usa _method=PUT via generalidades.create/spoof, o cambia a PUT si tu helper lo soporta
            'headers': {
                'Accept': generalidades.CONTENT_TYPE_JSON,
            },
            'body': formData
        };

        const success = (response) => {
            if (response.estado == 'success') {
                $('.btnCerrarModal').trigger('click');
                generalidades.ocultarValidaciones(formEditarPlantilla);
                if (window.sincronizarPlantillas) window.sincronizarPlantillas();
            }
            generalidades.ocultarCargando(formEditarPlantilla);
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
        };

        const error = (response) => {
            generalidades.ocultarCargando(formEditarPlantilla);
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
            generalidades.mostrarValidaciones(formEditarPlantilla, response.validaciones);
        };

        const ruta = route(rutaActualizar, { plantilla: state.plantillaId });
        generalidades.create(ruta, config, success, error);
        generalidades.mostrarCargando(formEditarPlantilla);
    };

    $(document).on('hidden.bs.modal', modalEditar, function () {
        generalidades.resetValidate(formEditarPlantilla);
    });

    $(function () {
        generalidades.validarFormulario(formEditarPlantilla, enviarDatos);
    });

    /* Exponer por si otro script necesita disparar la carga manualmente */
    window.cargarPlantillaEditar = cargarDatos;
})(jQuery);
