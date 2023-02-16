<?php

namespace App\Tools;

use Carbon\Carbon;

class GeneratorTool
{
    public static function generateTransactionId($transaction = null, $onlyNumber = false)
    {
        $dateNow = Carbon::now();
        if (!$onlyNumber) {
            $numArray = array();
            $lettersArray = array("A", "B", "C", "D", "E", "F", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "M", "Q", "R", "S", "V", "W", "X", "Y", "Z");
            shuffle($lettersArray);
            $numArray = self::UniqueRandomNumbersWithinRange(10, 99, 5);

            $bloc1 = $dateNow->format('ymd');
            $bloc2 = $dateNow->format('His');
            $bloc3 = $numArray[1] . $numArray[2];
            shuffle($lettersArray);
            if (empty($transaction)) {
                shuffle($lettersArray);
                $transaction = $lettersArray[0] . $lettersArray[1];
            }
            return $transaction . $bloc1 . '.' . $bloc2 . '.' . $bloc3;
        }

        return random_int(1000, 9999) . random_int(1000, 9999);
    }

    public static function UniqueRandomNumbersWithinRange($min, $max, $quantity)
    {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }
}
