<?php

namespace App\Tools;

class DeviceTool
{
    const MOBILE_USER_AGENTS = ['iphone', 'ipod', 'ipad', 'android', 'blackberry', 'webos', 'Mobile', 'wv'];

    public static function isClientMobileDevice($requestUserAgent = null)
    {
        $isMobile = false;
        $pregMachInsensitiveCase = '/%s/i';
        foreach (self::MOBILE_USER_AGENTS as $MobileUserAgent) {
            if (preg_match(sprintf($pregMachInsensitiveCase, $MobileUserAgent), $requestUserAgent ?? $_SERVER['HTTP_USER_AGENT'])) {
                $isMobile = true;
            }
        }
        $isWebView = false;
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $isWebView = true;
        }
        return ($isMobile || $isWebView);
    }
}
