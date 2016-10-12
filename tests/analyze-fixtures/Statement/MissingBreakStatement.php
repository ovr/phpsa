<?php

namespace Tests\Analyze\Fixtures\Statement;

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
            default:
                return 'what?';
        }

        return $value;
    }

    /**
     * @return string
     */
    public function testValidSwitch()
    {
        switch ('hello') {
            case 'hello':
                return 'world';
            case 'bar':
                $value = 'baz';
                break;
            default:
                return 'what?';
        }

        return $value;
    }

    /**
     * @return string
     */
    public function testValidSwitchWithSeveralJoinedCases()
    {
        switch ('hello') {
            case 'hello':
            case 'bar':
                $value = 'baz';
                break;
            case 'foo':
                $value = 'yay!';
                break;
            default:
                $value = 'what?';
        }

        return $value;
    }

    /**
     * @return string
     */
    public function testValidSwitchWithMissingBreakForLastCase()
    {
        $value = 'foo';

        switch ('hello') {
            case 'hello':
            case 'bar':
                $value = 'baz';
                break;
            case 'foo':
                $value = 'yay!';
        }

        return $value;
    }

    /**
     * @return string
     */
    public function missingBreakOnLastCaseWithDefault()
    {
        switch ('hello') {
            case 'bar':
                $value = 'baz';
                break;
            case 'foo':
                $value = 'yay!';
            default:
                $value = 'default';
        }

        return $value;
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Statement\MissingBreakStatement
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
        "message":"Missing \"break\" statement",
        "file":"MissingBreakStatement.php",
        "line":97
    }
]
