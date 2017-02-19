<?php

namespace Tests\PHPSA;

use PHPSA\CompiledExpression;

class CompiledExpressionTest extends TestCase
{
    public function testToVariableMethod()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::INTEGER, 1);
        self::assertInstanceOfCompiledExpression($compiledExpression);

        $resultVariable = $compiledExpression->toVariable('test');
        self::assertInstanceOf('PHPSA\Variable', $resultVariable);
        self::assertSame($compiledExpression->getType(), $resultVariable->getType());
        self::assertSame($compiledExpression->getValue(), $resultVariable->getValue());
    }

    public function testFromZvalInteger()
    {
        $result = CompiledExpression::fromZvalValue(1);
        self::assertInstanceOfCompiledExpression($result);
        self::assertSame(CompiledExpression::INTEGER, $result->getType());
        self::assertSame(1, $result->getValue());
    }

    public function testFromZvalBoolean()
    {
        $result = CompiledExpression::fromZvalValue(true);
        self::assertInstanceOfCompiledExpression($result);
        self::assertSame(CompiledExpression::BOOLEAN, $result->getType());
        self::assertSame(true, $result->getValue());

        $result = CompiledExpression::fromZvalValue(false);
        self::assertInstanceOfCompiledExpression($result);
        self::assertSame(CompiledExpression::BOOLEAN, $result->getType());
        self::assertSame(false, $result->getValue());
    }

    public function testFromZvalArray()
    {
        $result = CompiledExpression::fromZvalValue([]);
        self::assertInstanceOfCompiledExpression($result);
        self::assertSame(CompiledExpression::ARR, $result->getType());
        self::assertSame([], $result->getValue());
    }

    public function testFromZvalString()
    {
        $result = CompiledExpression::fromZvalValue('test string');
        self::assertInstanceOfCompiledExpression($result);
        self::assertSame(CompiledExpression::STRING, $result->getType());
        self::assertSame('test string', $result->getValue());
    }

    public function testFromZvalDouble()
    {
        $result = CompiledExpression::fromZvalValue(1.0);
        self::assertInstanceOfCompiledExpression($result);
        self::assertSame(CompiledExpression::DOUBLE, $result->getType());
        self::assertSame(1.0, $result->getValue());
    }

    public function testFromZvalNull()
    {
        $result = CompiledExpression::fromZvalValue(null);
        self::assertInstanceOfCompiledExpression($result);
        self::assertSame(CompiledExpression::NULL, $result->getType());
        self::assertSame(null, $result->getValue());
    }

    /**
     * @dataProvider scalarTypeProvider
     */
    public function testIsScalarWithScalarTypes($expressionType)
    {
        $compiledExpression = new CompiledExpression($expressionType);
        self::assertTrue($compiledExpression->isScalar());
    }

    public function scalarTypeProvider()
    {
        return [
            [ CompiledExpression::BOOLEAN ],
            [ CompiledExpression::STRING ],
            [ CompiledExpression::DOUBLE ],
            [ CompiledExpression::NUMBER ],
            [ CompiledExpression::INTEGER ],
        ];
    }

    /**
     * @dataProvider nonScalarTypeProvider
     */
    public function testIsScalarWithNonScalarTypes($expressionType)
    {
        $compiledExpression = new CompiledExpression($expressionType);
        self::assertFalse($compiledExpression->isScalar());
    }

    public function nonScalarTypeProvider()
    {
        return [
            [ CompiledExpression::UNKNOWN ],
            [ CompiledExpression::NULL ],
            [ CompiledExpression::ARR ],
            [ CompiledExpression::RESOURCE ],
            [ CompiledExpression::OBJECT ],
            [ CompiledExpression::VOID ],
            [ CompiledExpression::CALLABLE_TYPE ],
            [ CompiledExpression::VARIABLE ],
        ];
    }

    public function isTypeKnownProvider()
    {
        return [
            [ CompiledExpression::INTEGER ],
            [ CompiledExpression::DOUBLE ],
            [ CompiledExpression::NUMBER ],
            [ CompiledExpression::STRING ],
            [ CompiledExpression::ARR ],
            [ CompiledExpression::BOOLEAN ],
            [ CompiledExpression::RESOURCE ],
            [ CompiledExpression::CALLABLE_TYPE ],
            [ CompiledExpression::NULL ],
        ];
    }

    /**
     * @dataProvider isTypeKnownProvider
     * @param int $type
     */
    public function testIsTypeKnownTrue($type)
    {
        $compiledExpression = new CompiledExpression($type);
        self::assertTrue($compiledExpression->isTypeKnown());
    }

    public function testIsTypeKnownWithUnknownType()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::UNKNOWN);
        self::assertFalse($compiledExpression->isTypeKnown());
    }

    public function testHasValueWithAValue()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::BOOLEAN, false);
        self::assertTrue($compiledExpression->hasValue());
    }

    public function testHasValueWithAScalarTypeAndNoValue()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::BOOLEAN, /* just to be explicit */ null);
        self::assertFalse($compiledExpression->hasValue());
    }

    public function testHasValueWithANullType()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::NULL);
        self::assertTrue($compiledExpression->hasValue());
    }

    public function testCanBeObject()
    {
        // Mixed type can be object
        $expr = new CompiledExpression(CompiledExpression::MIXED, null);
        self::assertTrue($expr->canBeObject());

        // Integer type can't be object
        $expr2 = new CompiledExpression(CompiledExpression::INTEGER, 1);
        self::assertFalse($expr2->canBeObject());
    }

    public function testIsObject()
    {
        // Mixed type could be object but it's unclear
        $expr = new CompiledExpression(CompiledExpression::MIXED, null);
        self::assertFalse($expr->isObject());

        // Object type is object
        $expr2 = new CompiledExpression(CompiledExpression::OBJECT, null);
        self::assertTrue($expr2->isObject());
    }

    public function testIsArray()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::ARR);
        self::assertTrue($compiledExpression->isArray());
    }

    public function testIsArrayWhenFalse()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::BOOLEAN);
        self::assertFalse($compiledExpression->isArray());
    }
}
