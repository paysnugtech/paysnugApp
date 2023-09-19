<?php 


namespace App\Enums;

use Ramsey\Uuid\Type\Integer;

enum StatusEnum: int {
    case NotAvailable = 0;
    case Available = 1;
}