<?php 


namespace App\Enums;

use Ramsey\Uuid\Type\Integer;

enum TransactionStatusEnum: int {
    
    case Success = 1;
    case Failed = 2;
    case Pending = 3;
    case Processing = 4;
    case Reversed = 5;
}