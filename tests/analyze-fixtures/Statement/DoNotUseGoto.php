<?php

namespace Tests\Analyze\Fixtures\Statement;

class DoNotUseGoto
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
[
    {
        "type": "do_not_use_goto",
        "message": "Do not use labels",
        "file": "DoNotUseGoto.php",
        "line": 8
    },
    {
        "type": "do_not_use_goto",
        "message": "Do not use goto statements",
        "file": "DoNotUseGoto.php",
        "line": 9
    },
    {
        "type": "do_not_use_goto",
        "message": "Do not use labels",
        "file": "DoNotUseGoto.php",
        "line": 19
    }
]
