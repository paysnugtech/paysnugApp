<?php 


namespace App\Enums;

enum ResponseStatus: string {
    case Success = 'success';
    case Failed = 'failed';
}