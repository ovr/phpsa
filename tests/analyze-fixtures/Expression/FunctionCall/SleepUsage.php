<?php

namespace Tests\Analyze\Fixtures\Expression\FunctionCall;

class SleepUsage
{
    public function testSleep()
    {
        sleep(0);
    }

    public function testOther()
    {
        return uniqid();
    }
}

?>
----------------------------
PHPSA\Analyzer\Pass\Expression\FunctionCall\SleepUsage
----------------------------
[
    {
        "type":"sleep.usage",
        "message":"Function sleep() can cause a denial of service vulnerability.",
        "file":"SleepUsage.php",
        "line":8
    }
]
