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

    public function testReferenceToChange()
    {
        /**
         * $a = 1;
         * $b = &$a;
         */
        $parentVariable = new Variable('a', 1, CompiledExpression::INTEGER);
        $variable = new Variable('b', $parentVariable->getValue(), $parentVariable->getType());

        static::assertFalse($variable->isReferenced());
        $variable->setReferencedTo($parentVariable);
        static::assertTrue($variable->isReferenced());

        /**
         * $b = 55.00
         */
        $variable->modify(CompiledExpression::DOUBLE, 55.00);

        static::assertSame($variable->getValue(), $parentVariable->getValue());
        static::assertSame($variable->getType(), $parentVariable->getType());
    }
}
