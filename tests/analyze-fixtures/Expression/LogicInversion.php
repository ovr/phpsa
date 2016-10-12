<?php

/**
 * @author Medvedev Alexandr https://github.com/lexty <alexandr.mdr@gmail.com>
 */
namespace Tests\Analyze\Fixtures\Expression;

class LogicInversion
{
    public function inversionEqual()
    {
        return !(true == false);
    }

    public function inversionNotEqual()
    {
        return !(true != false);
    }

    public function inversionIdentical()
    {
        return !(true === false);
    }

    public function inversionNotIdentical()
    {
        return !(true !== false);
    }

    public function inversionGreater()
    {
        return !(true > false);
    }

    public function inversionGreaterOrEqual()
    {
        return !(true >= false);
    }

    public function inversionSmaller()
    {
        return !(true < false);
    }

    public function inversionSmallerOrEqual()
    {
        return !(true <= false);
    }
}

class LogicValid
{
    public function equal()
    {
        return true == false;
    }

    public function notEqual()
    {
        return true != false;
    }

    public function identical()
    {
        return true === false;
    }

    public function notIdentical()
    {
        return true !== false;
    }

    public function greater()
    {
        return true > false;
    }

    public function greaterOrEqual()
    {
        return true >= false;
    }

    public function smaller()
    {
        return true < false;
    }

    public function smallerOrEqual()
    {
        return true <= false;
    }

    public function booleanNot()
    {
        return !false;
    }

    public function inversionBooleanAnd()
    {
        return !(true && false);
    }

    public function booleanAnd()
    {
        return true && false;
    }

    public function inversionBooleanNot()
    {
        return !(!false);
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\LogicInversion
----------------------------
[
    {
        "type":"logic_inversion",
        "message":"Use \"a != b\" expression instead of \"!(a == b)\".",
        "file":"LogicInversion.php",
        "line":11
    },
    {
        "type":"logic_inversion",
        "message":"Use \"a == b\" expression instead of \"!(a != b)\".",
        "file":"LogicInversion.php",
        "line":16
    },
    {
        "type":"logic_inversion",
        "message":"Use \"a === b\" expression instead of \"!(a !== b)\".",
        "file":"LogicInversion.php",
        "line":21
    },
    {
        "type":"logic_inversion",
        "message":"Use \"a !== b\" expression instead of \"!(a === b)\".",
        "file":"LogicInversion.php",
        "line":26
    },
    {
        "type":"logic_inversion",
        "message":"Use \"a > b\" expression instead of \"!(a <= b)\".",
        "file":"LogicInversion.php",
        "line":31
    },
    {
        "type":"logic_inversion",
        "message":"Use \"a >= b\" expression instead of \"!(a < b)\".",
        "file":"LogicInversion.php",
        "line":36
    },
    {
        "type":"logic_inversion",
        "message":"Use \"a < b\" expression instead of \"!(a >= b)\".",
        "file":"LogicInversion.php",
        "line":41
    },
    {
        "type":"logic_inversion",
        "message":"Use \"a <= b\" expression instead of \"!(a > b)\".",
        "file":"LogicInversion.php",
        "line":46
    }
]
