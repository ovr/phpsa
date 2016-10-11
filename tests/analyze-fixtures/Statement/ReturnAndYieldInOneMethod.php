<?php

namespace Tests\Analyze\Fixtures\Statement;

class ReturnAndYieldInOneMethod
{
    public function testReturnAndYield($a = true)
    {
        if ($a) {
            return $a;
        }
        yield $a;
    }

    public function testReturnOnly($a = true)
    {
        if ($a) {
            return $a;
        }
        return !$a;
    }

    public function testYieldOnly($a = true)
    {
        if ($a) {
            yield $a;
        }
    }

    public function testVoid(&$a)
    {
        $a = false;
    }
}
?>
----------------------------
[
    {
        "type": "return_and_yield_in_one_method",
        "message": "Do not use return and yield in a one method",
        "file": "ReturnAndYieldInOneMethod.php",
        "line": 6
    }
]
