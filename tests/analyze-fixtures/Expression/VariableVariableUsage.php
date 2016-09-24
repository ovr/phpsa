<?php

namespace Tests\Analyze\Fixtures\Expression;

class VariableVariableUsage
{
    /**
     * @return string
     */
    public function usingVariablesIsOk()
    {
        $var = '42';

        return $var;
    }

    /**
     * @return string
     */
    public function simpleVariableVariablesAreDiscouraged()
    {
        $varName = 'name';

        ${$varName} = 'foo';

        return $name;
    }

    /**
     * @return array
     */
    public function arrayAssignmentIsOk()
    {
        $array = [];
        $array[] = 'bar';

        return $array;
    }

    /**
     * @return string
     */
    public function variableVariableInArrayAssignmentIsDiscouraged()
    {
        $array = [];
        $varName = 'array';

        ${$varName}[] = 'foo';

        return $varName;
    }

    /**
     * @return string
     */
    public function listStructureIsOk()
    {
        list($foo, ) = [1, 2];

        return $foo;
    }

    /**
     * @return string
     */
    public function variableVariableInListAssignmentIsDiscouraged()
    {
        $a = 'foo';
        list(${$a}, $b) = [1, 2];

        return $foo;
    }

    /**
     * @return void
     */
    public function propertyAccessIsOk()
    {
        $this->name = 'foo';
    }

    /**
     * @return void
     */
    public function variablePropertyAccessIsDiscouraged()
    {
        $varName = 'name';

        $this->{$varName} = 'foo';
    }

    /**
     * @return void
     */
    public function arrayPropertyAccessIsOk()
    {
        $this->names[] = 'foo';
    }

    /**
     * @return void
     */
    public function variableArrayPropertyAccessIsDiscouraged()
    {
        $varName = 'name';

        $this->{$varName}[] = 'foo';
    }
}
?>
----------------------------
[
    {
        "type": "variable.dynamic_assignment",
        "message": "Dynamic assignment is greatly discouraged.",
        "file": "VariableVariableUsage.php",
        "line": 23
    },
    {
        "type": "variable.dynamic_assignment",
        "message": "Dynamic assignment is greatly discouraged.",
        "file": "VariableVariableUsage.php",
        "line": 47
    },
    {
        "type": "variable.dynamic_assignment",
        "message": "Dynamic assignment is greatly discouraged.",
        "file": "VariableVariableUsage.php",
        "line": 68
    },
    {
        "type": "variable.dynamic_assignment",
        "message": "Dynamic assignment is greatly discouraged.",
        "file": "VariableVariableUsage.php",
        "line": 88
    },
    {
        "type": "variable.dynamic_assignment",
        "message": "Dynamic assignment is greatly discouraged.",
        "file": "VariableVariableUsage.php",
        "line": 106
    }
]
