<?php 

namespace App\Traits;


trait StatusTrait{


    protected function statusCode($name = "ok")
    {
        $code = [
            'success' => '00',
            'continue' => '100',
            'switching_protocols' => '101',
            'processing' => '102',
            'early_hints' => '103',

            'ok' => '200',
            'created' => '201',
            'accepted' => '202',
            'non_authoritative_information' => '203',
            'no_content' => '204',
            'reset_content' => '205',
            'partial_content' => '206',
            'multi_status' => '207',
            'already_reported' => '208',
            'im_used' => '226',

            // 'ok' => '300',	Multiple Choices
            // 'ok' => '301',	Moved Permanently
            // 'ok' => '302',	Found (Previously "Moved Temporarily")
            // 'ok' => '303',	See Other
            // 'ok' => '304',	Not Modified
            // 'ok' => '305',	Use Proxy
            // 'ok' => '306',	Switch Proxy
            'temporary_redirect' => '307',
            'permanent_redirect' => '308',

            'bad_request' => '400',
            'unauthorized' => '401',
            'payment_required' => '402',
            'forbidden' => '403',
            'not_found' => '404',
            'method_not_allowed' => '405',
            'not_acceptable' => '406',	// Not Acceptable
            // 'ok' => '407',	Proxy Authentication Required
            'request_timeout' => '408',
            'conflict' => '409',
            // 'ok' => '410',	Gone
            // 'ok' => '411',	Length Required
            // 'ok' => '412',	Precondition Failed
            // 'ok' => '413',	Payload Too Large
            // 'ok' => '414',	URI Too Long
            // 'ok' => '415',	Unsupported Media Type
            // 'ok' => '416',	Range Not Satisfiable
            // 'ok' => '417',	Expectation Failed
            // 'ok' => '418',	I'm a Teapot
            // 'ok' => '421',	Misdirected Request
            // 'ok' => '422',	Unprocessable Entity
            'locked' => '423',	// Locked
            // 'ok' => '424',	Failed Dependency
            // 'ok' => '425',	Too Early
            // 'ok' => '426',	Upgrade Required
            // 'ok' => '428',	Precondition Required
            // 'ok' => '429',	Too Many Requests
            // 'ok' => '431',	Request Header Fields Too Large
            // 'ok' => '451',	Unavailable For Legal Reasons
            
            'token_sent' => '470',
            'token_not_valid' => '471',
            'token_expired' => '472',

            'server_error' => '500',
            'not_implemented' => '501',
            'bad_gateway' => '502',
            'service_unavailable' => '503',
            'gateway_timeout' => '504',
            'http_version_not_supported' => '505',
            'variant_also_negotiates' => '506',
            'insufficient_storage' => '507',
            'loop_detected' => '508',
            'not_extended' => '510',
            'network_authentication_required' => '511',

            'new_device' => '520',
        ];

        return array_key_exists($name, $code) ? $code[$name] : "";
    }
}