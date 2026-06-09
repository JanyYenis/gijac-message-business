"use strict";

const defaults = {
    language: {
        aria: {
            sortAscending: ": activate to sort column ascending",
            sortDescending: ": activate to sort column descending"
        },
        processing: "Cargando...",
        emptyTable: "No hay datos en la tabla",
        info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        infoEmpty: "Ningún dato disponible en esta tabla",
        infoFiltered: "(filtrado de un total de _MAX_ registros)",
        lengthMenu: "_MENU_",
        search: "_INPUT_",
        searchPlaceholder: "Buscar",
        zeroRecords: "No se encontraron resultados",
        paginate: {
            first: '<i class="la la-angle-double-left"></i>',
            last: '<i class="la la-angle-double-right"></i>',
            next: '<i class="la la-angle-right"></i>',
            previous: '<i class="la la-angle-left"></i>'
        }
    },
    processing: false,
    loadingMessage: "Cargando...",
    orderCellsTop: true,
    autoWidth: false,
    stateSave: false,
    deferRender: true,
    colReorder: true,
    destroy: true,
    pagingType: "full_numbers",
    colReorder: {
        reorderCallback: function () {
            // console.log("callback");
        }
    },
    order: [
        [0, "asc"]
    ],
    lengthMenu: [
        [5, 10, 15, 20, -1],
        [5, 10, 15, 20, "Todos"] // change per page values here
    ],
    pageLength: 5,
    initComplete: function () {
        var thisTable = this;
        var rowFilter = $(this).find(".filterDT");

        // hide search column for responsive table
        var hideSearchColumnResponsive = function () {
            thisTable
                .api()
                .columns()
                .every(function () {
                    var column = this;
                    if (column.responsiveHidden()) {
                        $(rowFilter)
                            .find("th")
                            .eq(column.index())
                            .show();
                    } else {
                        $(rowFilter)
                            .find("th")
                            .eq(column.index())
                            .hide();
                    }
                });

            if (window.screen.width <= 1024) {
                thisTable.DataTable().fixedHeader.disable();
            } else {
                thisTable.DataTable().fixedHeader.enable();
            }
        };

        // init on datatable load
        hideSearchColumnResponsive();
        // recheck on window resize
        window.onresize = hideSearchColumnResponsive;
    }
};

$.extend(true, $.fn.dataTable.defaults, defaults);

$(function () {
    $.fn.dataTable.ext.errMode = "none";
});
