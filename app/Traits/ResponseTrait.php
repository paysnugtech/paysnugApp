<?php 

namespace App\Traits;


trait ResponseTrait{

    
    protected function errorResponse($code = 500, $message = 'Successful', $errors = [], $http_code = 500)
    {
        return response()->json([
                'success' => "false",
                'code' => $code,
                'message' => $message,
                'errors'    => $errors
            ], $http_code
        );
    }

    
    protected function successResponse($code = 200, $message = 'Successful', $data = [], $http_code = 200)
    {
        return response()->json([
                'success' => true,
                'code' => $code,
                'message' => $message,
                'data'    => $data,
            ], $http_code
        );
    }


    protected function tokenResponse($token, $data= [], $message= 'Successful', $code= 200, $http_code= 200)
    {
        return response()->json([
                'success' => true,
                'code' => $code,
                'message' => $message,
                'data'    => [
                    'user' => $data,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ],
            ], $http_code
        );
    }
}