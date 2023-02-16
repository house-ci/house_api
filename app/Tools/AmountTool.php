<?php

namespace App\Tools;

class AmountTool
{
    public static function getAmountHtml($payToken)
    {
        if($payToken->cpm_currency == 'USD'){
            return sprintf('%s %s', number_format($payToken->cpm_amount, 2, '.', ','), $payToken->cpm_currency);
        }
        return sprintf('%s %s', number_format($payToken->cpm_amount, 0, '', ' '), $payToken->cpm_currency);
    }

    public static function showAmountInOtherCurrency($float, $currency_from = 'XOF', $currency_to = 'EUR')
    {
        $currencyArray = [
            "USD" => [
                "name" => "USD",
                "prefix" => "$",
                "suffix" => "USD",
            ],
            "EUR" => [
                "name" => "EUR",
                "prefix" => "€",
                "suffix" => "EUR",
            ],
            "GBP" => [
                "name" => "GBP",
                "prefix" => "£",
                "suffix" => "POUND",
            ],
            "XOF" => [
                "name" => "XOF",
                "prefix" => null,
                "suffix" => "FCFA" ?? "FR (FCFA)",
            ],
            "XAF" => [
                "name" => "XAF",
                "prefix" => null,
                "suffix" => "FCFA" ?? "FR (FCFA)",
            ],
            "JPY" => [
                "name" => "JPY",
                "prefix" => "¥",
                "suffix" => "YEN",
            ],
            "INR" => [
                "name" => "INR",
                "prefix" => "₹",
                "suffix" => "RUPEE",
            ],
            "NGN" => [
                "name" => "NGN",
                "prefix" => "₦",
                "suffix" => "NAIRA",
            ],
            "MXN" => [
                "name" => "MXN",
                "prefix" => "₱",
                "suffix" => "PESO",
            ],
            "KRW" => [
                "name" => "KRW",
                "prefix" => "₩",
                "suffix" => "WON",
            ],
            "RUB" => [
                "name" => "RUB",
                "prefix" => "₽",
                "suffix" => "RUBLE",
            ],
            "GHS" => [
                "name" => "GHS",
                "prefix" => "₵",
                "suffix" => "CEDI",
            ],
            "BTC" => [
                "name" => "BTC",
                "prefix" => "₿",
                "suffix" => "BITCOIN",
            ],
            "XBT" => [
                "name" => "XBT",
                "prefix" => "₿",
                "suffix" => "BITCOIN",
            ],
        ];

        try {
            if ($currency_from == 'CFA') {
                $currency_from = 'XOF';
            }
            if ($currency_from == $currency_to) {
                return null;
            }
            $convertedAmount = self::moneyConvert($float, $currency_from, $currency_to);
            if (array_key_exists($currency_to, $currencyArray)) {
                $prefix = $currencyArray[$currency_to]['prefix'];
                $suffix = $prefix ? null : $currencyArray[$currency_to]['suffix'];
                $res = "(" . __('soit environ') . " $prefix $convertedAmount $suffix)*";
            } else {
                $res = "(" . __('soit environ') . " $convertedAmount $currency_to )*";
            }
        } catch (\Exception $e) {
            $res = null;
        }
        return $res;
    }

    public static function moneyConvert($float, $currency_from = 'XOF', $currency_to = 'USD')
    {
        if ($currency_from != $currency_to) {
            $url = sprintf("https://api.cinetpay.com/new/index.php/v1/currency?q=%s-%s", $currency_from, $currency_to);
            $exchangeRate = null;
            $response = file_get_contents($url);
            if (!empty($response)) {
                $response = (float)$response;
                if ($response > 0) {
                    $exchangeRate = $response;
                }
            }
            if (empty($exchangeRate)) {
                throw new \Exception('Error 222222222221');
            }
            $amount_to = $float * $exchangeRate;
        } else {
            $amount_to = $float;
        }

        if (in_array($currency_to, ['XOF', 'XAF'])) {
            $amount_to = round($amount_to, 0, PHP_ROUND_HALF_UP); // cast amount to string to avoid rounder than return it as int
        } else {
            $amount_to = number_format($amount_to, 2);
        }
        return $amount_to;
    }

}
