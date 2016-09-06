<?php

namespace Tests\Compiling\Statements;

class MissingBreakStatement
{
    /**
     * @return string
     */
    public function testMissingBreakStatement()
    {
        $value = 'default';

        switch ('hello') {
            case 'hello':
                return 'world';
            case 'bar':
                $value = 'baz';
                break;
            case 'whoops':
                $value = 'missing "break" statement here';
            case 'foo':
                $value = 'bar';
                break;
            case 'error':
                $value = 'an empty return looks a bit weird in a switch';
                return;
            default:
                return 'what?';
        }

        return $value;
    }
}
?>
----------------------------
[
    {
        "type":"missing_break_statement",
        "message":"Missing \"break\" statement",
        "file":"MissingBreakStatement.php",
        "line":19
    },

    {
        "type":"missing_break_statement",
        "message":"Empty return in \"case\" statement",
        "file":"MissingBreakStatement.php",
        "line":26
    }
]
