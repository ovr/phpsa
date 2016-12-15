<?php

namespace Tests\PHPSA\Compiler\Expression\BinaryOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

/**
 * Class IndenticalTest
 * @package Tests\PHPSA\Expression\BinaryOp
 */
class IndenticalTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function providerForStaticIntToIntCases()
    {
        return [
            [-1, -1],
            [-5, -5],
            [-150, -150],
            [150, 150],
            [150, 150],
        ];
    }

    /**
     * Tests (int) {left-expr} ==== (int) {right-expr}
     *
     * @param int $a
     * @param int $b
     *
     * @dataProvider providerForStaticIntToIntCases
     */
    public function testStaticIntToInt($a, $b)
    {
        $this->assertInternalType('int', $a);
        $this->assertInternalType('int', $b);

        $baseExpression = new Node\Expr\BinaryOp\Identical(
            new Node\Scalar\LNumber($a),
            new Node\Scalar\LNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression, $this->getContext());

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(true, $compiledExpression->getValue());
    }

    /**
     * Tests (float) {left-expr} ==== (float) {right-expr}
     *
     * @param int $a
     * @param int $b
     *
     * @dataProvider providerForStaticIntToIntCases
     */
    public function testStaticFloatToFloat($a, $b)
    {
        $a = (float) $a;
        $b = (float) $b;

        $this->assertInternalType('double', $a);
        $this->assertInternalType('double', $b);

        $baseExpression = new Node\Expr\BinaryOp\Identical(
            /**
             * Cheating - float casting
             */
            new Node\Scalar\DNumber($a),
            new Node\Scalar\DNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression, $this->getContext());

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(true, $compiledExpression->getValue());
    }

    public function providerForStaticIntToFloatCases()
    {
        return [
            [-1, -1.0],
            [-5, -5.0],
            [-150, -150.0],
            [150, 150.0],
            [150, 150.0],
        ];
    }

    /**
     * Tests (int) {left-expr} ==== (float) {right-expr}
     *
     * @param int $a
     * @param int $b
     *
     * @dataProvider providerForStaticIntToIntCases
     */
    public function testStaticFailIntToFloat($a, $b)
    {
        $b = (float) $b;

        $this->assertInternalType('int', $a);
        $this->assertInternalType('double', $b);

        $baseExpression = new Node\Expr\BinaryOp\Identical(
            new Node\Scalar\LNumber($a),
            new Node\Scalar\DNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression, $this->getContext());

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(false, $compiledExpression->getValue());
    }

    /**
     * Tests (float) {left-expr} ==== (int) {right-expr}
     *
     * @param int $a
     * @param int $b
     *
     * @dataProvider providerForStaticIntToIntCases
     */
    public function testStaticFailFloatToInt($a, $b)
    {
        $a = (float) $a;

        $this->assertInternalType('double', $a);
        $this->assertInternalType('int', $b);

        $baseExpression = new Node\Expr\BinaryOp\Identical(
            new Node\Scalar\DNumber($a),
            new Node\Scalar\LNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression, $this->getContext());

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(false, $compiledExpression->getValue());
    }

    /**
     * Tests {left-expr::UNKNOWN} === {right-expr}
     */
    public function testFirstUnexpectedTypes()
    {
        $baseExpression = new Node\Expr\BinaryOp\Identical(
            $this->newFakeScalarExpr(),
            $this->newScalarExpr(1)
        );
        $compiledExpression = $this->compileExpression($baseExpression, $this->getContext());

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }

    /**
     * Tests {left-expr} === {right-expr::UNKNOWN}
     */
    public function testSecondUnexpectedTypes()
    {
        $baseExpression = new Node\Expr\BinaryOp\Identical(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression, $this->getContext());

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
