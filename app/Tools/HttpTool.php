<?php

namespace App\Tools;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class HttpTool
{
    public static function getSignature($data, $debug = 0)
    {
        $client = self::getClient();

        try {
            $response = $client->request('POST', '/v1/', [
                'query' => [
                    'method' => 'getSignatureByPost'
                ],
                'form_params' => $data
            ]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        } catch (\Exception $e) {
            return [false, ['status' => ["code" => "709", "message" => "EXECUTION_ERROR"]]];
        }
        $jsonArray = \GuzzleHttp\json_decode((string)$response->getBody(), true);
        if (is_string($jsonArray)) {
            return [true, $jsonArray];
        } else {
            return [false, $jsonArray];
        }
    }

    public static function getClient($operator = 'others')
    {
        $wl = get_cp_white_brand_name();
        if ($wl == 'allianz') {
            return self::getClientAllianz();
        }
        if ($operator == 'OM') {
            return self::getClientDefaultOrange();
        }
        return self::getClientDefault();
    }

    private static function getClientDefault()
    {
        return new Client([
            'curl' => [CURLOPT_RESOLVE => ['api.cinetpay.com:443:145.239.141.52']],
            'base_uri' => "https://api.cinetpay.com/v1",
            'verify' => false
        ]);
    }

    private static function getClientDefaultOrange()
    {
        return new Client([
            'curl' => [CURLOPT_RESOLVE => ['api-om.cinetpay.com:443:15.188.43.114']],
            'base_uri' => "https://api-om.cinetpay.com/v1",
            'verify' => false
        ]);
    }

    private static function getClientAllianz()
    {
        return new Client([
            'curl' => [CURLOPT_RESOLVE => ['api-allianz.cinetpay.com:443:188.165.229.42']],
            'base_uri' => "https://api-allianz.cinetpay.com/v1",
            'verify' => false
        ]);
    }

    public static function processPayment($data)
    {
        $paymentMethod = !empty($data["payment_method"]) ? $data["payment_method"] : null;
        $client = self::getClient($paymentMethod);
        try {
            $response = $client->request('POST', '/v1/', [
                'query' => [
                    'method' => 'processPayment'
                ],
                'form_params' => $data
            ]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        } catch (\Exception $e) {
            return [false, ['status' => ["code" => "709", "message" => "EXECUTION_ERROR"]]];
        }
        if (!empty($response)) {
            $content = str_replace(['\n', '\t'], "", trim($response->getBody()->getContents()));
            try {
                $jsonArray = json_decode($content, true);
            } catch (\Exception $e) {
                return [false, ['status' => ["code" => "709", "message" => "EXECUTION_ERROR"]]];
            }
            return [true, $jsonArray];
        }
        return [false, ['status' => ["code" => "709", "message" => "EXECUTION_ERROR"]]];
    }

    public static function checkOmStatus($data)
    {
        $paymentMethod = !empty($data["payment_method"]) ? $data["payment_method"] : null;
        $client = self::getClient($paymentMethod);
        if ($data["payment_method"] == "OM") {
            try {
                $response = $client->request('POST', '/v1/', [
                    'query' => [
                        'method' => 'verifyOperatorTransactionStatus'
                    ],
                    'form_params' => $data
                ]);
            } catch (ClientException $e) {
                $response = $e->getResponse();
            } catch (\Exception $e) {
                return "error";
            }
            return \GuzzleHttp\json_decode((string)$response->getBody(), true);
        }
        return null;
    }

    public static function checkMoovStatus($data)
    {
        $client = self::getClient();
        if ($data["payment_method"] == "FLOOZ") {
            try {
                $response = $client->request('POST', '/v1/', [
                    'query' => [
                        'method' => 'verifyOperatorTransactionStatus'
                    ],
                    'form_params' => $data
                ]);
            } catch (ClientException $e) {
                $response = $e->getResponse();
            } catch (\Exception $e) {
                return "error";
            }
            return \GuzzleHttp\json_decode((string)$response->getBody(), true);
        }
        return null;
    }

    public static function getPhoneNumberCarrierInformation($phoneNum)
    {
        $client = new Client();
        if (is_array($phoneNum)) {
            $phone = $phoneNum;
        } else {
            $phone = Tools::phoneNumber($phoneNum);
        }

        try {
            $response = $client->request('GET', 'https://api.data24-7.com/v/2.0', [
                'query' => [
                    'api' => 'C',
                    'user' => 'cinetpay',
                    'pass' => '8w32jNPt3F1MTwL',
                    'p1' => $phone["prefix"] . $phone["phone"],
                ]
            ]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        } catch (\Exception $e) {
            return null;
        }
        $responseString = $response->getBody()->getContents();
        try {
            $xml = @simplexml_load_string($responseString);
            if ($xml->results->result[0]->status != 'OK') {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
        return [
            "status" => $xml->results->result[0]->status,
            "number" => $xml->results->result[0]->number,
            "wless" => $xml->results->result[0]->wless,
            "carrierName" => $xml->results->result[0]->carrier_name,
            "carrierId" => $xml->results->result[0]->carrier_id,
            "country" => $xml->results->result[0]->country,
        ];
    }

    public static function getCurlOption($operator)
    {
        $url = config('cinetpay.api_legacy_url');
        $curlOption = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_VERBOSE => true,
            CURLOPT_STDERR => fopen(storage_path('logs/queries.log'), 'w'),
        ];
        if ($operator == "OMSN") {
            $curlResolve = ["api-omsn.cinetpay.com:443:15.188.6.80"];
            if (random_int(0, 1) == 1) {
                $curlResolve = ["api-omsn.cinetpay.com:443:145.239.141.54"];
            }
            $curlOption[CURLOPT_RESOLVE] = $curlResolve;
            $url = str_replace('api.cinetpay.com', 'api-omsn.cinetpay.com', $url);
        } elseif (in_array($operator, ['OM', '123'])) {
            $curlOption[CURLOPT_RESOLVE] = ["api-om.cinetpay.com:443:15.188.43.114"];
            $url = str_replace('api.cinetpay.com', 'api-om.cinetpay.com', $url);
        } elseif (in_array($operator,
            ['FLOOZ', 'FLOOZTG', 'MOOVBF', 'MTNCM', 'MTNBJ', 'OMCD', 'OMCDUSD', 'OMCM', 'YUPCI', 'MPESACD',
                'MPESACDUSD', 'DDVAOMCI', 'DDVAMTNCI', 'DDVAMOOVCI', 'DDVAVISAM', 'OMML', 'ECOQRCI', 'ECOQRCM']
        )) {
            $curlOption[CURLOPT_RESOLVE] = ["api-flooz.cinetpay.com:443:145.239.141.54"];
            $url = str_replace('api.cinetpay.com', 'api-flooz.cinetpay.com', $url);


            // Uniquement à des fin de TEST
            if (in_array($operator, ['YUPCI', 'OMCD', 'OMCDUSD', 'MPESACD', 'MPESACDUSD', 'OMGN']) && config('app.env') !== 'production') {
                $curlOption[CURLOPT_RESOLVE] = ["apifauxtruc.cinetpay.ci:443:145.239.141.54"];
                $url = str_replace('api.cinetpay.ci', 'apifauxtruc.cinetpay.ci', $url);
            }
        } elseif (in_array($operator, ['FREESN', 'AAFREESN', 'AAVFREESN'])) {
            $curlOption[CURLOPT_RESOLVE] = ["api-freesn.cinetpay.com:443:54.144.205.199"];
            $url = str_replace('api.cinetpay.com', 'api-freesn.cinetpay.com', $url);
        }

        return [
            'url' => $url,
            'curl' => $curlOption,
        ];
    }
}
