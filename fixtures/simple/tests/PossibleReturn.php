<?php

namespace Tests\Simple\Tests;

class PossibleReturn
{
    /**
     * @return int
     */
    public function returnInt()
    {
        return 1;
    }

    /**
     * @return float
     */
    public function returnFloat()
    {
        return 1.5;
    }

    /**
     * @return bool
     */
    public function returnBoolTrue()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function returnBoolFalse()
    {
        return false;
    }

    /**
     * @return string
     */
    public function returnString()
    {
        return "test string";
    }

    /**
     * @return array
     */
    public function returnEmptyArray()
    {
        return array();
    }

    /**
     * @return array
     */
    public function returnExampleArray()
    {
        return array(
            1 => "test",
            2 => "string"
        );
    }

    /**
     * @param $a
     * @return bool
     */
    public function simpleIf($a)
    {
        if ($a) {
            return $a;
        }

        return false;
    }

    /**
     * @param $a
     * @return bool
     */
    public function simpleIf2($a)
    {
        if (!$a) {
            return $a;
        }

        return true;
    }

    /**
     * @param $a
     * @return bool
     */
    public function simpleIf3($a)
    {
        if ($a === 3) {
            return true;
        }

        return true;
    }

    /**
     * @param $a
     * @return string
     */
    public function simpleIf4($a)
    {
        if ($a === 1) {
            return 'hello';
        } elseif ($a == 4) {
            return 'world';
        } else {
            return 'unknown';
        }
    }

    /**
     * @param $a
     * @return string
     */
    public function simpleIf5($a)
    {
        if ($a === 1 || $a == 2) {
            return 'hello';
        } elseif ($a == 3) {
            return 'world';
        } else {
            return 'unknown';
        }
    }
}
