<?php

namespace App\Tools;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ThrowableTool
{
    /**
     * @throws Exception
     */
    public static function check($condition, $errorMessage)
    {
        if ($condition) {
            $trace = debug_backtrace()[1];
            $function = ucfirst($trace['function']);
            $classTrace = explode('\\', $trace['class']) ;
            $class = end($classTrace);
            $tag = sprintf('[%s][%s]', session()->getId(), $class . '@' . $function);
            LogTool::trace($tag, $errorMessage);
            throw new Exception($errorMessage);
        }
    }


    public static function view($tag, $errorMessage = 'Votre session de paiement a expiré', $title = 'Accès interdit.'): Factory|View|Application
    {
        LogTool::trace($tag, $errorMessage);
        LogTool::trace($tag, '-- FIN --');
        return view('errors.generic_error')->with([
            'message' => $title,
            'description' => $errorMessage
        ]);
    }

    public static function errorMessage($message)
    {
        return strlen($message) < 100 ? $message : __("Une erreur est survenue");
    }
}
