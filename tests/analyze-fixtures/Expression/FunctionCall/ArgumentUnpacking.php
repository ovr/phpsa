<?php

namespace Tests\Analyze\Fixtures\Expression\FunctionCall;

class ArgumentUnpacking
{
    /**
     * @return array
     */
    public function testFuncGetArgsEmpty()
    {
        return func_get_args();
    }

    /**
    * @return array
    */
    public function testFuncGetArgsNotEmpty($a)
    {
        return func_get_args();
    }
}

function testFuncGetArgsEmpty()
{
    return func_get_args();
}

function testFuncGetArgsNotEmpty($a)
{
    return func_get_args();
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\FunctionCall\ArgumentUnpacking
----------------------------
[
    {
        "type":"fcall.argumentunpacking",
        "message":"Please use argument unpacking (...) instead of func_get_args().",
        "file":"ArgumentUnpacking.php",
        "line":11
    },
    {
        "type":"fcall.argumentunpacking",
        "message":"Please use argument unpacking (...) instead of func_get_args().",
        "file":"ArgumentUnpacking.php",
        "line":25
    }
]
