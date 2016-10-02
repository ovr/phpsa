<?php

namespace Tests\Analyze\Fixtures\Statement;

class DoNotUseGoto {
    public function testGoto()
    {
        marker:
        goto marker;
    }

    public function testNoGoto()
    {

    }
}

?>
----------------------------
[
    {
        "type": "do_not_use_goto",
        "message": "Do not use goto statements",
        "file": "DoNotUseGoto.php",
        "line": 8
    }
]
