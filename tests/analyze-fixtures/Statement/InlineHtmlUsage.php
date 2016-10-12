<?php

namespace Tests\Analyze\Fixtures\Statement;

class InlineHtmlUsage
{
    public function testInlineHTML()
    {
        $a = 1;
        ?><p>Test Inline HTML</p><?php
    }

    public function testNoInlineHTML()
    {
        echo "<p>HTML</p>";
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Statement\InlineHtmlUsage
----------------------------
[
    {
        "type": "inline_html_usage",
        "message": "Do not use inline HTML",
        "file": "InlineHtmlUsage.php",
        "line": 9
    }
]
