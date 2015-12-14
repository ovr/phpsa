<?php

namespace Tests\Compiling\Statements;

class DebugCode
{
    /**
     * @return bool
     */
    public function testIntVal()
    {
        intval(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testBoolVal()
    {
        boolval(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testFloatVal()
    {
        floatval(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testDoubleVal()
    {
        doubleval(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testStrVal()
    {
        strval(1);

        return true;
    }
}
