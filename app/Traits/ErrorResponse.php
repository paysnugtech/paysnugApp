<?php 

namespace App\Traits;

trait ErrorResponse{
    protected function errorResponse($data, $message = '', $code = 500)
    {
        return response()->json([
                'success' => "false",
                'message' => $message,
                'error'    => $data,
            ], $code
        );
    }
}