<?php

namespace Tests\Analyze\Fixtures\Expression;

class StupidUnaryOperators
{
    public function unary_plus()
    {
        $a = +1;
        $a = +1.0;
        $a = +true;
    }

    public function inc()
    {
        $a = 1;
        ++$a;
        $a++;
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\StupidUnaryOperators
----------------------------
[
    {
        "type":"stupid_unary_operators",
        "message":"Better to use type casting then unary plus.",
        "file":"StupidUnaryOperators.php",
        "line":8
    },
    {
        "type":"stupid_unary_operators",
        "message":"Better to use type casting then unary plus.",
        "file":"StupidUnaryOperators.php",
        "line":9
    },
    {
        "type":"stupid_unary_operators",
        "message":"Better to use type casting then unary plus.",
        "file":"StupidUnaryOperators.php",
        "line":10
    }
]
