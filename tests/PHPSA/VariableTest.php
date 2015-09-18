<?php

namespace Tests\PHPSA;

use PHPSA\CompiledExpression;
use PHPSA\Variable;

class VariableTest extends TestCase
{
    public function testSimpleInstanceWithDefaultValue()
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

    public function testIsNumericMethodIntSuccess()
    {
        $variable = new Variable('a', 1, CompiledExpression::INTEGER);
        static::assertTrue($variable->isNumeric());
    }

    public function testIsNumericMethodDoubleSuccess()
    {
        $variable = new Variable('a', 1, CompiledExpression::DOUBLE);
        static::assertTrue($variable->isNumeric());
    }

    public function testIsNumericMethodFalseOnBoolean()
    {
        $variable = new Variable('a', 1, CompiledExpression::BOOLEAN);
        static::assertFalse($variable->isNumeric());
    }

    public function testIncValue()
    {
        $variable = new Variable('a', 1, CompiledExpression::INTEGER);
        static::assertSame(1, $variable->getValue());

        $variable->inc();
        static::assertSame(2, $variable->getValue());

        $variable->inc();
        static::assertSame(3, $variable->getValue());

        $variable->inc();
        $variable->inc();
        static::assertSame(5, $variable->getValue());
    }

    public function testDecValue()
    {
        $variable = new Variable('a', 1, CompiledExpression::INTEGER);
        static::assertSame(1, $variable->getValue());

        $variable->dec();
        static::assertSame(0, $variable->getValue());

        $variable->dec();
        static::assertSame(-1, $variable->getValue());

        $variable->dec();
        $variable->dec();
        static::assertSame(-3, $variable->getValue());
    }

    public function testIncUse()
    {
        $variable = new Variable('a', null, CompiledExpression::UNKNOWN);
        static::assertSame(0, $variable->getGets());
        static::assertSame(0, $variable->getSets());

        $variable->incUse();
        static::assertSame(1, $variable->getGets());
        static::assertSame(1, $variable->getSets());

        $variable->incUse();
        static::assertSame(2, $variable->getGets());
        static::assertSame(2, $variable->getSets());

        $variable->incUse();
        $variable->incUse();
        static::assertSame(4, $variable->getGets());
        static::assertSame(4, $variable->getSets());
    }

    public function testIncSets()
    {
        $variable = new Variable('a', null, CompiledExpression::UNKNOWN);
        static::assertSame(0, $variable->getSets());

        $variable->incSets();
        static::assertSame(1, $variable->getSets());

        $variable->incSets();
        static::assertSame(2, $variable->getSets());

        $variable->incSets();
        $variable->incSets();
        static::assertSame(4, $variable->getSets());
    }

    public function testIncGets()
    {
        $variable = new Variable('a', null, CompiledExpression::UNKNOWN);
        static::assertSame(0, $variable->getGets());

        $variable->incGets();
        static::assertSame(1, $variable->getGets());

        $variable->incGets();
        static::assertSame(2, $variable->getGets());

        $variable->incGets();
        $variable->incGets();
        static::assertSame(4, $variable->getGets());
    }

    public function testModifyType()
    {
        $variable = new Variable('a', 1, CompiledExpression::INTEGER);
        static::assertSame(CompiledExpression::INTEGER, $variable->getType());

        $newType = CompiledExpression::BOOLEAN;
        $variable->modifyType($newType);
        static::assertSame($newType, $variable->getType());
    }

    public function testIsUnusedTrue()
    {
        $variable = new Variable('a', 1, CompiledExpression::INTEGER);
        static::assertTrue($variable->isUnused());

        $variable = new Variable('a', null, CompiledExpression::UNKNOWN);
        $variable->incSets();
        static::assertTrue($variable->isUnused());
    }

    public function testIsUnusedFalse()
    {
        $variable = new Variable('a', null, CompiledExpression::UNKNOWN);
        static::assertFalse($variable->isUnused());
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
        static::assertSame($parentVariable, $variable->getReferencedTo());

        /**
         * $b = 55.00
         */
        $variable->modify(CompiledExpression::DOUBLE, 55.00);

        static::assertSame($variable->getValue(), $parentVariable->getValue());
        static::assertSame($variable->getType(), $parentVariable->getType());
    }
}
