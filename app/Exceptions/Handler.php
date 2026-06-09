<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        ErrorException::class,
        InformationException::class,
        WarningException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        // if (app()->bound('sentry') && $this->shouldReport($exception)) {
        //     app('sentry')->captureException($exception);
        // }

        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()->route('login');
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->view('sistema.404', [], 404);
        }

        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return response()->view('sistema.404', [], 404);
        }

        return parent::render($request, $exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });


        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {

            if ($request->wantsJson()) {
                return response()->json([
                    'responseMessage' => 'No tienen los permisos necesarios para visualizar esta ruta.',
                    'responseStatus'  => 403,
                ]);
            }
            $swal['estado'] = 'error';
            $swal['titulo'] = 'Acción restringida';
            $swal['mensaje'] = 'No tienen los permisos necesarios para visualizar esta ruta';

            return redirect()->route("home")
                ->with("swal", $swal);
        });
    }
}
