<?php

use App\Exceptions\ErrorException;
use App\Models\Factura;
use App\Models\InvoiceTemporal;
use App\Models\Sistema\TipoConcepto;
use App\Models\Sistema\Concepto;
use App\Models\Sistema\Parametro;
use App\Models\Usuario;
use Carbon\Carbon;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Luecano\NumeroALetras\NumeroALetras;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

if (!function_exists('routeActive')) {
    /**
     * Función que retorna texto si la ruta esta activa o no
     * @param ruta ruta actual del usuario
     * @return string
     */
    function routeActive($ruta)
    {
        if (strpos($ruta, 'http') === 0) {
            return (url()->current() == $ruta) ? 'kt-menu__item--active' : "";
        } else {
            $name = Route::currentRouteName();
            return ($name == $ruta) ? 'kt-menu__item--active' : "";
        }
    }
}

if (!function_exists('routeStartWith')) {
    /**
     * Función que retorna texto si la ruta esta activa o no
     * @param ruta ruta actual del usuario
     * @return string
     */
    function routeStartWith($ruta)
    {
        // dd(request()->is("$ruta/*"), request()->is($ruta));
        return request()->is("$ruta/*") ? 'kt-menu__item--open' : "";
    }
}

if (!function_exists('initCap')) {
    /**
     * Función que retorna texto con la inicial de cada palabra en mayusculas
     * @param ruta Cadena de texto a convertir
     * @return string
     */
    function initCap($string)
    {
        return mb_convert_encoding(mb_convert_case($string, MB_CASE_TITLE), "UTF-8");
    }
}

if (!function_exists('lower')) {
    /**
     * Función que retorna texto con la inicial de cada palabra en mayusculas
     * @param ruta Cadena de texto a convertir
     * @return string
     */
    function lower($string)
    {
        return trim(mb_strtolower($string));
    }
}

if (!function_exists('removerEspacios')) {
    /**
     * Función que retorna texto si la ruta esta activa o no
     * @param ruta ruta actual del usuario
     * @return string
     */
    function removerEspacios($string)
    {
        return preg_replace("/\s{2,}/", " ", trim($string));
    }
}

if (!function_exists('lnInput')) {
    /**
     * Función para convertir los saltos de lineas en br y preveniendo el sql inyección
     */
    function lnInput($texto)
    {
        return nl2br(e($texto));
    }
}

if (!function_exists('darInfoConcepto')) {
    function darInfoConcepto($model, $tipoConcepto, $campo)
    {
        return $model->belongsTo(Concepto::class, $campo, 'codigo')
            ->join('tipo_conceptos tc', 'tc.id', 'conceptos.tipo_id')
            ->where('tc.codigo', $tipoConcepto);
    }
}

if (!function_exists('darConceptoPorTipo')) {
    function darConceptoPorTipo($tipoConcepto, $concepto)
    {
        return Concepto::select('conceptos.*')
            ->join('tipo_conceptos tc', 'tc.id', 'conceptos.tipo_id')
            ->where('conceptos.codigo', $concepto)
            ->where('tc.codigo', $tipoConcepto)
            ->first();
    }
}

if (!function_exists('whereInSafe')) {
    function whereInSafe($query, $campo, $valor)
    {
        if (is_array($valor)) {
            $query->whereIn($campo, $valor);
        } elseif (!is_array($valor)) {
            $query->where($campo, $valor);
        }
        return $query;
    }
}

if (!function_exists('orWhereInSafe')) {
    function orWhereInSafe($query, $campo, $valor)
    {
        if (is_array($valor)) {
            $query->orWhereIn($campo, $valor);
        } elseif (!is_array($valor)) {
            $query->orWhere($campo, $valor);
        }
        return $query;
    }
}

