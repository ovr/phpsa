<?php

namespace Tests\PHPSA;

use PHPSA\CompiledExpression;
use PHPSA\Variable;

class VariableTest extends TestCase
{
    public function testSimpleInstanceWithDefaultValue()
    {
        $variable = new Variable('test', 1, CompiledExpression::INTEGER);

        self::assertSame(CompiledExpression::INTEGER, $variable->getType());
        self::assertSame('test', $variable->getName());
        self::assertSame(1, $variable->getValue());
        self::assertSame(0, $variable->getGets());

        /**
         * it's one via default value is present
         */
        self::assertSame(1, $variable->getSets());
    }

    public function testIsNumericMethodIntSuccess()
    {
        $variable = new Variable('a', 1, CompiledExpression::INTEGER);
        self::assertTrue($variable->isNumeric());
    }

    public function testIsNumericMethodDoubleSuccess()
    {
        $variable = new Variable('a', 1, CompiledExpression::DOUBLE);
        self::assertTrue($variable->isNumeric());
    }

    public function testIsNumericMethodFalseOnBoolean()
    {
        $variable = new Variable('a', 1, CompiledExpression::BOOLEAN);
        self::assertFalse($variable->isNumeric());
    }

    public function testIncValue()
    {
        $variable = new Variable('a', 1, CompiledExpression::INTEGER);
        self::assertSame(1, $variable->getValue());

        $variable->inc();
        self::assertSame(2, $variable->getValue());

        $variable->inc();
        self::assertSame(3, $variable->getValue());

        $variable->inc();
        $variable->inc();
        self::assertSame(5, $variable->getValue());
    }

    public function testDecValue()
    {
        $variable = new Variable('a', 1, CompiledExpression::INTEGER);
        self::assertSame(1, $variable->getValue());

        $variable->dec();
        self::assertSame(0, $variable->getValue());

        $variable->dec();
        self::assertSame(-1, $variable->getValue());

        $variable->dec();
        $variable->dec();
        self::assertSame(-3, $variable->getValue());
    }

    public function testIncUse()
    {
        $variable = new Variable('a', null, CompiledExpression::UNKNOWN);
        self::assertSame(0, $variable->getGets());
        self::assertSame(0, $variable->getSets());

        $variable->incUse();
        self::assertSame(1, $variable->getGets());
        self::assertSame(1, $variable->getSets());

        $variable->incUse();
        self::assertSame(2, $variable->getGets());
        self::assertSame(2, $variable->getSets());

        $variable->incUse();
        $variable->incUse();
        self::assertSame(4, $variable->getGets());
        self::assertSame(4, $variable->getSets());
    }

    public function testIncSets()
    {
        $variable = new Variable('a', null, CompiledExpression::UNKNOWN);
        self::assertSame(0, $variable->getSets());

        $variable->incSets();
        self::assertSame(1, $variable->getSets());

        $variable->incSets();
        self::assertSame(2, $variable->getSets());

        $variable->incSets();
        $variable->incSets();
        self::assertSame(4, $variable->getSets());
    }

    public function testIncGets()
    {
        $variable = new Variable('a', null, CompiledExpression::UNKNOWN);
        self::assertSame(0, $variable->getGets());

        $variable->incGets();
        self::assertSame(1, $variable->getGets());

        $variable->incGets();
        self::assertSame(2, $variable->getGets());

        $variable->incGets();
        $variable->incGets();
        self::assertSame(4, $variable->getGets());
    }

    public function testModifyType()
    {
        $variable = new Variable('a', 1, CompiledExpression::INTEGER);
        self::assertSame(CompiledExpression::INTEGER, $variable->getType());

        $newType = CompiledExpression::BOOLEAN;
        $variable->modifyType($newType);
        self::assertSame($newType, $variable->getType());
    }

    public function testIsUnusedTrue()
    {
        $variable = new Variable('a', 1, CompiledExpression::INTEGER);
        self::assertTrue($variable->isUnused());

        $variable = new Variable('a', null, CompiledExpression::UNKNOWN);
        $variable->incSets();
        self::assertTrue($variable->isUnused());
    }

    public function testIsUnusedFalse()
    {
        $variable = new Variable('a', null, CompiledExpression::UNKNOWN);
        self::assertFalse($variable->isUnused());
    }

    public function testReferenceToChange()
    {
        /**
         * $a = 1;
         * $b = &$a;
         */
        $parentVariable = new Variable('a', 1, CompiledExpression::INTEGER);
        $variable = new Variable('b', $parentVariable->getValue(), $parentVariable->getType());

        self::assertFalse($variable->isReferenced());
        $variable->setReferencedTo($parentVariable);
        self::assertTrue($variable->isReferenced());
        self::assertSame($parentVariable, $variable->getReferencedTo());

        /**
         * $b = 55.00
         */
        $variable->modify(CompiledExpression::DOUBLE, 55.00);

        self::assertSame($variable->getValue(), $parentVariable->getValue());
        self::assertSame($variable->getType(), $parentVariable->getType());
    }

    public function testGetTypeName()
    {
        $int = new Variable('a', 1, CompiledExpression::INTEGER);
        self::assertSame("integer", $int->getTypeName());

        $double = new Variable('b', 1, CompiledExpression::DOUBLE);
        self::assertSame("double", $double->getTypeName());

        $number = new Variable('c', 1, CompiledExpression::NUMBER);
        self::assertSame("number", $number->getTypeName());

        $arr = new Variable('d', [1,2], CompiledExpression::ARR);
        self::assertSame("array", $arr->getTypeName());

        $object = new Variable('e', 1, CompiledExpression::OBJECT);
        self::assertSame("object", $object->getTypeName());

        $resource = new Variable('f', 1, CompiledExpression::RESOURCE);
        self::assertSame("resource", $resource->getTypeName());

        $callable = new Variable('g', 1, CompiledExpression::CALLABLE_TYPE);
        self::assertSame("callable", $callable->getTypeName());

        $boolean = new Variable('h', 1, CompiledExpression::BOOLEAN);
        self::assertSame("boolean", $boolean->getTypeName());

        $null = new Variable('i', 1, CompiledExpression::NULL);
        self::assertSame("null", $null->getTypeName());

        $unknown = new Variable('j', 1, CompiledExpression::UNKNOWN);
        self::assertSame("unknown", $unknown->getTypeName());
    }
}
