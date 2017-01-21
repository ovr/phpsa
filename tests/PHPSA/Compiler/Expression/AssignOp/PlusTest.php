<?php

namespace Tests\PHPSA\Compiler\Expression\AssignOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

class PlusTest extends AbstractBinaryOp
{
    /**
     * Data provider for {var} += {expr} with result type = int
     *
     * @return array
     */
    public function plusResultIntDataProvider()
    {
        return [
            [2, 2, 4],
            [true, 2, 3],
            [3, true, 4],
            [true, true, 2],
            [2, 0, 2],
            [false, -1, -1],
            [2, false, 2],
            [false, false, 0],
            [-2, 1, -1],
            [true, false, 1],
        ];
    }

    /**
     * Tests {var} += {expr} with result type = int
     *
     * @dataProvider plusResultIntDataProvider
     */
    public function testPlusResultInt($a, $b, $c)
    {

        $baseExpression = new Node\Expr\AssignOp\Plus(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Data provider for {var} += {expr} with result type = double
     *
     * @return array
     */
    public function plusResultDoubleDataProvider()
    {
        return [
            [2, -1.5, 0.5],
            [1.5, 2, 3.5],
            [true, 1.5, 2.5],
            [1.5, false, 1.5],
            [true, -2.5, -1.5],
        ];
    }

    /**
     * Tests {var} += {expr} with result type = double
     *
     * @dataProvider plusResultDoubleDataProvider
     */
    public function testPlusResultDouble($a, $b, $c)
    {

        $baseExpression = new Node\Expr\AssignOp\Plus(
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
     * @return Node\Expr\AssignOp\Plus
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\AssignOp\Plus($a, $b);
    }
}
