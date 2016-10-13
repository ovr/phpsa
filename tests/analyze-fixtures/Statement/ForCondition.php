<?php

namespace Tests\Analyze\Fixtures\Statement;

class ForCondition
{
    public function testMultipleConditions()
    {
        for ($i=0,$j=0; $i<3,$j<2; $i++,$j++) {
            echo "test"; // 2 times
        }
    }

    public function testNormal()
    {
        for ($i=0; $i<3; $i++) {
            echo "test"; // 1 time
        }
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Statement\ForCondition
----------------------------
[
    {
        "type": "for_condition",
        "message": "You should merge the conditions into one with &&",
        "file": "ForCondition.php",
        "line": 8
    }
]