if (!function_exists('castCursor')) {
    /**
     * Función que permite convertir un cursor a un arreglo
     * @param cursor Tipo de objeto cursor base de datos oracle
     * @return
     */
    function castCursor($Cursor)
    {
        oci_execute($Cursor, OCI_DEFAULT);
        oci_fetch_all($Cursor, $array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
        oci_free_cursor($Cursor);
        return $array;
    }
}

if (!function_exists('getChannels')) {
    /**
     * Get the channels of the given user.
     *
     * @param number $id
     * @return array
     */
    function getChannels($usuario)
    {
        //Global chanel
        $channels[0] = 'notify-channel';

        //Personal channel
        $channels[1] = 'notify-channel-' . $usuario->id;

        //Role channels
        // $roles = $usuario->getRoles();

        // foreach ($roles as $role) {
        //     $channels[] = 'notify-channel-' . $role;
        // }

        return $channels;
    }
}

// if (!function_exists('validarInput')) {
//     /**
//      *
//      */
//     function validarInput($name, $array, $upper = null)
//     {
//         if (isset($array[$name]) && $upper == EmailRequest::UPPER) {
//             $array[$name] = trim(mb_strtoupper($array[$name]));
//         } elseif (isset($array[$name]) && $upper == EmailRequest::LOWER) {
//             $array[$name] = trim(mb_strtolower($array[$name]));
//         } elseif (isset($array[$name]) && !$upper) {
//             $array[$name] = trim($array[$name]);
//         }
//         return $array;
//     }
// }

if (!function_exists('validarInputNumerico')) {
    /**
     *
     */
    function validarInputNumerico($name, $array)
    {
        if (isset($array[$name])) {
            $array[$name] = intval($array[$name]);
        }
        return $array;
    }
}

if (!function_exists('db_env')) {
    /**
     *
     */
    function db_env($nombre)
    {
        return config("settings.$nombre");
    }
}

if (!function_exists('array_undot')) {
    /**
     *
     */
    function array_undot($array)
    {
        $results = array();
        foreach ($array as $key => $value) {
            $dot = strpos($key, '.');
            if ($dot === false) {
                $results[$key] = $value;
            } else {
                list($first, $second) = explode('.', $key, 2);
                if (!isset($results[$first])) {
                    $results[$first] = array();
                }
                $results[$first][$second] = $value;
            }
        }
        return array_map(function ($value) {
            return is_string($value) ? $value : array_undot($value);
        }, $results);
    }
}

if (!function_exists('diffDate')) {
    /**
     * Función que retorna la diferencia de tiempo entre el parámetro y la fecha actual
     * @param date - Contiene la fecha del registro.
     */
    function diffDate($date)
    {
        return Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();
    }
}

if (!function_exists('formatLocalized')) {
    /**
     * Función que retorna el string de una fecha dada otro string de tipo fecha
     * @param date - Contiene la fecha del registro.
     */
    function formatLocalized($date, $formato = "d/m/Y", $formatoOriginal = null)
    {
        if ($formatoOriginal) {
            return Carbon::createFromFormat($formatoOriginal, $date)->format($formato);
        }
        return Carbon::parse($date)->format($formato);
    }
}

if (!function_exists('darTablas')) {
    function darTablas()
    {
        return DB::table('user_tables')
            ->selectRaw("lower(table_name) table_name")
            ->pluck('table_name')->toArray();
    }
}

if (!function_exists('calcularPeriodo')) {
    function calcularPeriodo($fecha)
    {
        $mes = $fecha->month;
        $anio = $fecha->year;

        $letra = $mes < 7 ? 'A' : 'B';

        return "$anio$letra";
    }
}

// if (!function_exists('darModelos')) {
//     /**
//      * Función que retorna la diferencia de tiempo entre el parámetro y la fecha actual
//      * @param string - Contiene la fecha del registro.
//      */
//     function darModelos($path)
//     {
//         $out = [];
//         $results = XXXXXXX($path);
//         foreach ($results as $result) {
//             if ($result === '.' or $result === '..') {
//                 continue;
//             }
//             $filename = $path . '/' . $result;
//             if (is_dir($filename)) {
//                 $out = array_merge($out, darModelos($filename));
//             } else {
//                 $prefix = '/var/www/';
//                 $str = $filename;
//                 if (substr($str, 0, strlen($prefix)) == $prefix) {
//                     $str = substr($str, strlen($prefix));
//                 }
//                 $str = ucfirst(substr($str, 0, -4));
//                 $str = str_replace('/', '\\', $str);
//                 $model = new $str;
//                 $existeFuncion = method_exists($model, 'darNombreTabla');
//                 if ($existeFuncion) {
//                     $out[] = ['class' => $str . '::class', 'table' => $model::darNombreTabla()];
//                     // $out[] = [$model::darNombreTabla() => $str . '::class'];
//                 }
//             }
//         }
//         return $out;
//     }
// }

// if (!function_exists('darTablasConModelos')) {
//     /**
//      * Función que retorna la diferencia de tiempo entre el parámetro y la fecha actual
//      * @param string - Contiene la fecha del registro.
//      */
//     function darTablasConModelos($ruta)
//     {
//         $modelos = darModelos($ruta);
//         $tablas = darTablas();
//         $resulado = [];

//         foreach ($modelos as $index => $valor) {
//             $llave = $valor['table'];
//             $pos = array_search($llave, $tablas);
//             if ($pos) {
//                 $resulado[$llave] = $valor['class'];
//                 unset($tablas[$pos]);
//             }
//         }
//         return $resulado;
//     }
// }

if (!function_exists('darModelo')) {
    /**
     * Función que trae el modelo según la llave en config.
     * @param string $modelo - Nombre del modelo.
     */
    function darModelo($modelo)
    {
        return config("modelos")[$modelo];
    }
}

if (!function_exists('validarConstante')) {
    /**
     * Función que valida la existencia de una constante en un modelo, de ser así retorna el valor del mismo.
     * @param string $modelo - Nombre del modelo.
     * @param string $cons - Nombre de la constante.
     */
    function validarConstante($modelo, $cons)
    {
        return defined("\\$modelo::$cons") ? constant("$modelo::$cons") : null;
    }
}

if (!function_exists('renameKeyArray')) {
    /**
     * Función que permite cambiar la llave de un arreglo por otra, dado un arreglo con la opción
     * @param array $array - arreglo con el listado de elementos a cambiar la llave.
     * @param array $keyChange - arreglo con la opción para cambiar la llave.
     */
    function renameKeyArray($array, $keyChange = [])
    {
        if (!empty($keyChange)) {
            // $contador = 0;
            foreach ($array as $llave => $elemento) {
                $nuevaLlave = $llave;
                foreach ($keyChange as $key => $value) {
                    // dump(++$contador);

                    if ($key == $llave) {
                        $nuevaLlave = $value;
                        unset($keyChange[$key]);
                        break;
                    }
                }
                $resultado[$nuevaLlave] = $elemento;
            }
        } else {
            $resultado = $array;
        }

        return $resultado;
    }
}

if (!function_exists("obfuscarCorreo")) {
    /**
     * Función que permite censurar un correo electrónico.
     * @param string $email Correo electrónico.
     * @return string Retorna la cadena de texto censurada.
     */
    function obfuscarCorreo($email)
    {
        $em = explode("@", $email);
        $name = implode('@', array_slice($em, 0, count($em) - 1));
        $len = floor(strlen($name) / 2);

        return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
    }
}

if (!function_exists("palabraPorGenero")) {
    /**
     * Función que permite censurar un correo electrónico.
     * @param string $email Correo electrónico.
     * @return string Retorna la cadena de texto censurada.
     */
    function palabraPorGenero($usuario, $pMasculino, $pFemenino, $pIndefinido = "")
    {
        $resultado = $pIndefinido;
        if ($usuario->genero == Usuario::MASCULINO) {
            $resultado = $pMasculino;
        } elseif ($usuario->genero == Usuario::FEMENINO) {
            $resultado = $pFemenino;
        }
        return $resultado;
    }
}

// if (!function_exists("palabraPorGeneroEmpleado")) {
//     /**
//      * Función que permite censurar un correo electrónico.
//      * @param string $email Correo electrónico.
//      * @return string Retorna la cadena de texto censurada.
//      */
//     function palabraPorGeneroEmpleado($empleado, $pMasculino, $pFemenino, $pIndefinido = "")
//     {
//         // dd($empleado);
//         $resultado = $pIndefinido;
//         if ($empleado->genero == Empleado::MASCULINO) {
//             $resultado = $pMasculino;
//         } elseif ($empleado->genero == Empleado::FEMENINO) {
//             $resultado = $pFemenino;
//         }
//         return $resultado;
//     }
// }

if (!function_exists("diffechas")) {
    /**
     * Función que permite censurar un correo electrónico.
     * @param string $email Correo electrónico.
     * @return string Retorna la cadena de texto censurada.
     */
    function diffechas($fInicial, $fFinal)
    {
        $diferencia = $fInicial->diff($fFinal);

        // Si el año es cero no muestra nada, Si el año es uno muestra Año, de lo contrario muestra Años
        $txtAnio = (($diferencia->y == 0) ? "" : ($diferencia->y == 1)) ? "Año" : "Años";

        // Si el mes es cero no muestra nada, Si el mes es uno muestra mes, de lo contrario muestra Meses
        $txtMes = (($diferencia->m == 0) ? "" : ($diferencia->m == 1)) ? "Mes" : "Meses";

        $formAnio = $diferencia->y == 0 ? "" : "%y {$txtAnio}";
        // Valida si no tiene año no aparece en y en los meses
        $conY = $diferencia->y == 0 ? "" : "y ";
        $formMes = $diferencia->m == 0 ? "" : "$conY%m {$txtMes}";
        $format = " $formAnio $formMes";
        return $diferencia->format($format);
    }
}

if (!function_exists("fechaMensaje")) {
    /**
     * Función que permite censurar un correo electrónico.
     * @param string $email Correo electrónico.
     * @return string Retorna la cadena de texto censurada.
     */
    function fechaMensaje($fecha)
    {
        if ($fecha) {
            $fecha = new DateTime($fecha);
            $hoy = new DateTime('today');
            $ayer = new DateTime('yesterday');

            if ($fecha->format('Y-m-d') == $hoy->format('Y-m-d')) {
                return $fecha->format('h:i a');
            } elseif ($fecha->format('Y-m-d') == $ayer->format('Y-m-d')) {
                return 'Ayer';
            } else {
                return $fecha->format('d/m/Y'); // O el formato que desees
            }
        }

        return '';
    }
}

if (!function_exists("calcularDiferenciaDeTiempo")) {
    /**
     * Función que permite censurar un correo electrónico.
     * @param string $email Correo electrónico.
     * @return string Retorna la cadena de texto censurada.
     */
    function calcularDiferenciaDeTiempo($fecha)
    {
        $fecha = new DateTime($fecha);
        $hoy = new DateTime('today');
        $ayer = new DateTime('yesterday');

        if ($fecha->format('Y-m-d') == $hoy->format('Y-m-d')) {
            $diferencia = $hoy->diff($fecha);
            if ($diferencia->format('%h')) {
                return $diferencia->format('%i m');
            } else {
                return $diferencia->format('%h h %i m');
            }
        } elseif ($fecha->format('Y-m-d') == $ayer->format('Y-m-d')) {
            return 'Ayer';
        } else {
            return $fecha->format('d/m/Y'); // O el formato que desees
        }
    }
}

// if (!function_exists('uuid')) {
//     function uuid($string = null)
//     {
//         if (!$string) {
//             $string = rand();
//             $string = uniqid($string, true);
//             $string = Hash::make($string);
//         }
//         return Uuid::generate(5, $string, Uuid::NS_URL)->string;
//     }
// }

if (!function_exists('darTablaModelo')) {
    function darTablaModelo($modelo)
    {
        return $modelo?->getTable();
    }
}

/**
 *
 */
if (!function_exists('propiedadesModelo')) {
    function propiedadesModelo($modelo)
    {
        return json_encode([
            "modelo" => darTablaModelo($modelo),
            "id" => $modelo?->id,
            "estado" => $modelo?->estado
        ]);
    }
}

if (!function_exists("selectGenerico")) {
    /**
     * Ajusta un array para visualizar con el select genérico
     * @return array $opcionesSelect
     */
    function selectGenerico($array)
    {
        $opcionesSelect = [];
        foreach ($array as $item) {
            $opcionesSelect["conceptosActivos"][] = [
                "codigo" => $item->id,
                "nombre" => $item->nombre
            ];
        }
        return $opcionesSelect;
    }
}

if (!function_exists("generarStringRandom")) {
    /**
     * Función que permite generar un string aleatorio.
     * Implementación en el backend de función del frontend.
     * @param string $extension Cantidad de caracteres con los que se generará la contraseña.
     * @param string $caracteres Grupo de caracteres disponibles.
     * @return string Retorna un string.
     */
    function generarStringRandom($extension, $caracteres)
    {
        $disponibles = "";
        $resultado = "";
        if (str_contains($caracteres, "a")) {
            $disponibles .= "abcdefghijklmnopqrstuvwxyz";
        }
        if (str_contains($caracteres, "A")) {
            $disponibles .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }
        if (str_contains($caracteres, "#")) {
            $disponibles .= "0123456789";
        }
        if (str_contains($caracteres, "!")) {
            $disponibles .= "~`!@#$%^&*()_+-={}[]:\"'<>?,./|\\";
        }
        for ($i = $extension; $i > 0; --$i) {
            $resultado .= $disponibles[random_int(0, strlen($disponibles) - 1)];
        }
        return $resultado;
    }
}

// if (!function_exists("generarClave")) {
//     /**
//      * Función que permite generar una clave aleatoria, y validando que cumplca con los requisitos de la
//      * clave. Implementación en el backend del frontend.
//      * @return string Retorna un string random validado.
//      */
//     function generarClave($longitud = null)
//     {
//         if (!$longitud) {
//             $longitud = Parametro::dar("LONGITUD_MIN_CLAVE")?->valor;
//         }
//         $string = generarStringRandom($longitud, "aA#");
//         if (!preg_match("/(?=.*[a-z])[a-zA-Z\d]{1,}/", $string)) {
//             $indice = random_int(0, strlen($string) - 1);
//             $disponibles = "abcdefghijklmnopqrstuvwxyz";
//             $reemplazar = $disponibles[random_int(0, strlen($disponibles) - 1)];
//             $string = substr($string, 0, $indice) . $reemplazar . substr($string, $indice + 1);
//         }
//         if (!preg_match("/(?=.*[A-Z])[a-zA-Z\d]{1,}/", $string)) {
//             $indice = random_int(0, strlen($string) - 1);
//             $disponibles = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
//             $reemplazar = $disponibles[random_int(0, strlen($disponibles) - 1)];
//             $string = substr($string, 0, $indice) . $reemplazar . substr($string, $indice + 1);
//         }
//         if (!preg_match("/(?=.*[0-9])[a-zA-Z\d]{1,}/", $string)) {
//             $indice = random_int(0, strlen($string) - 1);
//             $disponibles = "0123456789";
//             $reemplazar = $disponibles[random_int(0, strlen($disponibles) - 1)];
//             $string = substr($string, 0, $indice) . $reemplazar . substr($string, $indice + 1);
//         }
//         return $string;
//     }
// }

// if (!function_exists("combinarPDFs")) {
//     /**
//      * Función que permite combinar múltiples PDFs en uno solo.
//      * @param string $nombreFinal Nombre final del PDF, con su extensión apropiada.
//      * @param array $pdfs Array de DOMPDFs.
//      * @return Webklex\PDFMerger\PDFMerger Retorna un merger de PDF.
//      */
//     function combinarPDFs($nombreFinal, $pdfs)
//     {
//         $merger = PDFMerger::init();
//         $merger->setFileName($nombreFinal);
//         foreach ($pdfs as $pdf) {
//             if (is_string($pdf)) {
//                 $merger->addString($pdf);
//             } else {
//                 $merger->addPDF($pdf);
//             }
//         }
//         $merger->merge();
//         return $merger;
//     }
// }

if (!function_exists("generarQR")) {
    /**
     * Función que permite generar una clave aleatoria, y validando que cumplca con los requisitos de la
     * clave. Implementación en el backend del frontend.
     * @return string Retorna un string random validado.
     */
    function generarQR($ruta = null)
    {
        $writer = new PngWriter();

        $logo = Logo::create(asset("img/fondo.png"))
            ->setResizeToWidth(50);

        $qr = QrCode::create($ruta)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
            ->setSize(120)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::None)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        return $writer->write($qr, $logo)->getDataUri();
    }
}

