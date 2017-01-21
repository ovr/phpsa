<?php

namespace Tests\PHPSA\Compiler\Expression\BinaryOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;


/**
 * Class NotIndenticalTest
 * @package Tests\PHPSA\Expression\BinaryOp
 */
class NotIndenticalTest extends AbstractBinaryOp
{
    /**
     * @return array
     */
    public function providerForStaticIntToIntCases()
    {
        return [
            [-1, -2],
            [-1, 0],
            [-5, -4],
            [-150, -151],
            [150, 151],
            [151, 150],
        ];
    }

    /**
     * Tests (int) {left-expr} !=== (int) {right-expr}
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

        $baseExpression = new Node\Expr\BinaryOp\NotIdentical(
            new Node\Scalar\LNumber($a),
            new Node\Scalar\LNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression, $this->getContext());

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(true, $compiledExpression->getValue());
    }

    /**
     * Tests (float) {left-expr} !== (float) {right-expr}
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

        $baseExpression = new Node\Expr\BinaryOp\NotIdentical(
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
     * Tests (int) {left-expr} !== (float) {right-expr}
     *
     * @param int $a
     * @param int $b
     *
     * @dataProvider providerForStaticIntToFloatCases
     */
    public function testStaticIntToFloat($a, $b)
    {
        $b = (float) $b;

        $this->assertInternalType('int', $a);
        $this->assertInternalType('double', $b);

        $baseExpression = new Node\Expr\BinaryOp\NotIdentical(
            new Node\Scalar\LNumber($a),
            new Node\Scalar\DNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression, $this->getContext());

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(true, $compiledExpression->getValue());
    }

    /**
     * @param Node\Scalar $a
     * @param Node\Scalar $b
     * @return Node\Expr\BinaryOp\NotIdentical
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\NotIdentical($a, $b);
    }
}
