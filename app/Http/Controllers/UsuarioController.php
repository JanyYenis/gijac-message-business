<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\Contacto;
use App\Models\Factura;
use App\Models\Pais;
use App\Models\Plan;
use App\Models\Usuario;
use App\Models\UsuarioEmpresa;
use App\Models\UsuarioProyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PragmaRX\Google2FALaravel\Facade as Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables;

class UsuarioController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!can(Usuario::PERMISO_LISTADO) && !can(Usuario::PERMISO_CREAR) && !can(Usuario::PERMISO_EDITAR) && !can(Usuario::PERMISO_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $info['tiposDocumentos'] = Usuario::darTipoDocumento();
        $info['generos'] = Usuario::darTipoGenero();
        $info['paises'] = Pais::where('estado', Pais::ACTIVO)->orderBy('nombre')->get();

        return view('usuarios.index', $info);
    }

    public function listado(Request $request)
    {
        if (!can(Usuario::PERMISO_LISTADO) && !can(Usuario::PERMISO_CREAR) && !can(Usuario::PERMISO_EDITAR) && !can(Usuario::PERMISO_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $usuarios = Usuario::with(
            'infoEstado',
            'infoGenero',
            'infoDocumento',
            'ciudad',
        )->where('estado', '!=', Usuario::ELIMINADO)
        ->when(auth()->user()->hasRole(Usuario::ROL_CLIENTE), function($query) {
            $query->where('cod_empresa', auth()->user()->uuid);
        });

        return DataTables::eloquent($usuarios)
            ->addColumn("estado", function ($model) {
                $info['concepto'] = $model?->infoEstado;
                return view("sistema.estado", $info);
            })
            ->addColumn("action", function($model){
                $info['model'] = $model;
                $info['puedeAgregarRol'] = can(Usuario::PERMISO_AGREGAR_PERMIDO) && can(Usuario::PERMISO_AGREGAR_ROL);
                $info['puedeEditar'] = can(Usuario::PERMISO_EDITAR);
                $info['puedeEliminar'] = can(Usuario::PERMISO_ELIMINAR);
                $info['estados'] = Usuario::darEstado();
                $info['nombreTabla'] = Usuario::darNombreTabla();
                return view("usuarios.columnas.acciones", $info);
            })
            ->rawColumns(["action"])
            ->make(true);
    }

    public function store(StoreUsuarioRequest $request)
    {
        if (!can(Usuario::PERMISO_LISTADO) && !can(Usuario::PERMISO_CREAR) && !can(Usuario::PERMISO_EDITAR) && !can(Usuario::PERMISO_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $datos = $request->all();
        $datos['password'] = password_hash($datos['identificacion'], PASSWORD_DEFAULT);
        $usuario = Usuario::create($datos);

        if (!$usuario) {
            throw new ErrorException('Error al intentar crear el nuevo usuario.');
        }

        if (auth()->user()->hasRole(Usuario::ROL_CLIENTE)) {
            $usuario->assignRole(Usuario::ROL_AGENTE);
        } else {
            $usuario->assignRole(Usuario::ROL_CLIENTE);
        }

        $usuario->refresh();

        // Verifica el ID
        if (empty($usuario->id)) {
            throw new ErrorException("El usuario no tiene un ID asignado.");
        }

        UsuarioEmpresa::create([
            'cod_empresa' => auth()->user()->cod_empresa,
            'cod_usuario' => $usuario?->uuid,
        ]);

        return [
            'estado' => 'success',
            'mensaje' => 'Se creo correctamente el usuario.',
        ];
    }

    public function edit(Request $request, $usuario)
    {
        if (!can(Usuario::PERMISO_LISTADO) && !can(Usuario::PERMISO_CREAR) && !can(Usuario::PERMISO_EDITAR) && !can(Usuario::PERMISO_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $usuario = Usuario::with('ciudad.pais')
            ->where('uuid', $usuario)
            ->first();
        $info["usuario"] = $usuario;
        $tipos = Usuario::darTipoGenero();
        $info["generos"] = $tipos;
        $tipos = Usuario::darTipoDocumento();
        $info["tiposDocumentos"] = $tipos;
        $info['paises'] = Pais::where('estado', Pais::ACTIVO)->get();

        $respuesta["estado"] = "success";
        $respuesta["mensaje"] = "Datos cargados correctamente";
        $respuesta['html'] = view("usuarios.modals.editar", $info)->render();

        return response()->json($respuesta);
    }

    public function update(UpdateUsuarioRequest $request, $usuario)
    {
        $usuario = Usuario::where('uuid', $usuario)
            ->first();
        if ($usuario?->id != auth()->user()->id && !can(Usuario::PERMISO_EDITAR) && !can(Usuario::PERMISO_ELIMINAR)) {
            throw new ErrorException("No tienes permisos para acceder a esta sección.");
        }

        $datos = $request->all();
        if (array_key_exists('google2fa_secret', $datos)) {
            $datos['google2fa_secret'] = $datos['google2fa_secret'] == 'null' ? null : $datos['google2fa_secret'];
        }
        $actualizar = $usuario->update($datos);
        if (!$actualizar) {
            throw new ErrorException('Error al intentar actualizar el usuario.');
        }

        $image = $request->file('avatar') ?? null;
        if ($image) {
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('perfil', $imageName, 'public');
            $datos['foto'] = url(Storage::url($path));
            $usuario = Usuario::find($request->input('id'));
            if (count($datos)) {
                $actualizar = $usuario->update($datos);
                if (!$actualizar) {
                    throw new ErrorException("No se ha actualizado la imagen.");
                }
            }
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se actualizo correctamente el usuario.',
        ];
    }

    public function show(Request $request)
    {
        $usuario = auth()->user();
        $QrCode = QrCode::size(500)
            ->color(255, 255, 255)
            ->backgroundColor(31, 59, 115)
            ->generate(route('perfil'));

        $info['usuario'] = $usuario;
        $info['QrCode'] = $QrCode;
        $tipos = Usuario::darTipoGenero();
        $info["generos"] = $tipos;
        $tipos = Usuario::darTipoDocumento();
        $info["tiposDocumentos"] = $tipos;
        $info['paises'] = Pais::where('estado', Pais::ACTIVO)->get();

        if (!$usuario->google2fa_secret) {
            $google2fa = app('pragmarx.google2fa');
            $google2fa_secret = $google2fa->generateSecretKey();

            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $usuario->email,
                $google2fa_secret
            );
        }

        $info['qr'] = $QR_Image ?? null;
        $info['secret'] = $google2fa_secret ?? null;

        $info['ultimaTransaccion'] = Factura::where('cod_empresa', auth()->user()->empresa?->id)
            ->latest('created_at')
            ->first() ?? null;

        $ultimaFacturaPagada = Factura::where('cod_empresa', auth()->user()->empresa?->id)
            ->where('x_response', 'Aceptada')
            ->latest('created_at')
            ->first() ?? null;
        $info['ultimaFacturaPagada'] = $ultimaFacturaPagada;

        $validarFechaVencimiento = optional($ultimaFacturaPagada)->fecha_vencimiento < now();
        $info['validarFechaVencimiento'] = $validarFechaVencimiento;
        $info['plan'] = !$validarFechaVencimiento ? Plan::find($ultimaFacturaPagada->cod_plan) : null;
        $info['cantidad_contactos_activos'] = Contacto::where('estado', Contacto::ACTIVO)
            ->where('uuid', $this->uuid)
            ->count();

        return view('perfil.index', $info);
    }

    public function actualizarEmail(Request $request, $usuario)
    {
        $usuario = Usuario::where('uuid', $usuario)
            ->first();
        $email = $request->input('emailaddress');
        $password = $request->input('confirmemailpassword');

        if ($usuario && Hash::check($password, $usuario->password)) {
            $actualizar = $usuario->update([
                'email' => $email
            ]);

            if (!$actualizar) {
                throw new ErrorException("Error al intentar actualizar el correo.");
            }
        } else {
            throw new ErrorException("Su contraseña es incorrecta.");
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se actualizo correctamente su contraseña',
        ];
    }

    public function actualizarContrasena(Request $request, $usuario)
    {
        $usuario = Usuario::where('uuid', $usuario)
            ->first();
        $password = $request->input('currentpassword');
        $newpassword = $request->input('newpassword');

        if ($usuario && Hash::check($password, $usuario->password)) {
            $actualizar = $usuario->update([
                'password' => Hash::make($newpassword)
            ]);

            if (!$actualizar) {
                throw new ErrorException("Error al intentar actualizar la contraseña.");
            }
        } else {
            throw new ErrorException("Su contraseña es incorrecta.");
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se actualizo correctamente su contraseña',
        ];
    }

    public function verify2FA(Request $request)
    {
        $secret = $request->input('secret');
        $code = $request->input('code');

        $valid = Google2FA::verifyKey($secret, $code);

        if (!$valid) {
            throw new ErrorException("El código es incorrecto.");
        }

        $user = Usuario::find(auth()->id());
        $user->google2fa_secret = $secret;
        $user->save();

        return [
            'estado' => 'success',
            'mensaje' => 'Se activo correctamente la autenticaón de dos factores.',
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($usuario)
    {
        $usuario = Usuario::where('uuid', $usuario)
            ->first();
        $eliminar = $usuario->eliminar();

        if (!$eliminar) {
            throw new ErrorException('A ocurrido un error al intentar eliminar el usuario.');
        }

        return [
            'estado' => 'success',
            'mensaje' => 'Se eliminado correctamente el usuario.',
        ];
    }

    public function buscar(Request $request)
    {
        $nombre = $request->get("busqueda");
        $filtro = "%$nombre%";
        $proyecto = $request->input('proyecto') ?? '';
        if (!$proyecto) {
            throw new ErrorException("Por favor, seleccione un proyecto.");
        }

        $usuarios = Usuario::selectRaw('id, nombre, apellido')
            ->where(function($query) use($filtro){
                $query->whereRaw("LOWER(nombre) LIKE LOWER(?)", $filtro)
                    ->orWhereRaw("LOWER(apellido) LIKE LOWER(?)", $filtro);
            })
            ->where('estado', Usuario::ACTIVO)
            ->get()
            ->map(function ($item, $key) {
                $item->seleccionado = false;
                return $item;
            });

        return [
            'estado' => 'success',
            'usuarios' => $usuarios,
        ];
    }
}
