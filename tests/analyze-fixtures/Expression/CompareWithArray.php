<?php

namespace Tests\Analyze\Fixtures\Expression;

class CompareWithArray
{
    /**
     * @return array
     */
    public function testErrorSuppression()
    {
        if ([] > 1) {
            return 1;
        }
    }

    /**
     * @return array
     */
    public function testNoErrorSuppression()
    {
        if ([] == 1) {
            return 1;
        }
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\CompareWithArray
----------------------------
[
    {
        "type":"compare_with_array",
        "message":"You are comparing an array. Did you want to use count()?",
        "file":"CompareWithArray.php",
        "line":11
    }
]
