<?php

namespace App\Tools;

class ColorTool
{
    public static function hexToRgb($color): array
    {
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }
        list($r, $g, $b) = array_map("hexdec", str_split($color, (strlen($color) / 3)));
        return array('red' => (int)$r, 'green' => (int)$g, 'blue' => (int)$b);
    }

    public static function rgbToHex($red, $green, $blue): string
    {
        return sprintf("#%02x%02x%02x", (int)$red, (int)$green, (int)$blue);
    }
}
