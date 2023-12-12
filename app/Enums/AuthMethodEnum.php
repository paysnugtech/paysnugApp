<?php 


namespace App\Enums;

enum AuthMethodEnum: int {
    case Pin = 1;
    case Biometric = 2;
}