if (!function_exists("upper")) {
    function upper($texto)
    {
        return Str::upper($texto);
    }
}

if (!function_exists("dateToText")) {
    function dateToText($fecha, $formato)
    {
        return $fecha
            ->formatLocalized($formato);
    }
}

if (!function_exists("numeroALetras")) {
    /**
     * Función que permite transformar un número a letras.
     * @param int $numero Número a transformar a letras.
     * @param string $locale Idioma al que se transformará. Por defecto, el que está en config("app.locale").
     * @param bool
     * @return string Retorna un string con el número convertido a letras.
     */
    function numeroALetras($numero, $locale = null, $mayus = false)
    {
        $formatter = new NumeroALetras();
        return $formatter->toWords((int) $numero);
    }
}

if (!function_exists("fechaEnLetras")) {
    /**
     * Función que permite transformar un número a letras.
     * @param \Carbon\Carbon $fecha Fecha a transformar a letras.
     * @param string $locale Idioma al que se transformará. Por defecto, el que está en config("app.locale").
     * @return string Retorna un string con el número convertido a letras.
     */
    function fechaEnLetras($fecha, $locale = null, $mayus = false)
    {
        if (!$fecha) {
            return "";
        }
        $dia = ucwords(numeroALetras($fecha?->day, $locale));
        $anio = numeroALetras($fecha?->year, $locale);
        $fechaFinal = $fecha->formatLocalized("$dia (%d) de %B de $anio (%Y)");
        if ($mayus) {
            $fechaFinal = upper($fechaFinal);
        }
        return $fechaFinal;
    }
}

