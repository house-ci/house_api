<?php

namespace App\Http\Middleware;

use App\Tools\LangTool;
use Closure;
use Illuminate\Http\Request;

class LanguageChecker
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lang = @$request['lang'] ?? LangTool::getCurrentLanguage();
        LangTool::setCurrentLanguage($lang);
        return $next($request);
    }
}
