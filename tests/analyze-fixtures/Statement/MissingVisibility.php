<?php

namespace Tests\Compiling\Statements;

class PropertyWithVar
{
    public $a = 1;
    protected $b = 2;
    private $c = 3;
    var $d = 4;
    var $e = 5, $f = 6;

    public function abc() {
        return 1;
    }

    function def() {
        return 2;
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Statement\MissingVisibility
----------------------------
[
    {
        "type":"missing_visibility",
        "message":"Class property was defined with the deprecated var keyword. Use a visibility modifier instead.",
        "file":"MissingVisibility.php",
        "line":9
    },
    {
        "type":"missing_visibility",
        "message":"Class property was defined with the deprecated var keyword. Use a visibility modifier instead.",
        "file":"MissingVisibility.php",
        "line":10
    },
    {
        "type":"missing_visibility",
        "message":"Class method was defined without a visibility modifier.",
        "file":"MissingVisibility.php",
        "line":16
    }
]
