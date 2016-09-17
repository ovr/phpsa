<?php

namespace Tests\Compiling\Statements;

class ConstantNaming
{
    const Lowercase = 1;
    const UPPERCASE = 2;
}
?>
----------------------------
[
    {
        "type":"constant.naming",
        "message":"Constant names should be all uppercase.",
        "file":"ConstantNaming.php",
        "line":6
    }
]
