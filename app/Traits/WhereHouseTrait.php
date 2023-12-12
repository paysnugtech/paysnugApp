<?php 

namespace App\Traits;

use App\Interfaces\Repositories\IAccountRepository;
use App\Interfaces\Repositories\IVerificationRepository;
use App\Models\Account;
use Illuminate\Support\Facades\Http;

trait WhereHouseTrait{


    
    /*  
    */
    protected function httpClientGetApi($request)
    {
        $url = $request['url'];
        $header = $request['header'];

        $response = Http::withHeaders($header)->get($url);

        $result = json_decode($response->body());

        // Determine if the status code is >= 200 and < 300...
        if($response->successful())
        {
            
            if(isset($result->status) && $result->status ==='00')
            {
                return $this->Success(
                    $response->status(),
                    $result->message,
                    data: $result,
                );
            }

        }
        else
        {

            if(isset($result->errors))
            {
                return $this->Error(
                    $response->status(),
                    $result->message,
                    errors: $result->errors,
                );
            }

            
            return $this->Error(
                $this->statusCode('request_timeout'),
                "Request Timeout"
            );
        }
        

        /* $result = json_decode($response->body());

        print_r($result);
        
        if(isset($result->errors))
        {
            return (object)[
                'code' => $response->status(),
                'success' => false,
                'message' => $result->message,
                'errors' => $result->errors,
            ];
        }
        if(isset($result->status) && $result->status ==='00')
        {
            return (object)[
                'code' => $response->status(),
                'success' => true,
                'message' => $result->message,
                'data' => $result,
            ];
        }

        
        return (object)[
            'code' => $code->request_timeout,
            'success' => false,
            'message' => "Request Timeout"
        ]; */
 
    }

    
    /*  
    */
    protected function httpClientPostApi($request)
    {

        $url = $request['url'];
        $data = $request['data'];
        $header = $request['header'];

        $response = Http::withHeaders($header)->post($url, $data);

        $result = json_decode($response->body());

        print_r($result);
        
        if(isset($result->errors))
        {

            return $this->Error(
                $response->status(),
                $result->message,
                errors: $result->errors,
            );
        }
        if(isset($result->status) && $result->status ==='00')
        {

            return $this->Success(
                $response->status(),
                $result->message,
                data: $result,
            );
        }

        return $this->Error(
            $$this->statusCode('request_timeout'),
            "Request Timeout"
        );
 
    }

    
    /*  
    */
    protected function uploadBill1($data)
    {
        $secretKey = env('PSG_SECRET_KEY');

        $url = env('PSG_BILL_UPLOAD_URL');

        $code = $this->statusCode();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'X-Secret-Key: '. $secretKey,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        $err = curl_error( $curl );

        curl_close($curl);
        echo $response;

        
        if($err)
        {
            $error_msg = "Curl return: $err";

			return (object)[
                'code' => $this->statusCode('service_unavailable'),
                'success' => false,
                'message' => $error_msg,
            ];
        }
        else
        {
            $responseData = json_decode($response);

            if($responseData->status != '00')
            {
                return (object)[
                    'code' => $this->statusCode('conflict'),
                    'success' => false,
                    'message' => "Unable to create account number",
                ];
            }
            elseif($responseData->status == '00')
            {
                return (object)[
                    'code' => $this->statusCode('success'),
                    'success' => true,
                    'message' => "Successfully",
                    'data' => [],
                ];
            }
            

            return (object)[
                'code' => $this->statusCode('request_timeout'),
                'success' => false,
                'message' => "Request Timeout"
            ];
            
        }

    }

    
    /*  
    */
    protected function uploadProfilePicture($data)
    {
        $code = $this->statusCode();

        $secretKey = env('PSG_SECRET_KEY');

        $url = env('PSG_UPLOAD_PROFILE_PICTURE_URL');

        $response = Http::withHeaders(
            [
                'Accept' => 'application/json',
                'X-Secret-Key' => $secretKey,
                'Content-Type: application/json'
            ]
        )->post($url, $data);


        $result = json_decode($response->body());

        print_r($result);
        
        if(isset($result->errors))
        {
            return (object)[
                'code' => $response->status(),
                'success' => false,
                'message' => $result->message,
                'errors' => $result->errors,
            ];
        }
        if(isset($result->status) && $result->status ==='00')
        {
            return (object)[
                'code' => $response->status(),
                'success' => true,
                'message' => $result->message,
                'data' => (object)[
                    'front_url' => $result->url,
                ],
            ];
        }

        
        return (object)[
            'code' => $this->statusCode('request_timeout'),
            'success' => false,
            'message' => "Request Timeout"
        ];
        
    }

   
    
