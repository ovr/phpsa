<?php

namespace Tests\PHPSA\Compiler\Expression\AssignOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

class PowTest extends AbstractBinaryOp
{
    /**
     * Data provider for {var} **= {expr} with result type = int
     *
     * @return array
     */
    public function powResultIntDataProvider()
    {
        return [
            [2, 2, 4],
            [true, 2, 1],
            [3, true, 3],
            [true, true, 1],
            [2, 0, 1],
            [false, 3, 0],
            [2, false, 1],
            [false, false, 1],
            [0, 0, 1],
            [0, 3, 0],
            [true, false, 1],
        ];
    }

    /**
     * Tests {var} **= {expr} with result type = int
     *
     * @dataProvider powResultIntDataProvider
     */
    public function testPowResultInt($a, $b, $c)
    {

        $baseExpression = new Node\Expr\AssignOp\Pow(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Data provider for {var} **= {expr} with result type = double
     *
     * @return array
     */
    public function powResultDoubleDataProvider()
    {
        return [
            [2, -2, 0.25],
            [1.5, 2, 2.25],
            [1, 1.5, 1.0],
            [100, 2.5, 100000.0],
            [true, 1.5, 1.0],
            [false, 1.5, 0.0],
            [1.5, false, 1.0],
            [1.5, true, 1.5],
        ];
    }

    /**
     * Tests {var} **= {expr} with result type = double
     *
     * @dataProvider powResultDoubleDataProvider
     */
    public function testPowResultDouble($a, $b, $c)
    {

        $baseExpression = new Node\Expr\AssignOp\Pow(
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
     * @return Node\Expr\AssignOp\Pow
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\AssignOp\Pow($a, $b);
    }
}
