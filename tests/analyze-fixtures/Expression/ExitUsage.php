<?php

namespace Tests\Analyze\Fixtures\Expression;

class ExitUsage
{
    /**
     * @return void
     */
    public function exitUsageShouldBeDetected()
    {
        exit('stop');
    }

    /**
     * @return void
     */
    public function dieUsageShouldBeDetected()
    {
        die('stop');
    }

    /**
     * @return array
     */
    public function everythingIsOkayHere()
    {
        return [
            0 => 42,
            1 => 43,
        ];
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\ExitUsage
----------------------------
[
    {
        "type":"exit_usage",
        "message":"exit/die statements make the code hard to test and should not be used",
        "file":"ExitUsage.php",
        "line":11
    },
    {
        "type":"exit_usage",
        "message":"exit/die statements make the code hard to test and should not be used",
        "file":"ExitUsage.php",
        "line":19
    }
]
