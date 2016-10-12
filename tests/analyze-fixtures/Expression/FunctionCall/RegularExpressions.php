<?php

namespace Tests\Analyze\Fixtures\Expression\FunctionCall;

class RegularExpressions
{
    public function testRegExInvalid()
    {
        return preg_match("[","abc");
    }

    public function testRegExValid()
    {
        return preg_match("/a/","abc");
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\FunctionCall\RegularExpressions
----------------------------
[
    {
        "type":"regex.invalid",
        "message":"Regular expression [ is not valid",
        "file":"RegularExpressions.php",
        "line":8
    }
]
