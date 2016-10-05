<?php

namespace Tests\Analyze\Fixtures\Expression;

final class FinalStaticUsage
{
    public static function test()
    {
        static::test1();
    }

    public static function test1()
    {
    }
}
?>
----------------------------
[
    {
        "type":"error.final-static-usage",
        "message":"Don't use static:: in final class",
        "file":"FinalStaticUsage.php",
        "line":8
    }
]
