<?php 


namespace App\Enums;

enum ServiceTypeEnum: int {
    case Airtime = 1;
    case Cable = 2;
    case Betting = 3;
    case Data = 4;
    case Electricity = 5;
    case Exam = 6;
    case BankTransfer = 7;
    case ReceiveMoney = 8;
    case Fee = 888;
    case Commission = 999;
}