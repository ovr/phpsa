<?php

namespace Tests\Analyze\Fixtures\Expression;

class ErrorSuppression
{
    /**
     * @return array
     */
    public function testErrorSuppression()
    {
        return @in_array(1,[2,3]);
    }

    /**
     * @return array
     */
    public function testNoErrorSuppression()
    {
        return in_array(1,[2,3]);
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\ErrorSuppression
----------------------------
[
    {
        "type":"error.suppression",
        "message":"Please don't suppress errors with the @ operator.",
        "file":"ErrorSuppression.php",
        "line":11
    }
]
