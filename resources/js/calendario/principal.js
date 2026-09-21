"use strict";

let calendar = null;
let currentEvent = null;
const statusColors = {
    0: '#EF4444',
    1: '#22C55E',
    2: '#F59E0B',
    3: '#94A3B8'
};
const statusLabels = {
    0: __('Eliminado'),
    1: __('Enviada'),
    2: __('Pendiente'),
    3: __('Cancelada')
};

$(function () {
    iniciarComponetes();
});

const iniciarComponetes = () => {
    $("#kt_datepicker_1").flatpickr();

    const $p = $('#particles');
    for (let i = 0; i < 28; i++) {
        const dur = 12 + Math.random() * 18,
            delay = -Math.random() * 20,
            size = 3 + Math.random() * 4;
        $('<span class="particle">').css({
            left: Math.random() * 100 + '%',
            bottom: -20 + 'px',
            width: size,
            height: size,
            background: `rgba(30,111,120,${.15 + Math.random() * .35})`,
            animationDuration: dur + 's',
            animationDelay: delay + 's'
        }).appendTo($p);
    }

    /* Counter animations */
    $('[data-count]').each(function () {
        const $el = $(this),
            target = parseInt($el.data('count'), 10);
        $({
            v: 0
        }).animate({
            v: target
        }, {
            duration: 1400,
            easing: 'swing',
            step: function (now) {
                $el.text(Math.floor(now).toLocaleString('es-PE'));
            },
            complete: function () {
                $el.text(target.toLocaleString('es-PE'));
            }
        });
    });

    inicializarCalendario();

    consultarCampanasHoy();
    consultarCampanasProximas();
}

function rand(a) {
    return a[Math.floor(Math.random() * a.length)]
}

function pad(n) {
    return n < 10 ? '0' + n : n
}

/* Calendar */
const inicializarCalendario = () => {
    const el = document.getElementById('calendar');

    calendar = new FullCalendar.Calendar(el, {
        initialView: 'dayGridMonth',
        locale: 'es',
        width: '100%',
        height: 'auto',
        firstDay: 1,
        editable: true,
        droppable: true,
        eventResizableFromStart: true,
        headerToolbar: false,
        dayMaxEvents: 3,
        eventDisplay: 'block',
        events: consultarCampanas,
        eventContent(arg) {
            const p = arg.event.extendedProps;
            const color = statusColors[p.status] ?? '#1E6F78';
            const time = arg.timeText || arg.event.start.toLocaleTimeString('es-PE', {
                hour: '2-digit',
                minute: '2-digit'
            });
            const el = document.createElement('div');
            el.className = 'evt ' + p.status;
            el.style.background = color;      // el estilo va aquí
            el.style.color = '#fff';
            el.style.borderRadius = '6px';
            el.style.padding = '2px 6px';

            el.innerHTML = `
                <div class="t">
                    <span class="n"></span>
                    <span class="h">${time}</span>
                </div>
                <span class="m">
                    <i class="fa-solid fa-users"></i> ${p.audience.toLocaleString('es-PE')}
                </span>`;
            el.querySelector('.n').textContent = arg.event.title; // evita inyección HTML

            return { domNodes: [el] };
        },

        eventMouseEnter(info) {
            showTooltip(info);
        },

        eventMouseLeave() {
            hideTooltip();
        },

        eventClick(info) {
            info.jsEvent.preventDefault();
            openDrawer(info.event);
        },

        datesSet(info) {
            document.getElementById('calTitle').textContent =
                info.view.title.replace(/^\w/, c => c.toUpperCase());
        }
    });

    calendar.render();

    document.getElementById('prev').onclick = () => calendar.prev();
    document.getElementById('next').onclick = () => calendar.next();
    document.getElementById('today').onclick = () => calendar.today();

    document.querySelectorAll('.cal-views button').forEach(b => {
        b.onclick = () => {
            document.querySelectorAll('.cal-views button')
                .forEach(x => x.classList.remove('active'));

            b.classList.add('active');
            calendar.changeView(b.dataset.view);
        };
    });
};

