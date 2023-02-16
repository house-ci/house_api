<?php

namespace App\Tools;

class OperatorTool
{
    const OPERATORS = [
        'AIRTEL' => ['AIRTEL'],
        'EXPRESSO' => ['EXPRESSO SENEGAL (SUDATEL)'],
        'FREE' => ['TIGO', 'FREE'],
        'ORANGE' => ['ORANGE', 'OASIS', 'SONATEL MOBILES (ORANGE)'],
        'MOOV' => ['MOOV', 'ONATEL', 'ATLANTIC TELECOM - CÔTE DIVOIRE', 'MALITEL (SOTELMA)', 'ATLANTIC TELECOM - COTE DIVOIRE'],
        'MPESA' => ['MPESA'],
        'MTN' => ['MTN'],
        'TMONEY' => ['TOGOCEL', 'TMONEY'],
        'VODACOM' => ['VODACOM', 'MPESA',],
    ];

    const OPERATOR_DETECTION = [

        "bf" => [
            "regex" => [],
            "prefixes" => [
                [
                    "values" => ["55", "64", "65", "66", "67", "74", "75", "76", "77"],
                    "name" => "orange"
                ],
                [
                    "values" => ["51", "53", "60", "61", "62", "63", "70", "71", "72", "73"],
                    "name" => "moov"
                ],
            ],
            "max_digits" => 8,
            "currencies" => ['XOF']
        ],

        "bj" => [
            "regex" => [],
            "prefixes" => [
                [
                    "values" => ["51", "52", "53", "54", "61", "62", "66", "67", "69", "90", "91", "96", "97"],
                    "name" => "mtn"
                ],
                [
                    "values" => ["60", "63", "64", "65", "94", "95", "98", "99"],
                    "name" => "moov"
                ],
            ],
            "max_digits" => 8,
            "currencies" => ['XOF']
        ],

        "cd" => [
            "regex" => [],
            "prefixes" => [
                [
                    "values" => ["80", "84", "85", "89"],
                    "currencies" => ['CDF', 'USD'],
                    "name" => "orange",
                ],
                [
                    "currencies" => ['CDF', 'USD'],
                    "values" => ["97", "99"],
                    "name" => "airtel",
                ],
                [
                    "values" => ["81", "82", "83", "081", "082", "080", "083"],
                    "currencies" => ['CDF', 'USD'],
                    "name" => "mpesa",
                ],
            ],
            "max_digits" => 10,
            "currencies" => ['CDF', 'USD']
        ],

        "ci" => [
            "regex" => [
                [
                    "values" => "^[0,4,5,6,7,8,9]{1}[7,8,9]\\d{0,8}",
                    "name" => "orange"
                ],
                [
                    "values" => "^[0,4,5,6,7,8,9]{1}[4,5,6]\\d{0,8}",
                    "name" => "mtn"
                ],
                [
                    "values" => "^[0,4,5,6,7,8,9]{1}[0,1,2,3]{1}\\d{0,8}",
                    "name" => "moov"
                ],
            ],
            "prefixes" => [],
            "max_digits" => 10,
            "currencies" => ['XOF']
        ],

        "cm" => [
            "regex" => [],
            "prefixes" => [
                [
                    "values" => ["690", "691", "692", "693", "694", "695", "696", "697", "698", "699", "655", "656", "657", "658", "659"],
                    "name" => "orange"
                ],
                [
                    "values" => ["680", "681", "682", "683", "684", "685", "686", "687", "688", "689", "650", "651", "652", "653", "654", "678"],
                    "name" => "mtn"
                ],
            ],
            "max_digits" => 10,
            "currencies" => ['XAF']
        ],

        "ml" => [
            "regex" => [],
            "prefixes" => [
                [
                    "values" => ["65", "66", "67", "68", "69", "95", "96", "97", "98", "99",],
                    "name" => "moov"
                ],
            ],
            "max_digits" => 10,
            "currencies" => ['XOF']
        ],

        "ne" => [
            "regex" => [],
            "prefixes" => [
                [
                    "values" => ["86", "87", "88", "89", "96", "97", "98", "99"],
                    "name" => "airtel"
                ],
            ],
            "max_digits" => 8,
            "currencies" => ['XOF']
        ],

        "sn" => [
            "regex" => [],
            "prefixes" => [
                [
                    "values" => ["77", "78"],
                    "name" => "orange"
                ],
                [
                    "values" => ["65", "66", "76"],
                    "name" => "freemoney"
                ],
                [
                    "values" => ["70",],
                    "name" => "expresso"
                ],
            ],
            "max_digits" => 10,
            "currencies" => ['XOF']
        ],

        "tg" => [
            "regex" => [],
            "prefixes" => [
                [
                    "values" => ["79", "96", "97", "98", "99"],
                    "name" => "moov"
                ],
                [
                    "values" => ["70", "90", "91", "92", "93"],
                    "name" => "tmoney"
                ],
            ],
            "max_digits" => 10,
            "currencies" => ['XOF']
        ],

        "gn" => [
            "regex" => [],
            "prefixes" => [
                [
                    "values" => ["610","611","62",],
                    "name" => "orange"
                ],
                [
                    "values" => ["66",],
                    "name" => "mtn"
                ],
            ],
            "max_digits" => 8,
            "currencies" => ['GNF']
        ],
    ];

