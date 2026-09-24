<?php

use App\Http\Controllers\Auth\LoginQrController;
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
use App\Services\Meta\CatalogoService;
use App\Services\Meta\WabaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use libphonenumber\PhoneNumberUtil;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Spatie\Sitemap\SitemapGenerator;
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

Route::get('/generar-sitemap', function () {
    SitemapGenerator::create('https://message-business.gijac.com')
        ->writeToFile(public_path('sitemap.xml'));

    return 'Sitemap generado';
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
    Route::post('/perfil/foto', [UsuarioController::class, 'actualizarFoto'])
        ->name('perfil.foto');
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

    Route::get('/login-qr', [LoginQrController::class, 'index'])
        ->name('login-qr');

    Route::get('/login-qr/refresh', [LoginQrController::class, 'refresh'])
        ->name('login-qr.refresh');
});

Route::post('/device-link', [LoginQrController::class, 'deviceLink']);

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
    if (in_array($locale, ['es', 'en', 'de', 'ja', 'fr'])) {
        session(['locale' => $locale]);
        Session::put('locale', $locale);

        if (auth()->check()) {
            auth()->user()->update([
                'locale' => $locale
            ]);
        }
    }
    return redirect()->back();
})->name('lang.switch');

// Web
Route::get('/preguntas-frecuentes', [PreguntasController::class, 'index'])->name('preguntas');
Route::get('/videotutoriales', [VideotutorialesController::class, 'index'])->name('videotutoriales');
Route::get('/pricing', [PlanController::class, 'show'])->name('show');
include 'web/articulos/principal.php';

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
    $contacto = Contacto::find('45bf225e-f996-40c2-907a-c7e3df925aaf');
    $campo = 'numero_completo';
    dd($contacto->$campo);
});

Route::get('/meta', function() {
    $config = ConfiguracionMeta::find(1);
    // dd($config);

    // dd(app(WabaService::class)->subscribeWaba($config->version, $config->token, $config->waba_id));
    // dd(app(WabaService::class)->getWaba($config->version, $config->token, $config->waba_id));
    dd(app(CatalogoService::class)->getCatalogProducts($config->version, '', $config->token));
});
