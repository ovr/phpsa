<?php

namespace Tests\Analyze\Fixtures\Statement;

class DoNotUseLabels
{
    public function testLabel()
    {
        marker1:
        $a = 1;

        marker2:

        marker3:
        goto marker1;

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
        "type": "do_not_use_labels",
        "message": "Do not use labels",
        "file": "DoNotUseLabels.php",
        "line": 8
    },
    {
        "type": "do_not_use_labels",
        "message": "Do not use labels",
        "file": "DoNotUseLabels.php",
        "line": 11
    },
    {
        "type": "do_not_use_labels",
        "message": "Do not use labels",
        "file": "DoNotUseLabels.php",
        "line": 13
    },
    {
        "type": "do_not_use_goto",
        "message": "Do not use goto statements",
        "file": "DoNotUseLabels.php",
        "line": 14
    }
]