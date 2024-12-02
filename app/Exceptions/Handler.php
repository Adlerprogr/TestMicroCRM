<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($e instanceof BusinessException) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getStatusCode());
        }

        if ($e instanceof ValidationException) {
            return response()->json([
                'error' => 'Validation Error',
                'messages' => $e->errors()
            ], 422);
        }

        return parent::render($request, $e);
    }
}
