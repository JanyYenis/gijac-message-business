<?php

namespace App\Exceptions;

use Exception;

class ErrorException extends Exception
{
    const ERROR = 'error';
    protected $info;
    public $mergeArray = false;

    // Redefinir la excepción, por lo que el mensaje no es opcional
    public function __construct($message, $info = [], $code = 0, Exception $previous = null, bool $arrMerge = false)
    {
        // algo de código
        $this->info = $info;
        $this->mergeArray = $arrMerge;

        // asegúrese de que todo está asignado apropiadamente
        parent::__construct($message, $code, $previous);
    }

    // representación de cadena personalizada del objeto
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        if ($request->wantsJson()) {
            $response['estado'] = self::ERROR;
            $response['code'] = $this->code;
            if (isset($this->info) && !empty($this->info)) {
                if ($this->mergeArray) {
                    $response = array_merge($response, $this->info);
                } else {
                    $response[] = $this->info;
                }
            }
            $response['mensaje'] = $this->message;
            return response()->json($response, $this->code == 0 ? 200 : $this->code);
        }
        $ruta = "home";
        if (!auth(darGuard())->check()) {
            $ruta = "login";
        }
        return redirect()->route($ruta)->withErrors([$this->message]);
    }
}
