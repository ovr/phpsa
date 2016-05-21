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
?>
----------------------------
[
    {
        "type":"fcall.cast",
        "message":"Please use (int) cast instead of function call.",
        "file":"UseCast.php",
        "line":11
    },
    {
        "type":"fcall.cast",
        "message":"Please use (bool) cast instead of function call.",
        "file":"UseCast.php",
        "line":21
    },
    {
        "type":"fcall.cast",
        "message":"Please use (double) cast instead of function call.",
        "file":"UseCast.php",
        "line":31
    },
    {
        "type":"fcall.cast",
        "message":"Please use (double) cast instead of function call.",
        "file":"UseCast.php",
        "line":41
    },
    {
        "type":"fcall.cast",
        "message":"Please use (string) cast instead of function call.",
        "file":"UseCast.php",
        "line":51
    }
]