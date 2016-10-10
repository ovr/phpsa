<?php

namespace Tests\Analyze\Fixtures\Statement;

class FixedCondition
{

    public function testInfiniteLoop()
    {
        $condition = new \stdClass;

        while ($condition) {
            return;
        }
    }


    public function testInfiniteDoLoop()
    {
        do {
            return;
        } while (1);
    }


    public function testFixedIf()
    {
        if (5 - 5) {
            return;
        }
    }


    public function testFixedElseIf()
    {
        if ($this->dynamicValue()) {
            return;
        } elseif ('foo' == 'bar') {
            return;
        }
    }


    public function testFixedElseSpaceIf()
    {
        if ($this->dynamicValue()) {
            return;
        } else if (0.123) {
            return;
        }
    }


    public function testFixedSwitch()
    {
        switch (13) {
            case 10:
                return;

            case 20:
                return;
        }
    }


    public function testDynamicIf()
    {
        if ($this->dynamicValue()) {
            return;
        }
    }


    private function dynamicValue()
    {
        return phpversion();
    }
}

?>
----------------------------
[
    {
        "type": "fixed_condition",
        "message": "The condition will always result in the same boolean value",
        "file": "FixedCondition.php",
        "line": 11
    },
    {
        "type": "fixed_condition",
        "message": "The condition will always result in the same boolean value",
        "file": "FixedCondition.php",
        "line": 19
    },
    {
        "type": "fixed_condition",
        "message": "The condition will always result in the same boolean value",
        "file": "FixedCondition.php",
        "line": 27
    },
    {
        "type": "fixed_condition",
        "message": "The condition will always result in the same boolean value",
        "file": "FixedCondition.php",
        "line": 37
    },
    {
        "type": "fixed_condition",
        "message": "The condition will always result in the same boolean value",
        "file": "FixedCondition.php",
        "line": 47
    },
    {
        "type": "fixed_condition",
        "message": "The condition will always result in the same boolean value",
        "file": "FixedCondition.php",
        "line": 55
    }
]
