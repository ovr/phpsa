<?php

namespace Tests\PHPSA;

use PHPSA\CompiledExpression;
use PHPSA\Variable;

class VariableTest extends TestCase
{
    public function testSimpleConstruct()
    {
        $variable = new Variable('test', 1, CompiledExpression::INTEGER);

        static::assertSame(CompiledExpression::INTEGER, $variable->getType());
        static::assertSame('test', $variable->getName());
        static::assertSame(1, $variable->getValue());
        static::assertSame(0, $variable->getGets());

        /**
         * it's one via default value is present
         */
        static::assertSame(1, $variable->getSets());
    }
}
