<?php

namespace Tests\PHPSA\Compiler\Expression\AssignOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class MinusTest extends \Tests\PHPSA\TestCase
{
    /**
     * Data provider for {var} -= {expr} with result type = int
     *
     * @return array
     */
    public function minusResultIntDataProvider()
    {
        return [
            [2, 2, 0],
            [true, 2, -1],
            [3, true, 2],
            [true, true, 0],
            [2, 0, 2],
            [-1, 1, -2],
            [false, 3, -3],
            [2, false, 2],
            [false, false, 0],
            [0, 0, 0],
            [true, false, 1],
        ];
    }

    /**
     * Tests {var} -= {expr} with result type = int
     *
     * @dataProvider minusResultIntDataProvider
     */
    public function testMinusResultInt($a, $b, $c)
    {

        $baseExpression = new Node\Expr\AssignOp\Minus(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Data provider for {var} -= {expr} with result type = double
     *
     * @return array
     */
    public function minusResultDoubleDataProvider()
    {
        return [
            [2, 1.5, 0.5],
            [1.5, 2, -0.5],
            [true, 1.5, -0.5],
            [false, 1.5, -1.5],
            [1.5, false, 1.5],
            [1.5, true, 0.5],
            [-1.5, 1, -2.5],
        ];
    }

    /**
     * Tests {var} -= {expr} with result type = double
     *
     * @dataProvider minusResultDoubleDataProvider
     */
    public function testMinusResultDouble($a, $b, $c)
    {

        $baseExpression = new Node\Expr\AssignOp\Minus(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::DOUBLE, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Tests {var-type::UNKNOWN} -= {right-expr}
     */
    public function testFirstUnexpectedType()
    {
        $baseExpression = new Node\Expr\AssignOp\Minus(
            $this->newFakeScalarExpr(),
            $this->newScalarExpr(1)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }

    /**
     * Tests {var} -= {right-expr::UNKNOWN}
     */
    public function testSecondUnexpectedType()
    {
        $baseExpression = new Node\Expr\AssignOp\Minus(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
