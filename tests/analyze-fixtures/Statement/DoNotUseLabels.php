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
        "line": 7
    },
    {
        "type": "do_not_use_labels",
        "message": "Do not use labels",
        "file": "DoNotUseLabels.php",
        "line": 10
    },
    {
        "type": "do_not_use_labels",
        "message": "Do not use labels",
        "file": "DoNotUseLabels.php",
        "line": 12
    },
    {
        "type": "do_not_use_goto",
        "message": "Do not use goto statements",
        "file": "DoNotUseLabels.php",
        "line": 13
    }
]