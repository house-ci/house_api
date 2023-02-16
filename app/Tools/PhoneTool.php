<?php

namespace App\Tools;

use App\Models\ApiCredential;
use App\Models\Country;
use Exception;
use InvalidArgumentException;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use UnexpectedValueException;

class PhoneTool
{
    public static function recognize($phoneNumber)
    {
        $tag = sprintf('[%s][%s]', session()->getId(), 'PhoneTool@recognize ==> ' . $phoneNumber);

        $phoneUtil = PhoneNumberUtil::getInstance();
        $phoneNumber = str_replace([' ', '-', '.', '/', '"'], [''], $phoneNumber);
        try {
            $phoneNumberObject = $phoneUtil->parse($phoneNumber);
            if ($phoneNumberObject !== null) {
                $isValid = $phoneUtil->isPossibleNumber($phoneNumberObject);
                $countryCode = $phoneUtil->getRegionCodeForNumber($phoneNumberObject);
                if ($isValid) {
                    $phoneArray = [
                        'country' => $countryCode,
                        'prefix' => (string)$phoneUtil->getCountryCodeForRegion($countryCode),
                        'phone' => (string)str_replace(' ', '', $phoneUtil->format($phoneNumberObject, PhoneNumberFormat::NATIONAL)),
                        'phone_number' => (string)$phoneNumber,
                    ];
                    return self::sanitizePhone($phoneArray);
                } elseif ($countryCode === 'CI') {
                    $phone = str_replace('+225', '', $phoneNumber);
                    self::getPhoneOperatorByAlgorithm($countryCode, $phone);
                    $phoneArray = [
                        'country' => $countryCode,
                        'prefix' => (string)$phoneUtil->getCountryCodeForRegion($countryCode),
                        'phone' => $phone,
                        'phone_number' => (string)$phoneNumber,
                    ];
                    return self::sanitizePhone($phoneArray);
                }
            }
        } catch (NumberParseException $e) {
            LogTool::trace($tag, $e->getMessage(), 'critical', $e->getTrace());
        }
        $errMessage = __("Le numéro de téléphone saisi n'est pas correct.");
        throw new InvalidArgumentException($errMessage);
    }

    public static function getPhoneOperatorByAlgorithm($country, $phone)
    {
        if ($country === 'CI') {
            $phone = self::removeIsd($phone);
            if (strlen($phone) == 8) {
                $omPattern = '/^[0,4,5,6,7,8,9]{1}[7,8,9]{1}\d{6}$/i';
                $momoPattern = '/^[0,4,5,6,7,8,9]{1}[4,5,6]{1}\d{6}$/i';
                $floozPattern = '/^[0,4,5,6,7,8,9]{1}[0,1,2,3]{1}\d{6}$/i';
            } else {
                $omPattern = '/^[0]{1}[7,8,9]{1}\d{8}$/i';
                $momoPattern = '/^[0]{1}[4,5,6]{1}\d{8}$/i';
                $floozPattern = '/^[0]{1}[0,1,2,3]{1}\d{8}$/i';
            }
            if (preg_match($omPattern, $phone)) {
                $phoneNumberToCarrierInfo = 'OM';
            } elseif (preg_match($momoPattern, $phone)) {
                $phoneNumberToCarrierInfo = 'MOMO';
            } elseif (preg_match($floozPattern, $phone)) {
                $phoneNumberToCarrierInfo = 'FLOOZ';
            } else {
                throw new UnexpectedValueException(__("Le numéro de téléphone saisi n'est pas correct."));
            }
            return $phoneNumberToCarrierInfo;
        }
        return null;
    }

    public static function addPrefix($phone, $carrier)
    {
        $phone = self::removeIsd($phone);
        if (strlen($phone) == 8) {
            if ($carrier == 'OM') {
                $phone = '07' . $phone;
            } elseif ($carrier == 'MOMO') {
                $phone = '05' . $phone;
            } elseif ($carrier == 'FLOOZ') {
                $phone = '01' . $phone;
            }
        }
        return $phone;
    }

    public static function removeIsd($phone)
    {
        if (strpos($phone, '2') === 0) {
            $phone = str_replace('225', '', $phone);
        }
        return str_replace('+225', '', $phone);
    }

    public static function sanitizePhone($phoneArray)
    {
        if ($phoneArray['country'] === 'CI') {
            try {
                $operator = self::getPhoneOperatorByAlgorithm($phoneArray['country'], $phoneArray['phone']);
                $phoneArray['phone'] = self::addPrefix($phoneArray['phone'], $operator);
                $phoneArray['phone_number'] = '+' . $phoneArray['prefix'] . $phoneArray['phone'];
            } catch (Exception $e) {
                //Todo Hello Dolly
            }
        }
        return $phoneArray;
    }


