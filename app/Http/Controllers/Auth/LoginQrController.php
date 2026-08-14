<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Dispositivo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginQrController extends Controller
{
    public function index(Request $request)
    {
        // Eliminar tokens expirados que aún no se vincularon
        Dispositivo::whereNull('vinculado_en')
            ->where('expira_en', '<=', now())
            ->delete();

        // ¿Ya existe un dispositivo vinculado?
        $dispositivo = Dispositivo::where('usuario_id', auth()->user()->uuid)
            ->whereNotNull('vinculado_en')
            ->latest()
            ->first();

        if ($dispositivo) {
            return view('auth.login-qr', [
                'dispositivo' => $dispositivo,
                'qr' => null,
            ]);
        }

        // Buscar un token vigente
        $dispositivo = Dispositivo::where('usuario_id', auth()->user()->uuid)
            ->whereNull('vinculado_en')
            ->where('expira_en', '>', now())
            ->latest()
            ->first();

        if (!$dispositivo) {
            $dispositivo = Dispositivo::create([
                'usuario_id' => auth()->user()->uuid,
                'token' => Str::uuid(),
                'expira_en' => now()->addMinutes(2),
            ]);
        }

        $qrData = config('app.url')
            . '/device-link?token=' . urlencode($dispositivo->token)
            . '&server=' . urlencode(config('app.url'));

        return view('auth.login-qr', [
            'qr' => generarQR($qrData),
            'dispositivo' => null,
        ]);
    }

    public function deviceLink(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'nombre_dispositivo' => 'required',
            'modelo' => 'nullable',
            'sistema_operativo' => 'required',
            'version_so' => 'nullable',
            'ip' => 'nullable',
            'fcm_token' => 'nullable',
        ]);

        $dispositivo = Dispositivo::where('token', $request->token)->first();

        if (!$dispositivo) {
            return response()->json([
                'error' => 'Token inválido.'
            ], 404);
        }

        if (Carbon::parse($dispositivo->expira_en)->isPast()) {
            return response()->json([
                'error' => 'Token expirado.'
            ], 400);
        }

        $dispositivo->update([
            'nombre_dispositivo' => $request->nombre_dispositivo,
            'modelo' => $request->modelo,
            'sistema_operativo' => $request->sistema_operativo,
            'version_so' => $request->version_so,
            'ip' => $request->ip ?? $request->ip(),
            'fcm_token' => $request->fcm_token,
            'vinculado_en' => now(),
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}
