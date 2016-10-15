<?php

namespace Tests\Compiling\Statements;

class MissingBody
{
    public function missingBodyMethod() {
    }

    public function missingBodyOthers() {
        while (true) {
        }

        do {
        } while (true);

        if (true) {
        } elseif (false) {
        } else {
        }

        try {
        } catch (Exception $e) {
        }

        for ($i=1;$i<2;$i++){
        }

        $arr = [1,2];
        foreach ($arr as $b) {
        }
    }
}

function missingBodyFunction() {
}

abstract class ImplementedBody
{
    public function implementedBodyMethod() {
        echo "implemented";
    }

    public function implementedBodyOthers() {
        while (true) {
            echo "implemented";
        }

        do {
            echo "implemented";
        } while (true);

        if (true) {
            echo "implemented";
        } elseif (false) {
            echo "implemented";
        } else {
            echo "implemented";
        }

        try {
            echo "implemented";
        } catch (Exception $e) {
            echo "implemented";
        }

        for ($i=1;$i<2;$i++){
            echo "implemented";
        }

        $arr = [1,2];
        foreach ($arr as $b) {
            echo "implemented";
        }
    }

    abstract public function abstractFunc();
}

function implementedBodyFunction() {
    echo "implemented";
}
?>
----------------------------
PHPSA\Analyzer\Pass\Statement\MissingBody
----------------------------
[
    {
        "type":"missing_body",
        "message":"Missing Body",
        "file":"MissingBody.php",
        "line":6
    },
    {
        "type":"missing_body",
        "message":"Missing Body",
        "file":"MissingBody.php",
        "line":10
    },
    {
        "type":"missing_body",
        "message":"Missing Body",
        "file":"MissingBody.php",
        "line":13
    },
    {
        "type":"missing_body",
        "message":"Missing Body",
        "file":"MissingBody.php",
        "line":16
    },
    {
        "type":"missing_body",
        "message":"Missing Body",
        "file":"MissingBody.php",
        "line":17
    },
    {
        "type":"missing_body",
        "message":"Missing Body",
        "file":"MissingBody.php",
        "line":18
    },
    {
        "type":"missing_body",
        "message":"Missing Body",
        "file":"MissingBody.php",
        "line":21
    },
    {
        "type":"missing_body",
        "message":"Missing Body",
        "file":"MissingBody.php",
        "line":22
    },
    {
        "type":"missing_body",
        "message":"Missing Body",
        "file":"MissingBody.php",
        "line":25
    },
    {
        "type":"missing_body",
        "message":"Missing Body",
        "file":"MissingBody.php",
        "line":29
    },
    {
        "type":"missing_body",
        "message":"Missing Body",
        "file":"MissingBody.php",
        "line":34
    }
]