// if (!function_exists("generarQR")) {
//     /**
//      * Función que permite generar una clave aleatoria, y validando que cumplca con los requisitos de la
//      * clave. Implementación en el backend del frontend.
//      * @return string Retorna un string random validado.
//      */
//     function generarQR($ruta = null)
//     {
//         $writer = new PngWriter();

//         $logo = Logo::create(public_path("/images/logoUSC.png"))
//             ->setResizeToWidth(50);

//         $qr = QrCode::create($ruta)
//             ->setEncoding(new Encoding('UTF-8'))
//             ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh())
//             ->setSize(120)
//             ->setRoundBlockSizeMode(new RoundBlockSizeModeNone())
//             ->setForegroundColor(new Color(0, 0, 0))
//             ->setBackgroundColor(new Color(255, 255, 255));

//         return $writer->write($qr, $logo)->getDataUri();
//     }
// }

if (!function_exists("generarToken")) {
    /**
     * Función que permite generar un token basado en la información pasada.
     * @return string Retorna un token usando encrypt.
     */
    function generarToken($info, $fecha = null)
    {
        if (!$fecha) {
            $fecha = now();
        }
        $info[] = $fecha->format("Y-m-d");
        $salt = implode("_", $info);
        return encrypt($salt);
    }
}

if (!function_exists('darGuard')) {
    /**
     * Función que retorna el guard que esta activo
     * @param ruta ruta actual del usuario
     * @return string
     */
    function darGuard()
    {
        if (Auth::guard('web')->check()) {
            return 'web';
        } elseif (Auth::guard('institucion')->check()) {
            return 'institucion';
        }
    }
}

