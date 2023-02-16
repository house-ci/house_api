<?php

namespace App\Tools;

use Illuminate\Support\Facades\Http;

class SMSTool
{
    public static $tag = '[SMSTOOL]';

    public static function send($phone, $message)
    {
        try {
            if (!empty($phone)) {
                $phone = self::phoneToOldCameroonFormat($phone);
                $http = self::sendSms($message, $phone);
                if ($http->status() == 200) {
                    $response = [
                        'status' => true,
                        'message' => 'ok',
                        'content' => $http->body(),
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'message' => 'sms not ok',
                        'content' => $http->body(),
                    ];
                }
            } else {
                $response = [
                    'status' => false,
                    'message' => 'phone number is not specified',
                    'content' => '',
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'message' => 'fatal error',
                'content' => $e->getMessage(),
            ];
        }
        return $response;
    }


    private static function sendSms($message, $phones)
    {
        $provider = 'get' . ucfirst(config('cinetpay.sms.default_provider'));
        return self::$provider($message, $phones);
    }


    private static function getEdiata($message, $phone)
    {
        $url = config('cinetpay.sms.ediattah.url');
        $body = array(
            'username' => config('cinetpay.sms.ediattah.username'),
            'password' => config('cinetpay.sms.ediattah.password'),
            'sender' => config('cinetpay.sms.ediattah.sender'),
            'text' => $message,
            'to' => implode(';', array($phone)),
            'type' => 'text',
        );
        return Http::asForm()->post($url, $body);
    }

    private static function getInfobip($message, $phone)
    {
        $url = config('cinetpay.sms.infobip.url');
        $sender = config('cinetpay.sms.infobip.sender');
        $headers = ['Authorization' => 'App ' . config('cinetpay.sms.infobip.apikey'),];
        $body = [
            'messages' => [
                [
                    'from' => $sender,
                    'destinations' => [
                        [
                            'to' => $phone,
                        ]
                    ],
                    'text' => $message
                ]
            ]
        ];
        return Http::asJson()->withHeaders($headers)->post($url, $body);
    }

    private static function phoneToOldCameroonFormat(string $phone): string
    {
        $cameroonPrefix = "237";
        $prefix = substr($phone, 0, 3);
        $phone = preg_replace("/^$prefix/", '', $phone);
        if (preg_match("/$cameroonPrefix/", $prefix)) {
            if (strlen($phone) == 9 && ($phone[0] == '6')) {
                $phone = substr($phone, 1);
            }
        }
        return $prefix . $phone;
    }
}
