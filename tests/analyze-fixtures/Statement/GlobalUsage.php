<?php

namespace Tests\Analyze\Fixtures\Statement;

$a = 1;

class GlobalUsage
{
    public function testGlobal()
    {
        $a = 4;

        global $a;
    }

    public function testNoGlobal()
    {
        $a = 2;

        return $a * 3;
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Statement\GlobalUsage
----------------------------
[
    {
        "type": "global_usage",
        "message": "Do not use globals",
        "file": "GlobalUsage.php",
        "line": 12
    }
]
