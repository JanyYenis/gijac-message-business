<?php

namespace App\Http\Controllers\Apis\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserProfileResource;
use App\Models\Ciudad;
use App\Models\Pais;
use App\Models\Usuario;
use App\Rules\UsuarioIdentificacion;
use App\Rules\UsuarioTelefono;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/** Edición del perfil desde la app móvil (mismas reglas de foto que la web: imagen, máx. 2 MB, disco public/perfil) */
class PerfilMovilController extends Controller
{
    /** GET /api/mobile/profile/options — géneros, tipos de documento y países para el formulario */
    public function opciones(): JsonResponse
    {
        $conceptos = fn ($tipo) => collect(darConceptos($tipo, false))
            ->map(fn ($c) => ['id' => (string) $c->codigo, 'nombre' => $c->nombre])
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'generos' => $conceptos(Usuario::TC_GENERO_USUARIOS),
                'documentos' => $conceptos(Usuario::TC_TIPO_DOCUMENTO),
                'paises' => Pais::where('estado', Pais::ACTIVO)->orderBy('nombre')->get(['id', 'nombre'])
                    ->map(fn ($p) => ['id' => (string) $p->id, 'nombre' => $p->nombre])->values(),
            ],
        ]);
    }

    /** GET /api/mobile/profile/cities/{pais} — ciudades activas del país */
    public function ciudades($pais): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Ciudad::where('id_pais', $pais)->where('estado', Ciudad::ACTIVO)->orderBy('nombre')->get(['id', 'nombre'])
                ->map(fn ($c) => ['id' => (string) $c->id, 'nombre' => $c->nombre])->values(),
        ]);
    }

    /** PUT /api/mobile/profile — mismos campos y reglas que el formulario de la web (el correo y la contraseña se cambian allá) */
    public function actualizar(Request $request): JsonResponse
    {
        $user = $request->user();

        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'genero' => 'required|integer',
            'tipo_identificacion' => 'required|integer',
            'identificacion' => ['required', 'numeric', new UsuarioIdentificacion($user->id)],
            'telefono' => ['required', 'digits_between:6,15', new UsuarioTelefono($user->id)],
            'codigo_telefono' => ['nullable', 'regex:/^\+?\d{1,4}$/'],
            'pais_id' => ['required', Rule::exists('paises', 'id')],
            'cod_ciudad' => ['required', Rule::exists('ciudades', 'id')->where('id_pais', $request->input('pais_id'))],
        ], [
            'nombre.required' => 'El campo nombre es requerido.',
            'apellido.required' => 'El campo apellido es requerido.',
            'genero.required' => 'El campo género es requerido.',
            'tipo_identificacion.required' => 'El campo tipo de identificación es requerido.',
            'identificacion.required' => 'El campo identificación es requerido.',
            'identificacion.numeric' => 'La identificación debe ser numérica.',
            'telefono.required' => 'El campo teléfono es requerido.',
            'pais_id.required' => 'El campo país es requerido.',
            'cod_ciudad.required' => 'El campo ciudad es requerido.',
            'cod_ciudad.exists' => 'La ciudad no pertenece al país elegido.',
        ]);

        $cambios = [
            'nombre' => trim($datos['nombre']),
            'apellido' => trim($datos['apellido']),
            'genero' => $datos['genero'],
            'tipo_identificacion' => $datos['tipo_identificacion'],
            'identificacion' => $datos['identificacion'],
            'telefono' => $datos['telefono'],
            'cod_ciudad' => $datos['cod_ciudad'],
        ];
        if (! empty($datos['codigo_telefono'])) {
            $cambios['codigo_telefono'] = $datos['codigo_telefono'];
        }
        $user->forceFill($cambios)->save();

        return $this->respuesta($user);
    }

    /** POST /api/mobile/profile/photo — multipart con el campo "foto" */
    public function foto(Request $request): JsonResponse
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = $request->user();
        $anterior = $user->foto;

        $ruta = $request->file('foto')->store('perfil', 'public');
        $user->forceFill(['foto' => 'storage/' . $ruta])->save();

        // Se borra la foto anterior solo si estaba en la carpeta de perfiles de este servidor
        if ($anterior && str_starts_with($anterior, 'storage/perfil/')) {
            Storage::disk('public')->delete(substr($anterior, strlen('storage/')));
        }

        return $this->respuesta($user);
    }

    private function respuesta($user): JsonResponse
    {
        $user->load(['infoGenero', 'ciudad.pais', 'empresa', 'plan', 'roles']);

        return response()->json([
            'success' => true,
            'data' => new UserProfileResource($user),
        ]);
    }
}
