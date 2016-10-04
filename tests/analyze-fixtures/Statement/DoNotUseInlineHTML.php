<?php

namespace Tests\Analyze\Fixtures\Statement;

class DoNotUseInlineHTML {
    public function testInlineHTML()
    {
        $a = 1
        ?><p><?= $a ?></p><?php
    }

    public function testNoInlineHTML()
    {

    }

}

?>
----------------------------
[
    {
        "type": "do_not_use_inline_html",
        "message": "Do not use inline HTML",
        "file": "DoNotUseInlineHTML.php",
        "line": 8
    }
]
