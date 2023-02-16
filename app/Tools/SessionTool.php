<?php

namespace App\Tools;

use Illuminate\Support\Facades\Cache;

class SessionTool
{
    public static function getError($input = 'otp', $bag = 'default')
    {
        $error = session('errors');
        return $error?->getBag($bag)?->first($input);
    }

    public static function getCustomerBrowserCookies($request)
    {
        if (!empty($_COOKIE['__cpuid'])) {
            $value = $_COOKIE['__cpuid'];
        } else {
            $date_of_expiry = time() + (60 * 60 * 24 * 60);
            try {
                $value = random_int(100, 999) . md5(time()) . time() . random_int(100, 999);
                setcookie('__cpuid', $value, $date_of_expiry, '/', '', true, true);
            } catch (\Exception $e) {
            }
        }
        /*
        $value = $request->cookie('__cpuid');
        if (empty($value)) {
            try {
                $value = random_int(100, 999) . md5(time()) . random_int(100, 999);
                cookie('__cpuid', $value, 518400);
            } catch (\Exception $e) {
            }
        }
        */
        return $value ?? null;
    }


    public static function get($token, $defaultValue = 'token')
    {
        try {
            $response = session($token);
            if (empty($response)) {
                $value = @$_REQUEST[$defaultValue];
                if (!empty($value)) {
                    $response = Cache::get($value);
                }
            }
        } catch (\Exception $e) {
            $response = null;
        }
        return $response;
    }

    public static function getElement($elementName)
    {
        try {
            $response = session($elementName);
            if (empty($response)) {
                $token = @$_REQUEST['token'];
                if (!empty($elementName)) {
                    $response = Cache::get($token . $elementName);
                }
            }
        } catch (\Exception $e) {
            $response = null;
        }
        return $response;
    }

    public static function setElement($value, $name = 'token')
    {
        try {
            session([$name => $value]);
            if (!empty($_REQUEST['token'])) {
                Cache::remember($_REQUEST['token'] . $name, 300, function () use ($value) {
                    return $value;
                });
            }
        } catch (\Exception $e) {
        }
    }
}
