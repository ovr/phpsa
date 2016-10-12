<?php

namespace Tests\Analyze\Fixtures\Statement;

class CorrectMagicMethods
{
    public function __set($a, $b)
    {
        return 1;
    }

    public function __get($a)
    {
        return 1;
    }

    public function __clone()
    {
        return 1;
    }
}

class WrongMagicMethods
{
	public function __get()
	{
		return 1;
	}

    public function __set($a)
    {
        return 1;
    }

    public function __clone($a)
    {
        return 1;
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Statement\MagicMethodParameters
----------------------------
[
    {
        "type":"magic_method_parameters",
        "message":"Magic method __get must take 1 parameter at least",
        "file":"MagicMethodParameters.php",
        "line":24
    },
    {
        "type":"magic_method_parameters",
        "message":"Magic method __set must take 2 parameters at least",
        "file":"MagicMethodParameters.php",
        "line":29
    },
    {
        "type":"magic_method_parameters",
        "message":"Magic method __clone cannot accept arguments",
        "file":"MagicMethodParameters.php",
        "line":34
    }
]
