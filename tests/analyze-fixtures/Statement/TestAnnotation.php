<?php

namespace Tests\Analyze\Fixtures\Statement;

class TestAnnotation
{
    public function testNoTag()
    {
        return 1;
    }

    /**
     * @test
     */
    public function withTestTag()
    {
        return 2;
    }

    /**
     * @test
     */
    public function testWithTag()
    {
        return 3;
    }

    public function noDocBlock()
    {
        return 4;
    }

    /**
     * @return int
     */
    public function testOtherTag()
    {
        return 5;
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Statement\TestAnnotation
----------------------------
[
    {
        "type":"test.annotation",
        "message":"Annotation @test is not needed when the method is prefixed with test.",
        "file":"TestAnnotation.php",
        "line":22
    }
]
