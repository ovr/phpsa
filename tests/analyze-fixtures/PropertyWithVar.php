<?php

namespace Tests\Compiling\Statements;

class PropertyWithVar
{
    public $a = 1;
    protected $b = 2;
    private $c = 3;
    var $d = 4;
    var $e = 5, $f = 6;
}
?>
----------------------------
[
    {
        "type":"property.var",
        "message":"Class property was defined with the deprecated var keyword. Use a visibility modifier instead",
        "file":"PropertyWithVar.php",
        "line":9
    },
    {
        "type":"property.var",
        "message":"Class property was defined with the deprecated var keyword. Use a visibility modifier instead",
        "file":"PropertyWithVar.php",
        "line":10
    }
]
