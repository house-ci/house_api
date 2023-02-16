<?php

namespace App\Tools;

use App\Models\IpAddress;
use ipinfo\ipinfo\IPinfo;

class IpTool
{
    public static function getCountryByIP($ip)
    {
        try {
            if ($ip === '127.0.0.1') {
                return config('cinetpay.default_country');
            }
            $country = IpAddress::getCountry($ip);
            if (!empty($country)) {
                return $country;
            }
            $access_token = config('services.ipinfo.access_token');
            $settings = [
                'cache_maxsize' => config('services.ipinfo.cache_maxsize'),
                'cache_ttl' => config('services.ipinfo.cache_ttl')
            ];
            $client = new IPinfo($access_token, $settings);
            $details = $client->getDetails($ip);
            $country = ($details->all)['country'];
            IpAddress::add($ip, $country, $details->all);
            return $country;
        } catch (\Exception $e) {
            return config('cinetpay.default_country');
        }
    }
}
