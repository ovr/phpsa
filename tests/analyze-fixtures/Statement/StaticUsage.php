<?php

namespace Tests\Analyze\Fixtures\Statement;

$a = 1;

class StaticUsage
{
    public function testStatic()
    {
        $a = 4;

        static $a;
    }

    public function testNoStatic()
    {
        $a = 2;

        return $a * 3;
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Statement\StaticUsage
----------------------------
[
    {
        "type": "static_usage",
        "message": "Do not use static variable scoping",
        "file": "StaticUsage.php",
        "line": 12
    }
]
