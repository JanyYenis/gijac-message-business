const mix = require('laravel-mix');
const webpack = require("webpack");

mix.webpackConfig({
    resolve: {
        fallback: {
            crypto: require.resolve("crypto-browserify"),
            stream: require.resolve("stream-browserify"),
            buffer: require.resolve("buffer"),
            vm: require.resolve("vm-browserify"), // Agregando el polyfill para vm
        },
    },
    plugins: [
        new webpack.ProvidePlugin({
            process: "process/browser",
            Buffer: ["buffer", "Buffer"],
        }),
    ],
});


/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

// JS
mix.js("resources/js/jquery-validator.init.js", "public/js/jquery-validator.init.js");
mix.js("resources/js/home.js", "public/js/home.js");
mix.js("resources/js/prueba.js", "public/js/prueba.js");

// USUARIOS
mix.js("resources/js/usuarios/principal.js", "public/js/usuarios/principal.js");

// PERFIL DE USUARIOS
mix.js("resources/js/perfil/principal.js", "public/js/perfil/principal.js");

// 2FA
mix.js("resources/js/google2fa/principal.js", "public/js/google2fa/principal.js");

// NOTIFICACIONES
mix.js("resources/js/sistema/notificaciones.js", "public/js/sistema/notificaciones.js");

// ACCESO
mix.js("resources/js/sistema/accesos.js", "public/js/sistema/accesos.js");

// FACTURAS
mix.js("resources/js/facturas/crear.js", "public/js/facturas/crear.js");
mix.js("resources/js/facturas/principal.js", "public/js/facturas/principal.js");

// CHAT
mix.js("resources/js/chats/principal.js", "public/js/chats/principal.js");

// CAMPAÑAS
mix.js("resources/js/campanas/principal.js", "public/js/campanas/principal.js");
mix.js("resources/js/campanas/prediccion.js", "public/js/campanas/prediccion.js");
mix.js("resources/js/campanas/detalle.js", "public/js/campanas/detalle.js");
mix.js("resources/js/campanas/ver.js", "public/js/campanas/ver.js");

// CONTACTOS
mix.js("resources/js/contactos/principal.js", "public/js/contactos/principal.js");

// PLANTILLAS
mix.js("resources/js/plantillas/principal.js", "public/js/plantillas/principal.js");

// PERFIL DE WHATSAPP
mix.js("resources/js/perfil-whatsapp.js", "public/js/perfil-whatsapp.js");

// PALNES
mix.js("resources/js/planes/principal.js", "public/js/planes/principal.js");

// ETIQUETAS
mix.js("resources/js/etiquetas/principal.js", "public/js/etiquetas/principal.js");

// CONFIGURACIONES API WHATSAPP BUSINESS
mix.js("resources/js/configs/principal.js", "public/js/configs/principal.js");

// ETIQUETAS
mix.js("resources/js/tickets/principal.js", "public/js/tickets/principal.js");
mix.js("resources/js/tickets/editar.js", "public/js/tickets/editar.js");

// COMENTARIOS
mix.js("resources/js/comentarios/principal.js", "public/js/comentarios/principal.js");

// MODULO DE CONTACTAR CON GIJAC MESSAGUE BUSINESS
mix.js("resources/js/contactarnos/principal.js", "public/js/contactarnos/principal.js");

// CHATBOT
mix.js("resources/js/chatbots/principal.js", "public/js/chatbots/principal.js");
mix.js("resources/js/chatbots/crear.js", "public/js/chatbots/crear.js");
mix.js("resources/js/chatbots/editar.js", "public/js/chatbots/editar.js");
mix.js("resources/js/chatbots/demo.js", "public/js/chatbots/demo.js");

// CLASIFICACION CON IA
mix.js("resources/js/clasificacion-ia/principal.js", "public/js/clasificacion-ia/principal.js")

// ----------------------------------------------------------------------------------------------------
// Carpetas
mix.copyDirectory('resources/img', 'public/img');

// // --------------------------------------------------------------------------------------------------------------

// CSS
mix.styles(
    "resources/css/gmb.css",
    "public/css/gmb.css"
);

mix.styles(
    "resources/css/cel.css",
    "public/css/cel.css"
);

mix.styles(
    "resources/css/datatables.css",
    "public/css/datatables.css"
);

mix.styles(
    "resources/css/datatable-gijac.css",
    "public/css/datatable-gijac.css"
);

mix.styles(
    "resources/css/chats.css",
    "public/css/chats.css"
);

mix.styles(
    "resources/css/audio.css",
    "public/css/audio.css"
);

mix.styles(
    "resources/css/chatbot-n8n.css",
    "public/css/chatbot-n8n.css"
);

mix.styles(
    "resources/css/llamada.css",
    "public/css/llamada.css"
);
