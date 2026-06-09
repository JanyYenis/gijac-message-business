"use strict";

const fromFiltros = '#fromFiltros';
var chartLunea, chartMartes, chartMiercoles, chartJueves, chartViernes, chartSabado, chartDomingo, chartAlcance, chartEtiquetas;
var colorPalette = ['#00D8B6', '#008FFB', '#FEB019', '#FF4560', '#775DD0'];
let campaignsChart = null;

$(function () {
    // chartEfectividad();
    iniciarComponentes();
    generalidades.validarFormulario(fromFiltros, filtrar);
    filtrar();
});

const iniciarComponentes = () => {

    var start = moment().subtract(29, "days");
    var end = moment();

    $("#inputFechas").daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            "Hoy": [moment(), moment()],
            "Ayer": [moment().subtract(1, "days"), moment().subtract(1, "days")],
            "Últimos 7 días": [moment().subtract(6, "days"), moment()],
            "Últimos 30 días": [moment().subtract(29, "days"), moment()],
            "Este mes": [moment().startOf("month"), moment().endOf("month")],
            "El mes pasado": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
        },
        locale: {
            format: 'YYYY-MM-DD', // Formato de fecha
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Rango personalizado',
            weekLabel: 'S',
            daysOfWeek: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            monthNames: [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre',
            ],
        },
    }, cb);

    cb(start, end);

    var drawerEl = document.querySelector("#kt_drawer_example_basic");
    var drawer = KTDrawer.getInstance(drawerEl);
    drawer.on("kt.drawer.shown", function () {
        // Intenta encontrar el elemento cada 100ms (hasta 2 segundos)
        $("#fromFiltros #selectEtiquetas").select2({
            allowClear: true,
            placeholder: 'Seleccione la o las etiquetas',
            ajax: {
                url: route('etiquetas.buscar'),   // ruta de tu backend Laravel
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        busqueda: params.term // término de búsqueda
                    };
                },
                processResults: function (data) {
                    // Aquí conviertes la respuesta en el formato que Select2 entiende
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.text
                            };
                        })
                    };
                },
                cache: true
            }
        });
        $("#fromFiltros #selectContactos").select2({
            allowClear: true,
            placeholder: 'Seleccione el o los contactos',
            ajax: {
                url: route('contactos.buscar'),   // ruta de tu backend Laravel
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        busqueda: params.term // término de búsqueda
                    };
                },
                processResults: function (data) {
                    // Aquí conviertes la respuesta en el formato que Select2 entiende
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.text
                            };
                        })
                    };
                },
                cache: true
            }
        });
    });
}

const cb = (start, end) => {
    $("#inputFechas").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
}

const chartEtiqueta = (seriesEtiquetas) => {
    if (chartEtiquetas) {
        chartEtiquetas.destroy();
    }

    // ApexCharts - Tags Distribution (Pie)
    let opciones = {
        series: seriesEtiquetas['sales'],
        chart: {
            type: 'pie',
            height: 350
        },
        labels: seriesEtiquetas['etiqueta'],
        colors: seriesEtiquetas['colores'],
        legend: {
            position: 'bottom'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    chartEtiquetas = new ApexCharts(document.querySelector("#tagsChart"), opciones);
    chartEtiquetas.render();
}

const graficasDias = (nombreChartGlobal, datos, labels, div) => {
    if (window[nombreChartGlobal]) {
        window[nombreChartGlobal].destroy();
    }

    var options = {
        series: datos,
        chart: {
            type: 'area',
            stacked: false,
            height: 250,
            zoom: {
                type: 'x',
                enabled: true,
                autoScaleYaxis: true
            },
            toolbar: {
                // autoSelected: 'zoom'
                tools: {
                    zoom: false,            // Deshabilita la opción de zoom
                    zoomin: false,          // Deshabilita la opción de acercar
                    zoomout: false,         // Deshabilita la opción de alejar
                    pan: false,             // Deshabilita la opción de panorámica
                    reset: false,           // Deshabilita la opción de restablecer el zoom
                    download: true          // Habilita la opción de descarga
                }
            },
        },
        colors: ['#25D366'],
        dataLabels: {
            enabled: false
        },
        markers: {
            size: 0,
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                inverseColors: false,
                opacityFrom: 0.5,
                opacityTo: 0,
                stops: [0, 90, 100]
            },
        },
        legend: {
            position: 'top',  // Mueve las leyendas a la parte superior
            horizontalAlign: 'center' // Alinea las leyendas en el centro
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return val;
                },
            },
            title: {
                text: 'Cantidad Aperturas'
            },
        },
        xaxis: {
            categories: labels,
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false
            },
            tooltip: {
                enabled: true,
                formatter: undefined,
                offsetY: 0,
                style: {
                    fontSize: '12px'
                }
            }
        },
        tooltip: {
            shared: false,
            y: {
                formatter: function (val) {
                    return val
                }
            }
        }
    };

    window[nombreChartGlobal] = new ApexCharts(document.querySelector(div), options);
    window[nombreChartGlobal].render();
}

