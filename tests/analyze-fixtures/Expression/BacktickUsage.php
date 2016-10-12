<?php

namespace Tests\Compiling\Statements;

class BacktickUsage
{
    public function backtickUsage()
    {
        $output = `ls -al`;
    }

    public function noBacktickUsage()
    {
        $output = 5;
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\BacktickUsage
----------------------------
[
    {
        "type":"backtick_usage",
        "message":"It's bad practice to use the backtick operator for shell execution",
        "file":"BacktickUsage.php",
        "line":8
    }
]
