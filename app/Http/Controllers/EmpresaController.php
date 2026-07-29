<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\UsuarioEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        $info['negocio'] = auth()->user()->empresa ?? false;
        $info['tienePermiso'] = canAny([Usuario::PERMISO_EMPRESA_EDITAR, Usuario::PERMISO_EMPRESA_CREAR]);

        return view('empresas.index', $info);
    }

    public function store(Request $request)
    {
        $datos = $request->all();
        $datos['cod_usuario'] = auth()->user()->uuid;

        // Obtener la empresa actual (si existe)
        $empresa = Empresa::where('cod_usuario', auth()->user()->uuid)->first();

        if ($request->hasFile('imagen')) {

            // Eliminar imagen anterior
            if ($empresa && !empty($empresa->foto)) {

                // Convierte la URL en la ruta del storage
                $rutaAnterior = str_replace(url(Storage::url('')), '', $empresa->foto);

                if (Storage::disk('public')->exists($rutaAnterior)) {
                    Storage::disk('public')->delete($rutaAnterior);
                }
            }

            // Guardar nueva imagen
            $path = $request->file('imagen')->store('negocios', 'public');
            $datos['foto'] = url(Storage::url($path));
        }

        $empresa = Empresa::updateOrCreate(
            [
                'cod_usuario' => auth()->user()->uuid
            ],
            $datos
        );

        if (!$empresa) {
            throw new ErrorException('Ha ocurrido un error al intentar crear el negocio.');
        }

        auth()->user()->update([
            'cod_empresa' => $empresa->id
        ]);

        UsuarioEmpresa::updateOrCreate(
            [
                'cod_empresa' => $empresa->id,
                'cod_usuario' => auth()->user()->uuid,
            ],
            [
                'principal' => UsuarioEmpresa::PRINCIPAL,
            ]
        );

        return [
            'estado' => 'success',
            'mensaje' => 'Se registró correctamente la empresa.',
        ];
    }
}
