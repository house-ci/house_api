<?php

namespace App\Tools;

class TextTool
{

    public static function unEncodeText($text)
    {
        $input = utf8_decode(htmlspecialchars_decode(html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8')));
        return preg_replace_callback("/(&#[0-9]+;)/", function ($m) {
            return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
        }, $input);
    }
}
