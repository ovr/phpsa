<?php

namespace Tests\Compiling\Statements;

class VarExportUnexpected
{
    /**
     * @return string
     */
    public function testVarExportSuccess()
    {
        return "test " . var_export(1, true);
    }

    /**
     * @return string
     */
    public function testVarExportUnexpected()
    {
        /**
         * Second parameter in var_export must be true
         */
        return "test " . var_export(1);
    }
}
