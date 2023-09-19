<?php 

namespace App\Traits;

trait SuccessResponse{
    protected function successResponse($data, $message = '', $code = 200)
    {
        return response()->json([
                'success' => true,
                'message' => $message,
                'data'    => $data,
            ], $code
        );
    }
}