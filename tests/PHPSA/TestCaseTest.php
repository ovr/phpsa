<?php

namespace Tests\PHPSA;

class TestCaseTest extends TestCase
{
    public function testGetContext()
    {
        $result = $this->getContext();
        $this->assertInstanceOf('PHPSA\Context', $result);
    }

    public function testNewScalarExprInt()
    {
        $scalar = $this->newScalarExpr(-1);
        $this->assertInstanceOf('PHPParser\Node\Scalar\LNumber', $scalar);
        $this->assertSame(-1, $scalar->value);

        $scalar = $this->newScalarExpr(0);
        $this->assertInstanceOf('PHPParser\Node\Scalar\LNumber', $scalar);
        $this->assertSame(0, $scalar->value);

        $scalar = $this->newScalarExpr(1);
        $this->assertInstanceOf('PHPParser\Node\Scalar\LNumber', $scalar);
        $this->assertSame(1, $scalar->value);
    }

    public function testNewScalarExprDouble()
    {
        $scalar = $this->newScalarExpr(-1.0);
        $this->assertInstanceOf('PHPParser\Node\Scalar\DNumber', $scalar);
        $this->assertSame(-1.0, $scalar->value);

        $scalar = $this->newScalarExpr(0.0);
        $this->assertInstanceOf('PHPParser\Node\Scalar\DNumber', $scalar);
        $this->assertSame(0.0, $scalar->value);

        $scalar = $this->newScalarExpr(1.0);
        $this->assertInstanceOf('PHPParser\Node\Scalar\DNumber', $scalar);
        $this->assertSame(1.0, $scalar->value);
    }

    public function testNewScalarExprBoolean()
    {
        $scalar = $this->newScalarExpr(true);
        $this->assertInstanceOf('PHPSA\Node\Scalar\Boolean', $scalar);
        $this->assertSame(true, $scalar->value);

        $scalar = $this->newScalarExpr(false);
        $this->assertInstanceOf('PHPSA\Node\Scalar\Boolean', $scalar);
        $this->assertSame(false, $scalar->value);
    }

    public function testNewScalarExprNull()
    {
        $scalar = $this->newScalarExpr(null);
        $this->assertInstanceOf('PHPSA\Node\Scalar\Nil', $scalar);
        $this->assertSame(null, $scalar->value);
    }

    public function testNewScalarExprForEmptyArray()
    {
        $scalar = $this->newScalarExpr(array());
        $this->assertInstanceOf('PHPParser\Node\Expr\Array_', $scalar);
        $this->assertSame(array(), $scalar->items);
    }
}
