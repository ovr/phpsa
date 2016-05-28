<?php

namespace Tests\Compiling\Statements;

class ShortArrayDefinition
{
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
[
    {
        "type":"array.short-syntax",
        "message":"Please use [] (short syntax) for array definition.",
        "file":"ShortArrayDefinition.php",
        "line":11
    }
]
