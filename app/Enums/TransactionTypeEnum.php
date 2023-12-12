<?php 


namespace App\Enums;

enum TransactionTypeEnum: int {
    case Credit = 1;
    case Debit = 2;
}