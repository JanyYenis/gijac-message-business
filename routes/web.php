<?php

use App\Http\Controllers\Auth\OutlookController;
use App\Http\Controllers\CampanaController;
use App\Http\Controllers\ContactoPaginaController;
use App\Http\Controllers\EpaycoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceTemporalController;
use App\Http\Controllers\PerfilWhatsappController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PreguntasController;
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\Sistema\PoliticaPrivacidadController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VideotutorialesController;
use App\Http\Controllers\WompiController;
use App\Models\Chatbot;
use App\Models\ChatbotNode;
use App\Models\ChatbotOption;
use App\Models\ConfiguracionMeta;
use App\Models\Contacto;
use App\Models\EnvioCampana;
use App\Models\Mensaje;
use App\Models\Plan;
use App\Models\Plantilla;
use App\Models\Sistema\Autenticacion;
use App\Models\Usuario;
use App\Models\VariableCampana;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use libphonenumber\PhoneNumberUtil;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Stichoza\GoogleTranslate\GoogleTranslate;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Auth::routes(['verify' => true]);

Route::get('/precios', [HomeController::class, 'precios'])->name('precios');
Route::get('/contactarnos', [ContactoPaginaController::class, 'index'])->name('contactarnos');
Route::post('/contactarnos/guardar', [ContactoPaginaController::class, 'store'])->name('contactarnos.store');

Route::get('/campanas/redireccion/{telefono}/{idCampana}/{indexBtn}/link', [CampanaController::class, 'redireccionLink']);


Route::get('/chatbot-n8n', function (\Illuminate\Http\Request $request) {
    $userMessage = $request->input('message');

    $response = Http::post('https://jany1207.app.n8n.cloud/webhook/d2081356-62b0-4cac-970e-58d070efc7fe/chat', [
        'chatInput' => 'Hola',
        'sessionId' => '573152094191', // Para mantener la sesión
        // 'sessionId' => $request->session()->getId(), // Para mantener la sesión
    ]);

    dd($response->json());
});

Route::middleware(['web', 'auth', '2fa', 'verified', 'verify.company'])->group(function () {
    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])
        ->name('home');
    Route::post('/filtro', [HomeController::class, 'filtro'])
        ->name('filtro');

    // Perfil de usuario
    Route::get('/perfil', [UsuarioController::class, 'show'])
        ->name('perfil');
    Route::post('/perfil/{usuario}/actualizar', [UsuarioController::class, 'update'])
        ->name('perfil.update');
    Route::put('/perfil/{usuario}/actualizar/email', [UsuarioController::class, 'actualizarEmail'])
        ->name('perfil.email');
    Route::put('/perfil/{usuario}/actualizar/contraseña', [UsuarioController::class, 'actualizarContrasena'])
        ->name('perfil.contrasena');

    // Perfil WhatsApp
    Route::get('/perfil-WhatsApp', [PerfilWhatsappController::class, 'edit'])
        ->name('perfilWhatsapp');
    Route::post('/perfil-WhatsApp/actualizar', [PerfilWhatsappController::class, 'update'])
        ->name('perfilWhatsapp.update');

    Route::post('/validar-invoice', [InvoiceTemporalController::class, 'validarInvoiceActivo'])
        ->name('validar.invoice.activo');

    Route::post('/2fa', function () {
        return redirect(route('home'));
    })->name('2fa');
});

Route::get('/epayco/bancos', [EpaycoController::class, 'obtenerBancos'])
        ->withoutMiddleware(['auth']);
Route::post('/epayco/pago', [EpaycoController::class, 'transaccionPse'])
    ->name('epayco.pago')
    ->withoutMiddleware(['auth']);
Route::get('/epayco/callback', [EpaycoController::class, 'callback'])
    ->name('epayco.callback')
    ->withoutMiddleware(['auth']);
Route::post('/epayco/confirmation', [EpaycoController::class, 'confirmation'])
    ->name('epayco.confirmation')
    ->withoutMiddleware(['auth']);

