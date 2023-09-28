<?php 


namespace App\Enums;

use Ramsey\Uuid\Type\Integer;

enum LogStatusEnum: int {
    case Logout = 0;
    case Login = 1;
}