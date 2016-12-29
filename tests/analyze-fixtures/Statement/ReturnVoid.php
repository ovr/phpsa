<?php

namespace Tests\Analyze\Fixtures\Statement;

class ReturnVoid
{
    public function returnSomething()
    {
        return null;
    }

    public function returnVoid()
    {
        return;
    }
}

?>
----------------------------
PHPSA\Analyzer\Pass\Statement\ReturnVoid
----------------------------
[
    {
        "type":"return.void",
        "message":"You are trying to return void",
        "file":"ReturnVoid.php",
        "line":13
    }
]
