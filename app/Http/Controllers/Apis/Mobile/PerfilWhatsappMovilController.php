<?php

namespace App\Http\Controllers\Apis\Mobile;

use App\Http\Controllers\Controller;
use App\Jobs\SubirFotoPerfilWhatsApp;
use App\Models\WhatsappNumber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Perfil de WhatsApp Business desde la app móvil. Se lee y edita el perfil del número que usa el usuario.
 * Meta no permite editar el perfil de los números de prueba (es_prueba): solo se consulta.
 */
class PerfilWhatsappMovilController extends Controller
{
    private const CAMPOS = 'about,address,description,email,profile_picture_url,websites,vertical';

    private const CATEGORIAS = [
        'UNDEFINED' => 'Indefinido', 'OTHER' => 'Otro', 'AUTO' => 'Automotriz', 'BEAUTY' => 'Belleza',
        'APPAREL' => 'Moda y ropa', 'EDU' => 'Educación', 'ENTERTAIN' => 'Entretenimiento', 'EVENT_PLAN' => 'Eventos',
        'FINANCE' => 'Finanzas', 'GROCERY' => 'Tienda de comestibles', 'GOVT' => 'Gobierno', 'HOTEL' => 'Hotel',
        'HEALTH' => 'Salud', 'NONPROFIT' => 'Sin ánimo de lucro', 'PROF_SERVICES' => 'Servicios profesionales',
        'RETAIL' => 'Minorista', 'TRAVEL' => 'Viajes', 'RESTAURANT' => 'Restaurante',
    ];

    /** Misma versión de la Graph API que usa la web */
    private function graph(): string
    {
        return 'https://graph.facebook.com/' . config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v17.0'));
    }

    /** El token se guarda cifrado (igual que en el Controller base de la web) */
    private function token(WhatsappNumber $numero): ?string
    {
        $cifrado = $numero->cuenta?->access_token;
        if (! $cifrado) {
            return null;
        }
        try {
            return Crypt::decrypt($cifrado);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** GET /api/mobile/whatsapp-profile */
    public function mostrar(Request $request): JsonResponse
    {
        $numero = WhatsappNumber::paraUsuario($request->user());
        if (! $numero) {
            return response()->json(['success' => false, 'message' => 'Tu empresa no tiene un número de WhatsApp conectado.'], 404);
        }
        $token = $this->token($numero);

        $perfil = [];
        $avisoMeta = $token ? null : 'No se pudo leer el token de acceso de la cuenta.';
        $nombreVerificado = null;
        if ($token) {
            $r = Http::withToken($token)->timeout(20)
                ->get($this->graph() . "/{$numero->phone_number_id}/whatsapp_business_profile", ['fields' => self::CAMPOS]);
            if ($r->successful()) {
                $perfil = $r->json('data.0') ?? [];
            } else {
                $avisoMeta = $r->json('error.message') ?? 'Meta no devolvió el perfil (HTTP ' . $r->status() . ').';
                Log::warning('PERFIL WA móvil: Meta no devolvió el perfil', ['phone_number_id' => $numero->phone_number_id, 'status' => $r->status(), 'body' => $r->body()]);
            }
            $n = Http::withToken($token)->timeout(20)
                ->get($this->graph() . "/{$numero->phone_number_id}", ['fields' => 'verified_name']);
            $nombreVerificado = $n->successful() ? $n->json('verified_name') : null;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'phone_number_id' => $numero->phone_number_id,
                'nombre' => $nombreVerificado ?: $numero->display_name,
                'telefono' => $numero->phone_number,
                'aviso_meta' => $avisoMeta,
                'es_prueba' => (bool) $numero->es_prueba,
                'editable' => ! $numero->es_prueba && (bool) $token,
                'about' => $perfil['about'] ?? '',
                'address' => $perfil['address'] ?? '',
                'description' => $perfil['description'] ?? '',
                'email' => $perfil['email'] ?? '',
                'foto' => $perfil['profile_picture_url'] ?? null,
                'websites' => $perfil['websites'] ?? [],
                'vertical' => $perfil['vertical'] ?? null,
                'categorias' => collect(self::CATEGORIAS)->map(fn ($n, $id) => ['id' => $id, 'nombre' => $n])->values(),
            ],
        ]);
    }

    /** PUT /api/mobile/whatsapp-profile */
    public function actualizar(Request $request): JsonResponse
    {
        [$numero, $error] = $this->numeroEditable($request);
        if ($error) {
            return $error;
        }

        $datos = $request->validate([
            'about' => 'required|string|max:139',
            'email' => 'required|email|max:128',
            'vertical' => ['required', 'in:' . implode(',', array_keys(self::CATEGORIAS))],
            'description' => 'nullable|string|max:512',
            'address' => 'nullable|string|max:256',
            'websites' => 'nullable|array|max:2',
            'websites.*' => 'url|max:256',
        ], [
            'about.required' => 'La info es requerida.',
            'about.max' => 'La info admite máximo 139 caracteres.',
            'email.required' => 'El email es requerido.',
            'email.email' => 'El email no es válido.',
            'vertical.required' => 'La categoría es requerida.',
            'websites.max' => 'Meta admite máximo 2 sitios web.',
            'websites.*.url' => 'Cada sitio web debe empezar por https:// o http://.',
        ]);

        $cuerpo = [
            'messaging_product' => 'whatsapp',
            'about' => $datos['about'],
            'email' => $datos['email'],
            'vertical' => $datos['vertical'],
            'description' => $datos['description'] ?? '',
            'address' => $datos['address'] ?? '',
            'websites' => array_values(array_filter($datos['websites'] ?? [])),
        ];

        $r = Http::withToken($this->token($numero))->timeout(30)
            ->post($this->graph() . "/{$numero->phone_number_id}/whatsapp_business_profile", $cuerpo);

        if (! $r->successful()) {
            return response()->json([
                'success' => false,
                'message' => $r->json('error.error_user_msg') ?? $r->json('error.message') ?? 'Meta no aceptó los cambios.',
            ], 422);
        }

        return $this->mostrar($request);
    }

    /** POST /api/mobile/whatsapp-profile/photo — multipart "foto". La subida a Meta la hace el mismo job que usa la web */
    public function foto(Request $request): JsonResponse
    {
        [, $error] = $this->numeroEditable($request);
        if ($error) {
            return $error;
        }
        $request->validate(['foto' => 'required|image|mimes:jpg,jpeg,png|max:5120']);

        $archivo = $request->file('foto');
        $nombre = time() . '.' . $archivo->getClientOriginalExtension();
        $archivo->storeAs('perfil-whatsapp', $nombre, 'public');
        $archivo->move(public_path('img/perfil-whatsapp'), $nombre);

        dispatch(new SubirFotoPerfilWhatsApp(
            $request->user()->empresa?->id,
            public_path('img/perfil-whatsapp/' . $nombre),
            'img/perfil-whatsapp/' . $nombre
        ));

        return response()->json([
            'success' => true,
            'message' => 'La foto se está enviando a WhatsApp. Puede tardar unos minutos en verse.',
        ]);
    }

    /** @return array{0: ?WhatsappNumber, 1: ?JsonResponse} */
    private function numeroEditable(Request $request): array
    {
        $numero = WhatsappNumber::paraUsuario($request->user());
        if (! $numero || ! $this->token($numero)) {
            return [null, response()->json(['success' => false, 'message' => 'No hay un número de WhatsApp conectado.'], 404)];
        }
        if ($numero->es_prueba) {
            return [null, response()->json(['success' => false, 'message' => 'Meta no permite editar el perfil de un número de prueba.'], 403)];
        }

        return [$numero, null];
    }
}
