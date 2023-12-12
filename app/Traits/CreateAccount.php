<?php 

namespace App\Traits;
use App\Interfaces\Repositories\IAccountRepository;
use App\Interfaces\Repositories\IBankRepository;

trait CreateAccount{

    
    protected $accountRepository;
    protected $bankRepository;

    public function __construct(IAccountRepository $accountRepository, IBankRepository $bankRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->bankRepository = $bankRepository;
    }
    
    protected function CreateAccount($user, $wallet, $data)
    {

        /* $secretKey = "psgsk-c31d71797653e7fd4faf138bbcffdaf9";

        $url = 'https://paysnug.link/wherehouse/api/v1/notification/email';

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
        } */

        $data['created_by'] = $user->id;
        $data['user_id'] = $user->id;
        $data['wallet_id'] = $wallet->id;
        $data['number'] = (int)$user->profile->phone_no;

        
        $bank = $this->bankRepository->getByName('Paysnug')->firstOrFail();
        $data['bank_id'] = $bank->id;

        $accountExist = $this->accountRepository->getByAccountNoAndBankId($data['number'], $data['bank_id'])->first();

        if($accountExist)
        {
            return false;
        }

        $this->accountRepository->create($data);

        $account = $this->accountRepository->getByWalletId($wallet->id);

        return $account;
    }
}