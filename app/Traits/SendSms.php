<?php 

namespace App\Traits;

use App\Traits\StatusCode;

trait SendSms{

    use ErrorResponse, 
        StatusCode;

    
    /*  
    */
    protected function sendSms($data)
    {
        
        $secretKey = env('PSG_SECRET_KEY');

        $url = env('PSG_SMS_URL');

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
                'code' => $code->service_unavailable,
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
                    'code' => $code->conflict,
                    'success' => false,
                    'message' => "Unable to send the email",
                ];
            }
            

            return (object)[
                'code' => $code->success,
                'success' => true,
                'message' => "Email successfully sent",
            ];
        }

    }
}