<?php

namespace App\Http\Controllers\Apis\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** Edición del perfil desde la app móvil (mismas reglas de foto que la web: imagen, máx. 2 MB, disco public/perfil) */
class PerfilMovilController extends Controller
{
    /** PUT /api/mobile/profile — nombre, apellido y teléfono (el correo y la contraseña se cambian desde la web) */
    public function actualizar(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'nullable|string|max:100',
            'telefono' => 'nullable|digits_between:6,15',
            'codigo_telefono' => ['nullable', 'regex:/^\+?\d{1,4}$/'],
        ]);

        $user = $request->user();
        $cambios = [
            'nombre' => trim($datos['nombre']),
            'apellido' => trim($datos['apellido'] ?? ''),
        ];
        if (! empty($datos['telefono'])) {
            $cambios['telefono'] = $datos['telefono'];
            if (! empty($datos['codigo_telefono'])) {
                $cambios['codigo_telefono'] = $datos['codigo_telefono'];
            }
            // Si el número completo es una columna (y no un atributo calculado), se mantiene al día
            if (array_key_exists('numero_completo', $user->getAttributes())) {
                $codigo = ltrim($cambios['codigo_telefono'] ?? (string) $user->codigo_telefono, '+');
                $cambios['numero_completo'] = $codigo . $datos['telefono'];
            }
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
