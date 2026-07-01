<?php

namespace App\Http\Controllers;

use App\Jobs\SubirFotoPerfilWhatsApp;
use Illuminate\Http\Request;

class PerfilWhatsappController extends Controller
{
    public function edit(Request $request)
    {
        $response = $this->whatsapp_cloud_api->businessProfile('about,address,description,email,profile_picture_url,websites,vertical');
        $datos = json_decode($response->body())?->data[0];
        $info['dato'] = $datos;
        $info['numeroG'] = $this->numeroG;
        $info['categorias'] = [
            'UNDEFINED' => 'INDEFINIDO',
            'OTHER' => 'OTRO',
            'AUTO' => 'AUTO',
            'BEAUTY' => 'BELLEZA',
            'APPAREL' => 'VESTIR',
            'EDU' => 'EDUCACIÓN',
            'ENTERTAIN' => 'ENTRETENER',
            'EVENT_PLAN' => 'EVENTO_PLAN',
            'FINANCE' => 'FINANZAS',
            'GROCERY' => 'TIENDA DE COMESTIBLES',
            'GOVT' => 'GOBIERNO',
            'HOTEL' => 'HOTEL',
            'HEALTH' => 'SALUD',
            'NONPROFIT' => 'SIN ÁNIMO DE LUCRO',
            'PROF_SERVICES' => 'SERVICIOS PROFECIONALES',
            'RETAIL' => 'MINORISTA',
            'TRAVEL' => 'VIAJAR',
            'RESTAURANT' => 'RESTAURANTE',
            // 'NOT_A_BIZ' => 'NOT_A_BIZ',
        ];
        $info['datosNumero'] = getPhoneNumbers($this->waba_id, $this->version, $this->token);

        $respuesta["estado"] = "success";
        $respuesta["mensaje"] = "Datos cargados correctamente";
        $respuesta['html'] = view("perfil-whatsapp.editar", $info)->render();

        return response()->json($respuesta);
    }

    public function update(Request $request)
    {
        $datos = $request->all();
        // dd($datos, $request->file('profile_picture_url'));
        if (isset($datos['websites'])) {
            $datos['websites'] = explode(',', $datos['websites']);
        }
        if ($request->hasFile('profile_picture_url')) {
            $archivo = $request->file('profile_picture_url');
            $nombreOriginal = time() . '.' . $archivo->getClientOriginalExtension();

            $path = $archivo->storeAs('perfil-whatsapp', $nombreOriginal, 'public');
            $archivo->move(public_path('img/perfil-whatsapp'), $nombreOriginal);
            // $datos['profile_picture_url'] = asset('img/perfil-whatsapp/'.$nombreOriginal);
            $datos['profile_picture_url'] = public_path('img/perfil-whatsapp/'.$nombreOriginal);
            $url = 'img/perfil-whatsapp/'.$nombreOriginal;
            dispatch(new SubirFotoPerfilWhatsApp(auth()->user()->empresa?->id, $datos['profile_picture_url'], $url));
            // dd(generarSeccionSubirArchivo($datos['profile_picture_url'], $this->app_id, $this->version, $this->token));
            // $response = $this->whatsapp_cloud_api->uploadMedia($datos['profile_picture_url']);
            // $datos['profile_picture_url'] = $response->decodedBody()['id'];
        }
        unset($datos['profile_picture_url']);

        $this->whatsapp_cloud_api->updateBusinessProfile($datos);

        return response()->json([
            'estado' => 'success',
            'mensaje' => 'Se actualizo la informacion de WhatsApp correctamente.',
        ]);
    }
}
