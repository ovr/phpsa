<?php

namespace Tests\Analyze\Fixtures\Expression;

class DivisionFromZero
{
    public function testDivisionFromZero()
    {
        $x = 0;
        $a = $x / 2;
        $b = $x % 2;
        $x /= 2;
        $x %= 2;
    }

    public function testDivisionFromOther()
    {
        $x = 16;
        $a = $x / 2;
        $b = $x % 2;
        $x /= 2;
        $x %= 2;
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\DivisionFromZero
----------------------------
[
    {
        "type":"division_from_zero",
        "message":"You are trying to divide from zero",
        "file":"DivisionFromZero.php",
        "line":9
    },
    {
        "type":"division_from_zero",
        "message":"You are trying to divide from zero",
        "file":"DivisionFromZero.php",
        "line":10
    },
    {
        "type":"division_from_zero",
        "message":"You are trying to divide from zero",
        "file":"DivisionFromZero.php",
        "line":11
    },
    {
        "type":"division_from_zero",
        "message":"You are trying to divide from zero",
        "file":"DivisionFromZero.php",
        "line":12
    }
]
