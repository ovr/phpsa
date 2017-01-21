<?php

namespace Tests\PHPSA\Compiler\Expression\BinaryOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

/**
 * Class IndenticalTest
 * @package Tests\PHPSA\Expression\BinaryOp
 */
class IndenticalTest extends AbstractBinaryOp
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
     * @param Node\Scalar $a
     * @param Node\Scalar $b
     * @return Node\Expr\BinaryOp\Identical
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\Identical($a, $b);
    }
}
