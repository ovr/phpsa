<?php

namespace Tests\PHPSA\Compiler\Expression\AssignOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

class DivTest extends AbstractBinaryOp
{
    /**
     * Data provider for {var} /= {expr} with result type = int
     *
     * @return array
     */
    public function divResultIntDataProvider()
    {
        return [
            [2, 2, 1],
            [true, 1, 1],
            [3, true, 3],
            [true, true, 1],
            [2, 1, 2],
            [-1, 1, -1],
            [false, -3, 0],
            [false, 1, 0],
            [0, 1, 0],
            [false, true, 0],
        ];
    }

    /**
     * Tests {var} /= {expr} with result type = int
     *
     * @dataProvider divResultIntDataProvider
     */
    public function testDivResultInt($a, $b, $c)
    {

        $baseExpression = new Node\Expr\AssignOp\Div(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Data provider for {var} /= {expr} with result type = double
     *
     * @return array
     */
    public function divResultDoubleDataProvider()
    {
        return [
            [2, 0.5, 4.0],
            [1.5, 0.5, 3.0],
            [true, 2, 0.5],
            [false, -5.5, 0.0],
            [1.5, true, 1.5],
            [-1.5, 1, -1.5],
        ];
    }

    /**
     * Tests {var} /= {expr} with result type = double
     *
     * @dataProvider divResultDoubleDataProvider
     */
    public function testDivResultDouble($a, $b, $c)
    {

        $baseExpression = new Node\Expr\AssignOp\Div(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::DOUBLE, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * @param Node\Scalar $a
     * @param Node\Scalar $b
     * @return Node\Expr\AssignOp\Div
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\AssignOp\Div($a, $b);
    }
}
