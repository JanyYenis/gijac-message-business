<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Dispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginQrController extends Controller
{
    public function index(Request $request)
    {
        $token = Str::uuid();

        Dispositivo::create([
            'usuario_id' => auth()->user()->id,
            'token'      => $token,
            'expira_en'  => now()->addMinutes(2),
        ]);

        $qrData = config('app.url')
            . '/device-link?token=' . urlencode($token)
            . '&server=' . urlencode(config('app.url'));

        $info['qr'] = generarQR($qrData);

        return view('auth.login-qr', $info);
    }
}
