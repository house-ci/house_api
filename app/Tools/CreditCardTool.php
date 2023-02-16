<?php

namespace App\Tools;

use App\Services\PaymentMethodService;

class CreditCardTool
{
    const API = 'API';
    const ENV = 'app.env';
    const REDIRECT = 'REDIRECT';
    const API_ROUTE = 'payment_cybersource_api';
    const REDIRECT_ROUTE = 'payment_credit_card';
    const API_FORCED_SERVICES = [445160, 111111];
    const REDIRECT_FORCED_SERVICES = [];
    const SUPPORTED_CREDIT_CARDS_COUNTRY = ['CI', 'TG', 'CM', 'CD', 'CDUSD', 'KM', 'BJ'];
    const UNSUPPORTED_CREDIT_CARDS_PROCESSORS = ['jcb', 'elo', 'discover', 'dinersclub', 'maestro',];
    const SUPPORTED_CREDIT_CARDS_PROCESSORS = ['visa', 'mastercard', 'unionpay', 'americanexpress',];
    private static string $operatorNotSupportedString = "L'operateur n'est pas encore supporté par CinetPay";
    private static string $paymentMethodNotAllowedForMerchantString = "Le moyen de paiement est indisponible pour le marchand";

    const VISA_API_REQUIRED_FIELDS = ['customer_name', 'customer_city', 'customer_email', 'customer_surname', 'customer_address', 'customer_country', 'customer_zip_code', 'customer_phone_number'];
    public static array $CREDIT_CARD_PROCESSORS = [
        "XOF" => [
            [
                "name" => 'VISAMCI',
                "paymentMethod" => "VISAM",
                "currency" => 'XOF',
                "status" => true,
                "type" => self::API,
                "country" => 'CI',
                "prefix" => '225',
            ], [
                "name" => 'VISAM3DS',
                "paymentMethod" => "VISAM3DS",
                "currency" => 'XOF',
                "status" => true,
                "type" => self::REDIRECT,
                "country" => 'CI',
                "prefix" => '225',
            ],
            [
                "name" => 'VISAMTG',
                "paymentMethod" => "VISAMTG",
                "currency" => 'XOF',
                "status" => true,
                "type" => self::API,
                "country" => 'TG',
                "prefix" => '228',
            ],
            [
                "name" => 'VISAM3DSTG',
                "paymentMethod" => "VISAM3DSTG",
                "currency" => 'XOF',
                "status" => true,
                "type" => self::REDIRECT,
                "country" => 'TG',
                "prefix" => '228',
            ],
            [
                "name" => 'VISAMSN',
                "paymentMethod" => "VISAMSN",
                "currency" => 'XOF',
                "status" => true,
                "type" => self::API,
                "country" => 'SN',
                "prefix" => '221',
            ],
            [
                "name" => 'VISAM3DSSN',
                "paymentMethod" => "VISAM3DSSN",
                "currency" => 'XOF',
                "status" => true,
                "type" => self::REDIRECT,
                "country" => 'SN',
                "prefix" => '221',
            ],
            [
                "name" => 'VISAMBJ',
                "paymentMethod" => "VISAMBJ",
                "currency" => 'XOF',
                "status" => true,
                "type" => self::API,
                "country" => 'BJ',
                "prefix" => '229',
            ],
            [
                "name" => 'VISAM3DSBJ',
                "paymentMethod" => "VISAM3DSBJ",
                "currency" => 'XOF',
                "status" => true,
                "type" => self::REDIRECT,
                "country" => 'BJ',
                "prefix" => '229',
            ],
        ],
        "XAF" => [
            [
                "name" => 'VISAMCM',
                "paymentMethod" => 'VISAMCM',
                "currency" => 'XAF',
                "status" => true,
                "type" => self::API,
                "country" => 'CM',
                "prefix" => '237',
            ], [
                "name" => 'VISAM3DSCM',
                "paymentMethod" => 'VISAM3DSCM',
                "currency" => 'XAF',
                "status" => true,
                "type" => self::REDIRECT,
                "country" => 'CM',
                "prefix" => '237',
            ],
        ],
        "CDF" => [
            [
                "name" => 'VISAMCD',
                "paymentMethod" => 'VISAMCD',
                "currency" => 'CDF',
                "status" => false,
                "type" => self::API,
                "country" => 'CD',
                "prefix" => '243',
            ]
        ],
        "USD" => [
            [
                "name" => 'VISAMCDUSD',
                "paymentMethod" => 'VISAMCDUSD',
                "currency" => 'USD',
                "status" => true,
                "type" => self::API,
                "country" => 'CDUSD',
                "prefix" => '243',
            ],
            [
                "name" => 'VISAM3DSCDUSD',
                "paymentMethod" => 'VISAM3DSCDUSD',
                "currency" => 'USD',
                "status" => true,
                "type" => self::REDIRECT,
                "country" => 'CDUSD',
                "prefix" => '243',
            ]
        ],
        "KMF" => [
            [
                "name" => 'DBSVISAMKMF',
                "paymentMethod" => 'DBSVISAMKMF',
                "currency" => 'KMF',
                "status" => true,
                "type" => self::REDIRECT,
                "country" => 'KM',
                "prefix" => '269',
            ],
            [
                "name" => 'DBSVISAMKMF',
                "paymentMethod" => "VISAM",
                "currency" => 'KMF',
                "status" => true,
                "type" => self::API,
                "country" => 'KM',
                "prefix" => '269',
            ],
        ],
    ];

