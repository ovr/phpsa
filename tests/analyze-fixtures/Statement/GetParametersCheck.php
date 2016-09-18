<?php

namespace Tests\Compiling\Statements;

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
[
    {
        "type":"magic.get.wrong-parameters",
        "message":"Magic method __get must take 1 parameter at least",
        "file":"GetParametersCheck.php",
        "line":24
    },
    {
        "type":"magic.get.wrong-parameters",
        "message":"Magic method __set must take 2 parameters at least",
        "file":"GetParametersCheck.php",
        "line":29
    },
    {
        "type":"magic.get.wrong-parameters",
        "message":"Magic method __clone cannot accept arguments",
        "file":"GetParametersCheck.php",
        "line":34
    }
]