    /*  
    */
    protected function uploadUtilityBill($data)
    {
        $code = $this->statusCode();

        $secretKey = env('PSG_SECRET_KEY');

        $url = env('PSG_UTILITY_BILL_UPLOAD_URL');

        $response = Http::withHeaders(
            [
                'Accept' => 'application/json',
                'X-Secret-Key' => $secretKey,
                'Content-Type: application/json'
            ]
        )->post($url, $data);


        $result = json_decode($response->body());


        if(isset($result->errors))
        {
            return (object)[
                'code' => $response->status(),
                'success' => false,
                'message' => $result->message,
                'errors' => $result->errors,
            ];
        }
        if(isset($result->status) && $result->status ==='00')
        {
            return (object)[
                'code' => $response->status(),
                'success' => true,
                'message' => $result->message,
                'data' => (object)[
                    'url' => $result->doc_url,
                ],
            ];
        }

        
        return (object)[
            'code' => $this->statusCode('request_timeout'),
            'success' => false,
            'message' => "Request Timeout"
        ];
        
    }



    

    
    /*  
    */
    protected function uploadProfilePicture1($data)
    {
        
        $secretKey = env('PSG_SECRET_KEY');

        $url = env('PSG_UTILITY_BILL_UPLOAD_URL');

        $code = $this->statusCode();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'X-Secret-Key: '. $secretKey,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        $err = curl_error( $curl );

        curl_close($curl);
        // echo $response;

        
        if($err)
        {
            $error_msg = "Curl return: $err";

			return (object)[
                'code' => $this->statusCode('service_unavailable'),
                'success' => false,
                'message' => $error_msg,
            ];
        }
        else
        {
            $responseData = json_decode($response);

            if($responseData->status != '00')
            {
                return (object)[
                    'code' => $this->statusCode('conflict'),
                    'success' => false,
                    'message' => $responseData->message, 
                    // 'message' => "Unable to create account number",
                ];
            }
            

            return (object)[
                'code' => $this->statusCode('success'),
                'success' => true,
                'message' => "Account successfully created",
                'data' => $responseData->data,
            ];
        }

    }



    

    
    /*  
    */
    protected function curlGetApi($request)
    {

        $code = $this->statusCode();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $request['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $request['header'],
        ));

        $response = curl_exec($curl);

        $err = curl_error( $curl );

        $statusCode = curl_getinfo($curl,CURLINFO_HTTP_CODE);

        curl_close($curl);

        // print_r($response);

        
        if($err)
        {
            $error_msg = "Curl return: $err";

			return $this->Error(
                $this->statusCode('service_unavailable'),
                $error_msg,
            );

        }
        else
        {
            $responseObj = json_decode($response);

            // print_r($responseObj);

            if(isset($responseObj->errors))
            {

                return $this->Error(
                    $statusCode,
                    $responseObj->message,
                    errors: $responseObj->errors,
                );
    
            }
            if(isset($responseObj->status) && $responseObj->status ==='00')
            {
                return $this->Success(
                    code: $statusCode,
                    message: $responseObj->message,
                    data: $responseObj,
                );
            }


            return $this->Error(
                $this->statusCode('request_timeout'),
                "Request Timeout",
            );

        }

    }



    

    
    /*  
    */
    protected function curlPostApi($request)
    {
        
        $code = $this->statusCode();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $request['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $request['data'],
            CURLOPT_HTTPHEADER => $request['header']
        ));

        $response = curl_exec($curl);

        $err = curl_error( $curl );

        $statusCode = curl_getinfo($curl,CURLINFO_HTTP_CODE);

        curl_close($curl);

        // print_r($response);

        
        if($err)
        {
            $error_msg = "Curl return: $err";

            return $this->Error(
                $statusCode,
                $error_msg,
            );
        }
        else
        {

            $responseObj = json_decode($response);

            if(isset($responseObj->errors))
            {

                return $this->Error(
                    $statusCode,
                    $responseObj->message,
                    errors: $responseObj->errors,
                );
    
            }
            else if(isset($responseObj->status) && $responseObj->status !=='00')
            {
                return $this->Error(
                    code: $responseObj->status,
                    message: $responseObj->message,
                    errors: $responseObj->errors ?? [],
                );
            }
            

            return $this->Success(
                code: $statusCode,
                message: $responseObj->message,
                data: $responseObj ?? [],
            );

        }

    }



    private function Error(int $code = 500, string $message = '', $errors = [])
    {
        return (object)[
            'success' => false,
            'code' => $code,
            'message' => $message,
            'errors'    => $errors,
        ];
    }


    private function Success( int $code = 200, string $message = 'Successful', $data = [])
    {
        return (object)[
            'success' => true,
            'code' => $code,
            'message' => $message,
            'data'    => $data,
        ];
    }
}