if (!function_exists("numeroALetras")) {
    /**
     * Función que permite transformar un número a letras.
     * @param int $numero Número a transformar a letras.
     * @param string $locale Idioma al que se transformará. Por defecto, el que está en config("app.locale").
     * @param bool
     * @return string Retorna un string con el número convertido a letras.
     */
    function numeroALetras($numero, $locale = null, $mayus = false)
    {
        if (!$locale) {
            $locale = config("app.locale");
        }
        $convertido = (new NumberFormatter($locale, NumberFormatter::SPELLOUT))->format($numero);
        if ($mayus) {
            $convertido = upper($convertido);
        }
        return $convertido;
    }
}
if (!function_exists("fechaEnLetras")) {
    /**
     * Función que permite transformar un número a letras.
     * @param \Carbon\Carbon $fecha Fecha a transformar a letras.
     * @param string $locale Idioma al que se transformará. Por defecto, el que está en config("app.locale").
     * @return string Retorna un string con el número convertido a letras.
     */
    function fechaEnLetras($fecha, $locale = null, $mayus = false)
    {
        if (!$fecha) {
            return "";
        }
        $dia = ucwords(numeroALetras($fecha?->day, $locale));
        $anio = numeroALetras($fecha?->year, $locale);
        $fechaFinal = $fecha->formatLocalized("$dia (%d) de %B de $anio (%Y)");
        if ($mayus) {
            $fechaFinal = upper($fechaFinal);
        }
        return $fechaFinal;
    }
}

