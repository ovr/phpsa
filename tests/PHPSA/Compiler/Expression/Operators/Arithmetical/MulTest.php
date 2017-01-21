<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Arithmetical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

class MulTest extends AbstractBinaryOp
{
    /**
     * Data provider for Div {int} / {int} = {int}
     *
     * @return array
     */
    public function divIntResultDataProvider()
    {
        return [
            [-1, -1, 1],
            [-1, 1, -1],
            [1, 1, 1],
            [true, 1, 1],
            [true, true, 1],
            [1, true, 1],
            [0, 1, 0],
            [25, 25, 25*25],
            [50, 50, 50*50],
            [500, 50, 25000],
            [5000, 50, 250000]
        ];
    }

    /**
     * Tests {int} / {int} = {int}
     *
     * @dataProvider divIntResultDataProvider
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
    public function floatResultDataProvider()
    {
        return [
            [-1, -1.25, 1*1.25],
            [-1, 1.25, -(1*1.25)],
            [1, 1.25, 1*1.25],
            [true, 1.25, 1.25],
            [0, 1.25, 0.0],
            [25, 12.5, 25*12.5],
            [25, 6.25, 25*6.25],
            [25, 3.125, 25*3.125],
        ];
    }

    /**
     * Tests {int} * {double} = {double}
     *
     * @dataProvider floatResultDataProvider
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
    public function doubleOnDoubleResultDataProvider()
    {
        return [
            [-1.25, -1, 1.25],
            [1.25, -1, -1.25],
            [1.25, 1, 1.25],
            [1.25, true, 1.25],
            [12.5, 25, 312.5],
            [6.25, 25, 156.25],
            [3.125, 25, 78.125],
        ];
    }

    /**
     * Tests {double} * {int} = {double}
     *
     * @dataProvider doubleOnDoubleResultDataProvider
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
     * @dataProvider doubleOnDoubleResultDataProvider
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
     * @param Node\Scalar $a
     * @param Node\Scalar $b
     * @return Node\Expr\BinaryOp\Mul
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\Mul($a, $b);
    }
}
