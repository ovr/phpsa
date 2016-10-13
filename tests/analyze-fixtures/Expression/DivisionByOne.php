<?php

namespace Tests\Analyze\Fixtures\Expression;

class DivisionByOne
{
    public function testDivisionByOne()
    {
        $x = 2;
        $a = $x / 1;
        $b = $x % 1;
        $x /= 1;
        $x %= 1;
    }

    public function testDivisionByOther()
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
PHPSA\Analyzer\Pass\Expression\DivisionByOne
----------------------------
[
    {
        "type":"division_by_one",
        "message":"You are trying to divide by one",
        "file":"DivisionByOne.php",
        "line":9
    },
    {
        "type":"division_by_one",
        "message":"You are trying to divide by one",
        "file":"DivisionByOne.php",
        "line":10
    },
    {
        "type":"division_by_one",
        "message":"You are trying to divide by one",
        "file":"DivisionByOne.php",
        "line":11
    },
    {
        "type":"division_by_one",
        "message":"You are trying to divide by one",
        "file":"DivisionByOne.php",
        "line":12
    }
]
