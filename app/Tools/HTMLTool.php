<?php

namespace App\Tools;

use Illuminate\Http\RedirectResponse;

class HTMLTool
{
    const CHANGER = 'CURRENCY';

    public static function setToken(): ?string
    {
        try {
            $value = @$_REQUEST['token'];
            if (!empty($value)) {
                return sprintf('<input type="hidden" name="token" value="%s">', $value);
                /*
                $response = Cache::get($value);
                if (!empty($response)) {
                    return sprintf('<input type="hidden" name="token" value="%s">', $response);
                }
                */
            }
        } catch (\Exception $e) {
        }
        return null;
    }

    public static function route($route): string
    {
        try {
            $value = @$_REQUEST['token'];
            if (!empty($value)) {
                return route($route, ['token' => $value]);
            }
        } catch (\Exception $e) {
        }
        return route($route);
    }

    public static function redirectRoute($route): RedirectResponse
    {
        try {
            $value = @$_REQUEST['token'];
            if (!empty($value)) {
                return redirect()->route($route, ['token' => $value]);
            }
        } catch (\Exception $e) {
        }
        return redirect()->route($route);
    }

    public static function imagesMobileMoneyMethods($countries)
    {
        if (is_string($countries)) {
            $countries = json_decode($countries, true, 512, JSON_THROW_ON_ERROR);
        }

        $countryMethods = OperatorTool::getCountriesPaymentMethods();
        $countriesLogos = '';
        foreach ($countries as $country) {
            try {
                $elements = sprintf('<div class="col-12 px-0"><div><p id="methods%s" class="d-none payment-method-box operator-box">', $country);
                foreach ($countryMethods[strtoupper($country)] as $method => $status) {
                    try {
                        if ($status) {
                            $elements .= sprintf(
                                '<img src="%s" class="rounded mx-1 payment-method-logo" alt="%s">',
                                asset('assets/images/payment_methods/' . $method . '.png'),
                                strtoupper($method)
                            );
                        }
                    } catch (\Exception $e) {
                    }
                }
                $elements .= '</p></div></div>';

                $countriesLogos .= $elements;
            } catch (\Exception $e) {
            }
        }
        return $countriesLogos;
    }

    public static function imagesCreditCardMethods()
    {
        $visaLogos = CreditCardTool::SUPPORTED_CREDIT_CARDS_PROCESSORS;
        $elements = '<p id="methodsCreditCards" class="payment-method-box">';
        foreach ($visaLogos as $logo) {
            try {
                $elements .= sprintf(
                    '<img src="%s" class="payment-method-logo mx-1" alt="%s">',
                    asset('assets/images/payment_methods/' . $logo . '.png'),
                    strtoupper($logo)
                );
            } catch (\Exception $e) {
            }
        }
        $elements .= '</p>';
        return $elements;
    }

    public static function imagesCreditCardRedirectMethods()
    {
        $visaLogos = CreditCardTool::SUPPORTED_CREDIT_CARDS_PROCESSORS;
        $elements = '<span id="methodsCreditCards" class="payment-method-box">';
        foreach ($visaLogos as $logo) {
            try {
                $elements .= sprintf(
                    '<img src="%s" class="mx-1 payment-method-logo" alt="%s">',
                    asset('assets/images/payment_methods/' . $logo . '.png'),
                    strtoupper($logo)
                );
            } catch (\Exception $e) {
            }
        }
        $elements .= '</span>';
        return $elements;
    }

    public static function selectWalletPaymentMethods($countries, $payment)
    {
        $elements = null;
        $customerCountry = $payment->buyer_country;
        try {
            $countries = json_decode($countries, true, 512, JSON_THROW_ON_ERROR);
            $wallets = WalletTool::list();
            $elements = sprintf('<select id="methodsWallet" name="wallet" class="form-control selectpicker" style="text-align-last:center;" title="%s">', __('Choisissez votre wallet'));
            $elements .= sprintf('<option selected disabled value="">%s</option>', __('Choisissez votre wallet'));
            foreach ($wallets as $wallet) {
                if (self::getWalletBy($wallet, $customerCountry, $payment, self::CHANGER)) {
                    try {
                        $elements .= sprintf('<option value="%s">%s</option>', $wallet['paymentMethod'], $wallet['name']);
                    } catch (\Exception $e) {
                    }
                }
            }
            $elements .= '</select>';
        } catch (\JsonException $e) {
        }
        return $elements;
    }

    public static function imagesWalletMethods($countries, $payment)
    {
        $elements = null;
        $customerCountry = $payment->buyer_country;
        try {
            $countries = json_decode($countries, true, 512, JSON_THROW_ON_ERROR);
            $wallets = WalletTool::list();

            $elements = '<span id="methodsWalletLogos" class="payment-method-box">';
            foreach ($wallets as $wallet) {
                if (self::getWalletBy($wallet, $customerCountry, $payment, self::CHANGER)) {
                    try {
                        $elements .= sprintf('<img src="%s" class="mx-1 payment-method-logo" alt="%s-logo">', asset('assets/images/payment_methods/' . $wallet['logo'] . '.png'), strtoupper($wallet['logo']));
                    } catch (\Exception $e) {
                    }
                }
            }
            $elements .= '</span>';
        } catch (\Exception $e) {
        }
        return $elements;
    }

    private static function getWalletBy($wallet, $customerCountry, $payment, $changer)
    {
        if (in_array('ALL', $wallet['country'])) {
            return true;
        }
        return match ($changer) {
            'IP' => ($wallet['status'] && (in_array($customerCountry, $wallet['country']))),
            default => ($wallet['status'] && (in_array($payment->cpm_currency, $wallet['currencies']))),
        };
    }
}
