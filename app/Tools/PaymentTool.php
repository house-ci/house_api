<?php

namespace App\Tools;

class PaymentTool
{
    public static function verifyData($data): array
    {
        $result = self::verifyRequiredData($data);
        if ($result['status']) {
            $result = self::verifyAmount($data['amount'], $data['currency']);
        }
        return $result;
    }

    public static function verifyRequiredData($data): array
    {
        $response['status'] = false;
        $response['code'] = ResponseTool::MINIMUM_REQUIRED_FIELDS;
        if (empty($data['amount'])) {
            $response['description'] = 'amount is mandatory';
        } elseif (empty($data['currency'])) {
            $response['description'] = 'currency is mandatory';
        } elseif (empty($data['site_id'])) {
            $response['description'] = 'site_id is mandatory';
        } elseif (empty($data['transaction_id'])) {
            $response['description'] = 'transaction_id is mandatory';
        } elseif (empty($data['description'])) {
            $response['description'] = 'description is mandatory';
        } else {
            $response['description'] = self::checkValidity($data);
            if ($response['description'] === null) {
                $response['status'] = true;
            }
        }
        return $response;
    }

    public static function checkValidity($data): ?string
    {
        if (!in_array($data['currency'], ['XOF', 'XAF'])) {
            $description = 'currency must be either XOF or XAF';
        } elseif (!is_numeric($data['amount'])) {
            $description = 'amount must be an integer';
        } elseif (!is_numeric($data['site_id'])) {
            $description = 'site_id must be an integer';
        } elseif (!is_string($data['apikey'])) {
            $description = 'apikey must be an string';
        } elseif (!is_string($data['transaction_id'])) {
            $description = 'transaction_id must be a string';
        } elseif (strlen($data['transaction_id']) > 99) {
            $description = 'transaction_id max length is 99';
        } elseif (!empty($data['metadata']) && !is_string($data['metadata'])) {
            $description = 'metadata must be a string';
        } else {
            $description = null;
        }
        return $description;
    }

    public static function verifyAmount($amount, $currency): array
    {
        $response['status'] = true;
        if (in_array($currency, ['XOF', 'XAF'])) {
            if ($amount < 100) {
                $response['status'] = false;
                $response['code'] = ResponseTool::ERROR_AMOUNT_TOO_LOW;
            } elseif ($amount > 10000000) {
                $response['status'] = false;
                $response['code'] = ResponseTool::ERROR_AMOUNT_TOO_HIGH;
            }
        }
        return $response;
    }
}
