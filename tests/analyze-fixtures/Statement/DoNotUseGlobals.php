<?php

namespace Tests\Analyze\Fixtures\Statement;

$a = 1;

/**
 */
class DoNotUseGlobals
{

    /**
     * test global
     */
    public function testGlobal()
    {
        $a = 4;

        global $a;
    }

    /**
     * test no global
     */
    public function testNoGlobal()
    {
        $a = 2;

        return $a * 3;
    }

}

?>
----------------------------
[{
    "type": "do_not_use_globals",
    "message": "Do not use globals",
    "file": "DoNotUseGlobals.php",
    "line": 18
}]
