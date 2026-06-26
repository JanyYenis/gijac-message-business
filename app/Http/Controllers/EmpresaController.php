<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        $info['negocio'] = Empresa::where('cod_usuario', auth()->user()->uuid)->first();

        return view('empresas.index', $info);
    }

    public function store(Request $request)
    {
        $datos = $request->all();
        $datos['cod_usuario'] = auth()->user()->uuid;

        $path = null;

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $path = $archivo->store('negocios', 'public');
            $datos['foto'] = url(Storage::url($path));
        }

        $empresa = Empresa::create($datos);

        if (!$empresa) {
            throw new ErrorException('Ha ocurrido un error al interntar crear el negocio.');
        }

        $empresa->refresh();

        // Verifica el ID
        if (empty($empresa->id)) {
            throw new ErrorException("El empresa no tiene un ID asignado.");
        }

        auth()->user()->update(['cod_empresa' => $empresa->id]);

        return [
            'estado' => 'success',
            'mensaje' => 'Se registro correctamente la empresa.',
        ];
    }
}
