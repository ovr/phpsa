<?php

namespace Tests\Compiling\Statements;

class AliasCheck
{
    /**
     * @return bool
     */
    public function testJoing()
    {
        return join('-', [1, 2]);
    }
}

?>
----------------------------
[{
"type": "fcall.alias",
"message": "join() is an alias of function. Use implode(...).",
"file": "AliasCheck.php",
"line": 11
}]