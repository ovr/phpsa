<?php

namespace Tests\PHPSA;

use PHPSA\CompiledExpression;

class CompiledExpressionTest extends TestCase
{
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

    public function testFromZvalDouble()
    {
        $result = CompiledExpression::fromZvalValue(1.0);
        $this->assertInstanceOfCompiledExpression($result);
        $this->assertSame(CompiledExpression::DNUMBER, $result->getType());
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
