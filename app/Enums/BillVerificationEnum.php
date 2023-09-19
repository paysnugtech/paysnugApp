<?php 


namespace App\Enums;

enum BillVerificationEnum: int {
    case NotVerified = 0;
    case Verified = 1;
}