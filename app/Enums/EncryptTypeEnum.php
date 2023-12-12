<?php 


namespace App\Enums;

enum EncryptTypeEnum: int {
    case AIRTIME_PROVIDER_LIST = 1;
    case CABLE_PROVIDER_LIST = 2;
    case CABLE_PACKAGE_LIST = 3;
    case DATA_PROVIDER_LIST = 4;
    case DATA_PACKAGE_LIST = 5;
    case ELECTRICITY_PROVIDER_LIST = 6;
    case ELECTRICITY_PACKAGE_LIST = 7;
    case BANK_TRANSFER_PROVIDER = 8;
    case REGISTRATION_TOKEN = 9;
}