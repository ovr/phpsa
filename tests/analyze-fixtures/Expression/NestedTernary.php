<?php

namespace Tests\Analyze\Fixtures\Expression;

class ExitUsage
{
    public function nestedTernary()
    {
        $a = 1;
        return ($a == 2) ? "1" : (($a == 1) ? "2" : "3");
    }

    public function normalTernary()
    {
        $a = 1;
        return ($a == 1) ? "1" : "2";
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\NestedTernary
----------------------------
[
    {
        "type":"nested_ternary",
        "message":"Nested ternaries are confusing you should use if instead.",
        "file":"NestedTernary.php",
        "line":9
    }
]
