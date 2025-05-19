<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Session\TokenMismatchException;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

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
    }

    public function render($request, Throwable $exception): Response
    {
    if ($exception instanceof TokenMismatchException) {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => utf8_encode('Tu sesión ha expirado. Por favor, inicia sesión nuevamente.'),
                'success' => false
            ], 419);
        }
        
        if (!$request->is('login')) {
            return redirect()->route('usuario.login')
                             ->with('message', utf8_encode('Tu sesión ha expirado. Por favor, inicia sesión nuevamente.'));
        } else {
            // Si ya estamos en login, solo recargar sin redirección
            return redirect()->back()->withInput()->with('message', utf8_encode('Tu sesión ha expirado. Intenta nuevamente.'));
        }
    }    
        return parent::render($request, $exception);
    }    
}
