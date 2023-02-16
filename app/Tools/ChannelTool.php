<?php

namespace App\Tools;

class ChannelTool
{


    public static function isVisible($current, $channels, $payment = null)
    {
        if ($current === 'CREDIT_CARD' && !empty($payment) && !array_key_exists($payment->cpm_currency, CreditCardTool::$CREDIT_CARD_PROCESSORS)) {
            return false;
        }
        if ($current === 'WALLET' && !empty($payment) && empty(WalletTool::getWalletByCurrency($payment->cpm_currency))) {
            return false;
        }
        $availableChannels = self::getAvailableChannels();
        if (in_array($current, $availableChannels)
            && (in_array($current, $channels) || in_array('ALL', $channels))
        ) {
            return true;
        }
        return false;
    }

    public static function setActive($current, $channels, $channelDefault, $value = 'active show')
    {
        $availableChannels = self::getAvailableChannels();
        $return = null;
        if (in_array($current, $availableChannels)) {
            if ($current === $channelDefault) {
                $return = $value;
            } elseif ($current === 'MOBILE_MONEY' && $channelDefault === null
                && (in_array('MOBILE_MONEY', $channels, true) || in_array('ALL', $channels, true))) {
                $return = $value;
            } elseif ($current === 'CREDIT_CARD' && $channelDefault === null
                && in_array('CREDIT_CARD', $channels, true)) {
                if (!in_array('MOBILE_MONEY', $channels, true)) {
                    $return = $value;
                }
            } elseif ($current === 'WALLET' && $channelDefault === null
                && in_array('WALLET', $channels, true)) {
                if (!in_array('MOBILE_MONEY', $channels, true)
                    && !in_array('CREDIT_CARD', $channels, true)
                ) {
                    $return = $value;
                }
            } elseif ($current === 'QR_CODE' && $channelDefault === null
                && in_array('QR_CODE', $channels, true)) {
                if (!in_array('MOBILE_MONEY', $channels, true)
                    && !in_array('CREDIT_CARD', $channels, true)
                    && !in_array('WALLET', $channels, true)
                ) {
                    $return = $value;
                }
            }
        }
        return $return;
    }

    public static function set($channels): array
    {
        $ch = explode(',', $channels) ?? ['ALL'];
        if (in_array('MOBILE_MONEY', $ch)
            && in_array('CREDIT_CARD', $ch)
            && in_array('WALLET', $ch)
        ) {
            $ch = ['ALL'];
        }
        return $ch;
    }

    public static function getUniverses($payment)
    {
        return [
            'MOBILE_MONEY' => [
                'action' => route('payment_verify'),
                'name' => 'MOBILE_MONEY'
            ],
            'CREDIT_CARD' => [
                'action' => CreditCardTool::getCreditCardPaymentRoute($payment),
                'name' => 'CREDIT_CARD'
            ],
            'WALLET' => [
                'action' => route('payment_wallets'),
                'name' => 'WALLET'
            ],
        ];
    }

    public static function getPaymentMethodUniverse($payment)
    {
        $universe = 'MOBILE MONEY';
        $paymentMethod = $payment->payment_method;
        if (str_contains($paymentMethod, 'VISA')) {
            $universe = 'CREDIT CARD';
        }
        $wallets = WalletTool::list();
        foreach ($wallets as $wallet) {
            $paymentMethod === $wallet['paymentMethod'] ? $universe = 'WALLET' : null;
        }
        return $universe;
    }
}
