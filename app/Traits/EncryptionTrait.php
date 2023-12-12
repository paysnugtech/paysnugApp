<?php 

namespace App\Traits;



trait EncryptionTrait{

    
    protected $errorResponse;




    /*  
    */
    protected function dataEncrypt($data = [])
    {

        /* $bytes = openssl_random_pseudo_bytes(64); 
        $key = bin2hex($bytes); */
        
        // Secret key for encryption/decryption (32 bytes for AES-256)
        $secretKey = env('APP_ENC_KEY');

        // Convert data to JSON
        $jsonData = json_encode($data);

        // Encrypt data using AES-256
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encryptedData = openssl_encrypt($jsonData, 'aes-256-cbc', $secretKey, 0, $iv);

        // Combine IV and encrypted data
        $combinedData = base64_encode($iv . $encryptedData);

        return $combinedData;

    }


    
    /*  
    */
    protected function dataDecrypt($data)
    {
        
        $secretKey = env('APP_ENC_KEY');

        // Decode the combined data
        $decodedData = base64_decode($data);
        $ivSize = openssl_cipher_iv_length('aes-256-cbc');
        $receivedIv = substr($decodedData, 0, $ivSize);
        $receivedEncryptedData = substr($decodedData, $ivSize);

        // Decrypt data using AES-256
        $decryptedData = openssl_decrypt($receivedEncryptedData, 'aes-256-cbc', $secretKey, 0, $receivedIv);

        // Convert JSON data back to array
        $originalData = json_decode($decryptedData, true);

        return $decryptedData;
    }



    /*  
    */
    protected function encryptData($data = [])
    {

        /* $bytes = openssl_random_pseudo_bytes(64); 
        $key = bin2hex($bytes); */

        // print_r($key."<br>");
        
        $key = env('APP_ENC_KEY');
 
        
        /* $iv_len = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
        $iv = openssl_random_pseudo_bytes($iv_len); 
        $cipher_text_raw = openssl_encrypt($string, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
        $hmac = hash_hmac('sha256', $cipher_text_raw, $key, $as_binary=true); 
        
        // Encrypted string 
        $cipher_text = base64_encode($iv.$hmac.$cipher_text_raw); */


        

        // Convert data to JSON
        $jsonData = json_encode($data);
        
        $iv_len = openssl_cipher_iv_length($cipher="AES-256-CBC"); 
        $iv = openssl_random_pseudo_bytes($iv_len); 
        $cipher_text_raw = openssl_encrypt($jsonData, $cipher, $key, 0, $iv); 
        $hmac = hash_hmac('sha256', $cipher_text_raw, $key, $as_binary=true); 
        
        // Encrypted string 
        $cipher_text = base64_encode($iv.$hmac.$cipher_text_raw);

        return $cipher_text;

    }

    
    /*  
    */
    protected function decryptData($data)
    {
        
        $key = env('APP_ENC_KEY'); // Previously used in encryption 
        $c = base64_decode($data); 
        $iv_len = openssl_cipher_iv_length($cipher="AES-256-CBC"); 
        $iv = substr($c, 0, $iv_len); 
        $hmac = substr($c, $iv_len, $sha2len=32); 
        $cipher_text_raw = substr($c, $iv_len+$sha2len); 
        $original_plaintext = openssl_decrypt($cipher_text_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
        $calc_mac = hash_hmac('sha256', $cipher_text_raw, $key, $as_binary=true); 
        
        if(hash_equals($hmac, $calc_mac)){ //PHP 5.6+ Timing attack safe string comparison 
            // echo 'Original String: '.$original_plaintext; 
            $result = $original_plaintext;
        }else{ 
            // echo 'Decryption failed!'; 
            $result = null;
        }

        return $result;
    }

    
    
    
    public function checkEncryption($id, $type, $name = "package_id"){

        $provider = json_decode($this->dataDecrypt($id));

        if(!$provider )
        {
            
            $this->errorResponse = $this->errorResponse(
                422,
                'Validation error',
                [
                    $name => "Invalid $name"
                ], 
            );

            return false;
        }


        
        // 
        if($provider->_type !== $type)
        {

            $this->errorResponse = $this->errorResponse(
                422,
                'Validation error',
                [
                    $name => "Invalid id type"
                ], 
            );

            return false;
        }


        return $provider;
    }


    
    
    
    public function checkEncryptionType($id, $type, $name = "package_id"){

        $provider = json_decode($this->dataDecrypt($id));

        if(!$provider )
        {
            
            return (object)[
                "success" => false,
                "code" => 422,
                "message" => 'Validation error',
                "errors" => [
                    $name => "Invalid $name"
                ], 
            ];
        }


        // 
        if($provider->enc_type !== $type)
        {

            return (object)[
                "success" => false,
                "code" => 422,
                "message" => 'Validation error',
                "errors" => [
                    $name => "Invalid id type"
                ], 
            ];
        }


        return (object)[
            "success" => true,
            "code" => 200,
            "message" => 'Success',
            "data" => $provider, 
        ];
    }

}