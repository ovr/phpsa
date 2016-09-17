<?php

namespace Tests\Compiling\Statements;

class ConstantNaming
{
    const UPPERCASE = 2;
    const Lowercase = 1;
}
?>
----------------------------
[
    {
        "type":"constant.naming",
        "message":"Constant names should be all uppercase.",
        "file":"ConstantNaming.php",
        "line":7
    }
]
