<?php

namespace Tests\Compiling\Statements;

class DebugCode
{
    /**
     * @return bool
     */
    public function testVarDumpUnexpected()
    {
        var_dump(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testVarDumpExpected()
    {
        /**
         * @expected
         */
        var_dump(1);

        return true;
    }
}