if (!function_exists('darRegistroModelo')) {
    /**
     * Función que retorna el registro del modelo deseado, y hace las validaciones correspondientes. Especialmente
     * usado para registros que se obtengan mediante configuraciones de modelo (ej: cambiar estado, soportes, etc.)
     * @param string $modelo Modelo que el usuario está solicitando.
     * @param int $id ID del modelo que será cargado.
     * @param string|array|null $modeloDeseado Nombre del modelo con el que se desea validar. Puede ser un string o un array.
     * @return \App\Classes\Models\Model Retorna un modelo.
     */
    function darRegistroModelo($modelo, $id, $modeloDeseado = null)
    {
        if (!$modelo || !$id) {
            return [
                'estado'  => 'error',
                'mensaje' => 'Hacen falta campos para obtener el registro.'
            ];
            // throw new ErrorException("Hacen falta campos para obtener el registro.");
        }

        // validar que sean iguales, para que la gente no cambie el modelo o algo.
        if ($modeloDeseado) {
            $registroValido = (is_array($modeloDeseado) && in_array($modelo, $modeloDeseado)) || is_string($modeloDeseado) && $modelo == $modeloDeseado;
            if (!$registroValido) {
                return [
                    'estado'  => 'error',
                    'mensaje' => 'Registro no válido.'
                ];
                // throw new ErrorException("Registro no válido.");
            }
        }

        // obtenemos el modelo y el registro.
        $model = darModelo($modelo) ?? null;
        if (!$model) {
            return [
                'estado'  => 'error',
                'mensaje' => 'Registro no encontrado.'
            ];
            // throw new ErrorException("Registro no válido.");
        }

        $registro = $model::find($id);
        if (!$registro) {
            return [
                'estado'  => 'error',
                'mensaje' => 'Registro no encontrado.'
            ];
            // throw new ErrorException("Registro no encontrado.");
        }
        return $registro;
    }
}

