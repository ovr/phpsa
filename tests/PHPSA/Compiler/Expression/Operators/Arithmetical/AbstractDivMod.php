<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Arithmetical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

abstract class AbstractDivMod extends AbstractBinaryOp
{
    /**
     * @param $a
     * @param $b
     * @return float
     */
    abstract protected function process($a, $b);

    /**
     * Data provider for Div {int} / {int} = {int}
     *
     * @return array
     */
    public function divIntResultDataProvider()
    {
        return [
            [-1, -1],
            [-1, 1],
            [1, 1],
            [true, 1],
            [true, true],
            [1, true],
            [0, 1],
            [25, 25],
            [50, 50],
            [500, 50],
            [5000, 50]
        ];
    }

    /**
     * Tests {int} $operator {int} = {int}
     *
     * @dataProvider divIntResultDataProvider
     */
    public function testDivIntToIntWithIntResult($a, $b)
    {
        $compiledExpression = $this->compileExpression(
            $this->buildExpression(
                $this->newScalarExpr($a),
                $this->newScalarExpr($b)
            )
        );

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($this->process($a, $b), $compiledExpression->getValue());
    }

    abstract protected function getAssertType();

    /**
     * Data provider for Div {int} / {double} = {double}
     *
     * @return array
     */
    public function divFloatResultDataProvider()
    {
        return [
            [-1, -1.25, 0.8],
            [-1, 1.25, -0.8],
            [1, 1.25, 0.8],
            [true, 1.25, 0.8],
            [0, 1.25, 0.0],
            [25, 12.5, 2.0],
            [25, 6.25, 4.0],
            [25, 3.125, 8.0],
        ];
    }

    /**
     * Tests {int} $operator {double} = {double}
     *
     * @dataProvider divFloatResultDataProvider
     */
    public function testDivIntToDoubleWithDoubleResult($a, $b, $c)
    {
//        $this->assertInternalType('int', $a);
        $this->assertInternalType('double', $b);
        $this->assertInternalType('double', $c);

        $compiledExpression = $this->compileExpression(
            $this->buildExpression(
                $this->newScalarExpr($a),
                $this->newScalarExpr($b)
            )
        );

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame($this->getAssertType(), $compiledExpression->getType());
        $this->assertSame($this->process($a, $b), $compiledExpression->getValue());
    }

    /**
     * Data provider for Div {double} / {double} = {double}
     *
     * @return array
     */
    public function divDoubleToDoubleResultDataProvider()
    {
        return [
            [-1.25, -1, 1.25],
            [1.25, -1, -1.25],
            [1.25, 1, 1.25],
            [1.25, true, 1.25],
            [12.5, 25, 1/2],
            [6.25, 25, 1/4],
            [3.125, 25, 1/8],
        ];
    }

    /**
     * Tests {double} $operator {int} = {double}
     *
     * @dataProvider divDoubleToDoubleResultDataProvider
     */
    public function testDivDoubleToIntWithDoubleResult($a, $b, $c)
    {
        $this->assertInternalType('double', $a);
//        $this->assertInternalType('int', $b);
        $this->assertInternalType('double', $c);

        $compiledExpression = $this->compileExpression(
            $this->buildExpression(
                $this->newScalarExpr($a),
                $this->newScalarExpr($b)
            )
        );
        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame($this->getAssertType(), $compiledExpression->getType());
        $this->assertSame($this->process($a, $b), $compiledExpression->getValue());
    }

    /**
     * Tests {double} $operator {double} = {double}
     *
     * @dataProvider divDoubleToDoubleResultDataProvider
     */
    public function testDivDoubleToDoubleWithDoubleResult($a, $b, $c)
    {
        $b = (float) $b;

        $this->assertInternalType('double', $a);
        $this->assertInternalType('double', $b);
        $this->assertInternalType('double', $c);

        $compiledExpression = $this->compileExpression(
            $this->buildExpression(
                $this->newScalarExpr($a),
                $this->newScalarExpr($b)
            )
        );
        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame($this->getAssertType(), $compiledExpression->getType());
        $this->assertSame($this->process($a, $b), $compiledExpression->getValue());
    }
}
