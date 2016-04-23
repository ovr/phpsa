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
}
