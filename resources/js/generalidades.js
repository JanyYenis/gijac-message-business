"use strict";
export default class Generalidades {

    constructor() {
        $.validator = "";
        // $.validator.messages.required = "";
        this.token = $('meta[name="csrf-token"]').attr("content");
    }

}

$(document).on('hidden.bs.modal', '.modal', function () {
    if ($('.modal.show').length === 0) {
        $('body').removeClass('modal-open');
        $('body').css({
            overflow: '',
            paddingRight: ''
        });

        $('.modal-backdrop').remove();
    }
});

require('./generalidades/formularios');
require('./generalidades/genericos');
require('./generalidades/peticiones');
require('./generalidades/mis-genericos');

window.generalidades = new Generalidades();
