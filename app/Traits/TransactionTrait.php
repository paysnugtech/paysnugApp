<?php 

namespace App\Traits;

use App\Interfaces\Repositories\IAccountRepository;
use App\Interfaces\Repositories\IVerificationRepository;
use App\Enums\ServiceTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Transaction;
use App\Traits\StatusCode;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait TransactionTrait{

    

    
    /*  
    */
    protected function curlGetTransactionApi($request)
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
                $code->service_unavailable,
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
                $code->request_timeout,
                "Request Timeout",
            );

        }

    }



    

    
    /*  
    */
    protected function curlPostTransactionApi($request)
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
    
    
    public function calculateTransactionProfit($service){

        if($service->pivot->is_free)
        {
            
            $profit = $this->fee +  $this->commission - $this->discount - $this->total_fee;
        }
        else
        {
            $profit = $this->fee +  $this->commission  - $this->discount;
        }

        
        return number_format($profit, 2, '.', '');

    }


    protected function checkDuplicateTransaction($service, $user_id, $service_type, $amount){

        $duplicate = $this->transactionRepository->fetchByUserIdServiceTypeAmount($user_id, $service_type, $amount)->first();

        if(!$duplicate)
        {
            return false;
        }
        


        if(now()->subMinutes($service->duplicate_time) < $duplicate->created_at)
        {

            $this->errorResponse = $this->errorResponse(
                message:'Duplicate Transaction!'
            );

            return true;
    
        }

        return false;
    }


    protected function createTransactionFee($wallet, $narration, $reference_no){

        $balance_before = $wallet->balance;
        $wallet->balance -= $this->total_fee;

        if(!$this->checkWallet($wallet))
        {

            return false;
        }

        $transactionFee = new Transaction;
        $transactionFee->id = Str::uuid();
        $transactionFee->number = $this->transaction_no ."F";
        $transactionFee->amount = $this->total_fee;
        $transactionFee->balance_before = $balance_before;
        $transactionFee->balance_after = $wallet->balance;
        $transactionFee->commission = 0;
        $transactionFee->discount = 0;
        $transactionFee->profit = 0;
        $transactionFee->reference_no = $reference_no;
        $transactionFee->service_type = ServiceTypeEnum::Fee->value;
        $transactionFee->type = TransactionTypeEnum::Debit->value;
        $transactionFee->narration = $narration;
        $transactionFee->status = TransactionStatusEnum::Success->value;
        $transactionFee->remark = "Completed";
        $transactionFee->user_id = $wallet->user->id;

        return $transactionFee;

    }


    protected function createTransaction($wallet){

        $balance_before = $wallet->balance;
        $wallet->balance -= $this->amount;

        if(!$this->checkWallet($wallet))
        {

            return false;
        }
        

        $transaction = new Transaction;
        $transaction->id = Str::uuid();
        $transaction->number = $this->transaction_no;
        $transaction->amount = $this->amount;
        $transaction->balance_before = $balance_before;
        $transaction->balance_after = $wallet->balance;
        $transaction->commission = $this->commission;
        $transaction->discount = $this->discount;
        $transaction->profit = $this->profit;
        $transaction->reference_no = $this->reference_no;
        $transaction->type = $this->type;
        $transaction->service_type = $this->service_type;
        $transaction->narration = $this->narration;
        $transaction->status = $this->status;
        $transaction->remark = $this->remark;
        $transaction->user_id = $this->user_id;

        return $transaction;

    }
    
    
    protected function storeTransactionFee($service, $feeTransaction){

        if($service->pivot->is_free)
        {
            $service->pivot->free_count -= 1;

            if($service->pivot->free_count < 1)
            {
                $service->pivot->is_free = 0;
            }

            $service->pivot->update(
                [
                    'is_free' => $service->pivot->is_free,
                    'free_count' => $service->pivot->free_count
            ]);
        }
        else
        {

            // Fee
            if($this->total_fee > 0)
            {
                
                $this->transactionRepository->store($feeTransaction);
            }
        }

    }



    /* private function Error(int $code = 500, string $message = '', $errors = [])
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
    } */
}