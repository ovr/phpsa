<?php

namespace Tests\PHPSA\Compiler\Expression\AssignOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

class MulTest extends AbstractBinaryOp
{
    /**
     * @param $a
     * @param $b
     * @return mixed
     */
    protected function process($a, $b)
    {
        return $a * $b;
    }

    /**
     * @return array
     */
    protected function getSupportedTypes()
    {
        return [
            CompiledExpression::INTEGER,
            CompiledExpression::DOUBLE,
            CompiledExpression::BOOLEAN,
        ];
    }

    /**
     * Data provider for {var} *= {expr} with result type = double
     *
     * @return array
     */
    public function mulResultDoubleDataProvider()
    {
        return [
            [2, 1.5, 3.0],
            [1.5, 1.5, 2.25],
            [true, 1.5, 1.5],
            [false, 1.5, 0.0],
            [1.5, false, 0.0],
            [1.5, true, 1.5],
            [-1.5, true, -1.5],
        ];
    }

    /**
     * Tests {var} *= {expr} with result type = double
     *
     * @dataProvider mulResultDoubleDataProvider
     */
    public function testMulResultDouble($a, $b, $c)
    {

        $baseExpression = new Node\Expr\AssignOp\Mul(
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
     * @return Node\Expr\AssignOp\Mul
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\AssignOp\Mul($a, $b);
    }
}
