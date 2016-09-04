<?php

namespace Tests\Simple\CodeSmell;

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
