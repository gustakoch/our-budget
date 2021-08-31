<?php

namespace App\Utils;

class Utils {
    public function mb_ucfirst($string, $encoding)
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, null, $encoding);

        return mb_strtoupper($firstChar, $encoding) . $then;
    }
}
