"use strict";

$(function () {
    filtrar();
});

window.verPlantilla = (id) => {
    $('#templatePreview').empty();
    const config = {
        'method': 'GET',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
    }

    const success = (response) => {
        generalidades.ocultarCargando('body');
        if (response.estado == 'success') {
            generalidades.toastrGenerico(response?.estado, response?.mensaje);
            $('#previewModal').modal('show');
            const template = response?.plantilla;
            if (!template) return;

            let previewHTML = '';

            // Header (image/video)
            if (template.header) {
                if (template.header.format === 1) {
                    let texto = template.header?.text ?? 'N/A';
                    // texto += JSON.parse(template.header.example)?.header_text[0] ?? '';
                    previewHTML += `<h3>${texto}</h3>`;
                } else if (template.header.format === 2) {
                    let url = JSON.parse(template.header.example).header_handle[0];
                    previewHTML += `<img src="${url}" alt="Header" class="template-header-media">`;
                } else if (template.header.format === 3) {
                    let url = JSON.parse(template.header.example).header_handle[0];
                    previewHTML += `<video src="${url}" controls class="template-header-media"></video>`;
                } else if (template.header.format === 4) {
                    let url = JSON.parse(template.header.example).header_handle[0];
                    previewHTML += `<a href="${url}" target="_blank"><img src="../../img/documento-defecto.png" alt="Header" class="template-header-media"></a>`;
                }
            }

            // Body
            if (template.body) {
                let bodyText = template.body.text
                    .replace(/{{1}}/g, 'Juan Pérez')
                    .replace(/{{2}}/g, 'ORD-2024-001')
                    .replace(/{{3}}/g, '299.99')
                    .replace(/{{4}}/g, '15 de Enero, 2024')
                    .replace(/{{5}}/g, 'Av. Siempre Viva 123')
                    .replace(/\*([^*]+)\*/g, '<strong>$1</strong>')
                    .replace(/\n/g, '<br>');

                previewHTML += `<div class="template-body">${bodyText}</div>`;
            }

            // Footer
            if (template.footer) {
                previewHTML += `<div class="template-footer">${template.footer.text}</div>`;
            }

            // Buttons
            if (template.buttons) {
                let botones = JSON.parse(template.buttons.buttons) ?? [];
                if (botones.length > 0) {
                    previewHTML += '<div class="template-buttons">';
                    botones.forEach(button => {
                        const buttonClass = button.type === 'PHONE_NUMBER' ? 'call-button' : '';
                        const icon = button.type === 'PHONE_NUMBER' ? '<i class="fas fa-phone me-1"></i>' :
                            button.type === 'URL' ? '<i class="fas fa-external-link-alt me-1"></i>' : '';
                        previewHTML += `<button type="button" class="template-button ${buttonClass}">${icon}${button.text}</button>`;
                    });
                    previewHTML += '</div>';
                }
            }

            // Add timestamp
            previewHTML += '<div class="message-time">12:34 <span class="text-success">✓✓</span></div>';

            document.getElementById('templatePreview').innerHTML = previewHTML;
            if (template?.name) {
                document.getElementById('previewModalLabel').innerHTML = `<i class="fab fa-whatsapp me-2"></i>Vista Previa - ${template?.name ?? 'N/A'}`;
            }
        }
    }

    const error = (response) => {
        generalidades.ocultarCargando('body');
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    generalidades.get(route('plantillas.show', { plantilla: id }), config, success, error);
    generalidades.mostrarCargando('body');
}

const filtrar = () => {
    $.ajax({
        type: 'POST',
        url: route('campanas.filtro-show', {campana: window.campana_id}),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content"),
        },
        data: {},
        success: function (response) {
            graficaHorarios(response.datos.labelHorarios, response.datos.serieHorarios);
            graficaAperturas(response.datos.cantidad_aperturas, response.datos.cantidad_no_aperturas);
            graficaABiertosClicks(response.datos.cantidad_aperturas, response.datos.cantidad_clicks);
        }
    });
}

function graficaABiertosClicks(cantidad_aperturas, cantidad_clicks) {
    // Clicks vs Opens Chart (Bar)
    const clicksOptions = {
        series: [{
            name: 'Abiertos',
            data: [cantidad_aperturas]
        }, {
            name: 'Con Clics',
            data: [cantidad_clicks]
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            }
        },
        colors: ['#10B981', '#F59E0B'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 8
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toLocaleString()
            }
        },
        xaxis: {
            categories: ['Resultados de la Campaña']
        },
        yaxis: {
            title: {
                text: 'Número de Contactos'
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toLocaleString() + " contactos"
                }
            }
        }
    };

    const clicksChart = new ApexCharts(document.querySelector("#clicksChart"), clicksOptions);
    clicksChart.render();
}

const graficaAperturas = (cantidad_aperturas, cantidad_no_aperturas) => {
    // Opening Distribution Chart (Pie)
    const openingOptions = {
        series: [cantidad_aperturas, cantidad_no_aperturas],
        chart: {
            type: 'pie',
            height: 350
        },
        labels: ['Abiertos', 'No Abiertos'],
        colors: ['#10B981', '#EF4444'],
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                return opts.w.config.series[opts.seriesIndex].toLocaleString()
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toLocaleString() + " contactos"
                }
            }
        }
    };

    const openingChart = new ApexCharts(document.querySelector("#openingChart"), openingOptions);
    openingChart.render();
}

const graficaHorarios = (labelHorarios, serieHorarios) => {
    const hourlyOptions = {
        series: [{
            name: 'Aperturas',
            data: serieHorarios
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: false
            }
        },
        colors: ['#25D366'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 90, 100]
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        xaxis: {
            categories: labelHorarios,
            title: {
                text: 'Hora del Día'
            }
        },
        yaxis: {
            title: {
                text: 'Número de Aperturas'
            }
        },
        tooltip: {
            x: {
                format: 'HH:mm'
            },
            y: {
                formatter: function (val) {
                    return val + " aperturas"
                }
            }
        }
    };

    const hourlyChart = new ApexCharts(document.querySelector("#hourlyChart"), hourlyOptions);
    hourlyChart.render();
}

$(document).on('change', '#campaignSelector', function() {
    window.location.href = route('campanas.show', {campana: this.value});
});