    public static function canPayWithVisaApi($dataPayment, bool $visaStrictMode = false): bool
    {
        $canPayWithVisaApi = true;
        $payToken = $dataPayment['payment'];
        foreach (self::VISA_API_REQUIRED_FIELDS as $visaApiRequiredField) {
            if (empty($payToken[$visaApiRequiredField] ?? $payToken->{$visaApiRequiredField})) {
                $canPayWithVisaApi = false;
            }
        }
        try {
            $creditCardPaymentMethod = CreditCardTool::getCreditCardMethodByCurrency($payToken, $dataPayment['service']['pays_serv']);
            $paymentMethod = $creditCardPaymentMethod['paymentMethod'];

            ThrowableTool::check(empty($paymentMethod), __(static::$operatorNotSupportedString));
            // Verification
            $isPaymentMethodAllowForThisService = PaymentMethodService::isPaymentMethodAllowForService($dataPayment['service'], $paymentMethod);
            if ($visaStrictMode) {
                return (bool)$isPaymentMethodAllowForThisService;
            }

            ThrowableTool::check(!$isPaymentMethodAllowForThisService, __(static::$paymentMethodNotAllowedForMerchantString));

        } catch (\Exception $e) {
            $canPayWithVisaApi = false;
        }
        return $canPayWithVisaApi;
    }

    public static function getCreditCardPaymentRoute($payment)
    {
        $creditCardAllowedMethod = self::getCreditCardProcessingType($payment);
        return $creditCardAllowedMethod == self::API ? route(self::API_ROUTE) : route(self::REDIRECT_ROUTE);
    }


    public static function list($currency, $type = self::REDIRECT)
    {
        foreach (self::$CREDIT_CARD_PROCESSORS as $creditCardCountryCurrency => $creditCardCountryProcessors) {
            $list[$creditCardCountryCurrency] = $creditCardCountryProcessors;
            $allowedTypeCreditCardCountryProcessors = array_filter($creditCardCountryProcessors, function($cCCP) use ($type) { return $cCCP['type'] == $type; });
            $list[$creditCardCountryCurrency]['default'] = reset($allowedTypeCreditCardCountryProcessors);
        }
        return $list[$currency] ?? null;
    }

    public static function getCreditCardMethodByCurrency($paymentData, $serviceCountry = null)
    {
        $country = $serviceCountry ?? $paymentData['customer_country'] ?? 'CI';
        $currency = $paymentData['cpm_currency'] ?? 'XOF';

        if (!in_array($country, self::SUPPORTED_CREDIT_CARDS_COUNTRY)) {
            $country = match ($currency) {
                'XAF' => 'CM',
                'CDF' => 'CD',
                'KMF' => 'KM',
                'USD' => 'CDUSD',
                default => $country,
            };
        }

        $creditCardAllowedMethod = self::getCreditCardProcessingType($paymentData);

        $creditCardProcessors = self::list($currency, $creditCardAllowedMethod);
        if (empty($creditCardProcessors)) {
            return null;
        }

        $result = $creditCardProcessors['default'];
        unset($creditCardProcessors['default']);

        foreach ($creditCardProcessors as $creditCardProcessor) {

            if ($creditCardProcessor['status'] && $creditCardProcessor['country'] == strtoupper($country) && $creditCardProcessor['type'] == $creditCardAllowedMethod) {
                $result = $creditCardProcessor;
                break;
            } else {
                if ($creditCardProcessor['type'] == $creditCardAllowedMethod && $creditCardProcessor['country'] == strtoupper($country)) {
                    $result = $creditCardProcessor;
                    break;
                }
            }
        }
        return $result;
    }

    public static function getCreditCardProcessingType($payment)
    {
        $creditCardProcessingType = self::getDefaultVisaType();
        $creditCardAllowedTypes = config('cinetpay.label.credit_card_allowed_routes', [self::getDefaultVisaType()]);

        if (empty(@$payment->can_pay_with_visa_api) && in_array(self::REDIRECT, $creditCardAllowedTypes)) {
            $creditCardProcessingType = self::REDIRECT;
        }

        if (@$payment->is_visa_secured && in_array(self::REDIRECT, $creditCardAllowedTypes)) {
            $creditCardProcessingType = self::REDIRECT;
        }

        if (in_array((int)@$payment->cpm_site_id, self::REDIRECT_FORCED_SERVICES)) {
            $creditCardProcessingType = self::REDIRECT;
        }

        if (in_array((int)@$payment->cpm_site_id, self::API_FORCED_SERVICES)) {
            $creditCardProcessingType = self::API;
        }

        return $creditCardProcessingType;
    }

    private static function getDefaultVisaType(){
        return self::REDIRECT;
    }

}
