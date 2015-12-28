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
        $a = "test " . var_export(1);
        return $a;
    }

    /**
     * @return string
     */
    public function testVarExportUnexpectedWithReturn()
    {
        return var_export(1);
    }
}
