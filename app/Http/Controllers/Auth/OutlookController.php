<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Models\Sistema\Acceso;
use App\Models\Autenticacion;
use App\Models\Usuario;
use App\TokenStore\TokenCache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class OutlookController extends Controller
{
    public function index()
    {
        // Initialize the OAuth client
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => config('azure.appId'),
            'clientSecret'            => config('azure.appSecret'),
            'redirectUri'             => config('azure.redirectUri'),
            'urlAuthorize'            => config('azure.authority') . config('azure.authorizeEndpoint'),
            'urlAccessToken'          => config('azure.authority') . config('azure.tokenEndpoint'),
            'urlResourceOwnerDetails' => '',
            'scopes'                  => config('azure.scopes')
        ]);

        $authUrl = $oauthClient->getAuthorizationUrl();

        // Save client state so we can validate in callback
        session(['oauthState' => $oauthClient->getState()]);

        // Redirect to AAD signin page
        return redirect()->away($authUrl);
    }

    public function redireccion(Request $request)
    {
        // Validar estado
        $expectedState = session('oauthState');
        $request->session()->forget('oauthState');
        $providedState = $request->query('state');

        if (!isset($expectedState)) {
            // Si no hay ningún estado esperado en la sesión,
            // no hacer nada y redirigir a la página de inicio.
            return redirect(route('login'));
        }

        if (!isset($providedState) || $expectedState != $providedState) {
            return redirect(route('login'))
                ->with('error', 'Estado de autenticación no válido')
                ->with('errorDetail', 'El estado de autenticación proporcionado no coincidió con el valor esperado');
        }

        // El código de autorización debe estar en el parámetro de consulta "code"
        $authCode = $request->query('code');
        if (isset($authCode)) {
            // Inicializar el cliente OAuth
            $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
                'clientId'                => config('azure.appId'),
                'clientSecret'            => config('azure.appSecret'),
                'redirectUri'             => config('azure.redirectUri'),
                'urlAuthorize'            => config('azure.authority') . config('azure.authorizeEndpoint'),
                'urlAccessToken'          => config('azure.authority') . config('azure.tokenEndpoint'),
                'urlResourceOwnerDetails' => '',
                'scopes'                  => config('azure.scopes')
            ]);

            try {
                // Realizar la solicitud de token
                $accessToken = $oauthClient->getAccessToken('authorization_code', [
                    'code' => $authCode
                ]);

                $graph = new Graph();
                $graph->setAccessToken($accessToken->getToken());

                $user = $graph->createRequest('GET', '/me?$select=displayName,mail,mailboxSettings,userPrincipalName')
                    ->setReturnType(Model\User::class)
                    ->execute();

                $tokenCache = new TokenCache();
                $tokenCache->storeTokens($accessToken, $user);

                $accion = $this->login($request, $user);
                if ($accion) {
                    return redirect(route('home'));
                } else {
                    return redirect(route('login'))->with('error', 'El usuario no se encuentra en nuestra base de datos.');
                }
            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                return redirect(route('login'))
                    ->with('error', 'Error al solicitar el token de acceso')
                    ->with('errorDetail', json_encode($e->getResponseBody()));
            }
        }

        return redirect(route('login'))
            ->with('error', $request->query('error'))
            ->with('errorDetail', $request->query('error_description'));
    }

    public function login(Request $request, $usuario)
    {
        $validarUsuario = Usuario::where([
            'email'  => null !== $usuario->getMail() ? $usuario->getMail() : $usuario->getUserPrincipalName(),
            'estado' => Usuario::ACTIVO,
        ])->first();

        if ($validarUsuario) {
            Autenticacion::updateOrCreate([
                'cod_usuario' => $validarUsuario->id,
                'external_auth' => 'outlook',
            ], [
                'external_id' => null !== $usuario->getMail() ? $usuario->getMail() : $usuario->getUserPrincipalName(),
            ]);

            Auth::login($validarUsuario);

        //     $agent = new Agent();

        //     // Obteniendo la IP del cliente
        //     $ipAddress = $request->ip();

        //     // Obteniendo el sistema operativo del cliente
        //     $platform = $agent->platform();

        //     // Obteniendo el navegador del cliente
        //     $browser = $agent->browser();

        //     // Obteniendo la ubicación del cliente (esto requerirá un servicio de geolocalización de terceros)
        //     // $location = obtenerUbicacion($ipAddress);

        //     Acceso::create([
        //         'cod_usuario' => auth()->user()->id,
        //         'tipo' => Acceso::OUTLOOCK,
        //         'ip' => $ipAddress,
        //         'navegador' => $browser,
        //         'sistema' => $platform,
        //         'fecha_ingreso' => Carbon::now(),
        //     ]);

            return true;
        // } else {
        //     $agent = new Agent();

        //     // Obteniendo la IP del cliente
        //     $ipAddress = $request->ip();

        //     // Obteniendo el sistema operativo del cliente
        //     $platform = $agent->platform();

        //     // Obteniendo el navegador del cliente
        //     $browser = $agent->browser();

        //     // Obteniendo la ubicación del cliente (esto requerirá un servicio de geolocalización de terceros)
        //     // $location = obtenerUbicacion($ipAddress);

        //     Acceso::create([
        //         'tipo' => Acceso::OUTLOOCK,
        //         'ip' => $ipAddress,
        //         'navegador' => $browser,
        //         'sistema' => $platform,
        //         'fecha_ingreso' => Carbon::now(),
        //         'estado' => Acceso::ERROR,
        //     ]);
        }

        return false;
    }
}
