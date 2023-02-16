<?php

namespace App\Tools;

use Illuminate\Support\Facades\Log;

class LogTool
{
    public static function trace(string $tag, string $message, string $level = 'info', array $context = [], $operation = 'operation'): void
    {
        try {
            Log::channel($operation)->log($level, $tag . ' ' . $message, $context);
        } catch (\Exception $e) {
        }
    }
}
