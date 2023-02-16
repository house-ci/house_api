<?php


namespace App\Tools;


use GuzzleHttp\Client;

class SeamlessTool
{
    public static function getClient()
    {
        return new Client([
            'base_uri' => config('cinetpay.seamless.host'),
            'verify' => false
        ]);
    }

    public static function getToken()
    {
        return SessionTool::getElement('seamless_device_id') ?? null;
    }

    public static function sendTransactionStatus($data)
    {
        $response = self::getClient()->request('POST', 'send', [
            'json' => self::setBody($data)
        ]);

        return $response->getStatusCode();
    }

    public static function setBody($payload)
    {
        return [
            'to' => self::getToken(),
            'channel' => 'channel',
            'data' => self::hydrateData($payload)
        ];
    }

    public static function hydrateData($data)
    {
        return [
            "amount" => $data['cpm_amount'],
            "currency" => $data['cpm_currency'],
            "status" => $data['cpm_trans_status'],
            "payment_method" => $data['payment_method'],
            "description" => $data['cpm_designation'],
            "metadata" => $data['cpm_custom'],
            "operator_id" => $data['cpm_payid'],
            "payment_date" => $data['cpm_payment_date'] . ' ' . $data['cpm_payment_time'],
        ];
    }
}
