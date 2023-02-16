<?php

namespace App\Tools;

class WalletTool
{
    public static function list()
    {
        $env = 'app.env';
        $walletArray = [
            [
                "name" => 'Yup',
                "paymentMethod" => 'YUP',
                "logo" => 'yup',
                "status" => false,
                "country" => ['CI'],
                "currencies" => ['XOF'],
            ],
            [
                "name" => 'PayCard',
                "paymentMethod" => 'PAYCARD',
                "logo" => 'paycard',
                "status" => true,
                "country" => ['GN'],
                "currencies" => ['GNF'],
            ],
            [
                "name" => 'Wizall',
                "paymentMethod" => 'WIZALL',
                "logo" => 'wizall',
                "status" => config($env) !== 'production',
                "country" => ['SN', 'CI', 'ML', 'BF'],
                "currencies" => ['XOF'],
            ],
            [
                "name" => 'EcoQr',
                "paymentMethod" => 'ECOQR',
                "logo" => 'ecobank',
                "status" => true,
                "country" => ['CM', (config($env) !== 'production') ? 'CI' : '', (config($env) !== 'production') ? 'TG' : ''],
                "currencies" => ['XAF', (config($env) !== 'production') ? 'XOF' : ''],
            ],
            [
                "name" => 'CelPaid',
                "paymentMethod" => 'CELPAID',
                "logo" => 'celpaid',
                "status" => config($env) !== 'production',
                "country" => ['CI'],
                "currencies" => ['XOF'],
            ],
            [
                "name" => 'ColowSo',
                "paymentMethod" => 'COLOWSO',
                "logo" => 'colowso',
                "status" => config($env) !== 'production',
                "country" => ['CI'],
                "currencies" => ['XOF'],
            ],
            [
                "name" => 'ExpressUnion',
                "paymentMethod" => 'EXPRESSUNION',
                "logo" => 'unionexpress',
                "status" => true,
                "country" => ['CM'],
                "currencies" => ['XAF'],
            ],
            [
                "name" => 'Wave',
                "paymentMethod" => 'WAVE',
                "logo" => 'wave',
                "status" => true,
                "country" => ['SN'],
                "currencies" => ['XOF'],
            ],
            [
                "name" => 'Ecobank',
                "paymentMethod" => 'ECOBANK',
                "logo" => 'ecobank',
                "status" => config($env) !== 'production',
                "country" => ['GN'],
                "currencies" => ['XOF'],
            ],
        ];
        $keys = array_column($walletArray, 'name');
        array_multisort($keys, SORT_ASC, $walletArray);
        return $walletArray;
    }

    public static function getWalletByCountry($countries)
    {
        $wallets = WalletTool::list();
        try {
            $countries = json_decode($countries, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
        }
        $result = [];
        foreach ($wallets as $wallet) {
            foreach ($wallet['country'] as $country) {
                if ($wallet['status'] && (in_array(strtolower($country), $countries))) {
                    $wallet['paymentMethod'] = $wallet['paymentMethod'] . $country ?? @$wallet['country'][0];
                    array_push($result, $wallet);
                }
            }
        }
        return $result;
    }

    public static function getWalletByCurrency($currency)
    {
        $wallets = WalletTool::list();
        $result = [];
        foreach ($wallets as $wallet) {
            if ($wallet['status'] && in_array(strtoupper($currency), $wallet['currencies'])) {
                array_push($result, $wallet);
            }
        }
        return $result;
    }

    public static function getWalletPaymentMethodByNameCountryAndCurrency($name, $dataPayment, $country = null)
    {
        $result = '';
        $wallets = WalletTool::list();
        $currency = $dataPayment['payment']->cpm_currency;

        $countryCode = empty($country) ? strtoupper($dataPayment['service']->pays_serv) : strtoupper($country);
        foreach ($wallets as $wallet) {
            if ($wallet['status'] && $name == $wallet['paymentMethod']) {
                if (in_array($currency, $wallet['currencies']) && in_array($countryCode, $wallet['country'])) {
                    $result = $wallet['paymentMethod'] . $countryCode;
                }
            }
        }
        return @$dataPayment['payment']->payment_method ?? $result;
    }

    public static function getWalletByName($name)
    {
        $wallets = WalletTool::list();
        foreach ($wallets as $wallet) {
            if ($wallet['status'] && $name == $wallet['name']) {
                return $wallet;
            }
        }
        return null;
    }
}
