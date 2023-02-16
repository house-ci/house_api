<?php

namespace App\Tools;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\PhpExecutableFinder;
use TitasGailius\Terminal\Terminal;

class CommandTool
{
    public static function run($req, $args, $tag = null)
    {
        LogTool::trace($tag, 'BEGIN - Run Command ');
        $phpBinaryFinder = new PhpExecutableFinder();
        $phpBin = $phpBinaryFinder->find();

        if (empty($phpBin)) {
            $phpBin = config('cinetpay.php_bin');
        }
        //$phpBin = $phpBinaryPath ?? config('cinetpay.php_bin');

        $artisanBin = base_path('artisan');
        $cmd = $phpBin . ' ' . $artisanBin . ' ' . $req . ' ' . $args . ' &';

        LogTool::trace($tag, $cmd);
        try {
            $command = Terminal::command($cmd);
            $command->inBackground();
            $command->run();
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
        }
        LogTool::trace($tag, 'END - Run Command ');
        return true;
    }
}
