<?php

namespace Simple\CodeSmell;

class Cast
{
    public function testCostBooleanTrue()
    {
        return (bool) true;
    }

    public function testCostBooleanFalse()
    {
        return (bool) false;
    }
}