if (!function_exists('darColorPorcentaje')) {
    /**
     * Función que permite dar un color de CSS dependiendo del porcentaje que se pase.
     * @param int|float $porcentaje Porcentaje el cual se desea obtener un color
     * @return string Retorna un string de una clase de color CSS.
     */
    function darColorPorcentaje($porcentaje)
    {
        $rangos = [];
        $rangos[] = ['limite_superior' => 25, 'color' => "danger"];
        $rangos[] = ['limite_superior' => 50, 'color' => "warning"];
        $rangos[] = ['limite_superior' => 75, 'color' => "info"];
        $rangos[] = ['limite_superior' => 100, 'color' => "success"];
        foreach ($rangos as $rango) {
            if ($porcentaje >= 0 && $porcentaje <= $rango["limite_superior"]) {
                return $rango["color"];
            }
        }
        return "dark";
    }
}

if (!function_exists("snakeCaseACamelCase")) {
    /**
     * Función que permite convertir un string de snake case a camel case.
     * @param string $string String snake case a convertir a camel case.
     * @return string Retorna un string.
     */
    function snakeCaseACamelCase($string)
    {
        $conversion = str_replace("_", "", ucwords($string, "_"));
        return lcfirst($conversion);
    }
}

if (!function_exists("formatoTelefono")) {
    /**
     * Función que permite convertir un string de snake case a camel case.
     * @param string $string String snake case a convertir a camel case.
     * @return string Retorna un string.
     */
    function formatoTelefono($numero)
    {
        $numero = preg_replace('/\s+/', '', $numero);
        $numero = preg_replace("/[^ 0-9] /", "", (int)$numero);
        if (strlen($numero) == 7) {
            $numero = preg_replace('/([0-9]{3})([0-9]{4})/', '$1-$2', $numero);
        } else if (strlen($numero) == 10) {
            $numero = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '($1) $2-$3', $numero);
        } else if (strlen($numero) == 11) {
            $numero = preg_replace('/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/', '+$1 ($2) $3-$4', $numero);
        } else if (strlen($numero) == 12) {
            $numero = preg_replace('/([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{4})/', '+$1 ($2) $3-$4', $numero);
        }

        return $numero;
    }
}

if (!function_exists("calcularDiferenciasFechas")) {
    /**
     * Función que permite convertir un string de snake case a camel case.
     * @param string $string String snake case a convertir a camel case.
     * @return string Retorna un string.
     */
    function calcularDiferenciasFechas($fecha_inicio, $fecha_fin)
    {
        $fechaInicio = Carbon::parse($fecha_inicio);
        $fechaFin = Carbon::parse($fecha_fin);

        // Diferencia en minutos
        $minutos = $fechaFin->diffInMinutes($fechaInicio);

        // Diferencia en horas
        $horas = $fechaFin->diffInHours($fechaInicio);

        // Diferencia en días
        $dias = $fechaFin->diffInDays($fechaInicio);

        // También puedes obtener años, meses, etc.
        $anios = $fechaFin->diffInYears($fechaInicio);
        $meses = $fechaFin->diffInMonths($fechaInicio);

        // Si solo quieres saber si ha pasado un cierto período de tiempo
        if ($fechaInicio->diffInDays($fechaFin) > 7) {
            // Ha pasado más de una semana
        }

        $texto = '';

        if ($horas) {
            $texto = $horas.' horas ';
        }

        if ($minutos) {
            if ($horas) {
                $texto .= ($minutos % 60).' mins';
            } else {
                $texto .= $minutos.' mins';
            }
        }

        return $texto;
        // Si necesitas incluir la diferencia en un arreglo de salida
        // $diferencia = $fechaFin->diff($fechaInicio)->toArray();
    }
}

