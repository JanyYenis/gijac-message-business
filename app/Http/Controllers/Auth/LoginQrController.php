<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Dispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LoginQrController extends Controller
{
    public function index(Request $request)
    {
        $token = Str::uuid();

        Dispositivo::create([
            'usuario_id' => auth()->user()->id,
            'token' => $token,
            'expira_en' => now()->addMinutes(2),
        ]);

        $qrData = json_encode([
            'token' => $token,
            'server' => config('app.url')
        ]);

        $info['qr'] = QrCode::size(300)->generate($qrData);

        return view('auth.login-qr', $info);
    }
}