const graficaTop = (alcance, aperturas, fallos) => {
    var options = {
        series: [
            {
                name: "Total",
                data: [alcance, aperturas, fallos],
            },
        ],
        chart: {
            type: 'bar',
            height: 250,
        },
        colors: ['#25D366'],
        plotOptions: {
            bar: {
                borderRadius: 0,
                horizontal: true,
                barHeight: '80%',
                isFunnel: true,
            },
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opt) {
                return opt.w.globals.labels[opt.dataPointIndex] + ':  ' + val
            },
            dropShadow: {
                enabled: true,
            },
        },
        // title: {
        //     text: 'Embudo de efectividad',
        //     align: 'middle',
        // },
        xaxis: {
            categories: [
                'Alcance',
                'Aperturas',
                // 'Clicks',
                'Fallos',
            ],
        },
        legend: {
            show: false,
        },
    };

    if (chartAlcance) {
        chartAlcance.destroy();
    }
    chartAlcance = new ApexCharts(document.querySelector("#chartEmbudo"), options);
    chartAlcance.render();
}

const filtrar = () => {
    let fechas = $("#inputFechas").val();
    let contactos = $("#selectContactos").val();
    let etiquetas = $("#selectEtiquetas").val();

    $.ajax({
        type: 'POST',
        url: route('filtro'),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            fechas: fechas,
            contactos: contactos,
            etiquetas: etiquetas,
        },
        success: function (response) {
            $('#totalCampaigns').text(response?.cantidad_campanas ?? 0);
            $('#totalContacts').text(response?.cantidad_contactos ?? 0);
            $('#effectiveness').text(response?.cantidad_efectividad ? response?.cantidad_efectividad + '<span style="font-size: 1.5rem;">%</span>' : '0<span style="font-size: 1.5rem;">%</span>');
            $('#diaEfectivo').text(response?.diaMaxEfectividad ? response?.diaMaxEfectividad : 'N/A');

            animateValue('totalContacts', 0, response?.cantidad_contactos ?? 0, 2000);
            animateValue('totalCampaigns', 0, response?.cantidad_campanas ?? 0, 2000);
            animateValue('effectiveness', 0, response?.cantidad_efectividad ?? 0, 2000);

            chartEtiqueta(response.seriesEtiquetas);
            graficaTop(response.alcance, response.aperturas, response.fallos);
            graficasDias('chartLunea', response.seriesLunes, response.lablesLunes, "#diaLunes");
            graficasDias('chartMartes', response.seriesMartes, response.lablesMartes, "#diaMartes");
            graficasDias('chartMiercoles', response.seriesMiercoles, response.lablesMiercoles, "#diaMiercoles");
            graficasDias('chartJueves', response.seriesJueves, response.lablesJueves, "#diaJueves");
            graficasDias('chartViernes', response.seriesViernes, response.lablesViernes, "#diaViernes");
            graficasDias('chartSabado', response.seriesSabado, response.lablesSabado, "#diaSabado");
            graficasDias('chartDomingo', response.seriesDomingo, response.lablesDomingo, "#diaDomingo");
            graficaCampanas(response.campanas_por_mes);
        }
    });
}

// Set current date
document.getElementById('currentDate').textContent = new Date().toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
});


const graficaCampanas = (campanasPorMes) => {

    if (campaignsChart) {
        campaignsChart.destroy();
    }

    // ApexCharts - Campaigns by Month
    const campaignsOptions = {
        series: [{
            name: 'Campañas',
            data: campanasPorMes
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            }
        },
        colors: ['#25D366'],
        plotOptions: {
            bar: {
                borderRadius: 8,
                columnWidth: '60%'
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            }
        },
        yaxis: {
            title: {
                text: 'Número de Campañas'
            }
        },
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 4
        }
    };

    campaignsChart = new ApexCharts(document.querySelector("#campaignsChart"), campaignsOptions);
    campaignsChart.render();
}



// Animate numbers on load
function animateValue(id, start, end, duration) {
    const obj = document.getElementById(id);
    const range = end - start;
    const minTimer = 50;
    let stepTime = Math.abs(Math.floor(duration / range));
    stepTime = Math.max(stepTime, minTimer);
    const startTime = new Date().getTime();
    const endTime = startTime + duration;
    let timer;

    function run() {
        const now = new Date().getTime();
        const remaining = Math.max((endTime - now) / duration, 0);
        const value = Math.round(end - (remaining * range));

        if (id === 'effectiveness') {
            obj.innerHTML = value.toFixed(1) + '<span style="font-size: 1.5rem;">%</span>';
        } else {
            obj.textContent = value.toLocaleString();
        }

        if (value == end) {
            clearInterval(timer);
        }
    }

    timer = setInterval(run, stepTime);
    run();
}
