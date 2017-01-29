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

    /**
     * @return string
     */
    public function testValidSwitchWithThrowContinue()
    {
        switch (30) {
            case 1:
                $value = 'bar';
                break;
            case 2:
                {
                    $value = 'baz';
                    break;
                }
            case 3:
                $value = 'missing "break" statement here';
                if (mt_rand() % 2) {
                    break;
                }
            case 4:
                $value = 'throw';
                throw new \Exception();
            case 5:
                $value = 'continue';
                continue 1;
            default:
                $value = 'default';
        }

        return $value;
    }

    /**
     * @return string
     */
    public function testValidSwitchWithComments()
    {
        switch (30) {
            case 1: //< 001 comment
            case 2:
                $value = 'bar';
                break;
            // 002 comment
            case 3:
                {
                    $value = 'baz';
                    break;
                }

            /**
             * 003 comment
             */

            case 4:
            /*
             * 004 comment
             */
            case 5:
                $value = 'foo';
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
    },
    {
        "type":"missing_break_statement",
        "message":"Missing \"break\" statement",
        "file":"MissingBreakStatement.php",
        "line":120
    }
]
