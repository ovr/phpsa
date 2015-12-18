<?php

namespace Tests\Compiling\Statements;

class VarExportUnexpected
{
    /**
     * @return bool
     */
    public function testVarExportSuccess()
    {
        return "test " . var_export(1, true);
    }

    /**
     * @return bool
     */
    public function testVarExportUnexpected()
    {
        /**
         * Second parameter in var_export must be true
         */
        return "test " . var_export(1);
    }
}
