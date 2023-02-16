<?php

namespace App\Tools;

use ReflectionClass;

class ResponseTool
{
    public const HTTP_CONTINUE = 100;
    public const SWITCH_PROTOCOLS = 101;
    // Successful 2xx
    public const OK = 200;
    public const CREATED = 201;
    public const ACCEPTED = 202;
    public const NONAUTHORITATIVE = 203;
    public const NO_CONTENT = 204;
    public const RESET_CONTENT = 205;
    public const PARTIAL_CONTENT = 206;
    // Redirection 3xx
    public const MULTIPLE_CHOICES = 300;
    public const MOVED_PERMANENTLY = 301;
    public const FOUND = 302;
    public const SEE_OTHER = 303;
    public const NOT_MODIFIED = 304;
    public const USE_PROXY = 305;
    // 306 is deprecated but reserved
    public const TEMP_REDIRECT = 307;
    // Client Error 4xx
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const PAYMENT_REQUIRED = 402;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const NOT_ALLOWED = 405;
    public const NOT_ACCEPTABLE = 406;
    public const PROXY_AUTH_REQUIRED = 407;
    public const REQUEST_TIMEOUT = 408;
    public const CONFLICT = 409;
    public const GONE = 410;
    public const LENGTH_REQUIRED = 411;
    public const PRECONDITION_FAILED = 412;
    public const LARGE_REQUEST_ENTITY = 413;
    public const LONG_REQUEST_URI = 414;
    public const UNSUPPORTED_TYPE = 415;
    public const UNSATISFIABLE_RANGE = 416;
    public const EXPECTATION_FAILED = 417;
    // Server Error 5xx
    public const SERVER_ERROR = 500;
    public const NOT_IMPLEMENTED = 501;
    public const BAD_GATEWAY = 502;
    public const UNAVAILABLE = 503;
    public const GATEWAY_TIMEOUT = 504;
    public const UNSUPPORTED_VERSION = 505;
    public const BANDWIDTH_EXCEEDED = 509;

    // CinetPay Payment Code
    public const SUCCES = "00";
    public const PAYMENT_FAILED = 600;//-1
    public const MERCHANT_NOT_FOUND = 601;//-2
    public const INSUFFICIENT_BALANCE = 602;//-3
    public const SERVICE_UNAVAILABLE = 603;//-4
    public const OTP_CODE_ERROR = 604;//-5
    public const TRANSACTION_CLOSED = 605;//-6
    public const INCORRECT_SETTINGS = 606;//-7
    public const PENDING = 607;//-8

    public const MINIMUM_REQUIRED_FIELDS = 608;
    public const AUTH_NOT_FOUND = 609;
    public const ERROR_PAYMETHOD_NOTFOUND = 610;
    public const ERROR_AMOUNT_TYPE = 611;
    public const ERROR_CURRENCY_NOTVALID = 612;
    public const ERROR_SITE_ID_NOTVALID = 613;
    public const ERROR_FORMAT_TRANSACTION_DATE = 614;
    public const ERROR_LANGUAGE_NOTVALID = 615;
    public const ERROR_PAGE_ACTION_NOTVALID = 616;
    public const ERROR_PAYMENT_CONFIG_NOTVALID = 617;
    public const ERROR_API_VERSION_NOTVALID = 618;
    public const ERROR_SIGNATURE_DONT_MATCHED = 619;
    public const ERROR_DOUBLE_PAYEMNT = 620;
    public const ERROR_OMPAY_UNAVAILABLE = 621;
    public const ERROR_MOMOPAY_UNAVAILABLE = 622;
    public const WAITING_CUSTOMER_TO_VALIDATE = 623;
    public const UNKNOWN_ERROR = 624;
    public const ABONNEMENT_OR_TRANSACTIONS_EXPIRED = 625;
    public const ERROR_FLOOZPAY_UNAVAILABLE = 626;
    public const TRANSACTION_CANCEL = 627;
    public const INTERNAL_ERROR = 628;
    public const GENERAL_FAILURE = 629;
    public const INVALID_AMOUNT_FORMAT = 630;
    public const DAILY_LIMIT_HAS_BEEN_EXCEEDED = 631;
    public const SOURCE_ACCOUNT_NOT_ACTIVE = 632;
    public const MOBILE_ACCOUNT_NUMBER_IS_NOT_ACTIVE = 633;
    public const INVALID_MSISDN = 634;

    public const ERROR_PHONE_NUMBER_NOT_FOUND = 635;
    public const ERROR_PHONE_NUMBER_NOT_SUPPORTED = 636;
    public const ERROR_PHONE_PREFIX_NOT_SUPPORTED = 637;

    public const SECURE_PAYMENT_WAITING_CONFIRMATION = 638;
    public const ERROR_ATLVISA_UNAVAILABLE = 639;
    public const ERROR_YUP_UNAVAILABLE = 640;
    public const ERROR_AMOUNT_TOO_LOW = 641;
    public const ERROR_AMOUNT_TOO_HIGH = 642;
    public const REFUNDED = 660;
    public const REFUNDING_IN_PROCESS = 661;
    public const WAITING_CUSTOMER_PAYMENT = 662;
    public const WAITING_CUSTOMER_OTP_CODE = 663;
    public const FRAUD_DETECTED = 664;

    public const ACCESS_RESTRICTED = 800;
    public const QUOTA_REACHED = 801;
    public const PAYMENT_METHOD_DISABLED = 802;
    public const OTP_VALIDATION = 803;
    public const OPERATOR_UNAVAILABLE = 804;

    public static function setResponse($code, $data)
    {
        $err = [
            'code' => $code,
            'message' => self::getConstantName($code),
        ];
        return array_merge_recursive($err, $data);
    }

    public static function getConstantName($value, $class = self::class)
    {
        return array_flip((new ReflectionClass($class))->getConstants())[$value];
    }
}
