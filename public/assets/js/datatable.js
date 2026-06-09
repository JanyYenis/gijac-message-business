import $ from "jquery";
import 'datatables.net-dt';

const defaults = {
    //"paging": false,
    language: {
        aria: {
            sortAscending: ": activate to sort column ascending",
            sortDescending: ": activate to sort column descending"
        },
        processing: "Cargando...",
        emptyTable: "No hay datos en la tabla",
        info: "",
        infoEmpty: "",
        infoFiltered: "",
        lengthMenu: "_MENU_",
        search: "_INPUT_",
        searchPlaceholder: "Buscar",
        zeroRecords: "No se encontraron resultados",
        paginate: {
            next: 'Siguiente',
            previous: 'Atrás'
        },
    },
    loadingMessage: "Cargando...",
    // dom: `<'row'<'col-sm-12 col-xs-12 text-right pb-2 'B>r><'row'<'col-sm-6 col-xs-12'i><'col-sm-6 col-xs-12 dataTables_pager'lp>r><'table-scrollable't>`, // horizobtal scrollable datatable
};

$.extend(true, $.fn.dataTable.defaults, defaults);
