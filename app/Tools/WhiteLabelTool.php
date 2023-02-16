<?php

namespace App\Tools;

class WhiteLabelTool
{
    const CUSTOM_PAYMENT_METHOD = [
        'DBS' => [
            //Should be set like exemple bellow
            'VISAMCM' => 'DBSVISAM1'
        ],
        'CINETPAY' => [
            'CNPYVISAM' => 'CNPYVISAM',
            'VISAM3DS' => 'VISAM3DS',
            'CNPYVISAMCI' => 'CNPYVISAM',
            'VISAM3DSCI' => 'VISAM3DS',
            'VISAM3DSCDUSD' => 'VISAM3DSCDUSD',
        ],
    ];

    const WHITE_LABEL_COLORS = [
        'CINETPAY' => [
            "primary" => '#00A548',
            "secondary" => '#E9F5EC',
        ],
        'DBS' => [
            "primary" => '#146BB4',
            "secondary" => '#79ADD9',
        ],
        'IN-NOV-GROUP' => [
            "primary" => '#030224',
            "secondary" => '#F2700F',
        ],
    ];

    public static function getWhiteLabelCustomColor()
    {
        $whiteLabelName = strtoupper(config('cinetpay.label.name'));
        if (array_key_exists($whiteLabelName, self::WHITE_LABEL_COLORS)) {
            $labelCustomColors = self::WHITE_LABEL_COLORS[$whiteLabelName];
        } else {
            $labelCustomColors['primary'] = config('cinetpay.label.invoice.primaryColor');
            $labelCustomColors['secondary'] = config('cinetpay.label.invoice.secondaryColor');
        }
        return $labelCustomColors;
    }

    public static function getWhiteLabelCustomPaymentMethod($paymentMethod): string
    {
        $label = strtoupper(config('cinetpay.label.name'));
        if (array_key_exists($label, self::CUSTOM_PAYMENT_METHOD)) {
            $labelCustomPaymentMethods = self::CUSTOM_PAYMENT_METHOD[$label];
            if (array_key_exists($paymentMethod, $labelCustomPaymentMethods)) {
                $paymentMethod = $labelCustomPaymentMethods[$paymentMethod];
            }
        }
        return $paymentMethod;
    }

}
