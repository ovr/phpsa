<?php

namespace Tests\Analyze\Fixtures\Expression\FunctionCall;

class UnsafeUnserialize
{
    public function testWithoutParam()
    {
        return unserialize("i:1;");
    }

    public function testWithParam()
    {
        return unserialize("i:1;", false);
    }
}

?>
----------------------------
PHPSA\Analyzer\Pass\Expression\FunctionCall\UnsafeUnserialize
----------------------------
[
    {
        "type":"unsafe.unserialize",
        "message":"unserialize() should be used with a list of allowed classes or false as 2nd parameter.",
        "file":"UnsafeUnserialize.php",
        "line":8
    }
]
