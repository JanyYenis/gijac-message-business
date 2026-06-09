<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\Campana;
use App\Models\Plantilla;
use App\Models\Usuario;
use App\Models\VariableCampana;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PlantillaController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $info['numeroTel'] = $this->numeroG;
        $info['estados'] = Plantilla::darEstados();
        $info['categorias'] = Plantilla::darCategoria();

        return view('plantillas.index', $info);
    }

    public function listado()
    {
        $plantillas = Plantilla::with(
                'infoEstado',
                'infoCategoria',
                'body'
            )
            ->where('cod_config', $this->cod_config);

        return DataTables::eloquent($plantillas)
            ->addColumn("action", function($model){
                $info['model'] = $model;
                return view("plantillas.columnas.acciones", $info);
            })
            ->addColumn("estado", function ($model) {
                $info['concepto'] = $model?->infoEstado;
                return view("sistema.estado", $info);
            })
            ->addColumn("name", "plantillas.columnas.nombre")
            ->addColumn("category", "plantillas.columnas.categoria")
            ->addColumn("language", "plantillas.columnas.lenguaje")
            ->rawColumns(["action", "name", "category", "language"])
            ->make(true);
    }

    public function show(Request $request, Plantilla $plantilla)
    {
        $plantilla->load(
            'header',
            'body',
            'footer',
            'buttons',
        );

        $campana = null;
        $respuesta['url_campana'] = null;
        $respuesta['urls_de_campana'] = [];
        $respuesta['datos_variables'] = [];
        if ($request->input('campana')) {
            $campana = Campana::find($request->input('campana')) ?? null;
            if ($campana) {
                if ($campana->tipo == Campana::IMAGEN || $campana->tipo == Campana::VIDEO) {
                    $respuesta['url_campana'] = $campana->contenido_multimedia;
                }
                $variables_campana = VariableCampana::where('cod_campana', $campana->id)
                    ->where('estado', VariableCampana::ACTIVO)
                    ->where('tipo', VariableCampana::URL)
                    ->get();
                if (count($variables_campana)) {
                    foreach ($variables_campana as $detalle) {
                        $respuesta['urls_de_campana'][] = $detalle->valor;
                    }
                }

                $datosVariables = VariableCampana::where('estado', VariableCampana::ACTIVO)
                    ->where('cod_campana', $campana->id)
                    ->where('tipo', VariableCampana::TEXT)
                    ->orderBy('numero')
                    ->get();
                if (count($datosVariables)) {
                    $datosVariables = $datosVariables->pluck('valor')
                        ->toArray();
                    $respuesta['datos_variables'] = $datosVariables;
                }
            }
        }

        $respuesta['numeroTel'] = $this->numeroG;
        $respuesta['plantilla'] = $plantilla;
        $respuesta["estado"] = "success";
        $respuesta["mensaje"] = "Datos cargados correctamente";

        return response()->json($respuesta);
    }

    public function store(Request $request)
    {
        // if (!can(Usuario::PERMISO_PLANES_LISTADO) && !can(Usuario::PERMISO_PLANES_CREAR) &&
        // !can(Usuario::PERMISO_PLANES_EDITAR) && !can(Usuario::PERMISO_PLANES_ELIMINAR)) {
        //     throw new ErrorException("No tienes permisos para acceder a esta sección.");
        // }

        $nombre = $request->input('nombre');
        $tipo_encabezado = $request->input('tipo_encabezado') ?? null;
        $texto_encabzado = $request->input('texto_encabzado') ?? null;
        $boton = $request->input('boton') ?? null;
        $multimedia = $request->input('multimedia') ?? 1;
        $contenido = $request->input('contenido') ?? '';
        $archivo = $request->file('archivo');

        if ($request->input('variables')) {
            $variablesMensaje = $request->input('variables');
            // dd($variablesMensaje);
        }
        // dd($request->input('multimedia'));
        $info = [];
        if ($tipo_encabezado) {
            // dd($tipo_encabezado);
            if ($tipo_encabezado == 1) {
                $info[] = json_encode([
                    "type" => "HEADER",
                    "format" => 'TEXT',
                    "text" => $texto_encabzado
                ]);
            } else {
                $tipo = 'IMAGE';
                if ($multimedia == 1) {
                    $tipo = 'IMAGE';
                } elseif ($multimedia == 2) {
                    $tipo = 'VIDEO';
                } else {
                    $tipo = 'DOCUMENT';
                }

                if ($archivo) {
                    dd($archivo);
                    // $nombreOriginal = str_replace(' ', '_', $_FILES['archivo']['name']);
                    $nombreOriginal = time();
                    $extencionArchivo = $archivo->getClientOriginalExtension() ?? '.png';
                    $rutaTemporal = $_FILES['archivo']['tmp_name'];

                    $rutaDestino = $_SERVER['DOCUMENT_ROOT'] . '/public/img/' . $nombreOriginal.$extencionArchivo;
                    $urlArchivo = 'img/' . $nombreOriginal.$extencionArchivo;
                    if (strpos($extencionArchivo, 'pdf')) {
                        $extencionArchivo = '.pdf';
                        $rutaDestino = $_SERVER['DOCUMENT_ROOT'] . '/public/documentos/' . $nombreOriginal.$extencionArchivo;
                        $urlArchivo = 'documentos/' . $nombreOriginal.$extencionArchivo;
                    }
                    if (strpos($extencionArchivo, 'mp4')) {
                        $extencionArchivo = '.mp4';
                       $rutaDestino = $_SERVER['DOCUMENT_ROOT'].'/public/videos/'. $nombreOriginal.$extencionArchivo;
                       $urlArchivo = 'videos/' . $nombreOriginal.$extencionArchivo;
                    }

                    if(move_uploaded_file($rutaTemporal, $rutaDestino)) {
                        if (file_exists($urlArchivo)) {
                            $infoArchivo = $this->generarSeccionSubirArchivo($urlArchivo);

                            $info[] = json_encode([
                                "type" => "HEADER",
                                "format" => $tipo,
                                "example" => json_encode([
                                    "header_handle" => [
                                        $infoArchivo
                                    ]
                                ])
                            ]);
                        } else {
                            echo "El archivo no existe.";
                            echo '<br><br>';
                        }
                    }

                }
            }
        }

        $datos = [
            "type" => "BODY",
            "text" => $contenido,
        ];
        if (isset($variablesMensaje)) {
            $datos["example"] = json_encode([
                "body_text" => [
                    $variablesMensaje
                ]
            ]);
        }
        $info[] = json_encode($datos);

        if ($boton) {
            if ($boton == 1) {
                $url = '';
                // Verificar si el string comienza con "https://"
                if (strpos($request->input('url'), "https://") === 0) {
                    $url = $request->input('url');
                }
                // Verificar si el string comienza con "http://"
                elseif (strpos($request->input('url'), "http://") === 0) {
                    $url = $request->input('url');
                }
                // Si no contiene ninguno de los dos, agregar "https://"
                else {
                    $url = "https://" . $request->input('url');
                }

                $datosBoton = json_encode([
                    "type" => "URL",
                    "text" => "Sitio Web",
                    "url" => $url,
                ]);
            } else {
                $datosBoton = json_encode([
                    "type" => "URL",
                    "text" => "Contacte a su visitador",
                    "url" => "https://message-business.gijac.com/{{1}}",
                    "example" => [
                        "https://message-business.gijac.com/hola"
                    ]
                ]);
            }

            $info[] = json_encode([
                "type" => "BUTTONS",
                "buttons" => [
                    $datosBoton
                ]
            ]);
        }


        $info = json_encode($info);
        // dd($info);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://graph.facebook.com/{$this->version}/{$this->waba_id}/message_templates",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>"{
            'name': '$nombre',
            'language': 'es_ES',
            'category': 'MARKETING',
            'components': $info
        }",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer {$this->token}"
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

        header("location: ".$this->baseUrl."Plantilla");
    }

    public function generarSeccionSubirArchivo($urlArchivo)
    {
        $bytes = filesize($urlArchivo);
        echo "El tamaño de la imagen es $bytes bytes. app_id: {$this->app_id}";
        echo '<br><br>';

        $headers = get_headers($this->baseUrl.$urlArchivo, 1);
        // dd($headers);
        // echo '<br><br>';


        if (isset($headers['Content-Type'])) {
            $tipo_de_archivo = $headers['Content-Type'];
        } else {
            echo "No se pudo determinar el tipo de archivo.";
            echo '<br><br>';
        }

        // dd($this->token);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://graph.facebook.com/{$this->version}/{$this->app_id}/uploads?file_length={$bytes}&file_type={$tipo_de_archivo}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer {$this->token}"
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
        // echo '<br><br>';

        // Decodificar el JSON
        $data = json_decode($response, true);

        // Verificar si la decodificación fue exitosa
        $info = null;
        if (count($data)) {
            dd($data);
            echo '<br><br>';
            // Acceder al valor de la clave 'id'
            $id = $data['id'];
            echo "El valor del ID es: $id";
            echo '<br><br>';
            $info = $this->subirArchivo($urlArchivo, $tipo_de_archivo, $id);
        }

        return $info;
    }

    public function subirArchivo($urlArchivo, $tipo_de_archivo, $seccion)
    {
        $url = "https://graph.facebook.com/{$this->version}/{$seccion}"; // Facebook Upload URL
        // $filePath = $urlArchivo; // Local File Path
        $filePath = $this->baseUrl.$urlArchivo; // Local File Path
        $fileOffset = 0; // Set the file offset
        $archivo = file_get_contents($filePath);
        // dd($archivo);
        // echo '<br><br>';

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
                "Authorization: OAuth {$this->token}",
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

    public function buscar(Request $request)
    {
        $plantillas = Plantilla::with(
                'infoEstado',
                'infoCategoria',
                'body'
            )
            ->where('cod_config', $this->cod_config)
            ->get();

        return [
            'estado'     => 'success',
            'mensaje'    => 'Se cargo correctamente.',
            'plantillas' => $plantillas,
        ];
    }
}