/* Tooltip */
function showTooltip(info) {
    currentEvent = info.event;
    const p = info.event.extendedProps;
    const badge = document.getElementById('ttBadge');
    const colors = {
        scheduled: '#3B82F6',
        0: '#EF4444',
        1: '#22C55E',
        2: '#F59E0B',
        3: '#94A3B8'
    };
    badge.style.background = colors[p.status];
    badge.textContent = statusLabels[p.status];
    document.getElementById('ttName').textContent = info.event.title;
    document.getElementById('ttCo').textContent = p.company;
    document.getElementById('ttDate').textContent = info.event.start.toLocaleString('es-PE', {
        dateStyle: 'medium',
        timeStyle: 'short'
    });
    document.getElementById('ttMsgs').textContent = p.messages.toLocaleString('es-PE');
    document.getElementById('ttAud').textContent = p.audience.toLocaleString('es-PE');
    document.getElementById('ttOwner').textContent = p.owner;
    $('.btnVer').attr('data-registro', currentEvent.id);

    const tt = document.getElementById('tooltip');
    const r = info.el.getBoundingClientRect();
    let left = r.right + 12,
        top = r.top - 8;
    if (left + 300 > window.innerWidth) left = r.left - 292;
    if (top + 320 > window.innerHeight) top = window.innerHeight - 340;
    if (top < 8) top = 8;
    tt.style.left = left + 'px';
    tt.style.top = top + 'px';
    tt.classList.add('show');
}

function hideTooltip() {
    setTimeout(() => {
        if (!document.getElementById('tooltip').matches(':hover')) document.getElementById('tooltip')
            .classList.remove('show');
    }, 120);
}
document.getElementById('tooltip').addEventListener('mouseleave', () => document.getElementById('tooltip').classList
    .remove('show'));

/* Drawer */
function openDrawer(evt) {
    if (!evt) return;
    const p = evt.extendedProps;
    document.getElementById('dName').textContent = evt.title;
    document.getElementById('dCo').textContent = p.company + ' · ' + p.template;
    document.getElementById('dBadge').textContent = statusLabels[p.status];
    document.getElementById('dDate').textContent = evt.start.toLocaleString('es-PE', {
        dateStyle: 'long',
        timeStyle: 'short'
    });
    document.getElementById('dTpl').textContent = p.template;
    document.getElementById('dOwner').textContent = p.owner;
    document.getElementById('dAud').textContent = p.audience.toLocaleString('es-PE');
    document.getElementById('sMsgs').textContent = p.messages.toLocaleString('es-PE');
    document.getElementById('sDel').textContent = evt?.extendedProps?.campana?.mensajes_abiertos.length && p.messages ? ((evt?.extendedProps?.campana?.mensajes_abiertos.length / p.messages) * 100).toLocaleString('es-PE')+'%' : '0%';
    document.getElementById('sOpen').textContent = evt?.extendedProps?.campana?.mensajes_abiertos.length && p.messages ? ((evt?.extendedProps?.campana?.mensajes_abiertos.length / p.messages) * 100).toLocaleString('es-PE')+'%' : '0%';
    document.getElementById('dSeg').textContent = evt?.extendedProps?.campana?.etiqueta?.nombre ?? 'N/A';
    document.getElementById('drawer').classList.add('open');
    document.getElementById('drawerOverlay').classList.add('open');
    document.getElementById('tooltip').classList.remove('show');

    $('.msg-box').text(evt?.extendedProps?.campana?.contenido);
}

function closeDrawer() {
    document.getElementById('drawer').classList.remove('open');
    document.getElementById('drawerOverlay').classList.remove('open');
}

const fmt = (d) => {
    const dt = new Date(d);
    return {
        h: dt.toLocaleTimeString('es-PE', {
            hour: '2-digit',
            minute: '2-digit'
        }),
        d: dt.toLocaleDateString('es-PE', {
            day: '2-digit',
            month: 'short'
        })
    };
};

const renderList = (list, empty) => list.length ? list.map(campana => {
    const f = fmt(campana.fecha_envio);
    return `<div class="agenda-item">
                    <div class="agenda-time">${f.h}<small>${f.d}</small></div>
                    <div>
                        <div class="agenda-title">${campana.nombre}</div>
                        <div class="agenda-meta">${campana.info_categoria.nombre} · ${campana.envios_activos.length.toLocaleString('es-PE')} contactos</div>
                        <span class="agenda-pill badge-light-${campana.info_estado.color}">${campana?.info_estado?.nombre}</span>
                    </div>
                </div>`;
}).join('') :
    `<div style="text-align:center; padding:14px 0; color:var(--muted); font-size:12.5px">
            <i class="fa-regular fa-calendar" style="font-size:22px; display:block; margin-bottom:6px; color:var(--primary);
                opacity:.5"></i>
            ${empty}
        </div>`;

