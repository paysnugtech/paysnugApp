<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\RequestException;

class NotFoundHttpException extends Exception
{

        /**
     * Report the exception.
     */
    public function report(): void
    {
        // ...
    }
    

    public function render($request, Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['error' => 'Entry for '.str_replace('App', '', $exception->getModel()).' not found'], 404);
        }
        else if ($exception instanceof RequestException) {
            return response()->json(['error' => 'External API call failed.'], 500);
        }
        else if ($exception instanceof NotFoundHttpException) {
            return response()->json(['error' => 'External API call failed.'], 500);
        }

        // return parent::render($request, $exception);

        return false;
    }
}
