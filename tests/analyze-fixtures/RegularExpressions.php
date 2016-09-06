<?php

namespace Tests\Compiling\Statements;

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
[
    {
        "type":"regex.invalid",
        "message":"Regular expression [ is not valid",
        "file":"RegularExpressions.php",
        "line":8
    }
]
