<?php

namespace Tests\Compiling\Statements;

class ArgumentUnpacking
{
    /**
     * @return array
     */
    public function testFuncGetArgsEmpty()
    {
        return func_get_args();
    }
}
?>
----------------------------
[
    {
        "type":"fcall.argumentunpacking",
        "message":"Please use argument unpacking (...) instead of func_get_args().",
        "file":"ArgumentUnpacking.php",
        "line":11
    }
]
