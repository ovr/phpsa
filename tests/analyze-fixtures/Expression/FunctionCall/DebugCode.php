<?php

namespace Tests\Analyze\Fixtures\Expression\FunctionCall;

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
    public function testVarDumpWithSimpleComment()
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
    public function testVarExportUnexpected()
    {
        var_export(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testVarExportExpected()
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
    public function testVarExportWithSimpleComment()
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
    public function testDebugZvalDumpWithSimpleComment()
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
PHPSA\Analyzer\Pass\Expression\FunctionCall\DebugCode
----------------------------
[
    {
        "type":"debug.code",
        "message":"Function var_dump() is a debug function, please don`t use it in production.",
        "file":"DebugCode.php",
        "line":11
    },
    {
        "type":"debug.code",
        "message":"Function var_dump() is a debug function, please don`t use it in production.",
        "file":"DebugCode.php",
        "line":37
    },
    {
        "type":"debug.code",
        "message":"Function var_export() is a debug function, please don`t use it in production.",
        "file":"DebugCode.php",
        "line":47
    },
    {
        "type":"debug.code",
        "message":"Function var_export() is a debug function, please don`t use it in production.",
        "file":"DebugCode.php",
        "line":73
    },
    {
        "type":"debug.code",
        "message":"Function debug_zval_dump() is a debug function, please don`t use it in production.",
        "file":"DebugCode.php",
        "line":83
    },
    {
        "type":"debug.code",
        "message":"Function debug_zval_dump() is a debug function, please don`t use it in production.",
        "file":"DebugCode.php",
        "line":109
    }
]
