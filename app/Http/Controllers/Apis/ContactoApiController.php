<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Etiqueta;
use App\Models\EtiquetaContacto;
use App\Services\Contactos\ContactoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/** API pública: contactos y etiquetas de la empresa dueña de la API Key. */
class ContactoApiController extends Controller
{
    public function index(Request $request)
    {
        $porPagina = min(100, max(1, (int) $request->input('por_pagina', 25)));

        $contactos = Contacto::with('etiquetasActivas.etiqueta')
            ->where('cod_empresa', $this->empresaId($request))
            ->where('estado', '!=', Contacto::ELIMINADO)
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $b = '%' . str_replace(['%', '_'], ['\%', '\_'], $request->input('buscar')) . '%';
                $q->where(fn ($w) => $w->where('nombre', 'like', $b)->orWhere('apellido', 'like', $b)->orWhere('numero_completo', 'like', $b));
            })
            ->orderBy('created_at', 'desc')
            ->paginate($porPagina);

        return response()->json([
            'estado' => 'success',
            'contactos' => $contactos->getCollection()->map(fn ($c) => $this->formato($c))->values(),
            'pagina' => $contactos->currentPage(),
            'por_pagina' => $contactos->perPage(),
            'total' => $contactos->total(),
        ]);
    }

    public function show(Request $request, string $telefono)
    {
        $contacto = $this->buscar($request, $telefono);
        if (!$contacto) {
            return $this->noEncontrado();
        }

        return response()->json(['estado' => 'success', 'contacto' => $this->formato($contacto)]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'telefono' => ['required', 'regex:/^\+?[0-9]{8,15}$/'],
            'nombre' => 'nullable|string|max:100',
            'apellido' => 'nullable|string|max:100',
        ]);
        if ($v->fails()) {
            return $this->invalido($v);
        }

        $telefono = ltrim($request->input('telefono'), '+');
        if ($this->buscar($request, $telefono)) {
            return response()->json(['estado' => 'error', 'mensaje' => __('El contacto ya existe.')], 409);
        }

        $empresa = $request->attributes->get('api_empresa');
        $usuario = $request->attributes->get('api_usuario');

        $contacto = app(ContactoService::class)->crearAutomaticamente(
            empresa: $empresa,
            telefono: $telefono,
            uuid: $empresa->cod_usuario,
            esDemo: $usuario?->demo ?? false
        );

        if (!$contacto) {
            return response()->json(['estado' => 'error', 'mensaje' => __('No se pudo crear el contacto. Revisa el límite de contactos de tu plan.')], 422);
        }

        $datos = array_filter($request->only(['nombre', 'apellido']), fn ($x) => $x !== null && $x !== '');
        if ($datos) {
            $contacto->update($datos);
        }

        return response()->json(['estado' => 'success', 'contacto' => $this->formato($contacto->fresh('etiquetasActivas.etiqueta'))], 201);
    }

    public function update(Request $request, string $telefono)
    {
        $contacto = $this->buscar($request, $telefono);
        if (!$contacto) {
            return $this->noEncontrado();
        }

        $v = Validator::make($request->all(), [
            'nombre' => 'sometimes|nullable|string|max:100',
            'apellido' => 'sometimes|nullable|string|max:100',
            'genero' => ['sometimes', 'nullable', 'in:' . Contacto::MASCULINO . ',' . Contacto::FEMENINO],
        ]);
        if ($v->fails()) {
            return $this->invalido($v);
        }

        $contacto->update($request->only(['nombre', 'apellido', 'genero']));

        return response()->json(['estado' => 'success', 'contacto' => $this->formato($contacto->fresh('etiquetasActivas.etiqueta'))]);
    }

    /* ---------- Etiquetas ---------- */

    public function etiquetas(Request $request)
    {
        $etiquetas = Etiqueta::where('cod_empresa', $this->empresaId($request))
            ->where('estado', Etiqueta::ACTIVO)
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'descripcion', 'color']);

        return response()->json(['estado' => 'success', 'etiquetas' => $etiquetas]);
    }

    public function asignarEtiqueta(Request $request, string $telefono)
    {
        $contacto = $this->buscar($request, $telefono);
        if (!$contacto) {
            return $this->noEncontrado();
        }

        $v = Validator::make($request->all(), ['etiqueta' => 'required|string|max:64']);
        if ($v->fails()) {
            return $this->invalido($v);
        }

        $etiqueta = Etiqueta::where('id', $request->input('etiqueta'))
            ->where('cod_empresa', $this->empresaId($request))
            ->where('estado', Etiqueta::ACTIVO)
            ->first();
        if (!$etiqueta) {
            return response()->json(['estado' => 'error', 'mensaje' => __('Etiqueta no encontrada.')], 404);
        }

        EtiquetaContacto::updateOrCreate(
            ['cod_contacto' => $contacto->id, 'cod_etiqueta' => $etiqueta->id],
            ['estado' => EtiquetaContacto::ACTIVO]
        );

        return response()->json(['estado' => 'success', 'contacto' => $this->formato($contacto->fresh('etiquetasActivas.etiqueta'))]);
    }

    public function quitarEtiqueta(Request $request, string $telefono, string $etiqueta)
    {
        $contacto = $this->buscar($request, $telefono);
        if (!$contacto) {
            return $this->noEncontrado();
        }

        EtiquetaContacto::where('cod_contacto', $contacto->id)
            ->where('cod_etiqueta', $etiqueta)
            ->update(['estado' => EtiquetaContacto::ELIMINADO]);

        return response()->json(['estado' => 'success', 'contacto' => $this->formato($contacto->fresh('etiquetasActivas.etiqueta'))]);
    }

    /* ---------- Utilidades ---------- */

    private function empresaId(Request $request)
    {
        return $request->attributes->get('api_empresa_id');
    }

    private function buscar(Request $request, string $telefono): ?Contacto
    {
        return Contacto::with('etiquetasActivas.etiqueta')
            ->where('cod_empresa', $this->empresaId($request))
            ->where('numero_completo', ltrim($telefono, '+'))
            ->where('estado', '!=', Contacto::ELIMINADO)
            ->first();
    }

    private function formato(Contacto $c): array
    {
        return [
            'id' => $c->id,
            'nombre' => $c->nombre,
            'apellido' => $c->apellido,
            'telefono' => $c->numero_completo,
            'genero' => $c->genero,
            'estado' => $c->estado,
            'etiquetas' => $c->etiquetasActivas->map(fn ($e) => [
                'id' => $e->etiqueta?->id,
                'nombre' => $e->etiqueta?->nombre,
                'color' => $e->etiqueta?->color,
            ])->values(),
            'creado' => $c->created_at?->toIso8601String(),
        ];
    }

    private function noEncontrado()
    {
        return response()->json(['estado' => 'error', 'mensaje' => __('Contacto no encontrado.')], 404);
    }

    private function invalido($validator)
    {
        return response()->json(['estado' => 'error', 'mensaje' => __('Datos inválidos.'), 'errores' => $validator->errors()], 422);
    }
}
