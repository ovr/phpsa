<?php

namespace Tests\Analyze\Fixtures\Expression;

class Casts
{
    public function testIntToInt()
    {
        $a = 3;
        return (int) $a;
    }

    public function testStrToInt()
    {
        $a = "a";
        return (int) $a;
    }

    public function testNullToUnset()
    {
        $a = null;
        return (unset) $a;
    }

    public function testIntToUnset()
    {
        $a = 1;
        return (unset) $a;
    }

    public function testArrToArr()
    {
        $a = [1];
        return (array) $a;
    }

    public function testIntToArr()
    {
        $a = 1;
        return (array) $a;
    }

    public function testBoolToBool()
    {
        $a = true;
        return (bool) $a;
    }

    public function testIntToBool()
    {
        $a = 1;
        return (bool) $a;
    }

    public function testDoubleToDouble()
    {
        $a = 1.3;
        return (double) $a;
    }

    public function testIntToDouble()
    {
        $a = 1;
        return (double) $a;
    }

    public function testStringToString()
    {
        $a = "a";
        return (string) $a;
    }

    public function testIntToString()
    {
        $a = 1;
        return (string) $a;
    }

    public function testObjectToObject()
    {
        $a = $this;
        return (object) $a;
    }

    public function testIntToObject()
    {
        $a = 1;
        return (object) $a;
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\Casts
----------------------------
[
    {
        "type":"stupid.cast",
        "message":"You are trying to cast 'integer' to 'integer'",
        "file":"Casts.php",
        "line":9
    },
    {
        "type":"stupid.cast",
        "message":"You are trying to cast 'null' to 'unset' (null)",
        "file":"Casts.php",
        "line":21
    },
    {
        "type":"stupid.cast",
        "message":"You are trying to cast 'array' to 'array'",
        "file":"Casts.php",
        "line":33
    },
    {
        "type":"stupid.cast",
        "message":"You are trying to cast 'boolean' to 'boolean'",
        "file":"Casts.php",
        "line":45
    },
    {
        "type":"stupid.cast",
        "message":"You are trying to cast 'double' to 'double'",
        "file":"Casts.php",
        "line":57
    },
    {
        "type":"stupid.cast",
        "message":"You are trying to cast 'string' to 'string'",
        "file":"Casts.php",
        "line":69
    },
    {
        "type":"stupid.cast",
        "message":"You are trying to cast 'object' to 'object'",
        "file":"Casts.php",
        "line":81
    }
]
