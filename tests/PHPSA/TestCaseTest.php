<?php

namespace Tests\PHPSA;

class TestCaseTest extends TestCase
{
    public function testGetContext()
    {
        $result = $this->getContext();
        self::assertInstanceOf('PHPSA\Context', $result);
    }

    public function testNewScalarExprInt()
    {
        $scalar = $this->newScalarExpr(-1);
        self::assertInstanceOf('PHPParser\Node\Scalar\LNumber', $scalar);
        self::assertSame(-1, $scalar->value);

        $scalar = $this->newScalarExpr(0);
        self::assertInstanceOf('PHPParser\Node\Scalar\LNumber', $scalar);
        self::assertSame(0, $scalar->value);

        $scalar = $this->newScalarExpr(1);
        self::assertInstanceOf('PHPParser\Node\Scalar\LNumber', $scalar);
        self::assertSame(1, $scalar->value);
    }

    public function testNewScalarExprDouble()
    {
        $scalar = $this->newScalarExpr(-1.0);
        self::assertInstanceOf('PHPParser\Node\Scalar\DNumber', $scalar);
        self::assertSame(-1.0, $scalar->value);

        $scalar = $this->newScalarExpr(0.0);
        self::assertInstanceOf('PHPParser\Node\Scalar\DNumber', $scalar);
        self::assertSame(0.0, $scalar->value);

        $scalar = $this->newScalarExpr(1.0);
        self::assertInstanceOf('PHPParser\Node\Scalar\DNumber', $scalar);
        self::assertSame(1.0, $scalar->value);
    }

    public function testNewScalarExprBoolean()
    {
        $scalar = $this->newScalarExpr(true);
        self::assertInstanceOf('PHPSA\Node\Scalar\Boolean', $scalar);
        self::assertSame(true, $scalar->value);

        $scalar = $this->newScalarExpr(false);
        self::assertInstanceOf('PHPSA\Node\Scalar\Boolean', $scalar);
        self::assertSame(false, $scalar->value);
    }

    public function testNewScalarExprNull()
    {
        $scalar = $this->newScalarExpr(null);
        self::assertInstanceOf('PHPSA\Node\Scalar\Nil', $scalar);
        self::assertSame(null, $scalar->value);
    }

    public function testNewScalarExprForEmptyArray()
    {
        $scalar = $this->newScalarExpr(array());
        self::assertInstanceOf('PHPParser\Node\Expr\Array_', $scalar);
        self::assertSame(array(), $scalar->items);
    }
}
