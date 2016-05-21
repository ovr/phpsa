<?php

namespace Tests\Compiling\Statements;

class ShortArrayDefinition
{
    /**
     * @return array
     */
    public function testAnalyze()
    {
        return array(1);
    }

    /**
     * @return array
     */
    public function testShort()
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
