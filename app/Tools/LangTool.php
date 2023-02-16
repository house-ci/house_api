<?php

namespace App\Tools;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LangTool
{
    const DEFAULT_LANGUAGE = 'fr';
    const AVAILABLE_LANGUAGES = ['fr', 'en'];

    public static function getDefaultLanguage()
    {
        return config('app.lang', self::DEFAULT_LANGUAGE);
    }

    public static function getAvailableLanguages()
    {
        return config('app.available_languages', self::AVAILABLE_LANGUAGES);
    }

    public static function getCurrentLanguage()
    {
        return strtolower(Session::get('local') ?? App::getLocale());
    }

    public static function setCurrentLanguage($lang)
    {
        $lang = strtolower($lang);
        if (!in_array($lang, config('app.available_languages', self::AVAILABLE_LANGUAGES))) {
            $lang = self::getDefaultLanguage();
        }
        App::setLocale($lang);
        Session::put('local', $lang);
        return self::getCurrentLanguage();
    }

    public static function getOppositeUrl($local)
    {
        $local = strtolower($local);
        if ($local === 'fr') {
            return '?lang=en';
        }
        return '?lang=fr';
    }

}
