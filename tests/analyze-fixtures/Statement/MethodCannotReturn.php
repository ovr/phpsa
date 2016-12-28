<?php

namespace Tests\Analyze\Fixtures\Statement;

class MethodCannotReturn
{
    public function __construct()
    {
        return 1;
    }

    public function __destruct()
    {
        return 1;
    }
}

class TestNoReturn
{
    public function __construct()
    {
        array_filter([], function ($var) { return false; });
        function TestNoReturnFunctionInConstruct() { return 1; }
    }

    public function __destruct()
    {
        array_filter([], function ($var) { return false; });
        function TestNoReturnFunctionInDestruct() { return 1; }
    }
}

?>
----------------------------
PHPSA\Analyzer\Pass\Statement\MethodCannotReturn
----------------------------
[
    {
        "type":"return.construct",
        "message":"Method __construct cannot return a value.",
        "file":"MethodCannotReturn.php",
        "line":8
    },
    {
        "type":"return.construct",
        "message":"Method __destruct cannot return a value.",
        "file":"MethodCannotReturn.php",
        "line":13
    }
]
