<?php

namespace App\Exceptions;


use App\Traits\ErrorResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use Exception;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ErrorResponse;
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
        /* if(Exception $exception instanceOf TokenInvalidException){

        } */

         $this->renderable(function (Exception $e, Request $request) {
            
            $code = $e->getCode() == 0 ? 500 : $e->getCode();

            // print_r($e->getMessage());

            // print_r($code);

            if ($request->is('api/*')) {

                if($e instanceOf NotFoundHttpException)
                {
                    return $this->errorResponse(
                        [
                            'id' => $e->getMessage()
                        ], 
                        'Record not found.',
                        $code
                    );
                }
                else if($e instanceOf ModelNotFoundException)
                {
                    return $this->errorResponse(
                        [
                            'id' => $e->getMessage()
                        ], 
                        'Record not found.',
                        $code
                    );
                    
                }
                else if($e instanceOf MethodNotAllowedHttpException)
                {
                    return $this->errorResponse(
                        [
                            'token' => "Invalid Token"
                        ], 
                        'Token Error',
                        $code
                    );
                    
                }
                else
                {

                    return $this->errorResponse(
                        [], 
                        $e->getMessage(),
                        $code
                    );
                }
            }
            
        });

        /* $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found.',
                    'error' => [
                        'id' => $e->getMessage()
                    ]
                ], 404);
            }
        }); */


        /* $this->renderable(function (Throwable $e, Request $request) {
            
            if ($request->is('api/*')) {
                
                if (!$this->isHttpException($e)){
                    return response()->json([
                        // 'code' => 500,
                        'success' => false,
                        'message' => "Internal server error",
                        'error' => []
                    ], 500);
                }

                $code = $e->getCode() == 0 ? 500 : $e->getCode();
        
                return response()->json([
                    // 'code' => $code,
                    'success' => false,
                    'message' => $e->getMessage(),
                    'error' => []
                ], $code);
            }
        }); */

        

        /* $this->reportable(function (Throwable $e) {
            
        }); */
    }
}
