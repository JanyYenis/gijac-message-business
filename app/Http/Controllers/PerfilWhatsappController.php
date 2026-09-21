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
            'UNDEFINED' => __('INDEFINIDO'),
            'OTHER' => __('OTRO'),
            'AUTO' => __('AUTO'),
            'BEAUTY' => __('BELLEZA'),
            'APPAREL' => __('VESTIR'),
            'EDU' => __('EDUCACIÓN'),
            'ENTERTAIN' => __('ENTRETENER'),
            'EVENT_PLAN' => __('EVENTO_PLAN'),
            'FINANCE' => __('FINANZAS'),
            'GROCERY' => __('TIENDA DE COMESTIBLES'),
            'GOVT' => __('GOBIERNO'),
            'HOTEL' => __('HOTEL'),
            'HEALTH' => __('SALUD'),
            'NONPROFIT' => __('SIN ÁNIMO DE LUCRO'),
            'PROF_SERVICES' => __('SERVICIOS PROFECIONALES'),
            'RETAIL' => __('MINORISTA'),
            'TRAVEL' => __('VIAJAR'),
            'RESTAURANT' => __('RESTAURANTE'),
            // 'NOT_A_BIZ' => 'NOT_A_BIZ',
        ];
        $info['datosNumero'] = getPhoneNumbers($this->waba_id, $this->version, $this->token);

        $respuesta["estado"] = "success";
        $respuesta["mensaje"] = __("Datos cargados correctamente");
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
            'mensaje' => __('Se actualizo la informacion de WhatsApp correctamente.'),
        ]);
    }
}
