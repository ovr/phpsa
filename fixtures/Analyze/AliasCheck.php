<?php

namespace Tests\Compiling\Statements;

class AliasCheck
{
    /**
     * @return bool
     */
    public function testJoing()
    {
        return join('-', [1, 2]);
    }
}
