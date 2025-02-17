<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        // Capturar errores de validación y devolver JSON siempre
        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => $exception->errors()
            ], 422);
        }

        return parent::render($request, $exception);
    }
}
