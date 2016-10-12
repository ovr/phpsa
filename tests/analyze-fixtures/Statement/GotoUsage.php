<?php

namespace Tests\Analyze\Fixtures\Statement;

class GotoUsage
{
    public function testGoto()
    {
        marker:
        goto marker;
    }

    public function testNoGoto()
    {
        $a = 1;
    }

    public function testLabel()
    {
        marker1:
        $a = 1;
    }

    public function testNoLabel()
    {
        $a = 2;
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Statement\GotoUsage
----------------------------
[
    {
        "type": "goto_usage",
        "message": "Do not use labels",
        "file": "GotoUsage.php",
        "line": 8
    },
    {
        "type": "goto_usage",
        "message": "Do not use goto statements",
        "file": "GotoUsage.php",
        "line": 9
    },
    {
        "type": "goto_usage",
        "message": "Do not use labels",
        "file": "GotoUsage.php",
        "line": 19
    }
]
