<?php

namespace Tests\Compiling\Scalar;

class CheckLNumberKind
{
    public function badLNumberKind()
    {
        $hex = 0x76;
        $oct = 076;
    }

    public function goodLNumberKind()
    {
        $output = 5;
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Scalar\CheckLNumberKind
----------------------------
[
    {
        "type":"l_number_kind",
        "message":"Avoid using octal, hexadecimal or binary",
        "file":"CheckLNumberKind.php",
        "line":8
    },
    {
        "type":"l_number_kind",
        "message":"Avoid using octal, hexadecimal or binary",
        "file":"CheckLNumberKind.php",
        "line":9
    }
]
