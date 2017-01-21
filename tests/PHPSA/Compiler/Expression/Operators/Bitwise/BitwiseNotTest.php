<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Bitwise;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractUnaryOp;


class BitwiseNotTest extends AbstractUnaryOp
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            [1, -2],
            [-1, 0],
            [1.4,-2],
            [-2.7, 1],
        ];
    }

    /**
     * Tests ~{expr}
     *
     * @dataProvider getDataProvider
     */
    public function testSimpleSuccessCompile($a, $b)
    {
        $baseExpression = new Node\Expr\BitwiseNot(
            $this->newScalarExpr($a)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($b, $compiledExpression->getValue());
    }

    /**
     * @param Node\Scalar $a
     * @return Node\Expr\BitwiseNot
     */
    protected function buildExpression($a)
    {
        return new Node\Expr\BitwiseNot($a);
    }
}