if (!function_exists('formatearNitPorPais')) {
    /**
     * Función que retorna texto si la ruta esta activa o no
     * @param ruta ruta actual del usuario
     * @return string
     */
    function formatearNitPorPais($numero, $pais = 'COL') {
        $nit = preg_replace('/[^0-9]/', '', $numero);

        switch (strtoupper($pais)) {
            case 'GT': // Guatemala
                $nit = str_pad($nit, 9, '0', STR_PAD_LEFT);
                return substr($nit, 0, 4) . '-' . substr($nit, 4, 5) . '-' . substr($nit, 9, 1);

            case 'SV': // El Salvador
                $nit = str_pad($nit, 14, '0', STR_PAD_LEFT);
                return substr($nit, 0, 4) . '-' . substr($nit, 4, 6) . '-' . substr($nit, 10, 3) . '-' . substr($nit, 13, 1);

            case 'HN': // Honduras
                return substr($nit, 0, 4) . '-' . substr($nit, 4, 4) . '-' . substr($nit, 8);

            case 'COL': // Colombia
                // Extraer partes del NIT
                $digitos = substr($nit, 0, 9); // Primeros 9 dígitos
                $verificacion = substr($nit, 9, 1); // Dígito de verificación (opcional)

                // Formatear la parte principal con puntos
                $partePrincipal = '';
                if (strlen($digitos) > 0) {
                    $partePrincipal = substr($digitos, 0, 3) . '.' . substr($digitos, 3, 3) . '.' .
                        substr($digitos, 6, 3);
                }

                // Agregar dígito de verificación si existe
                if (!empty($verificacion)) {
                    return $partePrincipal . '-' . $verificacion;
                }
                return $partePrincipal;

            default: // Formato genérico
                $nit = str_pad($nit, 14, '0', STR_PAD_LEFT);
                return substr($nit, 0, 4) . '-' . substr($nit, 4, 6) . '-' . substr($nit, 10, 3) . '-' . substr($nit, 13, 1);
        }
    }
}

if (!function_exists('formatoMiles')) {
    /**
     * Función que retorna texto si la ruta esta activa o no
     * @param ruta ruta actual del usuario
     * @return string
     */
    function formatoMiles($numero, $decimal = 0) {
        return number_format(((int) $numero ?? 0), (int) $decimal, '', '.');
    }
}

if (!function_exists('acortarCadena')) {
    function acortarCadena($texto, $longitudMaxima = 500) {
        if (mb_strlen($texto) > $longitudMaxima) {
            return mb_strimwidth($texto, 0, $longitudMaxima, '...');
        }
        return $texto;
    }
}

if (!function_exists('modeloPorNombreTabla')) {
    function modeloPorNombreTabla($tableName) {
        $modelsPath = app_path('Models');
        $modelFiles = File::files($modelsPath);

        foreach ($modelFiles as $file) {
            $modelClass = 'App\\Models\\'.pathinfo($file, PATHINFO_FILENAME);

            if (class_exists($modelClass)) {
                $model = new $modelClass;

                if ($model->getTable() === $tableName) {
                    return $modelClass;
                }
            }
        }

        return null;
    }
}

if (!function_exists('generarInvoice')) {
    function generarInvoice(): string
    {
        return DB::transaction(function () {
            $maxIntentos = 100; // por si hay muchos en uso
            $prefijo = 'INV';
            $numero = 1;

            for ($i = 0; $i < $maxIntentos; $i++) {
                $invoice = $prefijo . str_pad($numero, 4, '0', STR_PAD_LEFT);

                $existeFactura = Factura::where('invoice', $invoice)
                    ->exists();
                $enUsoTemporal = InvoiceTemporal::where('invoice', $invoice)
                    ->where('estado', InvoiceTemporal::ACTIVO)
                    ->exists();

                if (!$existeFactura && !$enUsoTemporal) {
                    // Registrar en temporal
                    InvoiceTemporal::create([
                        'invoice' => $invoice,
                        'estado' => InvoiceTemporal::ACTIVO
                    ]);

                    return $invoice;
                }

                $numero++;
            }

            throw new \Exception("No se pudo generar un invoice único después de {$maxIntentos} intentos.");
        });
    }
}
