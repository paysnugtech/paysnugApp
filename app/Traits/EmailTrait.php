<?php 

namespace App\Traits;

use App\Traits\StatusCode;
use App\Traits\WhereHouseTrait;

trait EmailTrait{

    use WhereHouseTrait;


    protected $email_endpoint;
    protected $secret_key;


    public function __construct()
    {
        $this->email_endpoint = env('EMAIL_ENDPOINT');
        $this->secret_key = env('PSG_SECRET_KEY');
    }


    
    /*  
    */
    protected function changePinTokenEmail($email, $token)
    {

        $sendEmail = $this->send([
            'email' => $email,
            'subject' => "Change Pin Token",
            'message' => "<p>

                You recently attempted to change your Paysnug account pin.
            
                <div style=\"align: center\">
                    Use this Token to complete the process. <br>
                    <b>". $token ."</b>
                </div>
            </p>"
        ]);

        return $sendEmail;
    }
    

    /*  
    */
    protected function deviceVerificationSuccessEmail($user)
    {

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "New Device Token",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                You recently attempted to sign into your Paysnug account from a new device or in a new location. 
                As a security measure, we require additional confirmation before allowing access to your Paysnug account.
            </p>"
        ]);

        return $sendEmail;
    }

    
    /*  
    */
    protected function deviceVerificationTokenEmail($user, $token)
    {

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "New Device Token",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                You recently attempted to sign into your Paysnug account from a new device or in a new location. 
                As a security measure, we require additional confirmation before allowing access to your Paysnug account.
            
                <div style=\"align: center\">
                    Use this Token to complete the authorization. <br>
                    <b>". $token ."</b>
                </div>
            </p>"
        ]);

        return $sendEmail;
    }


    
    /*  
    */
    protected function loginSuccessEmail($user)
    {
        $today = now();
        $date = $today->format("Y");
        $time = $today->format("h:s:i");

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "Successful Login",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                Please be informed that your digital banking profile was accessed on: 

            </p>"
        ]);

        return $sendEmail;
    }


    protected function sendAirtimePurchaseSuccessEmail($transaction)
    {
        $user = $transaction->user;

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "Airtime",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                Transaction just occur on your account.\n

                NGN ". $transaction->total ."\n
            
                <div style=\"align: center\">
                    Transaction Details
                    Type: Airtime \n
                    Network: ". $transaction->airtime->name ."\n
                    Phone NO.: ". $transaction->airtime->phone_no ."\n
                    Amount: ". $transaction->amount ."\n
                    Bonus: ". $transaction->discount ."\n
                    Reference: ". $transaction->number ."\n
                    Date: ". $transaction->created_at ."\n\n


                    Regard\n
                    Paysnug Team
                </div>
            </p>"
        ]);

        return $sendEmail;
    }


    protected function sendCablePurchaseSuccessEmail($transaction)
    {
        $user = $transaction->user;

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "Cable",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                Transaction just occur on your account.\n

                NGN ". $transaction->total ."\n
            
                <div style=\"align: center\">
                    Transaction Details
                    Type: Cable \n
                    Network: ". $transaction->cable->provider_name ."\n
                    Customer Id: ". $transaction->cable->customer_id ."\n
                    Customer Name: ". $transaction->cable->customer_name ."\n
                    Amount: ". $transaction->amount ."\n
                    Bonus: ". $transaction->discount ."\n
                    Reference: ". $transaction->number ."\n
                    Date: ". $transaction->created_at ."\n\pn


                    Regard\n
                    Paysnug Team
                </div>
            </p>"
        ]);

        return $sendEmail;
    }


    protected function sendDataPurchaseSuccessEmail($transaction)
    {
        $user = $transaction->user;

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "Data",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                Transaction just occur on your account.\n

                NGN ". $transaction->amount_paid ."\n
            
                <div style=\"align: center\">
                    Transaction Details
                    Type: ". $transaction->type->name ."\n
                    Network: ". $transaction->data->provider_name ."\n
                    Phone NO.: ". $transaction->data->customer_id ."\n
                    Amount: ". $transaction->amount ."\n
                    Bonus: ". $transaction->discount ."\n
                    Fee: ". $transaction->fee ."\n
                    Reference: ". $transaction->number ."\n
                    Date: ". $transaction->created_at ."\n\n


                    Regard\n
                    Paysnug Team
                </div>
            </p>"
        ]);

        return $sendEmail;
    }


    protected function sendElectricityPurchaseSuccessEmail($transaction)
    {
        $user = $transaction->user;

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "Electricity",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                Transaction just occur on your account.\n

                NGN ". $transaction->total ."\n
            
                <div style=\"align: center\">
                    Transaction Details
                    Type: ". $transaction->service_type->name ."\n
                    Provider Name: ". $transaction->electricity->provider_name ."\n
                    Customer Id.: ". $transaction->electricity->customer_id ."\n
                    Customer Name.: ". $transaction->electricity->customer_name ."\n
                    Amount: ". $transaction->amount ."\n
                    Bonus: ". $transaction->discount ."\n
                    Reference: ". $transaction->number ."\n
                    Date: ". $transaction->created_at ."\n\n


                    Regard\n
                    Paysnug Team
                </div>
            </p>"
        ]);

        return $sendEmail;
    }
    
    

    protected function sendBankTransferEmail($transaction)
    {
        $user = $transaction->user;

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "Bank Transfer",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                Transaction just occur on your account.\n

                ". $transaction->total ."\n
            
                <div style=\"align: center\">
                    Transaction Details
                    Type: Bank Transfer \n
                    Bank Name: ". $transaction->transfer->bank_name ."\n
                    Account NO.: ". $transaction->transfer->account_no ."\n
                    Account Name: ". $transaction->transfer->account_name ."\n
                    Amount: ". $transaction->amount ."\n
                    Fee: ". $transaction->fee ."\n
                    Reference: ". $transaction->number ."\n
                    Date: ". $transaction->created_at ."\n\n

                    Regard\n
                    Paysnug Team
                </div>
            </p>"
        ]);

        return $sendEmail;
    }
    


    /*  
    */
    protected function sendChangePasswordTokenEmail($user, $token)
    {

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "Change Password Token",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                You recently attempted to change your Paysnug account password.
            
                <div style=\"align: center\">
                    Use this Token to complete the process. <br>
                    <b>". $token ."</b>
                </div>
            </p>"
        ]);

        return $sendEmail;
    }
    
    /*  
    */
    protected function sendChangePasswordSuccessEmail($user)
    {

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "Password Change successful",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                Your paysnug password changed successful.
            </p>"
        ]);

        return $sendEmail;
    }
    

    /*  
    */
    protected function sendResetPasswordTokenEmail($user, $token)
    {

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "Password Reset Token",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                You recently attempted to reset your Paysnug account password.
            
                <div style=\"align: center\">
                    Use this Token to complete the process. <br>
                    <b>". $token ."</b>
                </div>
            </p>"
        ]);

        return $sendEmail;
    }

    /*  
    */
    protected function sendResetPasswordSuccessEmail($user)
    {

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "Password Reset successful",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                Your paysnug password reset successful.
            </p>"
        ]);

        return $sendEmail;
    }

    /*  
    */
    protected function sendChangePinSuccessEmail($user)
    {

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "Pin change successful",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                Your paysnug pin change successful.
            </p>"
        ]);

        return $sendEmail;
    }
    

    /*  
    */
    protected function sendResetPinTokenEmail($user, $token)
    {

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "Reset Pin Token",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                You recently attempted to reset your Paysnug account pin.
            
                <div style=\"align: center\">
                    Use this Token to complete the process. <br>
                    <b>". $token ."</b>
                </div>
            </p>"
        ]);

        return $sendEmail;
    }

    /*  
    */
    protected function sendResetPinSuccessEmail($user)
    {

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "Pin reset successful",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                Your paysnug pin reset successful.
            </p>"
        ]);

        return $sendEmail;
    }
    

    /*  
    */
    protected function sendTokenEmail($request)
    {

        $sendEmail = $this->send($request);

        return $sendEmail;
    }
    
    /*  
    */
    protected function registrationSuccessEmail($user)
    {

        $sendEmail = $this->send([
            'email' => $user->email,
            'subject' => "Registration successful",
            'message' => "<p>
                Dear <b>". $user->profile->first_name ."</b>, <br><br>

                Thank you for registering with paysnug.
            </p>"
        ]);

        return $sendEmail;
    }

    
    /*  
    */
    protected function registrationTokenEmail($email, $token)
    {

        $sendEmail = $this->send([
            'email' => $email,
            'subject' => "Registration OTP",
            'message' => "<p>

                You recently attempted to register an Paysnug account.
            
                <div style=\"align: center\">
                    Use this Token to complete the registration. <br>
                    <b>". $token ."</b>
                </div>
            </p>"
        ]);

        return $sendEmail;
    }

    
    
    /*  
    */
    protected function send($data)
    {

        $request = [
            "data" => json_encode($data),
            "header" => [
                'Accept: application/json',
                'Content-Type: application/json',
                'X-Secret-Key: '. env('PSG_SECRET_KEY')
            ],
            "url" => env('EMAIL_ENDPOINT')
        ];


        $send = $this->curlPostApi($request);

        return $send;

    }


    
    /*  
    */
    protected function setFingerPrintTokenEmail($email, $token)
    {

        $sendEmail = $this->send([
            'email' => $email,
            'subject' => "Set Finger Print Token",
            'message' => "<p>

                You recently attempted to set your Paysnug account finger print.
            
                <div style=\"align: center\">
                    Use this Token to complete the process. <br>
                    <b>". $token ."</b>
                </div>
            </p>"
        ]);

        return $sendEmail;
    }
}