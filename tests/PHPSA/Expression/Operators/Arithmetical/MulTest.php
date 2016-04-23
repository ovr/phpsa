<?php

namespace Tests\PHPSA\Expression\Operators\Arithmetical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class MulTest extends \Tests\PHPSA\TestCase
{
    /**
     * Data provider for Div {int} / {int} = {int}
     *
     * @return array
     */
    public function testDivIntResultDataProvider()
    {
        return array(
            array(-1, -1, 1),
            array(-1, 1, -1),
            array(1, 1, 1),
            array(true, 1, 1),
            array(true, true, 1),
            array(1, true, 1),
            array(0, 1, 0),
            array(25, 25, 25*25),
            array(50, 50, 50*50),
            array(500, 50, 25000),
            array(5000, 50, 250000)
        );
    }

    /**
     * Tests {int} / {int} = {int}
     *
     * @dataProvider testDivIntResultDataProvider
     */
    public function testDivIntToIntWithIntResult($a, $b, $c)
    {
//        $this->assertInternalType('int', $a);
//        $this->assertInternalType('int', $b);
        $this->assertInternalType('int', $c);

        $baseExpression = new Node\Expr\BinaryOp\Mul(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Data provider for {int} * {double} = {double}
     *
     * @return array
     */
    public function testFloatResultDataProvider()
    {
        return array(
            array(-1, -1.25, 1*1.25),
            array(-1, 1.25, -(1*1.25)),
            array(1, 1.25, 1*1.25),
            array(true, 1.25, 1.25),
            array(0, 1.25, 0.0),
            array(25, 12.5, 25*12.5),
            array(25, 6.25, 25*6.25),
            array(25, 3.125, 25*3.125),
        );
    }

    /**
     * Tests {int} * {double} = {double}
     *
     * @dataProvider testFloatResultDataProvider
     */
    public function testIntOnDoubleWithDoubleResult($a, $b, $c)
    {
//        $this->assertInternalType('int', $a);
        $this->assertInternalType('double', $b);
        $this->assertInternalType('double', $c);

        $baseExpression = new Node\Expr\BinaryOp\Mul(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::DOUBLE, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Data provider for {double} * {double} = {double}
     *
     * @return array
     */
    public function testDoubleOnDoubleResultDataProvider()
    {
        return array(
            array(-1.25, -1, 1.25),
            array(1.25, -1, -1.25),
            array(1.25, 1, 1.25),
            array(1.25, true, 1.25),
            array(12.5, 25, 312.5),
            array(6.25, 25, 156.25),
            array(3.125, 25, 78.125),
        );
    }

    /**
     * Tests {double} * {int} = {double}
     *
     * @dataProvider testDoubleOnDoubleResultDataProvider
     */
    public function testDoubleOnIntWithDoubleResult($a, $b, $c)
    {
        $this->assertInternalType('double', $a);
//        $this->assertInternalType('int', $b);
        $this->assertInternalType('double', $c);

        $baseExpression = new Node\Expr\BinaryOp\Mul(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::DOUBLE, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Tests {double} * {double} = {double}
     *
     * @dataProvider testDoubleOnDoubleResultDataProvider
     */
    public function testDoubleOnDoubleWithDoubleResult($a, $b, $c)
    {
        $b = (float) $b;

        $this->assertInternalType('double', $a);
        $this->assertInternalType('double', $b);
        $this->assertInternalType('double', $c);

        $baseExpression = new Node\Expr\BinaryOp\Mul(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::DOUBLE, $compiledExpression->getType());
        $this->assertSame((float) $c, $compiledExpression->getValue());
    }

    /**
     * Tests {left-expr::UNKNOWN} * {right-expr}
     */
    public function testFirstUnexpectedTypes()
    {
        $baseExpression = new Node\Expr\BinaryOp\Mul(
            $this->newFakeScalarExpr(),
            $this->newScalarExpr(1)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }

    /**
     * Tests {left-expr} * {right-expr::UNKNOWN}
     */
    public function testSecondUnexpectedTypes()
    {
        $baseExpression = new Node\Expr\BinaryOp\Mul(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
