<?php

namespace Tests\PHPSA\Expression\Operators\Arithmetical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class MinusTest extends \Tests\PHPSA\TestCase
{
    /**
     * Data provider for Minus {int} - {int} = {int}
     *
     * @return array
     */
    public function testIntToIntDataProvider()
    {
        return array(
            array(-1, -1, 0),
            array(-1, 0, -1),
            array(0, -1, 1),
            array(-1, 2, -3),
            array(2, -1, 3),
            array(0, 0, 0),
            array(0, 1, -1),
            array(1, 0, 1),
            array(1, 2, -1),
            array(2, 1, 1),
            array(25, 25, 0),
            array(50, 25, 25),
            array(50, -25, 75),
            array(50, 50, 0),
            array(50, -50, 100),
            array(-50, -50, 0),
        );
    }

    /**
     * Tests {int} - {int} = {int}
     *
     * @dataProvider testIntToIntDataProvider
     */
    public function testMinusIntFromInt($a, $b, $c)
    {
        $this->assertInternalType('int', $a);
        $this->assertInternalType('int', $b);
        $this->assertInternalType('int', $c);

        $baseExpression = new Node\Expr\BinaryOp\Minus(
            new Node\Scalar\LNumber($a),
            new Node\Scalar\LNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Tests {left-expr::UNKNOWN} - {right-expr}
     */
    public function testFirstUnexpectedTypes()
    {
        $baseExpression = new Node\Expr\BinaryOp\Minus(
            $this->newFakeScalarExpr(),
            $this->newScalarExpr(1)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }

    /**
     * Tests {left-expr} - {right-expr::UNKNOWN}
     */
    public function testSecondUnexpectedTypes()
    {
        $baseExpression = new Node\Expr\BinaryOp\Minus(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
