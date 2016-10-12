<?php

namespace Tests\Compiling\Statements;

class EvalUsage
{
    /**
     * @return bool
     */
    public function evalUsage()
    {
        eval('some code');

        return true;
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\EvalUsage
----------------------------
[
    {
        "type":"eval_usage",
        "message":"Using eval is discouraged.",
        "file":"EvalUsage.php",
        "line":11
    }
]
