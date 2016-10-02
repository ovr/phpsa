<?php

namespace Tests\PHPSA;

use PHPSA\CompiledExpression;

class CompiledExpressionTest extends TestCase
{
    public function testToVariableMethod()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::INTEGER, 1);
        $this->assertInstanceOfCompiledExpression($compiledExpression);

        $resultVariable = $compiledExpression->toVariable('test');
        parent::assertInstanceOf('PHPSA\Variable', $resultVariable);
        parent::assertSame($compiledExpression->getType(), $resultVariable->getType());
        parent::assertSame($compiledExpression->getValue(), $resultVariable->getValue());
    }

    public function testFromZvalInteger()
    {
        $result = CompiledExpression::fromZvalValue(1);
        parent::assertInstanceOfCompiledExpression($result);
        parent::assertSame(CompiledExpression::INTEGER, $result->getType());
        parent::assertSame(1, $result->getValue());
    }

    public function testFromZvalBoolean()
    {
        $result = CompiledExpression::fromZvalValue(true);
        parent::assertInstanceOfCompiledExpression($result);
        parent::assertSame(CompiledExpression::BOOLEAN, $result->getType());
        parent::assertSame(true, $result->getValue());

        $result = CompiledExpression::fromZvalValue(false);
        parent::assertInstanceOfCompiledExpression($result);
        parent::assertSame(CompiledExpression::BOOLEAN, $result->getType());
        parent::assertSame(false, $result->getValue());
    }

    public function testFromZvalArray()
    {
        $result = CompiledExpression::fromZvalValue([]);
        parent::assertInstanceOfCompiledExpression($result);
        parent::assertSame(CompiledExpression::ARR, $result->getType());
        parent::assertSame([], $result->getValue());
    }

    public function testFromZvalString()
    {
        $result = CompiledExpression::fromZvalValue('test string');
        parent::assertInstanceOfCompiledExpression($result);
        parent::assertSame(CompiledExpression::STRING, $result->getType());
        parent::assertSame('test string', $result->getValue());
    }

    public function testFromZvalDouble()
    {
        $result = CompiledExpression::fromZvalValue(1.0);
        parent::assertInstanceOfCompiledExpression($result);
        parent::assertSame(CompiledExpression::DOUBLE, $result->getType());
        parent::assertSame(1.0, $result->getValue());
    }

    public function testFromZvalNull()
    {
        $result = CompiledExpression::fromZvalValue(null);
        parent::assertInstanceOfCompiledExpression($result);
        parent::assertSame(CompiledExpression::NULL, $result->getType());
        parent::assertSame(null, $result->getValue());
    }

    /**
     * @dataProvider scalarTypeProvider
     */
    public function testIsScalarWithScalarTypes($expressionType)
    {
        $compiledExpression = new CompiledExpression($expressionType);
        parent::assertTrue($compiledExpression->isScalar());
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
        parent::assertFalse($compiledExpression->isScalar());
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

    public function testIsTypeKnownWithUnknownType()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::UNKNOWN);
        parent::assertFalse($compiledExpression->isTypeKnown());
    }

    public function testIsTypeKnownWithKnownType()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::BOOLEAN);
        parent::assertTrue($compiledExpression->isTypeKnown());
    }

    public function testHasValueWithAValue()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::BOOLEAN, false);
        parent::assertTrue($compiledExpression->hasValue());
    }

    public function testHasValueWithAScalarTypeAndNoValue()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::BOOLEAN, /* just to be explicit */ null);
        parent::assertFalse($compiledExpression->hasValue());
    }

    public function testHasValueWithANullType()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::NULL);
        parent::assertTrue($compiledExpression->hasValue());
    }

    public function testCanBeObject()
    {
        // Mixed type can be object
        $expr = new CompiledExpression(CompiledExpression::MIXED, null);
        parent::assertTrue($expr->canBeObject());

        // Integer type can't be object
        $expr2 = new CompiledExpression(CompiledExpression::INTEGER, 1);
        parent::assertFalse($expr2->canBeObject());
    }

    public function testIsObject()
    {
        // Mixed type could be object but it's unclear
        $expr = new CompiledExpression(CompiledExpression::MIXED, null);
        parent::assertFalse($expr->isObject());

        // Object type is object
        $expr2 = new CompiledExpression(CompiledExpression::OBJECT, null);
        parent::assertTrue($expr2->isObject());
    }

    public function testIsArray()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::ARR);
        parent::assertTrue($compiledExpression->isArray());
    }

    public function testIsArrayWhenFalse()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::BOOLEAN);
        parent::assertFalse($compiledExpression->isArray());
    }
}
