<?php 

namespace App\Traits;

trait TokenResponse{
    
    protected function tokenResponse($data, $token, $message = '', $code = 200)
    {
        return response()->json([
                'success' => true,
                'message' => $message,
                'data'    => [
                    'user' => $data,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ],
            ], $code
        );
    }
}