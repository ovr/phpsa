<?php

namespace Tests\Analyze\Fixtures\Statement;

class OldConstructorUsed
{
    public function OldConstructorUsed()
    {
        $a = 1;
    }
}

class NewConstructorUsed
{
	public function __construct()
	{
		$a = 1;
	}
}

?>
----------------------------
PHPSA\Analyzer\Pass\Statement\OldConstructor
----------------------------
[
    {
        "type":"deprecated.constructor",
        "message":"Class OldConstructorUsed uses a PHP4 constructor.",
        "file":"OldConstructor.php",
        "line":4
    }
]
