<?php

namespace Tests\Analyze\Fixtures\Expression\FunctionCall;

class UseCast
{
    /**
     * @return bool
     */
    public function testIntval()
    {
        intval(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testBoolval()
    {
        boolval(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testFloatval()
    {
        floatval(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testDoubleval()
    {
        doubleval(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testStrval()
    {
        strval(1);

        return true;
    }

    /**
     * @return bool
     */
    public function testIntvalTwoArguments()
    {
        intval(7,2);

        return true;
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\FunctionCall\UseCast
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
