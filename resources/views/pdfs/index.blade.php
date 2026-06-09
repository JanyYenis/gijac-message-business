<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $titulo ?? 'PDF GIJAC WEB' }}</title>
    <meta name="description" content="Plataforma de procesos de GIJAC WEB">
    <meta name="keywords"
        content="
        tailwind, tailwindcss, metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js,
        Node.js, Flask, Symfony &amp; Laravel starter kits, admin themes, web design, figma, web development, free templates,
        free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button,
        bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon
    ">
    <meta property="og:locale" content="es_ES">
    <meta property="og:type" content="article">
    <meta property="og:title" content="GIJAC WEB - Te ayudamos a crecer">
    <meta property="og:url" content="https://message-business.gijac.com">
    <meta property="og:site_name" content="GIJAC WEB">

    <link rel="shortcut icon" href="{{ asset('img/logo_mini.png') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<style>
    @import url('https://fonts.cdnfonts.com/css/open-sans');
    @import url('https://fonts.cdnfonts.com/css/satoshi');

    @page {
        margin: 0cm 0cm;
    }

    /** Defina ahora los márgenes reales de cada página en el PDF **/
    body {
        margin-top: 3.7cm;
        margin-left: 2cm;
        margin-right: 2cm;
        margin-bottom: 2cm;
        font-size: 12px;
    }

    .logoGijac {
        position: absolute;
        width: 120px;
        height: 85px;
        left: 80px;
        top: -2%;
    }

    .logoConte {
        position: fixed;
        left: 20%;
        width: 500px;
        height: 480px;
        top: 30%;
        z-index: -100;
        opacity: 0.3;
    }

    .titulo {
        font-size: 20px;
        color: #0b0e8d;
    }

    .textoFooder {
        text-align: center;
    }

    .lista {
        padding-left: 10%
    }

    .firma {
        padding-top: 7%
    }

    footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2cm;
        text-align: center;
        margin-bottom: 3%;
    }

    header {
        margin-top: 3%;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 2cm;
        text-align: center;
    }

    p {
        font-size: 1.15rem!important;
        text-align: justify;
    }

    ul {
        font-size: 1.15rem!important
    }

    li {
        font-size: 1.15rem!important
    }

    .firmaGijac {
        width: 30%;
    }

    .text-primary-gijac {
        --bs-text-opacity: 1;
        color: #04dce9 !important
    }

    /* Estilos simplificados para PDF */
    .pdf-row {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    .pdf-col {
        display: table-cell;
    }

    .fs-1 {
        font-size: calc(1.3rem + .6vw)!important
    }

    .fs-2 {
        font-size: calc(1.275rem + .3vw)!important
    }

    .fs-3 {
        font-size: calc(1.26rem + .12vw)!important
    }

    .fs-4 {
        font-size: 1.25rem!important
    }

    .fs-5 {
        font-size: 1.15rem!important
    }

    .fs-6 {
        font-size: 1.075rem!important
    }

    .fs-7 {
        font-size: .95rem!important
    }

    .fs-8 {
        font-size: .85rem!important
    }

    .fs-9 {
        font-size: .75rem!important
    }

    .fs-10 {
        font-size: .5rem!important
    }

    .fs-sm {
        font-size: .95rem!important
    }

    .fs-base {
        font-size: 1rem!important
    }

    .fs-lg {
        font-size: 1.075rem!important
    }

    .fs-xl {
        font-size: 1.21rem!important
    }

    .fs-fluid {
        font-size: 100%!important
    }

    .fs-2x {
        font-size: calc(1.325rem + .9vw)!important
    }

    .fs-2qx {
        font-size: calc(1.35rem + 1.2vw)!important
    }

    .fs-2hx {
        font-size: calc(1.375rem + 1.5vw)!important
    }

    .fs-2tx {
        font-size: calc(1.4rem + 1.8vw)!important
    }

    .fs-3x {
        font-size: calc(1.425rem + 2.1vw)!important
    }

    .fs-3qx {
        font-size: calc(1.45rem + 2.4vw)!important
    }

    .fs-3hx {
        font-size: calc(1.475rem + 2.7vw)!important
    }

    .fs-3tx {
        font-size: calc(1.5rem + 3vw)!important
    }

    .fs-4x {
        font-size: calc(1.525rem + 3.3vw)!important
    }

    .fs-4qx {
        font-size: calc(1.55rem + 3.6vw)!important
    }

    .fs-4hx {
        font-size: calc(1.575rem + 3.9vw)!important
    }

    .fs-4tx {
        font-size: calc(1.6rem + 4.2vw)!important
    }

    .fs-5x {
        font-size: calc(1.625rem + 4.5vw)!important
    }

    .fs-5qx {
        font-size: calc(1.65rem + 4.8vw)!important
    }

    .fs-5hx {
        font-size: calc(1.675rem + 5.1vw)!important
    }

    .fs-5tx {
        font-size: calc(1.7rem + 5.4vw)!important
    }

    .fs-6x {
        font-size: calc(1.725rem + 5.7vw)!important
    }

    .fs-6qx {
        font-size: calc(1.75rem + 6vw)!important
    }

    .fs-6hx {
        font-size: calc(1.775rem + 6.3vw)!important
    }

    .fs-6tx {
        font-size: calc(1.8rem + 6.6vw)!important
    }

    .fs-7x {
        font-size: calc(1.825rem + 6.9vw)!important
    }

    .fs-7qx {
        font-size: calc(1.85rem + 7.2vw)!important
    }

    .fs-7hx {
        font-size: calc(1.875rem + 7.5vw)!important
    }

    .fs-7tx {
        font-size: calc(1.9rem + 7.8vw)!important
    }

    .text-start {
        text-align: left!important
    }

    .text-end {
        text-align: right!important
    }

</style>
@section('css')
@show

<body>
    <header>
        <div class="">
            <img class="logoGijac" src="data:image/png;base64,{{base64_encode(file_get_contents(base_path('public/img/logo.png')))}}"  alt="">
        </div>
    </header>
    <img class="logoConte" src="data:image/png;base64,{{base64_encode(file_get_contents(base_path('public/img/logo_mini.png')))}}"  alt="">
    <div class="contenido">
        @yield('content')
    </div>
</body>
</html>
