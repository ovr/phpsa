<?php
/**
 * @author Leonardo da Mata https://github.com/barroca <barroca@gmail.com>
 */
namespace Tests\Analyze\Fixtures\Statement;

class TestMultipleProperties 
{
    public $a, $b = 1;
}

class TestSingleProperty
{
	public $a = 1;
}

?>
----------------------------
PHPSA\Analyzer\Pass\Statement\HasMoreThanOneProperty
----------------------------
[
    {
        "type":"limit.properties",
        "message":"Number of properties larger than one.",
        "file":"TestMultipleProperties.php",
        "line":8
    }
]
