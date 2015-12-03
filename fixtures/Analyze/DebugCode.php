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

    /**
     * @return bool
     */
    public function testVarDumpWitSimpleComment()
    {
        /**
         * Expected
         */
        var_dump(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testVarExporUnexpected()
    {
        var_export(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testVarExporExpected()
    {
        /**
         * @expected
         */
        var_export(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testVarExporWitSimpleComment()
    {
        /**
         * Expected
         */
        var_export(1);

        return true;
    }
}