    public static function buyerName($payment, $transaction)
    {
        if (!empty($payment->customer_name)) {
            $buyer_name = $payment->customer_surname . ' ' . $payment->customer_name;
        } else {
            if (is_int($transaction->cel_phone_num)) {
                $buyer_name = !empty($transaction->buyer_name) ? $transaction->buyer_name : '+' . $transaction->cpm_phone_prefixe . ' ' . $transaction->cel_phone_num;
            } else {
                $buyer_name = !empty($transaction->buyer_name) ? $transaction->buyer_name : $transaction->cel_phone_num;
            }
            if (str_contains($transaction->payment_method, 'VISA')) {
                $buyer_name = 'XXXX XXXX XXXX XXXX';
            }
            if (str_contains($transaction->payment_method, 'PAYCARD')) {
                $buyer_name = 'XXXX XXXX XXXX XXXX';
            }
            if (str_contains($transaction->payment_method, 'COLOWSO')) {
                $buyer_name = 'XXXX XXXX XXXX XXXX';
            }
        }
        return $buyer_name;
    }

    public static function paymentMethodCountry($message)
    {
        $ivoryCoast = "Côte d'Ivoire";
        switch ($message) {
            case 'DDVAOMCI':
            case 'OM':
                $resReturn = [
                    'payment_method' => 'Orange Money',
                    'country' => $ivoryCoast,
                    'code' => "CI",
                    'currency' => "XOF",
                ];
                break;
            case 'DDVAMTNCI':
            case 'MOMO':
                $resReturn = [
                    'payment_method' => 'MTN Mobile Money',
                    'country' => $ivoryCoast,
                    'code' => "CI",
                    'currency' => "XOF",
                ];
                break;
            case 'FLOOZ':
                $resReturn = [
                    'payment_method' => 'Moov Money',
                    'country' => $ivoryCoast,
                    'code' => "CI",
                    'currency' => "XOF",
                ];
                break;
            case 'OMBF':
                $resReturn = [
                    'payment_method' => 'Orange Money',
                    'country' => "Burkina Faso",
                    'code' => "BF",
                    'currency' => "XOF",
                ];
                break;
            case 'MOOVBF':
                $resReturn = [
                    'payment_method' => 'Moov Money',
                    'country' => "Burkina Faso",
                    'code' => "BF",
                    'currency' => "XOF",
                ];
                break;
            case 'OMCM':
                $resReturn = [
                    'payment_method' => 'Orange Money',
                    'country' => 'Cameroun',
                    'code' => "CM",
                    'currency' => "XAF",
                ];
                break;
            case 'MTNCM':
                $resReturn = [
                    'payment_method' => 'MTN Money',
                    'country' => 'Cameroun',
                    'code' => "CM",
                    'currency' => "XAF",
                ];
                break;
            case 'OMML':
                $resReturn = [
                    'payment_method' => 'Orange Money',
                    'country' => 'Mali',
                    'code' => "ML",
                    'currency' => "XOF",
                ];
                break;
            case 'OMSN':
                $resReturn = [
                    'payment_method' => 'Orange Money',
                    'country' => 'Sénégal',
                    'code' => "SN",
                    'currency' => "XOF",
                ];
                break;
            case 'FREESN':
                $resReturn = [
                    'payment_method' => 'Free Money',
                    'country' => 'Sénégal',
                    'code' => "SN",
                    'currency' => "XOF",
                ];
                break;
            case 'AIRTELNE':
                $resReturn = [
                    'payment_method' => 'Airtel Money',
                    'country' => 'Niger',
                    'code' => "NE",
                    'currency' => "XOF",
                ];
                break;
            case 'DDVAVISA':
            case 'ECOVISA':
                $resReturn = [
                    'payment_method' => 'VISA/MasterCard',
                    'country' => '-',
                    'code' => "OTHER",
                    'currency' => "XOF",
                ];
                break;
            default:
                $apiCredential = ApiCredential::getMethod($message);
                $country = Country::getCountry($apiCredential->code_pays);
                $resReturn = [
                    'payment_method' => $apiCredential->display_name,
                    'country' => $apiCredential->libelle_pays_fr,
                    'code' => $country->code_pays,
                    'currency' => $country->devise,
                ];
        }
        return $resReturn;
    }

    public static function phoneNumber($payment)
    {
        if (isset($payment->customer_phone_number) && !empty($payment->customer_phone_number)) {
            return $payment->customer_phone_number;
        }
        if (!empty($payment->phone_prefixe)) {
            return '+' . $payment->phone_prefixe . $payment->cel_phone_num;
        }
        return null;
    }
}
