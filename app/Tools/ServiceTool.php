<?php

namespace App\Tools;

class ServiceTool
{
    const SERVICE_COUNTRIES_RESTRICTION = [
        '1034' => ['mlh']
    ];

    public static function getServicesRestrictedCountries($siteId)
    {
        return @self::SERVICE_COUNTRIES_RESTRICTION[$siteId] ?? [];
    }
}
