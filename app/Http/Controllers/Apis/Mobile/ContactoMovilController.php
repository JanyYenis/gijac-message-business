<?php

namespace App\Http\Controllers\Apis\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Mensaje;
use App\Models\WhatsappAccount;
use App\Models\WhatsappNumber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Contacto y gestión del chat desde la app móvil:
 * detalle, respuestas automáticas (chatbot / IA), multimedia compartida y búsqueda en el chat.
 */
class ContactoMovilController extends Controller
{
    private function phoneNumberId($user): ?string
    {
        $numero = WhatsappNumber::paraUsuario($user);
        if ($numero) {
            return $numero->phone_number_id;
        }

        return WhatsappAccount::where('cod_empresa', $user->cod_empresa)
            ->where('estado', WhatsappAccount::CONECTADO)
            ->value('phone_number_id');
    }

    /** El contacto debe ser de la empresa del usuario */
    private function contacto($user, string $numero): ?Contacto
    {
        return Contacto::where('numero_completo', $numero)
            ->where('cod_empresa', $user->cod_empresa)
            ->first();
    }

    private function noEncontrado(): JsonResponse
    {
        return response()->json(['success' => false, 'message' => __('Contacto no encontrado')], 404);
    }

    /**
     * GET chats/{numero}/detalle
     */
    public function detalle(Request $request, string $numero): JsonResponse
    {
        $contacto = $this->contacto($request->user(), $numero);
        if (!$contacto) {
            return $this->noEncontrado();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nombre' => trim(($contacto->nombre ?? '') . ' ' . ($contacto->apellido ?? '')),
                'telefono' => $contacto->numero_completo,
                'chatbot_activo' => (int) $contacto->estado_chatbot === Contacto::ACTIVO,
                'ia_activa' => (int) $contacto->estado_chatbot_ia === Contacto::ACTIVO,
                'permiso_envio' => (bool) $contacto->permiso_envio,
            ],
        ]);
    }

    /**
     * PUT chats/{numero}/automatizacion   { campo: "chatbot"|"ia", activo: true|false }
     */
    public function automatizacion(Request $request, string $numero): JsonResponse
    {
        $datos = $request->validate([
            'campo' => 'required|in:chatbot,ia',
            'activo' => 'required|boolean',
        ]);

        $contacto = $this->contacto($request->user(), $numero);
        if (!$contacto) {
            return $this->noEncontrado();
        }

        $columna = $datos['campo'] === 'ia' ? 'estado_chatbot_ia' : 'estado_chatbot';
        $contacto->{$columna} = $datos['activo'] ? Contacto::ACTIVO : Contacto::INACTIVO;
        $contacto->save();

        return response()->json([
            'success' => true,
            'data' => [
                'chatbot_activo' => (int) $contacto->estado_chatbot === Contacto::ACTIVO,
                'ia_activa' => (int) $contacto->estado_chatbot_ia === Contacto::ACTIVO,
            ],
        ]);
    }

    /**
     * GET chats/{numero}/multimedia?tipo=media|documentos&before_id=&limit=
     * media = imágenes y videos; documentos = archivos y PDF.
     */
    public function multimedia(Request $request, string $numero): JsonResponse
    {
        $user = $request->user();
        $contacto = $this->contacto($user, $numero);
        if (!$contacto) {
            return $this->noEncontrado();
        }

        $tipos = $request->input('tipo') === 'documentos'
            ? [Mensaje::DOCUMENTO]
            : [Mensaje::IMAGEN, Mensaje::VIDEO];

        $query = $this->mensajesDelChat($user, $numero)->whereIn('type', $tipos);

        if ($beforeId = $request->input('before_id')) {
            if ($ref = Mensaje::find($beforeId)) {
                $query->where('created_at', '<', $ref->created_at);
            }
        }

        $limit = min(max((int) $request->input('limit', 60), 1), 120);

        return $this->responder($query->take($limit)->get(), $this->phoneNumberId($user));
    }

    /**
     * GET chats/{numero}/buscar?q=texto
     */
    public function buscar(Request $request, string $numero): JsonResponse
    {
        $user = $request->user();
        $contacto = $this->contacto($user, $numero);
        if (!$contacto) {
            return $this->noEncontrado();
        }

        $q = trim((string) $request->input('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $like = '%' . addcslashes($q, '\\%_') . '%';
        $query = $this->mensajesDelChat($user, $numero)
            ->where('type', Mensaje::TEXTO)
            ->where('body', 'like', $like);

        return $this->responder($query->take(100)->get(), $this->phoneNumberId($user));
    }

    private function mensajesDelChat($user, string $numero)
    {
        $phoneNumberId = $this->phoneNumberId($user);

        return Mensaje::where(function ($q) use ($phoneNumberId, $numero) {
            $q->where(function ($q2) use ($phoneNumberId, $numero) {
                $q2->where('wa_from', $phoneNumberId)->where('wa_to', $numero);
            })->orWhere(function ($q2) use ($phoneNumberId, $numero) {
                $q2->where('wa_from', $numero)->where('wa_to', $phoneNumberId);
            });
        })->orderByDesc('created_at');
    }

    private function responder($mensajes, ?string $phoneNumberId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $mensajes->map(fn ($m) => [
                'id' => $m->id,
                'es mio' => $m->wa_from === $phoneNumberId,
                'tipo' => $m->type,
                'contenido' => $m->body,
                'metadata' => $m->metadata,
                'estado' => $m->estado,
                'fecha' => $m->created_at->toISOString(),
                'url_archivo' => $this->urlArchivo($m),
            ])->values(),
        ]);
    }

    /** Misma regla que ChatController::getFileUrl */
    private function urlArchivo($mensaje): ?string
    {
        if (!is_array($mensaje->metadata)) {
            return null;
        }
        $meta = $mensaje->metadata;
        $bodyUrl = filter_var($mensaje->body, FILTER_VALIDATE_URL) ? $mensaje->body : null;

        if ($mensaje->type == Mensaje::IMAGEN) {
            return isset($meta['image']['id']) ? url(Storage::url("chats/img/{$meta['image']['id']}.jpg")) : $bodyUrl;
        }
        if ($mensaje->type == Mensaje::VIDEO) {
            return isset($meta['video']['id']) ? url(Storage::url("chats/videos/{$meta['video']['id']}.mp4")) : $bodyUrl;
        }
        if ($mensaje->type == Mensaje::DOCUMENTO) {
            return isset($meta['document']['filename'])
                ? url(Storage::url('chats/documentos/' . basename($meta['document']['filename'])))
                : $bodyUrl;
        }

        return null;
    }
}
