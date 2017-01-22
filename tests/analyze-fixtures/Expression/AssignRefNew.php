<?php

namespace Tests\Analyze\Fixtures\Expression;

class AssignRefNew
{
    public function testAssignRefNew()
    {
        $a = & new \stdClass();

        return $a;
    }
}

?>
----------------------------
\PHPSA\Analyzer\Pass\Expression\AssignRefNew
----------------------------
[
    {
        "type":"assign_ref_new",
        "message":"Do not use = & new, all objects in PHP are passed by reference",
        "file":"AssignRefNew.php",
        "line": 8
    }
]
