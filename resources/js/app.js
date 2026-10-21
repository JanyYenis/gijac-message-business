import 'jquery-validation/dist/localization/messages_es.js';
import "jquery.quicksearch";
import { driver } from "driver.js";
import "driver.js/dist/driver.css";
import "bootstrap-icons/font/bootstrap-icons";

window.driver = driver;
window.intlTelInput = require("intl-tel-input");

$(function() {
    /* ---------- Header scroll effect ---------- */
    const $header = $('#kt_app_header');
    const $backTop = $('#back-to-top');
    function onScroll() {
        const y = window.scrollY;
        $header.toggleClass('scrolled', y > 30);
        $backTop.toggleClass('show', y > 500);
    }
    $(window).on('scroll', onScroll);
    onScroll();

    $backTop.on('click', function () {
        $('html, body').animate({ scrollTop: 0 }, 700);
    });
})

require('./generalidades');
require('./datatables.init');
require('./bootstrap');
require("fslightbox");
