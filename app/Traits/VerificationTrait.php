<?php 

namespace App\Traits;


use App\Interfaces\Repositories\IVerificationRepository;
use App\Traits\ResponseTrait;
use App\Traits\StatusCode;
use Illuminate\Support\Facades\Http;

trait VerificationTrait{

    use ResponseTrait, 
        StatusCode;

    protected $verificationRepository;

    public function __construct(IVerificationRepository $verificationRepository)
    {
        $this->verificationRepository = $verificationRepository;
    }

    
    /*  
    */
    protected function storeVerification($user)
    {

        // Save Notification
        $verification = $this->verificationRepository->create([
            'user_id' => $user->id
        ]);


        $verification->bill()->create([
            'user_id' => $user->id
        ]);

        $verification->bvn()->create([
            'user_id' => $user->id
        ]);

        $verification->card()->create([
            'user_id' => $user->id
        ]);

        return $verification; 
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
                    'message' => "Unable to create account number",
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
    protected function uploadIdCard($data)
    {
        $code = $this->statusCode();

        $secretKey = env('PSG_SECRET_KEY');

        $url = env('PSG_UPLOAD_ID_CARD_URL');

        $response = Http::withHeaders(
            [
                'Accept' => 'application/json',
                'X-Secret-Key' => $secretKey,
                'Content-Type: application/json'
            ]
        )->post($url, $data);


        $result = json_decode($response->body());

        // print_r($result);
        
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
                    'front_url' => $result->url1,
                    'back_url' => $result->url2,
                ],
            ];
        }

        
        return (object)[
            'code' => $code->request_timeout,
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

        $url = env('PSG_UPLOAD_UTILITY_BILL_URL');

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
            'code' => $code->request_timeout,
            'success' => false,
            'message' => "Request Timeout"
        ];
        
    }
}