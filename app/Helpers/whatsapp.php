<?php

use App\Models\Campana;
use Illuminate\Support\Facades\Http;

if (!function_exists('generarSeccionSubirArchivo')) {
    function generarSeccionSubirArchivo($urlArchivo, $app_id, $version, $token, $url)
    {
        $bytes = filesize($urlArchivo);
        // echo "El tamaño de la imagen es $bytes bytes. app_id: {$app_id}";
        // echo '<br><br>';

        $headers = get_headers(asset($url), 1);

        if (isset($headers['Content-Type'])) {
            $tipo_de_archivo = $headers['Content-Type'];
        } else {
            echo "No se pudo determinar el tipo de archivo.";
            echo '<br><br>';
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.facebook.com/{$version}/{$app_id}/uploads?file_length={$bytes}&file_type={$tipo_de_archivo}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer {$token}"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        // Decodificar el JSON
        $data = json_decode($response, true);

        // Verificar si la decodificación fue exitosa
        $info = null;
        if (count($data ?? [])) {
            // Acceder al valor de la clave 'id'
            $id = $data['id'];
            // echo "El valor del ID es: $id";
            // echo '<br><br>';
            $info = subirArchivo($url, $tipo_de_archivo, $id, $version, $token);
        }

        return $info;
    }
}

if (!function_exists('subirArchivo')) {
    function subirArchivo($urlArchivo, $tipo_de_archivo, $seccion, $version, $token)
    {
        $url = "https://graph.facebook.com/{$version}/{$seccion}"; // Facebook Upload URL
        $filePath = asset($urlArchivo); // Local File Path
        $fileOffset = 0; // Set the file offset
        $archivo = file_get_contents($filePath);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $archivo,
            CURLOPT_HTTPHEADER => [
                "Authorization: OAuth {$token}",
                "file_offset: " . $fileOffset,
                "Content-Type: {$tipo_de_archivo}",
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        echo $response;
        echo '<br><br>';

        $data = json_decode($response, true);
        return $data['h'];
    }
}

if (!function_exists('getPhoneNumbers')) {
    function getPhoneNumbers($waba_id, $version, $token)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$token
        ])->get("https://graph.facebook.com/{$version}/{$waba_id}/phone_numbers");

        $info = [];
        if ($response->body()) {
            $info = json_decode($response->body(), true);
        }
        return $info;
    }
}

if (!function_exists('consultaBase')) {
    function consultaBase($url, $metodo, $token)
    {
        $response = Http::withToken($token)
            ->timeout(30)
            ->withOptions([
                'max_redirects' => 10,
                'follow_location' => true,
            ])
            ->{$metodo}($url);

        return $response->body();
    }
}

if (!function_exists('extraerContenidoPlantilla')) {
    function extraerContenidoPlantilla($plantilla)
    {
        $info = 'N/A';
        foreach ($plantilla?->components as $index => $component) {
            if ($component->type == "BODY") {
                $info = property_exists($component, 'text') ? $component->text : 'N/A';
            }
        }
        return $info;
    }
}

if (!function_exists('conocerTipoPlantilla')) {
    function conocerTipoPlantilla($plantilla)
    {
        $tipo = Campana::TEXTO;
        if (property_exists($plantilla, 'components') && isset($plantilla?->components) && count($plantilla?->components)) {
            foreach ($plantilla->components as $key => $componente) {
                if ($componente->type == 'HEADER') {
                    if ($componente->format == 'IMAGE') {
                        $tipo = Campana::IMAGEN;
                    } else if ($componente->format == 'VIDEO') {
                        $tipo = Campana::VIDEO;
                    } else if ($componente->format == 'DOCUMENT') {
                        $tipo = Campana::DOCUMENTO;
                    }
                }
            }
        }

        return $tipo;
    }
}

if (!function_exists('contenidoPlantilla')) {
    function contenidoPlantilla($plantilla)
    {
        $info = [];
        if (property_exists($plantilla, 'components') && count($plantilla->components)) {
            $components = $plantilla->components;
            foreach ($components as $index => $component) {
                $info['tipo'] = $component->type;
                if ($index == 0) {
                    $info[$component->type]['text'] = property_exists($component, 'text') ? $component->text : 'N/A';
                    $info[$component->type]['format'] = property_exists($component, 'format') ? $component->format : 'N/A';
                    $info[$component->type]['example'] = 'N/A';
                    if (property_exists($component, 'example') && property_exists($component->example, 'header_handle')) {
                        $info['example'] = $component->example->header_handle[0];
                    }
                } else {
                    $info[$component->type]['text'] = property_exists($component, 'text') ? $component->text : 'N/A';
                    $info[$component->type]['body_text'] = ['N/A'];
                    if (property_exists($component, 'example')) {
                        if (property_exists($component->example, 'body_text')) {
                            $info[$component->type]['body_text'] = $component->example->body_text[0];
                        } else if (property_exists($component->example, 'body_text_named_params')) {
                            $info[$component->type]['body_text'] = $component->example->body_text_named_params[0];
                        }
                    }
                }
            }
        }
        return $info;
    }
}
