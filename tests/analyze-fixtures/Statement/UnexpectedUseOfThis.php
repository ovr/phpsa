<?php

namespace Tests\Compiling\Statements;

class UnexpectedUseOfThis
{
    /**
     * @return string
     */
    public function thisAsArgument($this)
    {
        return 'Cannot use $this as parameter';
    }

    /**
     * @return string
     */
    public function thisInCatch()
    {
        try {
            throw new \LogicException();
        } catch (\Exception $this) {
            return 'Fatal error: Cannot re-assign $this';
        }
    }

    /**
     * @return string
     */
    public function thisAsLoopVariable()
    {
        foreach (['foo'] as $this) {
            return 'Fatal error: Cannot re-assign $this';
        }
    }

    /**
     * @return string
     */
    public function thisAsStaticVariable()
    {
        static $this;

        return 'Fatal error: Cannot use $this as static variable';
    }

    /**
     * @return string
     */
    public function thisAsGlobalVariable()
    {
        global $this;

        return 'Fatal error: Cannot use $this as global variable';
    }

    /**
     * @return string
     */
    public function unsetThis()
    {
        unset($this);

        return 'Fatal error: Cannot unset $this';
    }
}
?>
----------------------------
[
    {
        "type":"unexpected_use.this",
        "message":"Method thisAsArgument can not have a parameter named \"this\".",
        "file":"UnexpectedUseOfThis.php",
        "line":9
    },
    {
        "type":"unexpected_use.this",
        "message":"Catch block can not have a catch variable named \"this\".",
        "file":"UnexpectedUseOfThis.php",
        "line":21
    },
    {
        "type":"unexpected_use.this",
        "message":"Foreach loop can not use a value variable named \"this\".",
        "file":"UnexpectedUseOfThis.php",
        "line":31
    },
    {
        "type":"unexpected_use.this",
        "message":"Can not declare a static variable named \"this\".",
        "file":"UnexpectedUseOfThis.php",
        "line":41
    },
    {
        "type":"unexpected_use.this",
        "message":"Can not declare a global variable named \"this\".",
        "file":"UnexpectedUseOfThis.php",
        "line":51
    },
    {
        "type":"unexpected_use.this",
        "message":"Can not unset $this.",
        "file":"UnexpectedUseOfThis.php",
        "line":61
    }
]
