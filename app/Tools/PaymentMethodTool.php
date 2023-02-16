<?php

namespace App\Tools;

use App\Models\TransactionSetting;

class PaymentMethodTool
{
    public static function getServiceCustomPaymentMethod($siteId, string $paymentMethod): string
    {
        $paymentMethodHasMapping = TransactionSetting::where([
            ['slug', TransactionSetting::PAYMENT_METHOD_MAPPING],
            ['value', 'ilike', "$paymentMethod:%"],
            ['name', $siteId],
        ])->first();
        if (!empty($paymentMethodHasMapping)) {
            $paymentMethod = trim(str_replace("$paymentMethod:", '', $paymentMethodHasMapping->value));
        }
        return $paymentMethod;
    }
}
