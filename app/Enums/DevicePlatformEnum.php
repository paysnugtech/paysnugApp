<?php 


namespace App\Enums;

enum DevicePlatformEnum: string {
    case ANDROID = 'android';
    case IOS = 'ios';
    case WEB = 'web';
}