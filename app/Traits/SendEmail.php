<?php 

namespace App\Traits;

use App\Traits\ResponseTrait;
use App\Traits\StatusCode;

trait SendEmail{

    use ResponseTrait, 
        StatusCode;


    
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
        $secretKey = env('PSG_SECRET_KEY');

        $url = env('PSG_EMAIL_URL');

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