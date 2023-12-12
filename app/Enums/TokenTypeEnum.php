<?php 


namespace App\Enums;

enum TokenTypeEnum: int {
    case Registration = 1;
    case DeviceVerification = 2;
    case ChangePin = 4;
    case FingerPrint = 5;
    case ChangePassword = 6;
    case ResetPassword = 7;
    case ResetPin = 8;

}