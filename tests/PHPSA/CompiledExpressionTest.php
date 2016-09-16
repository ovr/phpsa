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
        static::assertInstanceOf('PHPSA\Variable', $resultVariable);
        static::assertSame($compiledExpression->getType(), $resultVariable->getType());
        static::assertSame($compiledExpression->getValue(), $resultVariable->getValue());
    }

    public function testFromZvalInteger()
    {
        $result = CompiledExpression::fromZvalValue(1);
        $this->assertInstanceOfCompiledExpression($result);
        $this->assertSame(CompiledExpression::INTEGER, $result->getType());
        $this->assertSame(1, $result->getValue());
    }

    public function testFromZvalBoolean()
    {
        $result = CompiledExpression::fromZvalValue(true);
        $this->assertInstanceOfCompiledExpression($result);
        $this->assertSame(CompiledExpression::BOOLEAN, $result->getType());
        $this->assertSame(true, $result->getValue());

        $result = CompiledExpression::fromZvalValue(false);
        $this->assertInstanceOfCompiledExpression($result);
        $this->assertSame(CompiledExpression::BOOLEAN, $result->getType());
        $this->assertSame(false, $result->getValue());
    }

    public function testFromZvalArray()
    {
        $result = CompiledExpression::fromZvalValue([]);
        $this->assertInstanceOfCompiledExpression($result);
        $this->assertSame(CompiledExpression::ARR, $result->getType());
        $this->assertSame([], $result->getValue());
    }

    public function testFromZvalString()
    {
        $result = CompiledExpression::fromZvalValue("test string");
        $this->assertInstanceOfCompiledExpression($result);
        $this->assertSame(CompiledExpression::STRING, $result->getType());
        $this->assertSame("test string", $result->getValue());
    }

    public function testFromZvalDouble()
    {
        $result = CompiledExpression::fromZvalValue(1.0);
        $this->assertInstanceOfCompiledExpression($result);
        $this->assertSame(CompiledExpression::DOUBLE, $result->getType());
        $this->assertSame(1.0, $result->getValue());
    }

    public function testFromZvalNull()
    {
        $result = CompiledExpression::fromZvalValue(null);
        $this->assertInstanceOfCompiledExpression($result);
        $this->assertSame(CompiledExpression::NULL, $result->getType());
        $this->assertSame(null, $result->getValue());
    }

    /**
     * @dataProvider scalarTypeProvider
     */
    public function testIsScalarWithScalarTypes($expressionType)
    {
        $compiledExpression = new CompiledExpression($expressionType);
        $this->assertTrue($compiledExpression->isScalar());
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
        $this->assertFalse($compiledExpression->isScalar());
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
        $this->assertFalse($compiledExpression->isTypeKnown());
    }

    public function testIsTypeKnownWithKnownType()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::BOOLEAN);
        $this->assertTrue($compiledExpression->isTypeKnown());
    }

    public function testHasValueWithAValue()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::BOOLEAN, false);
        $this->assertTrue($compiledExpression->hasValue());
    }

    public function testHasValueWithAScalarTypeAndNoValue()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::BOOLEAN, /* just to be explicit */ null);
        $this->assertFalse($compiledExpression->hasValue());
    }

    public function testHasValueWithANullType()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::NULL);
        $this->assertTrue($compiledExpression->hasValue());
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
        $this->assertTrue($compiledExpression->isArray());
    }

    public function testIsArrayWhenFalse()
    {
        $compiledExpression = new CompiledExpression(CompiledExpression::BOOLEAN);
        $this->assertFalse($compiledExpression->isArray());
    }
}
