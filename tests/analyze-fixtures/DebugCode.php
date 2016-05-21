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

    /**
     * @return bool
     */
    public function testDebugZvalDumpUnexpected()
    {
        debug_zval_dump(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testDebugZvalDumpExpected()
    {
        /**
         * @expected
         */
        debug_zval_dump(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testDebugZvalDumpWitSimpleComment()
    {
        /**
         * Expected
         */
        debug_zval_dump(1);

        return true;
    }
}

?>
----------------------------
[
    {
        "type":"debug.code",
        "message":"Function var_dump() is a debug code, please don`t use it in production.",
        "file":"DebugCode.php",
        "line":11
    },
    {
        "type":"debug.code",
        "message":"Function var_dump() is a debug code, please don`t use it in production.",
        "file":"DebugCode.php",
        "line":37
    },
    {
        "type":"debug.code",
        "message":"Function var_export() is a debug code, please don`t use it in production.",
        "file":"DebugCode.php",
        "line":47
    },
    {
        "type":"debug.code",
        "message":"Function var_export() is a debug code, please don`t use it in production.",
        "file":"DebugCode.php",
        "line":73
    },
    {
        "type":"debug.code",
        "message":"Function debug_zval_dump() is a debug code, please don`t use it in production.",
        "file":"DebugCode.php",
        "line":83
    },
    {
        "type":"debug.code",
        "message":"Function debug_zval_dump() is a debug code, please don`t use it in production.",
        "file":"DebugCode.php",
        "line":109
    }
]
