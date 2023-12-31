<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return response()->json([
                'status' => 'error',
                'data' => [
                    'message' => 'Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.',
                ],
            ], 500);
        });

        $this->renderable(function (NotFoundHttpException $_) {
            return response()->json([
                'status' => 'error',
                'data' => [
                    'message' => 'Recurso não encontrado.',
                ],
            ], 404);
        });
    }
}