    public static function getPaymentMethodByCarrierName($carrierName, $country = 'CI')
    {
        $carrierName = mb_strtoupper($carrierName);
        foreach (self::OPERATORS as $item => $possibleValues) {
            foreach ($possibleValues as $value) {
                if (str_contains($carrierName, mb_strtoupper($value))) {
                    return self::getOperatorCodeByCountry($country, $item);
                }
            }
        }
        return null;
    }

    public static function getOperatorCodeByCountry($country, $operatorSlug)
    {
        $country = strtoupper($country);
        if ($operatorSlug == 'ORANGE') {
            $operatorSlug = 'OM';
        } elseif ($operatorSlug == 'MTN' && $country == "CI") {
            $operatorSlug = 'MOMO';
        } elseif ($operatorSlug == 'MOOV' && in_array($country, ['CI', 'TG'])) {
            $operatorSlug = 'FLOOZ';
        } elseif ($operatorSlug == 'VODACOM' && $country == "CD") {
            $operatorSlug = 'MPESA';
        }

        if ($country == "CI" && in_array($operatorSlug, ['OM', 'MOMO', 'FLOOZ'])) {
            return $operatorSlug;
        }
        return $operatorSlug . $country;
    }

    public static function getPaymentMethodBySlug($slug, $isdCode = null)
    {
        return $slug;
    }

    public static function getOperators()
    {
        return self::OPERATOR_DETECTION;
    }

    public static function getPaymentMethodByCountryAndCurrency($country, $currency)
    {
        if (in_array($country, self::OPERATOR_DETECTION)) {
            $countryOperator = self::OPERATOR_DETECTION[$country];
            if (in_array($currency, $countryOperator['currencies'])) {
                return $countryOperator;
            }
        }
        return null;
    }

    public static function getCountriesPaymentMethods(): array
    {
        $env = 'app.env';
        return [
            'CI' => [
                'orange' => true,
                'mtn' => true,
                'moov' => true,
            ],
            'CM' => [
                'orange' => true,
                'mtn' => true,
            ],
            'SN' => [
                'orange' => true,
                'freemoney' => true,
                'expresso' => true,
            ],
            'BF' => [
                'orange' => true,
                'moov' => true,
            ],
            'BJ' => [
                'mtn' => true,
            ],
            'NE' => [
                'airtel' => true
            ],
            'ML' => [
                'orange' => true,
                'moov' => true
            ],
            'TG' => [
                'moov' => true,
                'tmoney' => true,
            ],
            'GN' => [
                'orange' => true,
                'mtn' => true,
            ],
            'MG' => [
                'orange' => true,
            ],
            'CD' => [
                'orange' => true,
                'airtel' => true,
                'mpesa' => true,
            ]
        ];
    }

}
