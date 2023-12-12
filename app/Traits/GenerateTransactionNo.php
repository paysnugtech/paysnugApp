<?php 

namespace App\Traits;
use Illuminate\Support\Str;

trait GenerateTransactionNo{
    protected function generateTransactionNo($service_type = '')
    {
        $date = now()->format('ymd');

        // $transactionNo = $date . random_int(10101, 99999) . random_int(1010, 9999);
        // $transactionNo = Str::random(6) . time() . Str::random(3);

        $random_string = $date . Str::random(255);

        $numeric_string = filter_var($random_string, FILTER_SANITIZE_NUMBER_INT);

        $transactionNo = substr($numeric_string, 0, 15);

        return $transactionNo;
    }

    
    protected function generateReferenceNo()
    {
        $date = now()->format('ymd');

        // $transactionNo = $date . random_int(10101, 99999) . random_int(1010, 9999);
        // $transactionNo = Str::random(6) . time() . Str::random(3);

        $random_string = $date . Str::random(255);

        $numeric_string = filter_var($random_string, FILTER_SANITIZE_NUMBER_INT);

        $transactionNo = substr($numeric_string, 0, 15);

        return "Paysnug-". $transactionNo;
    }
}