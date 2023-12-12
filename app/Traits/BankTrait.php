<?php 

namespace App\Traits;

use App\Interfaces\Repositories\IAccountRepository;
use App\Models\Account;
use App\Traits\ResponseTrait;
use App\Traits\StatusCode;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait BankTrait{

    use ResponseTrait, 
        StatusCode;

    
    /*  
    */
    protected function bankTransfer($data)
    {
        
        $secretKey = env('PSG_SECRET_KEY');

        $url = env('PSG_BANK_TRANSFER_URL');

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
                    'message' => $responseData->message, 
                    // 'message' => "Unable to create account number",
                ];
            }
            elseif($responseData->status == '00')
            {
                return (object)[
                    'code' => $code->success,
                    'success' => true,
                    'message' => "Successfully",
                    'data' => [],
                ];
            }
            

            return (object)[
                'code' => $code->request_timeout,
                'success' => false,
                'message' => "Request Timeout"
            ];
        }

    }

    
    /*  
    */
    protected function fetchAllBank()
    {
        
        $secretKey = env('PSG_SECRET_KEY');

        $url = env('PSG_BANK_LIST_URL');

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
            CURLOPT_CUSTOMREQUEST => 'GET',
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
                    'message' => $responseData->message, 
                    // 'message' => "Unable to create account number",
                ];
            }
            elseif($responseData->status == '00')
            {
                return (object)[
                    'code' => $code->success,
                    'success' => true,
                    'message' => "Successfully",
                    'data' => $responseData->banks,
                ];
            }
            

            return (object)[
                'code' => $code->request_timeout,
                'success' => false,
                'message' => "Request Timeout"
            ];
        }

    }


    
    
    public function fetchBank($bank_code){


        $banks = $this->fetchAllBank();

        if(!$banks->success )
        {
            return (object)[
                'success' => false,
                'message' => $banks->message,
            ];
        }

        $banks = collect($banks->data);

        $bank = $banks->where('bank_code', $bank_code)->first();

        if(!$bank)
        {
            return (object)[
                'success' => false,
                'message' => "Bank not found!",
            ];
        }

        return (object)[
            'success' => true,
            'message' => "Successful",
            'data' => $bank,
        ];
    }

    
    /*  
    */
    protected function fetchAccountDetails($data)
    {
        
        $secretKey = env('PSG_SECRET_KEY');

        $url = env('PSG_BANK_ENQUIRY_URL');

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
                    'message' => $responseData->message, 
                    // 'message' => "Unable to create account number",
                ];
            }
            elseif($responseData->status == '00')
            {
                return (object)[
                    'code' => $code->success,
                    'success' => true,
                    'message' => "Successfully",
                    'data' => $responseData->data,
                ];
            }
            

            return (object)[
                'code' => $code->request_timeout,
                'success' => false,
                'message' => "Request Timeout"
            ];
        }

    }


    
    
    public function getBankName($bank_code){

        /* $bank = $this->fetchBank($bank_code);

        if(!$bank->success )
        {
            return (object)[
                'success' => false,
                'message' => $bank->message
            ];
        } */

        /* return (object)[
            'success' => true,
            'message' => "Successful",
            'data' => $bank->data,
        ]; */

        // return $bank->data->bank_name;


        $banks = $this->fetchAllBank();

        if(!$banks->success )
        {
            return (object)[
                'success' => false,
                'message' => $banks->message,
            ];
        }

        $banks = collect($banks->data);

        $bank = $banks->where('bank_code', $bank_code)->first();

        if(!$bank )
        {
            return (object)[
                'success' => false,
                'message' => "Bank not found",
            ];
        }

        return $bank->bank_name;

    }
}