<?php

use App\Http\Controllers\Admin\WhatsAppOnboardingController;
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
use App\Models\WhatsappAccount;
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
use App\Services\Meta\WehbookService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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
use Illuminate\Support\Str;

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

    Route::delete('/login-qr/desconectar', [LoginQrController::class, 'desconectar'])
        ->name('login-qr.desconectar');
});

Route::middleware(['web', 'auth', '2fa', 'verified', 'verify.company'])
    ->prefix('admin/whatsapp')
    ->group(function () {
    Route::get('/onboarding',  [WhatsAppOnboardingController::class, 'connect'])->name('whatsapp.onboarding');
    Route::post('/exchange-token',[WhatsAppOnboardingController::class, 'exchangeToken'])->name('whatsapp.exchange');
    Route::get('/status',      [WhatsAppOnboardingController::class, 'status'])->name('whatsapp.status');
    Route::get('/checklist', [WhatsAppOnboardingController::class, 'checklist'])->name('whatsapp.checklist');
    Route::post('/resubscribe',[WhatsAppOnboardingController::class, 'resubscribeWebhook'])->name('whatsapp.resubscribe');
    Route::post('/register',   [WhatsAppOnboardingController::class, 'registerNumber'])->name('whatsapp.register');
    Route::post('/renew-token',[WhatsAppOnboardingController::class, 'renewToken'])->name('whatsapp.renew');
    Route::post('/test-message',[WhatsAppOnboardingController::class, 'sendTest'])->name('whatsapp.test');
    Route::delete('/disconnect',[WhatsAppOnboardingController::class, 'disconnect'])->name('whatsapp.disconnect');

    Route::post('/numbers', [WhatsAppOnboardingController::class, 'numberStart'])->name('whatsapp.numbers.start');
    Route::middleware('throttle:6,1')->group(function () {
        Route::post('/numbers/{phone}/code',     [WhatsAppOnboardingController::class, 'numberCode'])->whereNumber('phone');
        Route::post('/numbers/{phone}/verify',   [WhatsAppOnboardingController::class, 'numberVerify'])->whereNumber('phone');
        Route::post('/numbers/{phone}/register', [WhatsAppOnboardingController::class, 'numberRegister'])->whereNumber('phone');
    });
    Route::post('/numbers/{phone}/link',      [WhatsAppOnboardingController::class, 'numberLink'])->whereNumber('phone');
    Route::post('/numbers/{phone}/principal', [WhatsAppOnboardingController::class, 'numberPrincipal'])->whereNumber('phone');
    Route::get('/numbers/{phone}/agentes', [WhatsAppOnboardingController::class, 'numberAgents'])->whereNumber('phone');
    Route::post('/numbers/{phone}/agentes', [WhatsAppOnboardingController::class, 'numberAssign'])->whereNumber('phone');
    Route::post('/numbers/{phone}/bot', [WhatsAppOnboardingController::class, 'numberBot'])->whereNumber('phone');
});

Route::post('/device-link', [LoginQrController::class, 'deviceLink']);

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
    // dd(app(WehbookService::class)->subscribeWaba('EAACsiX5e5eQBSnZCKwAzNe5zEPWhoP5u6ZCIAZC2Ch784dCWyoYrHAMVmCbg0LbLunMSWJ9D34a4YWGZB7Uz5ONJ0gQeG6NBHdZCXUEoyilf03oHtDssRiiHZBYv1isc2zgqi5lMTDGufW6ZBZAPElzLtt5xZAvMKtKgsIr5AHn9fC8hxvYbUy938gcV2af0QZCQZDZD',
    //     '106202962472888'));
    // dd(Str::uuid());

    // dd(Crypt::encrypt('EAACsiX5e5eQBSnZCKwAzNe5zEPWhoP5u6ZCIAZC2Ch784dCWyoYrHAMVmCbg0LbLunMSWJ9D34a4YWGZB7Uz5ONJ0gQeG6NBHdZCXUEoyilf03oHtDssRiiHZBYv1isc2zgqi5lMTDGufW6ZBZAPElzLtt5xZAvMKtKgsIr5AHn9fC8hxvYbUy938gcV2af0QZCQZDZD'));

    // $whatsapp_cloud_api = new WhatsAppCloudApi([
    //     'from_phone_number_id' => '1358651900662484',
    //     'access_token' => env('WHATSAPP_VERIFY_TOKEN'),
    //     'graph_version' => env('FACEBOOK_GRAPH_VERSION', 'v26.0'),
    // ]);
    // $response = $whatsapp_cloud_api->sendTextMessage('573152094191', 'Hola');
    // if ($response?->body()) {
    //     $data = json_decode($response?->body());
    //     dd($data);
    // }

    // return view('prueba.index');
});

Route::get('/meta', function() {
    $config = WhatsappAccount::find(1);
    // dd($config);

    // dd(app(WabaService::class)->subscribeWaba($config->token, $config->waba_id));
    // dd(app(WabaService::class)->getWaba($config->token, $config->waba_id));
    dd(app(CatalogoService::class)->getCatalogProducts('', $config->token));
});
