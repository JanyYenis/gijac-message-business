<?php

namespace App\Http\Controllers\Apis\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Dispositivo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /** POST /api/mobile/device/fcm — la app envía su token de notificaciones (cambia con el tiempo) */
    public function actualizarFcm(Request $request): JsonResponse
    {
        $data = $request->validate(['fcm_token' => 'required|string|max:4096']);

        $token = $request->bearerToken();
        if (! $token) {
            return response()->json(['success' => false], 401);
        }

        // Un mismo token FCM no puede quedar en dos dispositivos
        Dispositivo::where('fcm_token', $data['fcm_token'])->where('token', '!=', $token)->update(['fcm_token' => null]);
        Dispositivo::where('token', $token)->update(['fcm_token' => $data['fcm_token']]);

        return response()->json(['success' => true]);
    }
}
