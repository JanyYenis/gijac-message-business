<?php

namespace App\Classes\FormRequest;

use App\Exceptions\ErrorException;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as FormRequestOriginal;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

abstract class FormRequest extends FormRequestOriginal
{
    const ANTES = 'before';
    const DESPUES = 'after';

    protected $redirect = "/validaciones";
    protected $redirectRoute = 'validaciones';

    protected $fechas, $mayusculas, $minusculas, $titulos, $slugs, $agregarSlug, $campoSinEtiquetas = [];
    protected $jsons = "";

    protected $formatoFechas = "Y-m-d";

    /**
     * Atributo para establecer los inputs se seran tranformados
     * a una función de la clase Illuminate\Support\Str
     * @var array
     */
    protected array $strings = [];

    /**
     * Atributo para establecer los inputs se seran tranformados
     * a un funcion existe de esta clase o sus hijos
     * @var array
     */
    protected array $customs = [];

    /**
     * Atributo para establecer los inputs se seran tranformados
     * a un funcion existe de esta clase Illuminate\Support\Facades\Hash
     * @var array
     */
    protected array $hashes = [];

    /**
     * @return bool
     */
    public function isStoreMethod(): bool
    {
        return $this->isMethod('POST');
    }

    /**
     * @return bool
     */
    public function isUpdateMethod(): bool
    {
        return $this->isMethod('PUT') || $this->isMethod('PATCH');
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $result = true;
        if ($this->isStoreMethod() && method_exists($this, 'authorizeStore')) {
            $result = $this->authorizeStore();
        } elseif ($this->isUpdateMethod() && method_exists($this, 'authorizeUpdate')) {
            $result = $this->authorizeUpdate();
        }
        return $result;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $methods = [
            'upper' => 'mayusculas',
            'lower' => 'minusculas',
            'title' => 'titulos',
            'slug' => 'slugs'
        ];
        $this->callFunctionsPreviousVersion($methods, Str::class);

        $methods = ['agregarSlug', 'campoSinEtiquetas'];
        $this->callSimpleFunctions($methods);

        // Válida si el atributo $strings tiene elementos en la llave 'before'
        $structureString = data_get($this->strings, self::ANTES);
        if (!empty($structureString)) {
            $this->callFunctions($structureString, Str::class);
        }

        // Válida si el atributo $hashes tiene elementos en la llave 'before'
        $structureHashes = data_get($this->hashes, self::ANTES);
        if (!empty($structureHashes)) {
            $this->callFunctions($structureHashes, Hash::class);
        }

        // Válida si el atributo $customs tiene elementos en la llave 'before'
        $structureCustoms = data_get($this->customs, self::ANTES);
        if (!empty($structureCustoms)) {
            $this->callFunctions($structureCustoms, $this);
        }
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation()
    {
        $methods = ['fechas', 'jsons'];
        $this->callSimpleFunctions($methods);

        // Válida si el atributo $strings tiene elementos en la llave 'after'
        $structureString = data_get($this->strings, self::DESPUES);
        if (!empty($structureString)) {
            $this->callFunctions($structureString, Str::class);
        }

        // Válida si el atributo $hashes tiene elementos en la llave 'after'
        $structureHashes = data_get($this->hashes, self::DESPUES);
        if (!empty($structureHashes)) {
            $this->callFunctions($structureHashes, Hash::class);
        }

        // Válida si el atributo $customs tiene elementos en la llave 'before'
        $structureCustoms = data_get($this->customs, self::DESPUES);
        if (!empty($structureCustoms)) {
            $this->callFunctions($structureCustoms, $this);
        }
    }

    /**
     * Función que optimiza la función de la versión anterior para realizar las tranformaciones
     *
     * @param $methods Listado de metodos a ser llamados
     * @param $obj establece el objeto para llamar las funciónes estatico o no las normales.
     * @return void
     * @deprecated Esta función es para llamar las funciones que teniamos en la versión anterior de esta clase
     */
    protected function callFunctionsPreviousVersion($methods, $obj)
    {
        foreach ($methods as $method => $input) {
            if (!empty($this->$input)) {
                $inputs = $this->$input ?? [];
                $info = $this->only($inputs);

                $fn = function ($parameters) use ($obj, $method) {
                    if (isset($parameters) && method_exists($obj, $method)) {
                        // Permite llamar funciones de un objeto ya sean estaticas o no
                        $callable = [$obj, $method];
                        return call_user_func($callable, $parameters);
                    }
                };
                $this->merge(array_map($fn, $info));
            }
        }
    }

    /**
     * Función para validar si el parametro es un array dimensional
     *
     * @param $variable parametro de entrada para analizar
     * @return bool retorna true si es un arreglo dimensional, de caso contario devuelve false
     */
    protected function is_multi($variable)
    {
        $rv = array_filter($variable, 'is_array');
        return (count($rv) > 0);
    }

    /**
     * Función que estandariza el llamado de las funciones de un determinado objeto
     *
     * @param $structure
     * @param $obj
     * @return void
     */
    protected function callFunctions($structure, $obj)
    {
        foreach ($structure as $method => $inputs) {
            $keys = $this->is_multi($inputs) ? array_keys($inputs) : $inputs;
            $values = $this->only($keys);

            $fn = function ($item, ...$parameters) use ($obj, $method) {
                if (isset($item) && method_exists($obj, $method)) {
                    // Permite llamar funciones de un objeto ya sean estaticas o no
                    $callable = [$obj, $method];
                    list($extra) = $parameters;
                    return call_user_func($callable, $item, $extra);
                }
            };
            $results = array_map($fn, $values, $inputs);
            $results = array_filter($results);

            $info = [];
            if ($results) {
                foreach ($results as $key => $result) {
                    $info[$keys[$key]] = $result;
                }
            }

            $this->merge($info);
        }
    }

    /**
     * Función para llamar un listado de methods enviados por parametros
     *
     * @param $methods listado de los nombres de las funciones a llamar
     * @return void no retornar nada
     * @deprecated Esta función sera elimina una vez que se estandize las tranformaciones de los datos
     */
    public function callSimpleFunctions($methods)
    {
        foreach ($methods as $method) {
            if (!empty($this->$method) && method_exists($this, $method)) {
                $this->$method();
            }
        }
    }

    /**
     * convert all date inputs to an instance of carbon given a format
     *
     * @return void
     */
    public function dates($input, $parameter = [])
    {
        try {
            $format = data_get($parameter, 'format', $this->formatoFechas);
            return Carbon::createFromFormat($format, $input);
        } catch (InvalidFormatException $exception) {
            throw new ErrorException(__("Formato $format es invalido"));
        }
    }

    /**
     * convert a string field to JSON.
     *
     * @param bool $assoc defines JSON as an associative array or an object. Default false.
     */
    public function jsons($input, $parameters)
    {
        $assoc = data_get($parameters, 'assoc', false);

        if ($input) {
            return json_decode($input, $assoc);
        }
    }

    /**
     * Función que permite transformar un campo string a JSON.
     * @param bool $assoc Traer el JSON como un arreglo asociativo o un objeto. Por defecto falso.
     */
    public function campoSinEtiquetas()
    {
        $campos = $this->campoSinEtiquetas ?? "";
        $info = $this->only($campos);
        $fn = function ($texto) {
            if (isset($texto) && is_string($texto)) {
                return strip_tags($texto);
            }
        };
        $this->merge(array_map($fn, $info));
    }

    /**
     * Función que transforma los strings de fechas a un objeto
     * Carbon.
     * @param string $formato Formato de la fecha. Por defecto d/m/Y.
     */
    public function fechas($formato = "d/m/Y")
    {
        $campos = $this->fechas ?? [];
        $info = $this->only($campos);
        $upper = function ($texto) use ($formato) {
            if (isset($texto)) {
                return Carbon::createFromFormat($formato, $texto);
            }
        };
        $this->merge(array_map($upper, $info));
    }

    public function agregarSlug($input)
    {
        if ($input) {
            $datos['slug'] = Str::slug($input);
            $this->merge($datos);
        }
    }

    /**
     *
     */
    protected function failedValidation($validator)
    {
        $errors["estado"] = "error";
        $errors['mensaje'] = 'Existen errores de validación';
        if ($validator instanceof Validator) {
            $errors['validaciones'] = $validator->errors()->all();
            $errors['data'] = $validator->getData();
        } else {
            $errors['validaciones'] = $validator;
        }
        $errors["statusCode"] = 422;
        throw new HttpResponseException(response()->json($errors, 422));
    }
}
