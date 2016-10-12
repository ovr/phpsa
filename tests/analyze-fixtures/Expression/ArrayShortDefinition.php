<?php

namespace Tests\Analyze\Fixtures\Expression;

class ArrayShortDefinition
{
    public $a = [1,2];
    public $b = array(2,4);

    /**
     * @return array
     */
    public function testLongDefinition()
    {
        return array(1);
    }

    /**
     * @return array
     */
    public function testShortDefinition()
    {
        return [1];
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\ArrayShortDefinition
----------------------------
[
    {
        "type":"array.short-syntax",
        "message":"Please use [] (short syntax) for array definition.",
        "file":"ArrayShortDefinition.php",
        "line":7
    },
    {
        "type":"array.short-syntax",
        "message":"Please use [] (short syntax) for array definition.",
        "file":"ArrayShortDefinition.php",
        "line":14
    }
]