Route::post('/verify2FA', [UsuarioController::class, 'verify2FA'])->name('verify2FA');

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['es', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Web
Route::get('/preguntas-frecuentes', [PreguntasController::class, 'index'])->name('preguntas');
Route::get('/videotutoriales', [VideotutorialesController::class, 'index'])->name('videotutoriales');
Route::get('/pricing', [PlanController::class, 'show'])->name('show');

// Politicas de privacidad
Route::get('politicas-de-privacidad', [PoliticaPrivacidadController::class, 'index'])->name('politicas-privacidad');

// Politicas de privacidad
Route::get('terminos-y-condiciones', function() {
    return view('sistema.terminos-condiciones.index');
})->name('terminos-condiciones');

// Protocolo para eliminacion de datos
Route::get('eliminacion-de-datos', function() {
    return view('sistema.eliminacion-datos.index');
})->name('eliminacion-datos');

// Auth Redes
Route::get('/login-google', function () {
    return Socialite::driver('google')->redirect();
})->name('login-google');

Route::get('/google-callback', function () {
    $usuario = Socialite::driver('google')->user();

    $validarUsuario = Usuario::where([
        'email'  => $usuario?->email,
        'estado' => Usuario::ACTIVO,
    ])->first();

    if ($validarUsuario) {
        $validarAutenticaciones = Usuario::with('autenticacion')->where([
        'email'  => $usuario?->email,
        'estado' => Usuario::ACTIVO,
        ])
        ->whereHas('autenticacion', function($query) use($usuario) {
        $query->where([
            'external_id'   => $usuario?->id,
            'external_auth' => 'google'
        ]);
        })
        ->first();

        if (!$validarAutenticaciones) {
            Autenticacion::updateOrCreate([
                'cod_usuario' => $validarUsuario->id,
                'external_auth' => 'google',
            ], [
                'external_id' => $usuario->id,
            ]);
        }

        Auth::login($validarUsuario);

        return redirect(route('home'));
    } else {
        $validarUsuario = Usuario::create([
            'nombre' => $usuario?->user['given_name'] ?? 'N/A',
            'apellido' => $usuario?->user['family_name'] ?? 'N/A',
            'email' => $usuario?->email,
            'foto' => $usuario?->avatar,
            'demo' => 1,
            'external_id' => $usuario?->id,
        ]);

        $validarUsuario->assignRole(Usuario::ROL_CLIENTE);

        Autenticacion::updateOrCreate([
            'cod_usuario' => $validarUsuario->id,
            'external_auth' => 'google',
        ], [
            'external_id' => $usuario->id,
        ]);

        Auth::login($validarUsuario);
    }

    return redirect(route('login'))->with('error', 'El usuario no se encuentra en nuestra base de datos.');
});

Route::get('/login-outlook', [OutlookController::class, 'index'])->name('login-outlook');
Route::get('/outlook-callback', [OutlookController::class, 'redireccion']);

Route::post('/wompi/pago', [WompiController::class, 'crearTransaccion']);
Route::get('/wompi/callback', [WompiController::class, 'callback'])->name('wompi.callback');

// Route::get('/prueba', [PruebaController::class, 'index']);

Route::get('/prueba', function(){
    dd(Plantilla::with('componentes')->find(3209960805854522));
    dd(Usuario::darTipoDocumento());
    $config = ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)
        ->where('app_id', '189706530645476')
        ->first();
    $whatsapp_cloud_api = new WhatsAppCloudApi([
        'from_phone_number_id' => $config->phone_number_id,
        'access_token' => $config->token,
        'graph_version' => $config->version,
    ]);

    $usuario = Usuario::find(1);
    $dosMeses = Carbon::now()->subMonths(2);

    $datos = Contacto::selectRaw(
            'contactos.id, CONCAT(contactos.nombre, " ", contactos.apellido) AS nombre_cliente,
            envios_campanas.apertura, envios_campanas.fecha_apertura, envios_campanas.telefono,
            envios_campanas.wamid, campanas.nombre AS nombre_campana,
            campanas.contenido AS contenido_campana, campanas.fecha_envio'
        )
        ->join('envios_campanas', 'envios_campanas.cod_contacto', '=', 'contactos.id')
        ->join('campanas', 'campanas.id', '=', 'envios_campanas.cod_campana')
        ->where('contactos.id', '45bf225e-f996-40c2-907a-c7e3df925aaf')
        ->where('contactos.estado', Contacto::ACTIVO)
        ->where('campanas.fecha_envio', '>=', $dosMeses)
        ->orderBy('campanas.fecha_envio', 'desc')
        ->get();

    $info = [];

    if (count($datos)) {
        $info['id'] = $datos[0]->id;
        $info['nombre_cliente'] = $datos[0]->nombre_cliente;
        $info['telefono'] = $datos[0]->telefono;
        $info['envios'] = [];
        foreach ($datos as $dato) {
            $info['envios']['nombre_campana'] = $dato->nombre_campana;
            $info['envios']['contenido_campana'] = $dato->contenido_campana;
            $info['envios']['apertura'] = $dato->apertura;
            $info['envios']['fecha_apertura'] = $dato->fecha_apertura;
            $info['envios']['fecha_envio'] = $dato->fecha_envio;
        }
    }

    dd($datos, $info);

    // $response = Http::withToken($config->token)
    //     ->post("https://graph.facebook.com/v24.0/" . $config->phone_number_id . "/calls", [
    //         'to' => '573152094191',
    //         'call_type' => 'audio'
    //     ]);

    // if ($response->failed()) {
    //     return response()->json([
    //         'error' => true,
    //         'details' => $response->json()
    //     ], 400);
    // }

    // return response()->json([
    //     'success' => true,
    //     'data' => $response->json()
    // ]);

    // $valorChat = 'ada297ca-0fdc-489f-af9e-e535f6bc1c86';
    // // $valorChat = null;

    // if (!$valorChat) {
    //     $bot = Chatbot::with('nodeInmediato.opciones', 'nodePrincipal.opciones')
    //         ->where('estado', Chatbot::ACTIVO)
    //         ->where('uuid', $config->uuid)
    //         ->first();

    //     if ($bot) {
    //         // dd($bot);
    //         // Datos base del mensaje
    //         $datos = [
    //             'campaign_id' => null,
    //             'contact_id'  => $config->uuid,
    //             'wa_message_id' => null,
    //             'wa_from' => $config->phone_number_id,
    //             'wa_to'   => '573152094191',
    //             'type'    => Mensaje::TEXTO,
    //             'body'    => null,
    //             'metadata'=> null,
    //             'estado'  => Mensaje::ENVIADO,
    //             'sent_at' => now(),
    //         ];
    //         if ($bot->nodePrincipal) {
    //             $principal = $bot->nodePrincipal;
    //             $datos['body'] = $principal->message;
    //             if ($principal->type == ChatbotNode::TEXTO) {
    //                 $response = $whatsapp_cloud_api->sendTextMessage('573152094191', $principal->message);
    //                 if ($response?->body()) {
    //                     $data = json_decode($response?->body());
    //                     $messages = $data->messages ?? [];
    //                     $datos['wa_message_id'] = $messages[0]->id ?? null;
    //                 }

    //                 $mensaje = Mensaje::create($datos);
    //                 if (!$mensaje) {
    //                     dd('Error');
    //                 }
    //             }
    //         }

    //         if (count($bot->nodeInmediato)) {
    //             foreach ($bot->nodeInmediato as $node) {
    //                 $datos['body'] = $node->message;
    //                 if ($node->type == ChatbotNode::TEXTO) {
    //                     dump('Texto');
    //                     $response = $whatsapp_cloud_api->sendTextMessage('573152094191', $node->message);
    //                 } else if ($node->type == ChatbotNode::BOTON) {
    //                     dump('Boton');
    //                     $rows = [];
    //                     $buttons = [];
    //                     foreach ($node->opciones as $opcion) {
    //                         $rows[] = new Button($opcion->id, $opcion->label);
    //                         $buttons[] = ['type' => 'text', 'text' => $opcion->label];
    //                     }
    //                     $action = new ButtonAction($rows);

    //                     $response = $whatsapp_cloud_api->sendButton(
    //                         '573152094191',
    //                         $node->message,
    //                         $action,
    //                     );

    //                     $datos['metadata'] = (object) [
    //                         "tipo_header" => null,
    //                         "header"      => null,
    //                         "footer"      => null,
    //                         "buttons"     => json_encode($buttons),
    //                         "variables"   => []
    //                     ];
    //                 }

    //                 if ($response?->body()) {
    //                     $data = json_decode($response?->body());
    //                     $messages = $data->messages ?? [];
    //                     $datos['wa_message_id'] = $messages[0]->id ?? null;
    //                 }

    //                 $mensaje = Mensaje::create($datos);
    //                 if (!$mensaje) {
    //                     dd('Error');
    //                 }
    //             }
    //         }
    //     }
    // } else {
    //     $opcion = ChatbotOption::with('node')->find($valorChat);
    //     if ($opcion->next_node_id) {
    //         $node = ChatbotNode::where('number', $opcion->next_node_id)
    //             ->where('chatbot_id', $opcion->node->chatbot_id)
    //             ->first();

    //         dd('ada297ca-0fdc-489f-af9e-e535f6bc1c86');
    //     }
    // }

    // dd('FIN');

    // $plan = Plan::find('6e979274-0662-46a4-b5ae-335eea58a38f');
    // if ($plan->tieneServicio('chatbots.avanzados')) {
    //     dd('SI');
    // }
    // dd('NO');

    // $plantilla = Plantilla::with(
    //     'header',
    //     'body',
    //     'footer',
    //     'buttons',
    // )->find(1275424030632045);

    // $contacto = Contacto::find('45bf225e-f996-40c2-907a-c7e3df925aaf');
    // // dd($plantilla);

    // $tipo = Mensaje::PLANTILLA;
    // $estado = Mensaje::ENVIADO;
    // $mensaje =  $plantilla?->body?->text ?? null;
    // $variablesDetalle = VariableCampana::where('cod_campana', 'a78849db-c767-48e1-bc4e-3bddc593fecf')
    //     ->where('tipo', VariableCampana::TEXT)
    //     ->get();
    // $valores = [];
    // foreach ($variablesDetalle as $key => $value) {
    //     $llave = "{{".($key+1)."}}";
    //     $valores[$llave] = $value->valor;
    //     $campo = $value?->valor;
    //     if (isset($contacto->$campo)) {
    //         $valores[$llave] = $contacto->$campo;
    //     }
    // }

    // $mensaje = str_replace(array_keys($valores), array_values($valores), $mensaje);
    // $tipo_header = $plantilla?->header?->format ?? null;
    // // dd($tipo_header);
    // $header = null;
    // if ($tipo_header && in_array($tipo_header, [Mensaje::IMAGEN, Mensaje::VIDEO, Mensaje::DOCUMENTO])) {
    //     $header = 'https://ac5fb9361857.ngrok-free.app/descargas/meta_1756427549.png' ?? null;
    // } elseif ($tipo_header && $tipo_header == Mensaje::TEXTO) {
    //     $header = $plantilla?->header?->text ?? null;
    // }
    // $fooder = $plantilla?->footer?->text ?? null;
    // $buttons = $plantilla?->buttons?->buttons ?? '';

    // $idMensaje = 'wamid.HBgMNTczMTUyMDk0MTkxFQIAERgSNDkzQUQ4QUE3MkFGQTE1NjgyAA==';
    // $mensajeEnviado = Mensaje::updateOrCreate([
    //     "campaign_id"   => 'a78849db-c767-48e1-bc4e-3bddc593fecf',
    //     "contact_id"    => $contacto->id,
    //     "wa_message_id" => $idMensaje ?? null,
    // ],[
    //     "wa_from"       => '110450798716118',
    //     "wa_to"         => $contacto->numero_completo,
    //     "type"          => $tipo, // en tu caso: Mensaje::PLANTILLA
    //     "body"          => $mensaje, // el texto final con variables reemplazadas
    //     "metadata"      => (object) [
    //         "tipo_header" => $tipo_header,
    //         "header"      => $header,
    //         "footer"      => $fooder,
    //         "buttons"     => $buttons ?? '',
    //         "variables"   => $valores ?? []
    //     ],
    //     "estado"        => $estado, // Mensaje::ENVIADO
    //     "sent_at"       => now(),
    // ]);

    // if (!$mensajeEnviado) {
    //     dd('Error al registrar el mensaje.');
    // }

    // dd('OK');


    // $tr = new GoogleTranslate('en'); // Target: inglés
    // echo $tr->translate('Hola mundo'); // "Hello world"
    // $response = Http::timeout(60)->withHeaders([
    //     'Content-Type' => 'application/json',
    // ])->post('http://127.0.0.1:8000/chat', [
    //     'question' => "Cuales son los pasos para implementar GIJAC MEESSAGE BUSINESS segun el instructivo",
    //     'phone_number' => '573152094191',
    // ]);

    // // Manejar la respuesta
    // if ($response->successful()) {
    //     $mensajeResponse = $response->body(); // Muestra la respuesta
    //     dd($mensajeResponse);
    //     // $whatsapp_cloud_api->sendTextMessage($contacto?->numero_completo, $mensajeResponse['response']);
    // } else {
    //     dd('Error: ' . $response->status()); // Muestra el código de estado si falla
    // }
    // $telefono = '15550534684';
    // // $telefono = '573152094191';
    // // Asegurarte de que el número incluya el signo '+'
    // if (!str_starts_with($telefono, '+')) {
    //     $telefono = '+' . $telefono;
    // }
    // $phoneUtil = PhoneNumberUtil::getInstance();
    // $parsedNumber = $phoneUtil->parse($telefono, null);
    //     // Obtener el código del país
    // $countryCode = $parsedNumber->getCountryCode();

    // // Obtener el resto del número sin el código del país
    // $nationalNumber = $parsedNumber->getNationalNumber();
    // dd($countryCode, $nationalNumber);
    // $config = ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)->where('id', 1)->first();
    // $whatsapp_cloud_api = new WhatsAppCloudApi([
    //     'from_phone_number_id' => $config->phone_number_id,
    //     'access_token' => $config->token,
    //     'graph_version' => $config->version,
    // ]);

    // generarSeccionSubirArchivo('img/perfil-whatsapp/', $config->app_id, $config->version, $config->token);
    // try {
        // $audio_link = 'https://ac5fb9361857.ngrok-free.app/audios/prueba.ogg';
        // $audio_link = 'https://ac5fb9361857.ngrok-free.app/audios/chat/grabacion_1754018518.ogg';
        // $link_id = new LinkID($audio_link);
        // $media_id = new MediaObjectID($document_id);
        // $whatsapp_cloud_api->sendAudio('573152094191', $link_id);

        // $response = $whatsapp_cloud_api->uploadMedia($audio_link);
        // $whatsapp_cloud_api->sendAudio('573152094191', $response->decodedBody()['id']);

        // $audio_link = 'audios/chat/911946101007804.mp3';
        // $link_id = generarSeccionSubirArchivo($audio_link, $config->app_id, $config->version, $config->token);
        // dd($link_id);
        // $url = "https://graph.facebook.com/$config->version/$config->phone_number_id/messages";

        // $headers = [
        //     'Content-Type' => 'application/json',
        //     'Authorization' => 'Bearer '. $config->token, // Reemplaza con tu token de acceso
        // ];

        // $data = [
        //     'messaging_product' => 'whatsapp',
        //     'recipient_type' => 'individual',
        //     'to' => '+573152094191', // Número de teléfono del destinatario
        //     'type' => 'audio',
        //     'audio' => [
        //         'id' => $link_id, // ID del archivo de audio
        //     ],
        // ];

        // $response = Http::withHeaders($headers)->post($url, $data);

        // if ($response->successful()) {
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Mensaje enviado correctamente',
        //         'response' => $response->json(),
        //     ]);
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Error al enviar el mensaje',
        //         'response' => $response->json(),
        //     ], $response->status());
        // }


        // $rows = [
        //     new Row('1', '⭐️', "Experience wasn't good enough"),
        //     new Row('2', '⭐⭐️', "Experience could be better"),
        //     new Row('3', '⭐⭐⭐️', "Experience was ok"),
        //     new Row('4', '⭐⭐️⭐⭐', "Experience was good"),
        //     new Row('5', '⭐⭐️⭐⭐⭐️', "Experience was excellent"),
        // ];
        // $sections = [new Section('Stars', $rows)];
        // $action = new Action('Submit', $sections);

        // $whatsapp_cloud_api->sendList(
        //     '573152094191',
        //     'Rate your experience',
        //     'Please consider rating your shopping experience in our website',
        //     'Thanks for your time',
        //     $action
        // );

        // $rows = [
        //     new Button('button-1', 'Yes'),
        //     new Button('button-2', 'No'),
        //     new Button('button-3', 'Not Now'),
        // ];
        // $action = new ButtonAction($rows);

        // $whatsapp_cloud_api->sendButton(
        //     '573152094191',
        //     'Would you like to rate us on Trustpilot?',
        //     $action,
        //     'RATE US', // Optional: Specify a header (type "text")
        //     'Please choose an option' // Optional: Specify a footer
        // );
    // } catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
    //     dd($e->response()); // You can still check the Response returned from Meta servers
    // }
});
