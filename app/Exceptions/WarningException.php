<?php

namespace App\Exceptions;

use Exception;

class WarningException extends Exception
{
    const WARNING = 'warning';
    protected $info;

    // Redefinir la excepción, por lo que el mensaje no es opcional
    public function __construct($message, $info = [], $code = 0, Exception $previous = null)
    {
        // algo de código
        $this->info = $info;

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
            $response['estado'] = self::WARNING;
            $response['code'] = $this->code;
            if (isset($this->info) && !empty($this->info)) {
                $response[] = $this->info;
            }
            $response['mensaje'] = $this->message;
            return response()->json($response);
        }

        return redirect()->route("home")->with(self::WARNING, $this->message);
        // return redirect()->back()->with(self::WARNING, $this->message);
    }
}
