<?php

namespace Tests\PHPSA\Compiler\Expression\AssignOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

class ShiftLeftTest extends AbstractBinaryOp
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            [0, 5, 0],
            [1, 5, 32],
            [4, 5, 128],
            [-1, 5, -32],
            [1.4, 5, 32],
            [-19.7, 2, -76],
            [true, true, 2],
            [false, true, 0],
            [true, false, 1],
            [false, false, 0],
        ];
    }

    /**
     * Tests {var} <<= {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testSimpleSuccessCompile($a, $b, $c)
    {
        $baseExpression = new Node\Expr\AssignOp\ShiftLeft(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * @param Node\Scalar $a
     * @param Node\Scalar $b
     * @return Node\Expr\AssignOp\ShiftLeft
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\AssignOp\ShiftLeft($a, $b);
    }
}
