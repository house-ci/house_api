<?php

namespace App\Tools;

use Exception;

class ValidatorTool
{
    /**
     * @throws Exception
     */
    public static function otpVerify($otp, $length): void
    {
        ThrowableTool::check(empty($otp), __("Le code OTP est obligatoire"));

        ThrowableTool::check(!is_numeric($otp), __("Le code OTP doit être composé uniquement de chiffre"));

        ThrowableTool::check(strlen($otp) !== $length, __("Le code OTP doit etre compose de :number caractere", ['number' => $length]));
    }
}