const obtenerFiltros = () => ({
    etiqueta_id: $('#selectEtiqueta').val(),
    estado: $('#selectEstado').val(),
    tipo: $('#selectTipos').val(),
    categoria: $('#selectCategorias').val(),
    responsable_id: $('#selectResponsable').val(),
    fecha: $('#kt_datepicker_1').val(),
});

const consultarCampanas = (fetchInfo, successCallback, failureCallback) => {
    // el filtro "fecha" ya no se manda: ahora lo maneja el rango del calendario
    const { fecha, ...filtros } = obtenerFiltros();

    const datos = {
        ...filtros,
        inicio: fetchInfo.startStr.substring(0, 10), // YYYY-MM-DD
        fin: fetchInfo.endStr.substring(0, 10),      // exclusivo
    };

    const config = {
        method: 'GET',
        headers: {
            Accept: generalidades.CONTENT_TYPE_JSON,
        }
    };

    const success = (response) => {
        if (response.estado === 'success') {
            const lista = Array.isArray(response.campanas)
                ? response.campanas
                : (response.campanas?.data ?? []);

            const eventos = lista
                .filter(c => c.fecha_envio && !isNaN(new Date(c.fecha_envio)))
                .map(campana => {
                    const codigo = campana.info_estado?.codigo;
                    return {
                        id: campana.id,
                        title: campana.nombre,
                        start: new Date(campana.fecha_envio), // objeto Date, evita problemas de formato
                        backgroundColor: statusColors[codigo] ?? '#1E6F78',
                        borderColor: statusColors[codigo] ?? '#1E6F78',
                        extendedProps: {
                            status: codigo,
                            audience: campana.envios_activos?.length ?? 0,
                            messages: campana.envios_activos?.length ?? 0,
                            company: campana.empresa?.razon_social ?? '',
                            owner: campana.usuario?.nombre ?? '',
                            template: campana.plantilla?.name ?? '',
                            campana
                        }
                    };
                });

            successCallback(eventos); // FullCalendar los agrega solo
        } else {
            failureCallback(response);
        }

        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    };

    const error = (response) => {
        failureCallback(response);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    };

    generalidades.get(route('calendario.listado', datos), config, success, error);
};

const consultarCampanasHoy = () => {
    const config = {
        method: 'GET',
        headers: {
            Accept: generalidades.CONTENT_TYPE_JSON,
        },
    }

    const success = (response) => {
        if (response.estado == 'success') {
            document.getElementById('todayAgenda').innerHTML = renderList(response?.campanas, __('Sin campañas hoy'));
            document.getElementById('todayCount').textContent = response?.campanas?.length ?? 0;
        }
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    generalidades.get(route('calendario.listado-hoy', obtenerFiltros()), config, success, error);
}

const consultarCampanasProximas = () => {
    const config = {
        method: 'GET',
        headers: {
            Accept: generalidades.CONTENT_TYPE_JSON,
        },
    }

    const success = (response) => {
        if (response.estado == 'success') {
            document.getElementById('upcomingAgenda').innerHTML = renderList(response?.campanas, __('Sin campañas próximas'));
            document.getElementById('upcomingCount').textContent = response?.campanas.length ?? 0;
        }
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    generalidades.get(route('calendario.listado-proxima', obtenerFiltros()), config, success, error);
}

$(document).on('click', '.btnAplicar', function() {
    const fecha = $('#kt_datepicker_1').val();
    if (fecha) calendar.gotoDate(fecha); // salta al mes de esa fecha

    calendar.refetchEvents();
    consultarCampanasHoy();
    consultarCampanasProximas();
});

$(document).on('click', '.btnVer', function() {
    openDrawer(calendar.getEventById($(this).attr('data-registro')));
});

$(document).on('click', '#drawerOverlay, .drawer-close', function() {
    closeDrawer();
});

/* ESC closes drawer */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeDrawer();
});
