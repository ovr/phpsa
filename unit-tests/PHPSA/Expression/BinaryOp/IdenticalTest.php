<?php

namespace Tests\PHPSA\Expression\BinaryOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Visitor\Expression;

class IndenticalTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function testProviderForStaticIntToIntCases()
    {
        return array(
            array(-1, -1),
            array(-5, -5),
            array(-150, -150),
            array(150, 150),
            array(150, 150),
        );
    }

    /**
     * Tests (int) {left-expr} ==== (int) {right-expr}
     *
     * @param int $a
     * @param int $b
     *
     * @dataProvider testProviderForStaticIntToIntCases
     */
    public function testStaticIntToInt($a, $b)
    {
        $baseExpression = new Node\Expr\BinaryOp\Identical(
            new Node\Scalar\LNumber($a),
            new Node\Scalar\LNumber($b)
        );

        $visitor = new Expression($baseExpression, $this->getContext());
        $compiledExpression = $visitor->compile($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(true, $compiledExpression->getValue());
    }

    /**
     * Tests (float) {left-expr} ==== (float) {right-expr}
     *
     * @param int $a
     * @param int $b
     *
     * @dataProvider testProviderForStaticIntToIntCases
     */
    public function testStaticFloatToFloat($a, $b)
    {
        $baseExpression = new Node\Expr\BinaryOp\Identical(
            /**
             * Cheating - float casting
             */
            new Node\Scalar\DNumber((float) $a),
            new Node\Scalar\DNumber((float) $b)
        );

        $visitor = new Expression($baseExpression, $this->getContext());
        $compiledExpression = $visitor->compile($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(true, $compiledExpression->getValue());
    }

    public function testProviderForStaticIntToFloatCases()
    {
        return array(
            array(-1, -1.0),
            array(-5, -5.0),
            array(-150, -150.0),
            array(150, 150.0),
            array(150, 150.0),
        );
    }

    /**
     * Tests (int) {left-expr} ==== (float) {right-expr}
     *
     * @param int $a
     * @param int $b
     *
     * @dataProvider testProviderForStaticIntToIntCases
     */
    public function testStaticFailIntToFloat($a, $b)
    {
        $baseExpression = new Node\Expr\BinaryOp\Identical(
            new Node\Scalar\LNumber($a),
            new Node\Scalar\DNumber((float) $b)
        );

        $visitor = new Expression($baseExpression, $this->getContext());
        $compiledExpression = $visitor->compile($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(false, $compiledExpression->getValue());
    }

    /**
     * Tests (float) {left-expr} ==== (int) {right-expr}
     *
     * @param int $a
     * @param int $b
     *
     * @dataProvider testProviderForStaticIntToIntCases
     */
    public function testStaticFailFloatToInt($a, $b)
    {
        $baseExpression = new Node\Expr\BinaryOp\Identical(
            new Node\Scalar\DNumber((float) $a),
            new Node\Scalar\LNumber($b)
        );

        $visitor = new Expression($baseExpression, $this->getContext());
        $compiledExpression = $visitor->compile($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame(false, $compiledExpression->getValue());
    }
}