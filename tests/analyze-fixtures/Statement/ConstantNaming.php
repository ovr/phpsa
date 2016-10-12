<?php

namespace Tests\Compiling\Statements;

class ConstantNaming
{
    const UPPERCASE = 2;
    const Lowercase = 1;
    const Foo = 1, bar = 2;
    const ABC = 1, Def = 2;
    const Ghi = 1, JKL = 2;
    const MNO = 1, QRS = 2;
}
?>
----------------------------
PHPSA\Analyzer\Pass\Statement\ConstantNaming
----------------------------
[
    {
        "type":"constant.naming",
        "message":"Constant names should be all uppercase.",
        "file":"ConstantNaming.php",
        "line":7
    },
    {
        "type":"constant.naming",
        "message":"Constant names should be all uppercase.",
        "file":"ConstantNaming.php",
        "line":8
    },
    {
        "type":"constant.naming",
        "message":"Constant names should be all uppercase.",
        "file":"ConstantNaming.php",
        "line":9
    },
    {
        "type":"constant.naming",
        "message":"Constant names should be all uppercase.",
        "file":"ConstantNaming.php",
        "line":10
    }
]
