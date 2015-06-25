<?php

namespace Tests\Simple\Tests;

class PossibleReturn
{
    public function returnInt()
    {
        return 1;
    }

    public function returnFloat()
    {
        return 1.5;
    }

    public function returnBoolTrue()
    {
        return true;
    }

    public function returnBoolFalse()
    {
        return false;
    }

    public function returnString()
    {
        return "test string";
    }

    public function returnEmptyArray()
    {
        return array();
    }

    public function returnExampleArray()
    {
        return array(
            1 => "test",
            2 => "string"
        );
    }

    public function simpleIf($a)
    {
        if ($a) {
            return $a;
        }

        return false;
    }

    public function simpleIf2($a)
    {
        if (!$a) {
            return $a;
        }

        return true;
    }

    public function simpleIf3($a)
    {
        if ($a === 3) {
            return true;
        }

        return true;
    }
}
