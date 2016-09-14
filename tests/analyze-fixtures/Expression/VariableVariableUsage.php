<?php

namespace Tests\Compiling\Statements;

class VariableVariableUsage
{
    /**
     * @return string
     */
    public function method()
    {
        $varName = 'name';

        ${$varName} = 'foo';

        return $name;
    }
}
?>
----------------------------
[
    {
        "type": "variable.dynamic_assignment",
        "message": "Dynamic assignment is greatly discouraged.",
        "file": "VariableVariableUsage.php",
        "line": 13
    }
]
